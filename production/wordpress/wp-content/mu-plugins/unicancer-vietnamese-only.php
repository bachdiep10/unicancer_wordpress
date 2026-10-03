<?php
/**
 * Plugin Name: UNI-ASIA Vietnamese canonical routes
 * Description: Preserves legacy links while the public website uses one Vietnamese URL for each item.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function unicancer_vi_normalize_path( $url_or_path ) {
	$path = wp_parse_url( $url_or_path, PHP_URL_PATH );
	$path = '/' . trim( (string) $path, '/' );
	return '/' === $path ? '/' : $path . '/';
}

function unicancer_vi_legacy_redirect() {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	$request = unicancer_vi_normalize_path( $_SERVER['REQUEST_URI'] ?? '/' );
	$map     = get_option( 'unicancer_vi_redirect_map', array() );
	$target  = isset( $map[ $request ] ) ? $map[ $request ] : '';

	if ( ! $target && preg_match( '#^/(?:vi|en|id|zh-cn)(/.*)?/$#i', $request, $match ) ) {
		$without_language = unicancer_vi_normalize_path( $match[1] ?? '/' );
		$target = isset( $map[ $without_language ] ) ? $map[ $without_language ] : $without_language;
	}

	if ( $target ) {
		$target_path = unicancer_vi_normalize_path( $target );
		if ( $target_path !== $request ) {
			wp_safe_redirect( home_url( $target_path ), 301, 'UNI-ASIA Vietnamese canonical URL' );
			exit;
		}
	}
}
add_action( 'template_redirect', 'unicancer_vi_legacy_redirect', -1000 );

// A single-language website must not emit stale alternate-language tags.
add_filter( 'wpseo_hreflang_filter', '__return_false' );

