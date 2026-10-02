<?php
/**
 * Plugin Name: UNI-ASIA Multilingual SEO Redirects
 * Description: Language-aware redirects for slugs changed by the 2026 SEO architecture migration.
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function unicancer_redirect_pre_seo_architecture_slug() {
	if ( is_admin() || wp_doing_ajax() || empty( $_SERVER['REQUEST_URI'] ) ) { return; }
	$path = trim( (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ), '/' );
	if ( '' === $path ) { return; }
	$parts = array_values( array_filter( explode( '/', $path ), 'strlen' ) );
	$langs = array( 'vi', 'en', 'id', 'zh-cn' );
	$lang = in_array( $parts[0] ?? '', $langs, true ) ? array_shift( $parts ) : 'vi';
	$routes = array(
		'cancers' => 'cancer', 'treatments' => 'treatment', 'doctors' => 'doctor',
		'patient-stories' => 'patient_story', 'news' => 'post',
	);
	if ( 1 === count( $parts ) ) {
		$post_type = 'page';
		$old_slug = $parts[0];
	} elseif ( 2 === count( $parts ) && isset( $routes[ $parts[0] ] ) ) {
		$post_type = $routes[ $parts[0] ];
		$old_slug = $parts[1];
	} else {
		return;
	}
	global $wpdb;
	$ids = $wpdb->get_col( $wpdb->prepare(
		"SELECT p.ID FROM {$wpdb->posts} p INNER JOIN {$wpdb->postmeta} pm ON pm.post_id=p.ID WHERE p.post_type=%s AND p.post_status IN ('publish','draft') AND pm.meta_key=%s AND pm.meta_value=%s",
		$post_type, '_uc_pre_seo_architecture_20261002_post_name', $old_slug
	) );
	foreach ( $ids as $id ) {
		$post_lang = function_exists( 'pll_get_post_language' ) ? pll_get_post_language( $id, 'slug' ) : 'vi';
		if ( $post_lang !== $lang || 'publish' !== get_post_status( $id ) ) { continue; }
		$target = get_permalink( $id );
		if ( $target ) { wp_safe_redirect( $target, 301, 'UNI-ASIA multilingual SEO migration' ); exit; }
	}
}
add_action( 'template_redirect', 'unicancer_redirect_pre_seo_architecture_slug', 0 );
