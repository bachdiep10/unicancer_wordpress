<?php
/**
 * Plugin Name: UNI-ASIA Early Language Routing
 * Description: Keeps Polylang's site-root redirect on canonical language roots.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Polylang fires this filter before the active theme is loaded, so this must
 * live in an MU plugin rather than functions.php.
 */
function unicancer_filter_polylang_home_redirect( $redirect ) {
	$path = trim( (string) wp_parse_url( $redirect, PHP_URL_PATH ), '/' );
	$first = strtok( $path, '/' );
	if ( ! in_array( $first, array( 'vi', 'en', 'id', 'zh-cn' ), true ) ) {
		return $redirect;
	}
	return trailingslashit( untrailingslashit( (string) get_option( 'home' ) ) . '/' . $first );
}
add_filter( 'pll_redirect_home', 'unicancer_filter_polylang_home_redirect', PHP_INT_MAX );
