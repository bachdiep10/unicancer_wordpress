<?php
if ( PHP_SAPI !== 'cli' ) {
	exit(1);
}
require '/var/www/unicancercenter.com/wp-load.php';
global $wpdb;

$apply = in_array( '--apply', $argv, true );
$redirects = get_option( 'unicancer_vi_redirect_map', array() );
$bases = array(
	'doctor' => array( 'doctors', 'bac-si' ),
	'cancer' => array( 'cancers', 'ung-thu' ),
	'treatment' => array( 'treatments', 'phuong-phap-dieu-tri' ),
	'patient_story' => array( 'patient-stories', 'cau-chuyen-benh-nhan' ),
	'special_topic' => array( 'special-topics', 'chu-de-ung-thu' ),
	'post' => array( '', 'news', 'tin-tuc' ),
);

function uc_cleanup_path( $value ) {
	$path = wp_parse_url( $value, PHP_URL_PATH );
	$path = '/' . trim( (string) $path, '/' );
	return '/' === $path ? '/' : $path . '/';
}

$groups = $wpdb->get_col( "SELECT description FROM {$wpdb->term_taxonomy} WHERE taxonomy = 'post_translations'" );
$foreign_ids = array();
$mapped = 0;

foreach ( $groups as $serialized ) {
	$translations = maybe_unserialize( $serialized );
	if ( ! is_array( $translations ) || empty( $translations['vi'] ) ) {
		continue;
	}
	$vi_post = get_post( (int) $translations['vi'] );
	if ( ! $vi_post ) {
		continue;
	}
	$target = uc_cleanup_path( get_permalink( $vi_post ) );
	foreach ( array( 'en', 'id', 'zh-cn', 'jv', 'zh' ) as $language ) {
		if ( empty( $translations[ $language ] ) ) {
			continue;
		}
		$foreign = get_post( (int) $translations[ $language ] );
		if ( ! $foreign ) {
			continue;
		}
		$foreign_ids[ $foreign->ID ] = $language;
		$redirects[ uc_cleanup_path( get_permalink( $foreign ) ) ] = $target;
		foreach ( $bases[ $foreign->post_type ] ?? array( '' ) as $base ) {
			$route = '/' . trim( $base . '/' . $foreign->post_name, '/' ) . '/';
			$redirects[ uc_cleanup_path( $route ) ] = $target;
			$redirects[ uc_cleanup_path( '/' . $language . $route ) ] = $target;
			$redirects[ uc_cleanup_path( '/vi' . $route ) ] = $target;
		}
		$mapped++;
	}
}

if ( $apply ) {
	foreach ( $foreign_ids as $post_id => $language ) {
		$post = get_post( $post_id );
		if ( $post && 'draft' !== $post->post_status && 'trash' !== $post->post_status ) {
			update_post_meta( $post_id, '_unicancer_pre_single_language_status', $post->post_status );
			update_post_meta( $post_id, '_unicancer_archived_language', $language );
			wp_update_post( array( 'ID' => $post_id, 'post_status' => 'draft' ) );
		}
	}
	update_option( 'unicancer_vi_redirect_map', $redirects, false );
	$plugins = get_option( 'active_plugins', array() );
	$plugins = array_values( array_diff( $plugins, array( 'unicancer-ai-translator/unicancer-ai-translator.php' ) ) );
	update_option( 'active_plugins', $plugins );
	flush_rewrite_rules( true );
}

echo wp_json_encode( array(
	'mode' => $apply ? 'apply' : 'dry-run',
	'foreign_posts' => count( $foreign_ids ),
	'translation_links' => $mapped,
	'redirects' => count( $redirects ),
), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) . "\n";
