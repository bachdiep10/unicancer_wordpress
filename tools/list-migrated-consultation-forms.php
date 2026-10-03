<?php
if ( PHP_SAPI !== 'cli' ) { exit(1); }
require '/var/www/unicancercenter.com/wp-load.php';
$posts = get_posts( array(
	'post_type' => 'any', 'post_status' => 'publish', 'posts_per_page' => -1,
	'meta_key' => '_unicancer_pre_consultation_block_content_20261003',
	'orderby' => 'ID', 'order' => 'ASC', 'suppress_filters' => true,
) );
$rows = array();
foreach ( $posts as $post ) {
	$rows[] = array(
		'id' => $post->ID,
		'url' => get_permalink( $post ),
		'has_block' => has_block( 'unicancer/consultation-form', $post ),
		'has_legacy' => (bool) preg_match( '#<section\b[^>]*\bid=["\']consultation-form["\']#iu', $post->post_content ),
	);
}
echo wp_json_encode( $rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
