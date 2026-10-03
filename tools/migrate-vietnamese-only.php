<?php
if ( PHP_SAPI !== 'cli' ) {
	exit(1);
}

$apply = in_array( '--apply', $argv, true );
require '/var/www/unicancercenter.com/wp-load.php';

$bases = array(
	'doctor'        => 'bac-si',
	'cancer'        => 'ung-thu',
	'treatment'     => 'phuong-phap-dieu-tri',
	'patient_story' => 'cau-chuyen-benh-nhan',
	'special_topic' => 'chu-de-ung-thu',
);
$old_bases = array(
	'doctor'        => array( 'doctors', 'bac-si' ),
	'cancer'        => array( 'cancers', 'ung-thu' ),
	'treatment'     => array( 'treatments', 'phuong-phap-dieu-tri' ),
	'patient_story' => array( 'patient-stories', 'cau-chuyen-benh-nhan' ),
	'special_topic' => array( 'special-topics', 'chu-de-ung-thu' ),
);
$page_slugs = array(
	6 => 'trang-chu', 7 => 'gioi-thieu', 9 => 'lien-he', 13 => 'chinh-sach-bao-mat',
	14 => 'dich-vu-y-te', 15 => 'mien-tru-trach-nhiem', 180 => 'ung-thu',
	182 => 'bac-si', 183 => 'tin-tuc', 184 => 'cau-chuyen-benh-nhan',
	188 => 'phuong-phap-dieu-tri',
);

function uc_vi_title_slug( $post ) {
	$title = trim( wp_strip_all_tags( $post->post_title ) );
	$title = trim( preg_split( '/\s*[|｜]\s*/u', $title, 2 )[0] ?? $title );
	return sanitize_title( $title ) ?: 'bai-viet-' . $post->ID;
}

function uc_vi_path( $post, $slug, $bases, $page_slugs ) {
	if ( 'page' === $post->post_type ) {
		return (int) get_option( 'page_on_front' ) === (int) $post->ID ? '/' : '/' . ( $page_slugs[ $post->ID ] ?? $slug ) . '/';
	}
	if ( 'post' === $post->post_type ) {
		return '/tin-tuc/' . $slug . '/';
	}
	return isset( $bases[ $post->post_type ] ) ? '/' . $bases[ $post->post_type ] . '/' . $slug . '/' : '/' . $slug . '/';
}

$post_types = array_merge( array( 'page', 'post' ), array_keys( $bases ) );
$posts = get_posts( array( 'post_type' => $post_types, 'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future' ), 'posts_per_page' => -1, 'orderby' => 'ID', 'order' => 'ASC', 'suppress_filters' => false ) );
$planned = array();
$collisions = array();
$redirects = array();
$languages = array( '', 'vi', 'en', 'id', 'zh-cn' );

foreach ( $posts as $post ) {
	$slug = 'doctor' === $post->post_type ? sanitize_title( $post->post_name ) : ( $page_slugs[ $post->ID ] ?? uc_vi_title_slug( $post ) );
	$key = $post->post_type . ':' . $slug;
	if ( isset( $planned[ $key ] ) ) {
		$collisions[ $key ] = array( $planned[ $key ], $post->ID );
		continue;
	}
	$planned[ $key ] = $post->ID;
	$new_path = uc_vi_path( $post, $slug, $bases, $page_slugs );
	$old_path = '/' . ltrim( (string) wp_parse_url( get_permalink( $post ), PHP_URL_PATH ), '/' );
	$old_path = '/' === $old_path ? '/' : trailingslashit( $old_path );
	$old_path = preg_replace( '#^/(?:vi|en|id|zh-cn)(?=/|$)#i', '', $old_path );
	$old_path = '/' === $old_path ? '/' : trailingslashit( $old_path );

	foreach ( $languages as $language ) {
		$prefix = $language ? '/' . $language : '';
		$redirects[ trailingslashit( $prefix . $old_path ) ] = $new_path;
	}
	if ( 'page' === $post->post_type ) {
		foreach ( $languages as $language ) {
			$prefix = $language ? '/' . $language : '';
			$redirects[ trailingslashit( $prefix . '/' . $post->post_name ) ] = $new_path;
		}
	}
	if ( isset( $old_bases[ $post->post_type ] ) ) {
		foreach ( $old_bases[ $post->post_type ] as $old_base ) {
			foreach ( $languages as $language ) {
				$prefix = $language ? '/' . $language : '';
				$redirects[ trailingslashit( $prefix . '/' . $old_base . '/' . $post->post_name ) ] = $new_path;
			}
		}
	}
	$old_slugs = get_post_meta( $post->ID, '_wp_old_slug', false );
	foreach ( $old_slugs as $old_slug ) {
		$legacy_bases = $old_bases[ $post->post_type ] ?? ( 'post' === $post->post_type ? array( 'news', 'tin-tuc' ) : array( '' ) );
		foreach ( $legacy_bases as $old_base ) {
			foreach ( $languages as $language ) {
				$prefix = $language ? '/' . $language : '';
				$redirects[ trailingslashit( $prefix . '/' . trim( $old_base . '/' . $old_slug, '/' ) ) ] = $new_path;
			}
		}
	}

	if ( $apply && $post->post_name !== $slug ) {
		update_post_meta( $post->ID, '_unicancer_pre_vi_slug', $post->post_name );
		wp_update_post( array( 'ID' => $post->ID, 'post_name' => $slug ) );
	}
}

if ( $collisions ) {
	fwrite( STDERR, "Slug collisions detected:\n" . wp_json_encode( $collisions, JSON_PRETTY_PRINT ) . "\n" );
	exit(2);
}

foreach ( array( '/vi/', '/en/', '/id/', '/zh-cn/' ) as $root ) {
	$redirects[ $root ] = '/';
}

if ( $apply ) {
	update_option( 'permalink_structure', '/tin-tuc/%postname%/' );
	update_option( 'unicancer_vi_redirect_map', $redirects, false );
	update_option( 'WPLANG', 'vi' );
	foreach ( get_terms( array( 'taxonomy' => get_taxonomies( array( 'public' => true ), 'names' ), 'hide_empty' => false ) ) as $term ) {
		if ( ! is_wp_error( $term ) ) {
			wp_update_term( $term->term_id, $term->taxonomy, array( 'slug' => sanitize_title( $term->name ) ) );
		}
	}
	if ( ! function_exists( 'deactivate_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	if ( function_exists( 'deactivate_plugins' ) ) {
		deactivate_plugins( array( 'polylang/polylang.php', 'polylang-pro/polylang.php' ), true );
	}
	flush_rewrite_rules( true );
}

echo wp_json_encode( array( 'mode' => $apply ? 'apply' : 'dry-run', 'posts' => count( $posts ), 'redirects' => count( $redirects ), 'collisions' => $collisions ), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) . "\n";
