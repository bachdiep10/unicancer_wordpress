<?php
if ( ! defined( 'ABSPATH' ) ) { exit( 1 ); }
$contact_page = get_page_by_path( 'lien-he' );
if ( ! $contact_page ) { throw new RuntimeException( 'Contact page missing.' ); }
$contact_id = $contact_page->ID;
$original = $contact_page->post_content;
$html = $original;
$map = '<div class="relative overflow-hidden rounded-xl bg-gray-100 w-full h-64"><iframe style="width:100%;height:100%;border:0" src="https://www.google.com/maps?q=Trang%20An%20Complex%2C%201%20Phung%20Chi%20Kien%2C%20Ha%20Noi%2C%20Vietnam&amp;output=embed" title="Bản đồ Trung tâm Dịch vụ Hà Nội tại Tràng An Complex" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe></div>';
$html = str_replace( '<div class="relative overflow-hidden rounded-xl bg-gray-100 w-full h-64"></div>', $map, $html, $map_count );
if ( 1 !== $map_count ) { throw new RuntimeException( 'Expected one empty map wrapper.' ); }
$html = preg_replace( '~(?:<p>)?&lt;!(?:&#8211;|--|–)[\s\S]*?\{intro\.contactLabel\}[\s\S]*?(?:&#8211;|--|–)&gt;(?:</p>)?~u', '', $html, -1, $placeholder_count );
if ( 1 !== $placeholder_count ) { throw new RuntimeException( 'Expected one escaped contact placeholder, found ' . $placeholder_count ); }
$html = str_replace( array( 'https://unicancercenter.com/+84388925161', 'https://unicancercenter.com/service@uniasiacancer.com' ), array( 'tel:+84388925161', 'mailto:service@uniasiacancer.com' ), $html );
$html = str_replace( '<span class="font-medium text-gray-850">Địa chỉ:</span>', '<span class="font-medium text-gray-850" style="flex-shrink:0">Địa chỉ:</span>', $html );
add_post_meta( $contact_id, '_unicancer_pre_contact_layout_20261003', $original, true );
kses_remove_filters();
$saved = wp_update_post( array( 'ID' => $contact_id, 'post_content' => wp_slash( $html ) ), true );
kses_init_filters();
if ( is_wp_error( $saved ) ) { throw new RuntimeException( $saved->get_error_message() ); }
echo 'Repaired contact page ' . $contact_id . PHP_EOL;
