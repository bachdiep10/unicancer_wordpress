<?php
if ( PHP_SAPI !== 'cli' ) { exit(1); }
require '/var/www/unicancercenter.com/wp-load.php';

$apply = in_array( '--apply', $argv, true );
$posts = get_posts( array(
	'post_type' => 'any',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'ID',
	'order' => 'ASC',
	'suppress_filters' => true,
) );
$changed = array();
$pattern = '#<section\b[^>]*\bid=["\']consultation-form["\'][^>]*>.*?</section>#isu';

foreach ( $posts as $post ) {
	if ( ! preg_match( $pattern, $post->post_content ) ) { continue; }
	$has_block = has_block( 'unicancer/consultation-form', $post );
	$replacement = $has_block ? '' : "\n<!-- wp:unicancer/consultation-form /-->\n";
	$content = preg_replace( $pattern, $replacement, $post->post_content, 1, $count );
	if ( 1 !== $count || null === $content || $content === $post->post_content ) { continue; }
	$changed[] = array(
		'id' => $post->ID,
		'type' => $post->post_type,
		'url' => get_permalink( $post ),
		'action' => $has_block ? 'remove-duplicate-legacy-form' : 'replace-with-block',
	);
	if ( $apply ) {
		if ( ! metadata_exists( 'post', $post->ID, '_unicancer_pre_consultation_block_content_20261003' ) ) {
			update_post_meta( $post->ID, '_unicancer_pre_consultation_block_content_20261003', $post->post_content );
		}
		wp_update_post( array( 'ID' => $post->ID, 'post_content' => $content ) );
	}
}

echo wp_json_encode( array(
	'mode' => $apply ? 'apply' : 'dry-run',
	'count' => count( $changed ),
	'items' => $changed,
), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
