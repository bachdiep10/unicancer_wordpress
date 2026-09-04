<?php
require '/var/www/unicancercenter.com/wp-load.php';

if ( ! function_exists( 'pll_get_post_translations' ) ) {
	fwrite( STDERR, "Polylang is unavailable.\n" );
	exit( 1 );
}

$apply = in_array( '--apply', $argv, true );
$vi_posts = get_posts(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'lang'           => 'vi',
	)
);

$targets = array();
foreach ( $vi_posts as $vi_id ) {
	$translations = pll_get_post_translations( $vi_id );
	foreach ( array( 'en', 'id', 'zh-cn' ) as $lang ) {
		$id = isset( $translations[ $lang ] ) ? (int) $translations[ $lang ] : 0;
		if ( ! $id || 'post' !== get_post_type( $id ) ) { continue; }
		if ( $lang !== pll_get_post_language( $id, 'slug' ) ) { continue; }
		$targets[ $id ] = $lang;
	}
}

$published = 0;
$errors    = array();
if ( $apply ) {
	foreach ( $targets as $id => $lang ) {
		if ( 'publish' === get_post_status( $id ) ) { continue; }
		$result = wp_update_post( array( 'ID' => $id, 'post_status' => 'publish' ), true );
		if ( is_wp_error( $result ) ) {
			$errors[ $id ] = $result->get_error_message();
		} else {
			$published++;
		}
	}
	clean_post_cache( 0 );
	flush_rewrite_rules( false );
}

$status = array();
foreach ( $targets as $id => $lang ) {
	$key = $lang . ':' . get_post_status( $id );
	$status[ $key ] = ( $status[ $key ] ?? 0 ) + 1;
}
ksort( $status );

echo wp_json_encode(
	array(
		'mode'       => $apply ? 'apply' : 'dry-run',
		'targets'    => count( $targets ),
		'published'  => $published,
		'status'     => $status,
		'errors'     => $errors,
	),
	JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);
