<?php
if ( PHP_SAPI !== 'cli' ) { exit; }
require getcwd() . '/wp-load.php';
global $wpdb;
$slugs = array(
	'huang-wen-hui-bac-si-ung-buou', 'teng-hao-qi-bac-si-ung-buou',
	'wang-hui-bac-si-ung-buou', 'wu-li-zhang-bac-si-ung-buou',
	'yi-cheng-bac-si-ung-buou', 'zhang-jun-xi-bac-si-ung-buou',
	'zhou-liang-bac-si-ung-buou',
);
$placeholders = implode( ',', array_fill( 0, count( $slugs ), '%s' ) );
$sql = $wpdb->prepare(
	"SELECT ID, post_type, post_status, post_name, post_title FROM {$wpdb->posts} WHERE post_name IN ($placeholders) ORDER BY post_name, ID",
	$slugs
);
echo wp_json_encode( array(
	'blog_public' => get_option( 'blog_public' ),
	'collisions'  => $wpdb->get_results( $sql, ARRAY_A ),
), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . PHP_EOL;
