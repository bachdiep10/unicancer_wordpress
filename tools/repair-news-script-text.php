<?php
if ( ! defined( 'ABSPATH' ) ) { exit( 1 ); }
$page = get_page_by_path( 'tin-tuc' );
if ( ! $page ) { throw new RuntimeException( 'News page not found.' ); }
$page_id = $page->ID;
$content = $page->post_content;
$pattern = '~(?:<script\b[^>]*>\s*)?const s=document\.querySelectorAll\([\s\S]*?window\.scrollTo\([\s\S]*?\}\)\}\)\}\);(?:\s*</script>)?~';
$clean = preg_replace( $pattern, '', $content, -1, $count );
if ( 1 !== $count ) { throw new RuntimeException( 'Expected exactly one imported news script; found ' . $count ); }
add_post_meta( $page_id, '_unicancer_pre_news_script_repair_20261003', $content, true );
kses_remove_filters();
$result = wp_update_post( array( 'ID' => $page_id, 'post_content' => wp_slash( $clean ) ), true );
kses_init_filters();
if ( is_wp_error( $result ) ) { throw new RuntimeException( $result->get_error_message() ); }
echo 'Removed exposed script from page ' . $page_id . PHP_EOL;
