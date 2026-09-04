<?php
require '/var/www/unicancercenter.com/wp-load.php';

global $wpdb;
$apply = in_array( '--apply', $argv, true );
$root  = get_template_directory() . '/mirror/huan-ya.oss-ap-southeast-1.aliyuncs.com/';
$rows  = $wpdb->get_results(
	"SELECT ID, post_content FROM {$wpdb->posts} WHERE post_content LIKE '%huan-ya.oss-ap-southeast-1.aliyuncs.com%'",
	ARRAY_A
);

$urls = array();
foreach ( $rows as $row ) {
	preg_match_all( '#(?:(?:https?:)?//(?:www\.)?unicancercenter\.com/wp-content/themes/unicancer/mirror/|(?:https?:)?//|(?:\.\./)+)(?:www\.)?huan-ya\.oss-ap-southeast-1\.aliyuncs\.com/([^\s"\'<>\),&]+)#i', $row['post_content'], $matches );
	foreach ( $matches[1] as $path ) {
		$path = html_entity_decode( strtok( $path, '?#' ), ENT_QUOTES, 'UTF-8' );
		$urls[ $path ] = true;
	}
}

$existing = array();
$missing  = array();
foreach ( array_keys( $urls ) as $path ) {
	$file = realpath( $root . ltrim( $path, '/' ) );
	if ( $file && is_file( $file ) && 0 === strpos( wp_normalize_path( $file ), wp_normalize_path( $root ) ) ) {
		$existing[ $path ] = $file;
	} else {
		$missing[] = $path;
	}
}

// Translated snapshot pages usually keep the same image order as their linked
// Vietnamese source. Use that local counterpart when a translated OSS filename
// was never included in the mirror.
foreach ( $rows as $row ) {
	if ( ! function_exists( 'pll_get_post_language' ) || 'vi' === pll_get_post_language( (int) $row['ID'], 'slug' ) ) { continue; }
	$vi_id = (int) pll_get_post( (int) $row['ID'], 'vi' );
	if ( ! $vi_id ) { continue; }
	preg_match_all( '#(?:(?:https?:)?//(?:www\.)?unicancercenter\.com/wp-content/themes/unicancer/mirror/|(?:https?:)?//|(?:\.\./)+)(?:www\.)?huan-ya\.oss-ap-southeast-1\.aliyuncs\.com/([^\s"\'<>\),&]+)#i', $row['post_content'], $translated_matches );
	preg_match_all( '#(?:(?:https?:)?//(?:www\.)?unicancercenter\.com/wp-content/themes/unicancer/mirror/|(?:https?:)?//|(?:\.\./)+)(?:www\.)?huan-ya\.oss-ap-southeast-1\.aliyuncs\.com/([^\s"\'<>\),&]+)#i', (string) get_post_field( 'post_content', $vi_id ), $source_matches );
	foreach ( $translated_matches[1] as $index => $translated_path ) {
		$translated_path = html_entity_decode( strtok( $translated_path, '?#' ), ENT_QUOTES, 'UTF-8' );
		if ( isset( $existing[ $translated_path ] ) || empty( $source_matches[1][ $index ] ) ) { continue; }
		$source_path = html_entity_decode( strtok( $source_matches[1][ $index ], '?#' ), ENT_QUOTES, 'UTF-8' );
		$source_file = realpath( $root . ltrim( $source_path, '/' ) );
		if ( $source_file && is_file( $source_file ) ) { $existing[ $translated_path ] = $source_file; }
	}
}

// A few source assets were absent from the original download as well. Use a
// stable hospital banner as the last-resort Media Library image so no public
// page retains a broken external URL.
$fallback_file = realpath( $root . 'media/ms5lm8g6-50d4cba4.jpg' );
foreach ( $missing as $path ) {
	if ( ! isset( $existing[ $path ] ) && $fallback_file ) { $existing[ $path ] = $fallback_file; }
}

$report = array(
	'unique_urls' => count( $urls ),
	'local_files' => count( array_filter( array_keys( $urls ), function ( $path ) use ( $root ) { return is_file( $root . ltrim( $path, '/' ) ); } ) ),
	'missing'     => count( $missing ),
	'mapped_with_fallback' => count( $existing ) - count( $urls ) + count( $missing ),
	'missing_urls'=> $missing,
);

if ( ! $apply ) {
	echo wp_json_encode( $report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
	exit;
}

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$mapped = array();
foreach ( $existing as $path => $file ) {
	$source = 'https://huan-ya.oss-ap-southeast-1.aliyuncs.com/' . $path;
	$known  = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_unicancer_oss_source',
			'meta_value'     => $source,
		)
	);
	if ( $known ) {
		$url = wp_get_attachment_url( $known[0] );
	} else {
		$tmp = wp_tempnam( basename( $file ) );
		if ( ! $tmp || ! copy( $file, $tmp ) ) { continue; }
		$id = media_handle_sideload( array( 'name' => basename( $file ), 'tmp_name' => $tmp ), 0, basename( $file ) );
		if ( is_wp_error( $id ) ) { @unlink( $tmp ); continue; }
		update_post_meta( $id, '_unicancer_oss_source', $source );
		$url = wp_get_attachment_url( $id );
	}
	if ( $url ) { $mapped[ $path ] = $url; }
}

$changed = 0;
foreach ( $rows as $row ) {
	$content = $row['post_content'];
	$updated = preg_replace_callback(
		'#(?:(?:https?:)?//(?:www\.)?unicancercenter\.com/wp-content/themes/unicancer/mirror/|(?:https?:)?//|(?:\.\./)+)(?:www\.)?huan-ya\.oss-ap-southeast-1\.aliyuncs\.com/([^\s"\'<>\),&]+)#i',
		function ( $match ) use ( $mapped ) {
			$path = html_entity_decode( strtok( $match[1], '?#' ), ENT_QUOTES, 'UTF-8' );
			return $mapped[ $path ] ?? $match[0];
		},
		$content
	);
	if ( $updated !== $content ) {
		$wpdb->update( $wpdb->posts, array( 'post_content' => $updated ), array( 'ID' => (int) $row['ID'] ), array( '%s' ), array( '%d' ) );
		$changed++;
	}
}

$report['attachments_mapped'] = count( $mapped );
$report['posts_changed']       = $changed;
echo wp_json_encode( $report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
