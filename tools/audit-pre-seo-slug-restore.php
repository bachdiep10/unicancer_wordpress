<?php
if ( PHP_SAPI !== 'cli' ) { exit; }
require getcwd() . '/wp-load.php';
global $wpdb;
$key = '_uc_pre_seo_architecture_20261002_post_name';
$rows = $wpdb->get_results( $wpdb->prepare(
	"SELECT p.ID,p.post_type,p.post_status,p.post_name,pm.meta_value original_slug
	 FROM {$wpdb->posts} p JOIN {$wpdb->postmeta} pm ON pm.post_id=p.ID
	 WHERE pm.meta_key=%s AND p.post_name<>pm.meta_value ORDER BY pm.meta_value,p.ID",
	$key
) );
$groups = array();
foreach ( $rows as $row ) {
	$group_key = $row->post_type . '|' . $row->original_slug;
	$lang = function_exists( 'pll_get_post_language' ) ? pll_get_post_language( $row->ID, 'slug' ) : '';
	$vi = function_exists( 'pll_get_post' ) ? (int) pll_get_post( $row->ID, 'vi' ) : 0;
	$groups[ $group_key ][] = array( 'id'=>(int)$row->ID, 'lang'=>$lang, 'vi_group'=>$vi, 'status'=>$row->post_status, 'current'=>$row->post_name, 'original'=>$row->original_slug );
}
$summary = array( 'mismatches'=>count($rows), 'groups'=>count($groups), 'same_translation_group'=>0, 'mixed_translation_group'=>0 );
foreach ( $groups as $items ) {
	$vi_groups = array_values( array_unique( array_filter( array_column( $items, 'vi_group' ) ) ) );
	if ( count( $vi_groups ) <= 1 ) { $summary['same_translation_group']++; }
	else { $summary['mixed_translation_group']++; }
}
echo wp_json_encode( array( 'summary'=>$summary, 'groups'=>$groups ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . PHP_EOL;
