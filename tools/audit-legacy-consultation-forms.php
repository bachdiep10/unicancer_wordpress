<?php
if ( PHP_SAPI !== 'cli' ) { exit(1); }
require '/var/www/unicancercenter.com/wp-load.php';

$posts = get_posts( array(
	'post_type' => 'any',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'ID',
	'order' => 'ASC',
	'suppress_filters' => true,
) );
$rows = array();
foreach ( $posts as $post ) {
	if ( false === stripos( $post->post_content, 'consultation-form' ) ) { continue; }
	$rows[] = array(
		'id' => $post->ID,
		'type' => $post->post_type,
		'slug' => $post->post_name,
		'title' => $post->post_title,
		'url' => get_permalink( $post ),
		'legacy_section' => (bool) preg_match( '#<section\b[^>]*\bid=["\']consultation-form["\']#iu', $post->post_content ),
		'block' => has_block( 'unicancer/consultation-form', $post ),
	);
}
echo wp_json_encode( $rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
