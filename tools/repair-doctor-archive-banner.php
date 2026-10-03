<?php
if ( ! defined( 'ABSPATH' ) ) { exit( 1 ); }
$doctor_page = get_page_by_path( 'bac-si' );
if ( ! $doctor_page ) { throw new RuntimeException( 'Doctor archive missing.' ); }
$doctor_page_id = $doctor_page->ID;
$content = $doctor_page->post_content;
$assets = array( 'uni-asia-doctors-background.png', 'uni-asia-mdt-team.png' );
$count = 0;
$updated = preg_replace_callback( '~background-image:\s*url\([^)]*\)~', function ( $match ) use ( &$count, $assets ) {
	if ( ! isset( $assets[$count] ) ) { throw new RuntimeException( 'Unexpected empty background.' ); }
	return "background-image: url('" . esc_url( home_url( '/wp-content/uploads/2026/10/' . $assets[$count++] ) ) . "')";
}, $content );
if ( 2 !== $count ) { throw new RuntimeException( 'Expected two missing background images.' ); }
add_post_meta( $doctor_page_id, '_unicancer_pre_doctor_banner_20261003', $content, true );
kses_remove_filters();
$result = wp_update_post( array( 'ID' => $doctor_page_id, 'post_content' => wp_slash( $updated ) ), true );
kses_init_filters();
if ( is_wp_error( $result ) ) { throw new RuntimeException( $result->get_error_message() ); }
require_once ABSPATH . 'wp-admin/includes/image.php';
foreach ( $assets as $asset ) {
	$file = wp_upload_dir()['basedir'] . '/2026/10/' . $asset;
	$id = wp_insert_attachment( array( 'post_mime_type' => 'image/png', 'post_title' => 'Đội ngũ MDT UNI-ASIA — ' . $asset, 'post_status' => 'inherit' ), $file );
	if ( is_wp_error( $id ) ) { throw new RuntimeException( $id->get_error_message() ); }
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );
}
echo 'Restored doctor archive background and team image.' . PHP_EOL;
