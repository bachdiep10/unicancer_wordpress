<?php
if ( PHP_SAPI !== 'cli' ) {
	exit(1);
}
require '/var/www/unicancercenter.com/wp-load.php';

$posts = get_posts( array(
	'post_type' => array( 'page', 'post', 'doctor', 'cancer', 'treatment', 'patient_story', 'special_topic' ),
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'ID',
	'order' => 'ASC',
	'suppress_filters' => false,
) );

$urls = array();
foreach ( $posts as $post ) {
	$urls[] = array( 'id' => $post->ID, 'type' => $post->post_type, 'title' => $post->post_title, 'slug' => $post->post_name, 'url' => get_permalink( $post ) );
}

echo wp_json_encode( array(
	'home' => home_url( '/' ),
	'locale' => get_locale(),
	'permalink_structure' => get_option( 'permalink_structure' ),
	'polylang_active' => function_exists( 'pll_current_language' ),
	'active_plugins' => get_option( 'active_plugins', array() ),
	'redirect_count' => count( get_option( 'unicancer_vi_redirect_map', array() ) ),
	'published_count' => count( $urls ),
	'urls' => $urls,
), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
