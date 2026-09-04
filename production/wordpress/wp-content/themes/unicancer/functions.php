<?php
/**
 * Theme bootstrap and helpers for the migrated UNI-ASIA website.
 *
 * @package Unicancer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'UNICANCER_VERSION', '1.0.1' );
define( 'UNICANCER_MIRROR_DIR', get_template_directory() . '/mirror' );

require_once get_template_directory() . '/inc/content-model.php';

/**
 * Temporarily keep the whole website out of search-engine indexes.
 * Remove these hooks and set blog_public back to 1 when SEO is ready.
 */
function unicancer_block_search_indexing( $robots ) {
	$robots['noindex']             = true;
	$robots['nofollow']            = true;
	$robots['noarchive']           = true;
	$robots['noimageindex']        = true;
	$robots['max-image-preview']   = 'none';
	unset( $robots['index'], $robots['follow'] );
	return $robots;
}
add_filter( 'wp_robots', 'unicancer_block_search_indexing', 999 );

function unicancer_send_noindex_header() {
	if ( ! headers_sent() ) {
		header( 'X-Robots-Tag: noindex, nofollow, noarchive, noimageindex', true );
	}
}
add_action( 'send_headers', 'unicancer_send_noindex_header', 999 );

function unicancer_block_robots_txt( $output, $public ) {
	return "User-agent: *\nDisallow: /\n";
}
add_filter( 'robots_txt', 'unicancer_block_robots_txt', 999, 2 );

function unicancer_setup() {
	load_theme_textdomain( 'unicancer', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	register_nav_menus(
		array(
			'primary' => __( 'Primary navigation', 'unicancer' ),
			'footer'  => __( 'Footer navigation', 'unicancer' ),
			'sidebar_cancers' => __( 'Sidebar - Loại ung thư', 'unicancer' ),
			'sidebar_treatments' => __( 'Sidebar - Phương pháp điều trị', 'unicancer' ),
		)
	);
}
add_action( 'after_setup_theme', 'unicancer_setup' );

function unicancer_body_classes( $classes ) {
	$classes[] = 'unicancer-migrated-page';
	return $classes;
}
add_filter( 'body_class', 'unicancer_body_classes' );

/**
 * Return the mirror file corresponding to the current WordPress request.
 */
function unicancer_mirror_file() {
	if ( is_search() ) {
		return '';
	}
	$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ), '/' );
	$home_path = trim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );

	if ( $home_path && 0 === strpos( $path, $home_path ) ) {
		$path = trim( substr( $path, strlen( $home_path ) ), '/' );
	}

	if ( '' === $path ) {
		$path = 'vi';
	} elseif ( preg_match( '#^(?:en|id|zh-cn)(?:/(.*))?$#i', $path, $language_route ) ) {
		// Legacy news/detail routes only exist in the Vietnamese mirror. Serve
		// that content as a safe fallback while keeping navigation in the active
		// language, instead of returning a blank/404 translated URL.
		$path = 'vi/' . ltrim( $language_route[1] ?? '', '/' );
	} elseif ( ! preg_match( '#^vi(?:/|$)#i', $path ) ) {
		$path = 'vi/' . $path;
	}

	$base       = UNICANCER_MIRROR_DIR . '/uniasiacancer.com/';
	$candidates = array(
		$base . $path . '/index.html',
		$base . $path . '.html',
		$base . $path,
	);

	foreach ( $candidates as $candidate ) {
		$real = realpath( $candidate );
		if ( $real && 0 === strpos( wp_normalize_path( $real ), wp_normalize_path( $base ) ) && is_file( $real ) ) {
			return $real;
		}
	}

	return '';
}

/**
 * Resolve the active Polylang language even while the legacy mirror router is
 * handling the request before the main WordPress query is fully populated.
 */
function unicancer_current_language_slug() {
	$lang = function_exists( 'pll_current_language' ) ? pll_current_language( 'slug' ) : '';
	if ( $lang ) { return $lang; }
	$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ), '/' );
	$first = strtok( $path, '/' );
	return in_array( $first, array( 'en', 'id', 'zh-cn', 'vi' ), true ) ? $first : 'vi';
}

/**
 * Keep internal navigation in the active language. If Polylang has a linked
 * translation, use its canonical permalink; otherwise preserve the same route
 * below the requested language instead of silently returning to Vietnamese.
 */
function unicancer_localize_internal_url( $url, $lang = '' ) {
	$lang = $lang ?: unicancer_current_language_slug();
	if ( 'vi' === $lang || '' === $url || '#' === $url[0] ) { return $url; }
	$parts = wp_parse_url( html_entity_decode( $url, ENT_QUOTES, 'UTF-8' ) );
	if ( false === $parts ) { return $url; }
	$host = $parts['host'] ?? '';
	$site_host = (string) wp_parse_url( home_url( '/' ), PHP_URL_HOST );
	if ( $host && strtolower( $host ) !== strtolower( $site_host ) && ! in_array( strtolower( $host ), array( 'uniasiacancer.com', 'www.uniasiacancer.com' ), true ) ) { return $url; }
	$path = '/' . ltrim( $parts['path'] ?? '/', '/' );
	if ( preg_match( '#^/(?:wp-admin|wp-login\.php|wp-json|wp-content|wp-includes)(?:/|$)#i', $path ) ) { return $url; }
	$path = preg_replace( '#^/(?:vi|en|id|zh-cn)(?=/|$)#i', '', $path );
	if ( preg_match( '#^/(?:home|index)/?$#i', $path ) ) { $path = '/'; }
	$site_root = untrailingslashit( (string) get_option( 'home' ) );
	$base_url = $site_root . ( '/' === $path ? '/' : $path );
	$post_id = url_to_postid( $base_url );
	if ( $post_id && function_exists( 'pll_get_post' ) ) {
		$translation_id = (int) pll_get_post( $post_id, $lang );
		if ( $translation_id ) {
			$translated = get_permalink( $translation_id );
			if ( $translated ) {
				$url = $translated;
				goto unicancer_localized_url_suffix;
			}
		}
	}
	$url = $site_root . '/' . $lang . ( '/' === $path ? '/' : $path );

	unicancer_localized_url_suffix:
	if ( isset( $parts['query'] ) && false === strpos( $url, '?' ) ) { $url .= '?' . $parts['query']; }
	if ( isset( $parts['fragment'] ) ) { $url .= '#' . $parts['fragment']; }
	return $url;
}

/**
 * Redirect a language-prefixed Vietnamese slug to the real translated post.
 * This runs before Polylang's canonical redirect, which would otherwise send
 * visitors from /en/<vietnamese-slug>/ back to the Vietnamese post.
 */
function unicancer_redirect_prefixed_source_slug() {
	if ( is_admin() || wp_doing_ajax() || ! function_exists( 'pll_get_post' ) ) { return; }
	$path = (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH );
	if ( ! preg_match( '#^/(en|id|zh-cn)/(.+?)/?$#i', $path, $match ) ) { return; }
	$lang = strtolower( $match[1] );
	$source_url = home_url( '/' . trim( $match[2], '/' ) . '/' );
	$source_id = (int) url_to_postid( $source_url );
	if ( ! $source_id || 'vi' !== pll_get_post_language( $source_id, 'slug' ) ) { return; }
	$translated_id = (int) pll_get_post( $source_id, $lang );
	if ( ! $translated_id ) { return; }
	$destination = get_permalink( $translated_id );
	if ( ! $destination ) { return; }
	if ( ! empty( $_SERVER['QUERY_STRING'] ) ) { $destination .= '?' . $_SERVER['QUERY_STRING']; }
	wp_safe_redirect( $destination, 301, 'UNI-ASIA language routing' );
	exit;
}
add_action( 'template_redirect', 'unicancer_redirect_prefixed_source_slug', 0 );

/**
 * Resolve top-level news posts by both slug and requested language. AI-created
 * Polylang drafts can temporarily share the Vietnamese source slug; WordPress
 * otherwise selects an arbitrary matching row for logged-in editors, causing
 * a `/vi/.../` URL to render Indonesian (or another language) content.
 */
function unicancer_serve_language_matched_news_post() {
	if ( is_admin() || wp_doing_ajax() ) { return; }
	$path = (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH );
	if ( ! preg_match( '#^/(vi|en|id|zh-cn)/([^/]+)/?$#i', $path, $match ) ) { return; }
	$lang = strtolower( $match[1] );
	$slug = sanitize_title( rawurldecode( $match[2] ) );
	$candidates = get_posts(
		array(
			'name'           => $slug,
			'post_type'      => 'post',
			'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'future' ),
			'posts_per_page' => -1,
			'orderby'        => 'ID',
			'order'          => 'ASC',
			'suppress_filters' => true,
		)
	);
	foreach ( $candidates as $candidate ) {
		if ( ! function_exists( 'pll_get_post_language' ) || $lang !== pll_get_post_language( $candidate->ID, 'slug' ) ) { continue; }
		if ( 'publish' !== $candidate->post_status && ! current_user_can( 'read_post', $candidate->ID ) ) { continue; }
		status_header( 200 );
		nocache_headers();
		echo unicancer_render_wordpress_page( $candidate ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit;
	}
}
add_action( 'template_redirect', 'unicancer_serve_language_matched_news_post', -200 );

/**
 * Serve legacy numeric news articles below the selected language prefix.
 * These articles have no separate Polylang post yet; without this early
 * fallback WordPress canonicalizes `/en/news/21/` to the translated News
 * archive and visitors can never open the card they selected.
 */
function unicancer_serve_translated_legacy_news() {
	if ( is_admin() || wp_doing_ajax() ) { return; }
	$path = (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH );
	if ( ! preg_match( '#^/(?:en|id|zh-cn)/news/[0-9]+/?$#i', $path ) ) { return; }
	$file = unicancer_mirror_file();
	if ( ! $file ) { return; }
	status_header( 200 );
	nocache_headers();
	echo unicancer_render_mirror( $file ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit;
}
add_action( 'template_redirect', 'unicancer_serve_translated_legacy_news', -100 );

/**
 * Convert a URL found in the static snapshot to its WordPress/local equivalent.
 */
function unicancer_migrate_url( $url, $source_file ) {
	$url = html_entity_decode( trim( $url ), ENT_QUOTES, 'UTF-8' );
	if ( '' === $url || '#' === $url[0] || 0 === strpos( $url, 'data:' ) || 0 === strpos( $url, 'mailto:' ) || 0 === strpos( $url, 'tel:' ) || 0 === strpos( $url, 'javascript:' ) ) {
		return $url;
	}

	$fragment = '';
	if ( false !== strpos( $url, '#' ) ) {
		list( $url, $hash ) = explode( '#', $url, 2 );
		$fragment = '#' . $hash;
	}

	$mirror_url = trailingslashit( get_template_directory_uri() ) . 'mirror/';
	$host = (string) wp_parse_url( $url, PHP_URL_HOST );

	if ( in_array( $host, array( 'huan-ya.oss-ap-southeast-1.aliyuncs.com', 'www.huan-ya.oss-ap-southeast-1.aliyuncs.com' ), true ) ) {
		$asset_path = 'huan-ya.oss-ap-southeast-1.aliyuncs.com/' . ltrim( (string) wp_parse_url( $url, PHP_URL_PATH ), '/' );
		$local_file = UNICANCER_MIRROR_DIR . '/' . $asset_path;
		return is_file( $local_file ) ? $mirror_url . $asset_path . $fragment : $url . $fragment;
	}

	if ( in_array( $host, array( 'unicancercenter.com', 'www.unicancercenter.com' ), true ) ) {
		$path = (string) wp_parse_url( $url, PHP_URL_PATH );
		if ( preg_match( '#^/(vi|en|id|zh-cn)(?:/|$)#', $path, $path_language ) ) {
			// URLs saved in translated content are already intentional localized
			// routes. Preserve them verbatim: resolving `/news/21/` through
			// url_to_postid() can incorrectly match the News parent page and make
			// every article card point back to the archive.
			return home_url( '/' . ltrim( $path, '/' ) ) . $fragment;
		}
		return unicancer_localize_internal_url( home_url( '/' . ltrim( $path, '/' ) ) . $fragment );
	}

	if ( in_array( $host, array( 'uniasiacancer.com', 'www.uniasiacancer.com' ), true ) ) {
		$path = (string) wp_parse_url( $url, PHP_URL_PATH );
		return unicancer_localize_internal_url( home_url( '/' . ltrim( preg_replace( '#^/(?:vi|en|id|zh-cn)(?:/|$)#', '/', $path ), '/' ) ) . $fragment );
	}

	if ( preg_match( '#^(?:https?:)?//#', $url ) ) {
		return $url . $fragment;
	}

	$source_root = wp_normalize_path( UNICANCER_MIRROR_DIR ) . '/';
	$absolute    = realpath( dirname( $source_file ) . '/' . $url );
	if ( $absolute && 0 === strpos( wp_normalize_path( $absolute ), $source_root ) ) {
		$relative = ltrim( substr( wp_normalize_path( $absolute ), strlen( $source_root ) ), '/' );
		if ( preg_match( '/\.html$/i', $relative ) && 0 === strpos( $relative, 'uniasiacancer.com/vi/' ) ) {
			$route = preg_replace( array( '#^uniasiacancer\.com/vi/?#', '#/index\.html$#', '#\.html$#' ), array( '', '', '' ), $relative );
			return unicancer_localize_internal_url( home_url( '/' . trim( $route, '/' ) . '/' ) . $fragment );
		}
		return $mirror_url . $relative . $fragment;
	}

	// A number of snapshot links point to an old `.html` route while the mirror
	// stores the destination as `<route>/index.html`. `realpath()` above cannot
	// resolve those aliases. Never pass the unresolved `../` URL to `esc_url()`:
	// WordPress would turn it into the invalid absolute URL `http://../...`.
	if ( preg_match( '#^(?:\.\./)+(.+)$#', $url, $relative_match ) ) {
		$relative_path = preg_replace( '#[?#].*$#', '', $relative_match[1] );
		$relative_path = preg_replace( '#(?:^|/)index\.html$#i', '', $relative_path );
		$relative_path = preg_replace( '#\.html$#i', '', $relative_path );
		$relative_path = trim( $relative_path, '/' );

		// Keep only the public route from a path that crossed several directories.
		if ( preg_match( '#(?:^|/)(doctors|treatments|cancers|patient-stories|special-topics|services|news|about-us|contact-us|privacy-policy|statement)(?:/(.*))?$#i', $relative_path, $route_match ) ) {
			$relative_path = $route_match[1] . ( empty( $route_match[2] ) ? '' : '/' . $route_match[2] );
		}

		return unicancer_localize_internal_url( home_url( $relative_path ? '/' . $relative_path . '/' : '/' ) . $fragment );
	}

	return $url . $fragment;
}

/**
 * Resolve asset URLs kept inside imported CPT content.
 *
 * Imported profiles intentionally retain the original snapshot markup so they
 * remain editable in WordPress. Relative image URLs in that markup need the
 * snapshot file as their base; otherwise the browser resolves them below the
 * public /doctors/ route and returns 404 responses.
 */
function unicancer_migrate_imported_content_urls( $content ) {
	if ( is_admin() || ! is_singular( array( 'doctor', 'cancer', 'treatment', 'patient_story' ) ) ) {
		return $content;
	}

	$post = get_queried_object();
	if ( ! $post instanceof WP_Post ) {
		return $content;
	}

	$routes = array(
		'doctor'        => 'doctors',
		'cancer'        => 'cancers',
		'treatment'     => 'treatments',
		'patient_story' => 'patient-stories',
	);
	$route = $routes[ $post->post_type ] ?? '';
	$file  = unicancer_mirror_file();
	if ( ! $file ) {
		$file = UNICANCER_MIRROR_DIR . '/uniasiacancer.com/vi/' . $route . '/' . $post->post_name . '/index.html';
	}
	if ( ! $route || ! is_file( $file ) ) {
		return $content;
	}

	$content = preg_replace_callback(
		'/\b(href|src|action)=(["\'])([^"\']*)\2/i',
		function ( $matches ) use ( $file ) {
			return $matches[1] . '=' . $matches[2] . esc_url( unicancer_migrate_url( $matches[3], $file ) ) . $matches[2];
		},
		$content
	);

	return preg_replace_callback(
		'/url\(\s*(["\']?)([^)"\']+)\1\s*\)/',
		function ( $matches ) use ( $file ) {
			return "url('" . esc_url( unicancer_migrate_url( $matches[2], $file ) ) . "')";
		},
		$content
	);
}
add_filter( 'the_content', 'unicancer_migrate_imported_content_urls', 7 );

/**
 * Header language selector. Polylang supplies the URL of the matching
 * translation; when a page has no translation yet its language homepage is
 * used, so visitors never land on an invalid handcrafted URL.
 */
function unicancer_language_switcher_html() {
	$labels = array(
		'vi' => 'Tiếng Việt',
		'en' => 'English',
		'id' => 'Bahasa Indonesia',
		'zh-cn' => '简体中文',
	);
	$current = function_exists( 'pll_current_language' ) ? pll_current_language( 'slug' ) : 'vi';
	$current = $current ?: 'vi';
	$languages = array();
	if ( function_exists( 'pll_the_languages' ) ) {
		$raw = pll_the_languages( array( 'raw' => 1, 'hide_if_empty' => 0, 'hide_if_no_translation' => 0 ) );
		$request_path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ), '/' );
		$is_language_home = '' === $request_path || in_array( $request_path, array( 'vi', 'en', 'id', 'zh-cn' ), true );
		$current_post_id = get_queried_object_id();
		foreach ( (array) $raw as $language ) {
			$slug = $language['slug'] ?? '';
			if ( ! isset( $labels[ $slug ] ) ) { continue; }
			$url = '';
			if ( $is_language_home ) {
				$url = trailingslashit( untrailingslashit( get_option( 'home' ) ) . '/' . $slug );
			} elseif ( $current_post_id && function_exists( 'pll_get_post' ) ) {
				$translated_id = (int) pll_get_post( $current_post_id, $slug );
				if ( $translated_id ) { $url = get_permalink( $translated_id ); }
			}
			if ( ! $url ) {
				$current_url = untrailingslashit( (string) get_option( 'home' ) ) . '/' . ltrim( $request_path, '/' );
				$url = 'vi' === $slug ? preg_replace( '#/(?:en|id|zh-cn)(?=/|$)#', '', $current_url, 1 ) : unicancer_localize_internal_url( $current_url, $slug );
			}
			if ( ! $url && function_exists( 'pll_home_url' ) ) { $url = pll_home_url( $slug ); }
			$languages[ $slug ] = array( 'label' => $labels[ $slug ], 'url' => $url ?: home_url( '/' ), 'current' => ! empty( $language['current_lang'] ) );
		}
	}
	if ( ! $languages ) {
		$languages['vi'] = array( 'label' => $labels['vi'], 'url' => home_url( '/' ), 'current' => true );
	}
	$label = $labels[ $current ] ?? strtoupper( $current );
	$html = '<div class="flex-none lang-dropdown mr-2 ml-1 group relative" data-lang-dropdown data-open="false">';
	$html .= '<button class="h-8 flex items-center cursor-pointer text-white lg:text-gray-500 lg:group-hover:text-primary" type="button" aria-label="Chuyển ngôn ngữ" aria-expanded="false" data-lang-trigger>';
	$html .= '<span class="icon-[lucide--languages]"></span><span class="hidden lg:inline px-1 text-sm">' . esc_html( $label ) . '</span><span class="icon-[lucide--chevron-down]" data-lang-chevron></span></button>';
	$html .= '<ul class="hidden bg-white rounded-lg absolute right-0 z-50 shadow text-sm p-2 text-gray-850" data-lang-menu>';
	foreach ( $languages as $slug => $language ) {
		$html .= '<li><a class="flex leading-8 px-2 rounded hover:bg-gray-100 hover:text-primary whitespace-nowrap active:text-primary active:bg-gray-100" href="' . esc_url( $language['url'] ) . '" data-locale-option="' . esc_attr( $slug ) . '"' . ( $language['current'] ? ' aria-current="page"' : '' ) . '>' . esc_html( $language['label'] ) . '</a></li>';
	}
	$html .= '</ul></div>';
	$html .= '<script>(function(){document.querySelectorAll("[data-lang-dropdown]").forEach(function(box){var trigger=box.querySelector("[data-lang-trigger]"),menu=box.querySelector("[data-lang-menu]");if(!trigger||!menu)return;var close=function(){box.dataset.open="false";trigger.setAttribute("aria-expanded","false");menu.classList.add("hidden")};trigger.addEventListener("click",function(event){event.stopPropagation();var open=box.dataset.open!=="true";document.querySelectorAll("[data-lang-dropdown]").forEach(function(other){if(other!==box){other.dataset.open="false";var otherMenu=other.querySelector("[data-lang-menu]");if(otherMenu)otherMenu.classList.add("hidden")}});box.dataset.open=String(open);trigger.setAttribute("aria-expanded",String(open));menu.classList.toggle("hidden",!open)});document.addEventListener("click",function(event){if(!box.contains(event.target))close()});window.addEventListener("pagehide",close)})})();</script>';
	return $html;
}

/** Translate labels in the captured Vietnamese header and footer shell. */
function unicancer_localize_header_navigation( $html, $lang ) {
	$maps = array(
		'en' => array(
			'Tòa nhà Tràng An Complex, số 1 Phùng Chí Kiên, phường Nghĩa Đô, Hà Nội (Tòa nhà Trung tâm Dịch vụ thị thực Trung Quốc)' => 'Trang An Complex Building, No. 1 Phung Chi Kien Street, Nghia Do Ward, Hanoi (China Visa Application Service Center Building)',
			'Kỹ thuật truyền thuốc và nút mạch qua đường động mạch' => 'Transarterial chemoembolization (TACE)', 'Kỹ thuật tiêu hủy khối u bằng dao nano' => 'NanoKnife tumor ablation', 'Kỹ thuật cấy hạt phóng xạ I-125' => 'I-125 radioactive seed implantation', 'Kỹ thuật áp lạnh khối u bằng dao Argon-Helium' => 'Argon-Helium cryoablation', 'Kỹ thuật đốt u bằng sóng cao tần' => 'Radiofrequency ablation', 'Kỹ thuật đốt u bằng vi sóng' => 'Microwave ablation', 'Tạo hình thân đốt sống qua da' => 'Percutaneous vertebroplasty',
			'Cơ sở đào tạo đốt u chính xác WATA' => 'WATA Precision Tumor Ablation Training Base', 'Chuyên gia ung bướu hàng đầu' => 'Leading oncology experts', 'Công nghệ điều trị ung thư' => 'Cancer treatment technology', 'Trung tâm Dịch vụ Hà Nội, Việt Nam' => 'Hanoi Service Center, Vietnam', 'Thông tin trên website chỉ mang tính chất tham khảo, không thay thế chẩn đoán và điều trị của bác sĩ.' => 'The information on this website is for reference only and does not replace a doctor\'s diagnosis or treatment.',
			'Các bác sĩ của chúng tôi' => 'Our doctors', 'Câu chuyện của bệnh nhân' => 'Patient stories', 'Tin tức & Sự kiện' => 'News & Events', 'News & Sự kiện' => 'News & Events', 'Liên hệ với chúng tôi' => 'Contact us', 'Về UNI-ASIA' => 'About UNI-ASIA', 'Dịch vụ của chúng tôi' => 'Our services', 'Danh dự và thành tựu' => 'Honors and achievements', 'Dịch vụ và chăm sóc' => 'Services and care', 'Môi trường bệnh viện' => 'Hospital environment', 'Chủ đề ung thư gan' => 'Liver cancer topics', 'Thiết bị tiên tiến' => 'Advanced equipment', 'Hội chẩn chuyên gia' => 'Expert consultation', 'Hướng dẫn khám chữa bệnh' => 'Patient guide', 'Dịch vụ nổi bật' => 'Featured services', 'Du lịch y tế' => 'Medical tourism',
			'Ung thư đại trực tràng' => 'Colorectal cancer', 'Ung thư cổ tử cung' => 'Cervical cancer', 'Ung thư tuyến tụy' => 'Pancreatic cancer', 'Ung thư thực quản' => 'Esophageal cancer', 'Ung thư phổi' => 'Lung cancer', 'Ung thư gan' => 'Liver cancer', 'Ung thư vú' => 'Breast cancer', 'Ung thư dạ dày' => 'Stomach cancer', 'Kỹ thuật đặt stent' => 'Stent placement',
			'Chính sách bảo mật' => 'Privacy Policy', 'Tuyên bố miễn trừ trách nhiệm' => 'Disclaimer', 'Theo dõi chúng tôi' => 'Follow us', 'Phương pháp điều trị' => 'Treatment methods', 'Loại ung thư' => 'Cancer types', 'Bệnh viện UNI-ASIA' => 'UNI-ASIA Hospital', 'Trung tâm ung thư' => 'Cancer Center',
			'Trang chủ' => 'Home', 'Về chúng tôi' => 'About Us', 'Đội ngũ MDT' => 'MDT Team', 'Chủ đề ung thư' => 'Cancer Topics', 'Ung thư' => 'Cancers', 'Trung tâm điều trị' => 'Treatment Center', 'Dịch vụ y tế' => 'Medical Services', 'Câu chuyện bệnh nhân' => 'Patient Stories', 'Tin tức' => 'News', 'Liên hệ' => 'Contact', 'Tư vấn' => 'Consultation', 'Tìm kiếm' => 'Search', '*Tuyên bố:' => '*Disclaimer:',
		),
		'id' => array(
			'Tòa nhà Tràng An Complex, số 1 Phùng Chí Kiên, phường Nghĩa Đô, Hà Nội (Tòa nhà Trung tâm Dịch vụ thị thực Trung Quốc)' => 'Gedung Trang An Complex, No. 1 Phung Chi Kien, Kelurahan Nghia Do, Hanoi (Gedung Pusat Layanan Aplikasi Visa Tiongkok)',
			'Kỹ thuật truyền thuốc và nút mạch qua đường động mạch' => 'Kemoembolisasi transarterial (TACE)', 'Kỹ thuật tiêu hủy khối u bằng dao nano' => 'Ablasi tumor NanoKnife', 'Kỹ thuật cấy hạt phóng xạ I-125' => 'Implantasi biji radioaktif I-125', 'Kỹ thuật áp lạnh khối u bằng dao Argon-Helium' => 'Krioablasi Argon-Helium', 'Kỹ thuật đốt u bằng sóng cao tần' => 'Ablasi frekuensi radio', 'Kỹ thuật đốt u bằng vi sóng' => 'Ablasi gelombang mikro', 'Tạo hình thân đốt sống qua da' => 'Vertebroplasti perkutan',
			'Cơ sở đào tạo đốt u chính xác WATA' => 'Pusat Pelatihan Ablasi Tumor Presisi WATA', 'Chuyên gia ung bướu hàng đầu' => 'Pakar onkologi terkemuka', 'Công nghệ điều trị ung thư' => 'Teknologi pengobatan kanker', 'Trung tâm Dịch vụ Hà Nội, Việt Nam' => 'Pusat Layanan Hanoi, Vietnam', 'Thông tin trên website chỉ mang tính chất tham khảo, không thay thế chẩn đoán và điều trị của bác sĩ.' => 'Informasi di situs web ini hanya untuk referensi dan tidak menggantikan diagnosis atau perawatan dokter.',
			'Các bác sĩ của chúng tôi' => 'Dokter kami', 'Câu chuyện của bệnh nhân' => 'Kisah pasien', 'Tin tức & Sự kiện' => 'Berita & Acara', 'News & Sự kiện' => 'Berita & Acara', 'Liên hệ với chúng tôi' => 'Hubungi kami', 'Về UNI-ASIA' => 'Tentang UNI-ASIA', 'Dịch vụ của chúng tôi' => 'Layanan kami', 'Danh dự và thành tựu' => 'Penghargaan dan prestasi', 'Dịch vụ và chăm sóc' => 'Layanan dan perawatan', 'Môi trường bệnh viện' => 'Lingkungan rumah sakit', 'Chủ đề ung thư gan' => 'Topik kanker hati', 'Thiết bị tiên tiến' => 'Peralatan canggih', 'Hội chẩn chuyên gia' => 'Konsultasi ahli', 'Hướng dẫn khám chữa bệnh' => 'Panduan pasien', 'Dịch vụ nổi bật' => 'Layanan unggulan', 'Du lịch y tế' => 'Wisata medis',
			'Ung thư đại trực tràng' => 'Kanker kolorektal', 'Ung thư cổ tử cung' => 'Kanker serviks', 'Ung thư tuyến tụy' => 'Kanker pankreas', 'Ung thư thực quản' => 'Kanker esofagus', 'Ung thư phổi' => 'Kanker paru-paru', 'Ung thư gan' => 'Kanker hati', 'Ung thư vú' => 'Kanker payudara', 'Ung thư dạ dày' => 'Kanker lambung', 'Kỹ thuật đặt stent' => 'Pemasangan stent',
			'Chính sách bảo mật' => 'Kebijakan Privasi', 'Tuyên bố miễn trừ trách nhiệm' => 'Penafian', 'Theo dõi chúng tôi' => 'Ikuti kami', 'Phương pháp điều trị' => 'Metode pengobatan', 'Loại ung thư' => 'Jenis kanker', 'Bệnh viện UNI-ASIA' => 'Rumah Sakit UNI-ASIA', 'Trung tâm ung thư' => 'Pusat Kanker',
			'Trang chủ' => 'Beranda', 'Về chúng tôi' => 'Tentang Kami', 'Đội ngũ MDT' => 'Tim MDT', 'Chủ đề ung thư' => 'Topik Kanker', 'Ung thư' => 'Kanker', 'Trung tâm điều trị' => 'Pusat Perawatan', 'Dịch vụ y tế' => 'Layanan Medis', 'Câu chuyện bệnh nhân' => 'Kisah Pasien', 'Tin tức' => 'Berita', 'Liên hệ' => 'Kontak', 'Tư vấn' => 'Konsultasi', 'Tìm kiếm' => 'Cari', '*Tuyên bố:' => '*Penafian:',
		),
		'zh-cn' => array(
			'Tòa nhà Tràng An Complex, số 1 Phùng Chí Kiên, phường Nghĩa Đô, Hà Nội (Tòa nhà Trung tâm Dịch vụ thị thực Trung Quốc)' => '越南河内义都坊冯志坚路1号长安综合大楼（中国签证申请服务中心大楼）',
			'Kỹ thuật truyền thuốc và nút mạch qua đường động mạch' => '经动脉灌注化疗栓塞术', 'Kỹ thuật tiêu hủy khối u bằng dao nano' => '纳米刀肿瘤消融术', 'Kỹ thuật cấy hạt phóng xạ I-125' => '碘-125放射性粒子植入术', 'Kỹ thuật áp lạnh khối u bằng dao Argon-Helium' => '氩氦刀冷冻消融术', 'Kỹ thuật đốt u bằng sóng cao tần' => '射频消融术', 'Kỹ thuật đốt u bằng vi sóng' => '微波消融术', 'Tạo hình thân đốt sống qua da' => '经皮椎体成形术',
			'Cơ sở đào tạo đốt u chính xác WATA' => 'WATA精准肿瘤消融培训基地', 'Chuyên gia ung bướu hàng đầu' => '顶尖肿瘤专家', 'Công nghệ điều trị ung thư' => '癌症治疗技术', 'Trung tâm Dịch vụ Hà Nội, Việt Nam' => '越南河内服务中心', 'Thông tin trên website chỉ mang tính chất tham khảo, không thay thế chẩn đoán và điều trị của bác sĩ.' => '本网站信息仅供参考，不能替代医生的诊断和治疗。',
			'Các bác sĩ của chúng tôi' => '我们的医生', 'Câu chuyện của bệnh nhân' => '患者故事', 'Tin tức & Sự kiện' => '新闻与活动', 'News & Sự kiện' => '新闻与活动', 'Liên hệ với chúng tôi' => '联系我们', 'Về UNI-ASIA' => '关于UNI-ASIA', 'Dịch vụ của chúng tôi' => '我们的服务', 'Danh dự và thành tựu' => '荣誉与成就', 'Dịch vụ và chăm sóc' => '服务与关怀', 'Môi trường bệnh viện' => '医院环境', 'Chủ đề ung thư gan' => '肝癌专题', 'Thiết bị tiên tiến' => '先进设备', 'Hội chẩn chuyên gia' => '专家会诊', 'Hướng dẫn khám chữa bệnh' => '就医指南', 'Dịch vụ nổi bật' => '特色服务', 'Du lịch y tế' => '医疗旅游',
			'Ung thư đại trực tràng' => '结直肠癌', 'Ung thư cổ tử cung' => '宫颈癌', 'Ung thư tuyến tụy' => '胰腺癌', 'Ung thư thực quản' => '食管癌', 'Ung thư phổi' => '肺癌', 'Ung thư gan' => '肝癌', 'Ung thư vú' => '乳腺癌', 'Ung thư dạ dày' => '胃癌', 'Kỹ thuật đặt stent' => '支架置入术',
			'Chính sách bảo mật' => '隐私政策', 'Tuyên bố miễn trừ trách nhiệm' => '免责声明', 'Theo dõi chúng tôi' => '关注我们', 'Phương pháp điều trị' => '治疗方法', 'Loại ung thư' => '癌症类型', 'Bệnh viện UNI-ASIA' => 'UNI-ASIA医院', 'Trung tâm ung thư' => '肿瘤中心',
			'Trang chủ' => '首页', 'Về chúng tôi' => '关于我们', 'Đội ngũ MDT' => 'MDT团队', 'Chủ đề ung thư' => '癌症专题', 'Ung thư' => '癌症', 'Trung tâm điều trị' => '治疗中心', 'Dịch vụ y tế' => '医疗服务', 'Câu chuyện bệnh nhân' => '患者故事', 'Tin tức' => '新闻', 'Liên hệ' => '联系我们', 'Tư vấn' => '咨询', 'Tìm kiếm' => '搜索', '*Tuyên bố:' => '*免责声明：',
		),
	);
	if ( empty( $maps[ $lang ] ) ) { return $html; }
	return preg_replace_callback(
		'#<(header|footer)\b.*?</\1>#isu',
		function ( $match ) use ( $maps, $lang ) {
			$shell = str_replace( array( '&nbsp;', "\xC2\xA0" ), ' ', $match[0] );
			$shell = str_replace( array( 'Tin tức &amp; Sự kiện', 'News &amp; Sự kiện' ), array( 'Tin tức & Sự kiện', 'News & Sự kiện' ), $shell );
			return strtr( $shell, $maps[ $lang ] );
		},
		$html
	);
}

/**
 * Prepare a mirrored page for output by WordPress.
 */
function unicancer_render_mirror( $file, $html_override = null ) {
	$html = null !== $html_override ? $html_override : file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( false === $html ) {
		return '';
	}

	$html = preg_replace( '#<!-- Mirrored.*?-->#s', '', $html );
	$html = preg_replace( '#<!-- Added by HTTrack -->.*?<!-- /Added by HTTrack -->#s', '', $html );
	// The migrated shell does not call wp_head(), so add the temporary SEO block
	// directly to every rendered page as well.
	$html = preg_replace( '#<meta\s+name=["\']robots["\'][^>]*>#i', '', $html );
	$html = preg_replace( '#</head>#i', '<meta name="robots" content="noindex,nofollow,noarchive,noimageindex,max-image-preview:none"></head>', $html, 1 );

	// Submit consultation forms to WordPress and store entries in the admin area.
	$form_script = '<script>(function(){document.querySelectorAll(".consultation-form").forEach(function(form){form.addEventListener("submit",async function(event){event.preventDefault();event.stopImmediatePropagation();var button=form.querySelector("button[type=submit]"),status=form.querySelector(".form-status"),data=new FormData(form);data.append("action","unicancer_submit_consultation");data.append("nonce","' . esc_js( wp_create_nonce( 'unicancer_consultation' ) ) . '");data.append("source_url",window.location.href);if(button){button.disabled=true;button.textContent=form.dataset.submittingText||"Đang gửi..."}if(status){status.classList.remove("hidden");status.textContent=""}try{var response=await fetch("' . esc_url( admin_url( 'admin-ajax.php' ) ) . '",{method:"POST",body:data,credentials:"same-origin"}),json=await response.json();if(!response.ok||!json.success)throw new Error(json.data&&json.data.message?json.data.message:(form.dataset.errorText||"Gửi không thành công."));if(status){status.textContent=json.data.message;status.style.color="#159648"}form.reset();var dialog=document.getElementById("success-modal");if(dialog&&dialog.showModal)dialog.showModal()}catch(error){if(status){status.textContent=error.message;status.style.color="#dc2626"}}finally{if(button){button.disabled=false;button.textContent=form.dataset.submitText||"Đặt lịch với chuyên gia ngay"}}},true)})})();</script>';
	$html = preg_replace( '#<script[^>]+src="[^"]*Form\.astro[^"]*"[^>]*></script>#i', $form_script, $html );

	// Header logos are editable in Appearance > Customize > Header UNI-ASIA.
	$desktop_logo = get_theme_mod( 'unicancer_header_logo', get_template_directory_uri() . '/mirror/huan-ya.oss-ap-southeast-1.aliyuncs.com/frontend/logo.png' );
	$mobile_logo  = get_theme_mod( 'unicancer_mobile_logo', get_template_directory_uri() . '/mirror/huan-ya.oss-ap-southeast-1.aliyuncs.com/frontend/layout/logo-short-b.png' );
	$partner_logo = get_theme_mod( 'unicancer_partner_logo', get_template_directory_uri() . '/mirror/huan-ya.oss-ap-southeast-1.aliyuncs.com/frontend/layout/wata-g.png' );
	$html = preg_replace( '/(<img\b[^>]*class="[^"]*hidden lg:block[^"]*"[^>]*\bsrc=")[^"]*("[^>]*alt="UNI-ASIA Logo")/i', '$1' . esc_url( $desktop_logo ) . '$2', $html, 1 );
	$html = preg_replace( '/(<img\b[^>]*class="[^"]*lg:hidden[^"]*"[^>]*\bsrc=")[^"]*("[^>]*alt="UNI-ASIA Logo")/i', '$1' . esc_url( $mobile_logo ) . '$2', $html, 1 );
	if ( get_theme_mod( 'unicancer_show_partner_logo', true ) ) {
		$html = preg_replace( '/(<img\b[^>]*\bsrc=")[^"]*("[^>]*alt="WATA Logo")/i', '$1' . esc_url( $partner_logo ) . '$2', $html, 1 );
	} else {
		$html = preg_replace( '#<picture>\s*<source[^>]*>\s*<img[^>]*alt="WATA Logo"[^>]*>\s*</picture>#i', '', $html, 1 );
	}

	// Footer identity and contact details are editable in Appearance > Customize.
	$html = preg_replace_callback( '#<footer\b[^>]*>.*?</footer>#s', 'unicancer_customize_footer_html', $html, 1 );

	// Replace the snapshot search widget with a native WordPress search form.
	$search_form = '<form role="search" method="get" action="' . esc_url( home_url( '/' ) ) . '" class="mr-4 hidden lg:flex">'
		. '<input class="search-input w-50 h-8 pl-2 text-sm rounded-sm border border-gray-300 focus:outline-none focus:border-blue-500 focus:w-60 transition-[width] duration-300 ease-in-out" type="search" name="s" value="' . esc_attr( get_search_query() ) . '" placeholder="Tìm kiếm">'
		. '<button class="search-icon w-10 h-8 flex items-center justify-center -ml-10 cursor-pointer relative z-1" type="submit" aria-label="Tìm kiếm"><span class="size-4 icon-[lucide--search] text-primary"></span></button></form>';
	$html = preg_replace( '#<label for="uniasia-search"[^>]*>.*?</label>\s*<a\b[^>]*aria-label="Go Search"[^>]*>.*?</a>\s*<script[^>]*>.*?</script>#s', $search_form, $html, 1 );

	$html = unicancer_localize_header_navigation( $html, unicancer_current_language_slug() );

	$html = preg_replace_callback(
		'/\b(href|src|action)=([' . "\"'" . '])([^' . "\"'" . ']*)\2/i',
		function ( $matches ) use ( $file ) {
			return $matches[1] . '=' . $matches[2] . esc_url( unicancer_migrate_url( $matches[3], $file ) ) . $matches[2];
		},
		$html
	);

	$html = preg_replace_callback(
		'/\bsrcset=([' . "\"'" . '])([^' . "\"'" . ']*)\1/i',
		function ( $matches ) use ( $file ) {
			$items = array_map(
				function ( $item ) use ( $file ) {
					$parts = preg_split( '/\s+/', trim( $item ), 2 );
					return unicancer_migrate_url( $parts[0], $file ) . ( isset( $parts[1] ) ? ' ' . $parts[1] : '' );
				},
				explode( ',', $matches[2] )
			);
			return 'srcset=' . $matches[1] . esc_attr( implode( ', ', $items ) ) . $matches[1];
		},
		$html
	);

	// Insert the Polylang selector only after migrating snapshot URLs. Otherwise
	// its already-canonical language links would be localized a second time and
	// all choices would collapse to the current language URL.
	$html = preg_replace(
		'#<div class="flex-none lang-dropdown.*?</div>\s*<script type="module">const n=e=>.*?</script>#s',
		unicancer_language_switcher_html(),
		$html,
		1
	);

	// The translated homepages still reference protected OSS banner files which
	// return HTTP 403. Keep the translated links/text, but serve the matching
	// banner positions from assets bundled with this theme so every locale has a
	// reliable desktop and mobile image.
	if ( false !== strpos( $html, 'home-carousel' ) ) {
		$banner_fallbacks = array(
			1 => 'ms5lo61o-302cf657.jpg',
			2 => 'ms5ln4kk-edf47357.jpg',
			3 => 'ms5lm8g6-50d4cba4.jpg',
		);
		$banner_base = trailingslashit( get_template_directory_uri() ) . 'mirror/huan-ya.oss-ap-southeast-1.aliyuncs.com/media/';
		$html = preg_replace_callback(
			'#<a\b([^>]*\baria-label="Go to banner\s+([0-9]+)"[^>]*)>(.*?)</a>#is',
			function ( $matches ) use ( $banner_fallbacks, $banner_base ) {
				$position = ( (int) $matches[2] - 1 ) % count( $banner_fallbacks ) + 1;
				$image    = esc_url( $banner_base . $banner_fallbacks[ $position ] );
				$content  = preg_replace( '/\bsrcset="[^"]*"/i', 'srcset="' . $image . '"', $matches[3] );
				$content  = preg_replace( '/\bsrc="[^"]*"/i', 'src="' . $image . '"', $content );
				return '<a' . $matches[1] . '>' . $content . '</a>';
			},
			$html
		);
	}

	// Some mobile banner files were not included in the downloaded mirror. Fall back
	// to the existing desktop image instead of leaving a broken responsive source.
	$html = preg_replace_callback(
		'#<source\b([^>]*\bsrcset="([^"]+)"[^>]*)>\s*(?:</source>)?\s*<img\b([^>]*\bsrc="([^"]+)"[^>]*)>#i',
		function ( $matches ) {
			$source_url  = trim( preg_split( '/\s+/', $matches[2] )[0] );
			$source_path = ABSPATH . ltrim( (string) wp_parse_url( $source_url, PHP_URL_PATH ), '/' );
			if ( $source_url && ! file_exists( $source_path ) ) {
				$attributes = preg_replace( '/\bsrcset="[^"]+"/i', 'srcset="' . esc_url( $matches[4] ) . '"', $matches[1] );
				return '<source' . $attributes . '><img' . $matches[3] . '>';
			}
			return $matches[0];
		},
		$html
	);
	$html = preg_replace( '#</head>#i', '<style>@media(max-width:1023px){.home-carousel{aspect-ratio:auto!important}.home-carousel-slide picture{display:block;width:100%;height:100%}.home-carousel-slide picture img{width:100%;height:auto;object-fit:cover}}</style></head>', $html, 1 );

	// WordPress removes the original empty Iconify spans from the consultation
	// language cards. Restore the headset as inline SVG so it has no dependency.
	$headset_svg = '<svg class="uc-headset-icon" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 14a2 2 0 0 1-2-2v-1a10 10 0 0 1 20 0v1a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h3"/><path d="M3 14v1a5 5 0 0 0 5 5h4"/><path d="M4 14h2a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2H3"/></svg>';
	$html = preg_replace(
		'#<div class="size-16 rounded-full bg-secondary flex items-center justify-center">\s*(?:&nbsp;|\x{00A0})?\s*</div>#u',
		'<div class="size-16 rounded-full bg-secondary flex items-center justify-center text-white">' . $headset_svg . '</div>',
		$html
	);

	// Inline background images in the captured pages also use relative paths.
	$html = preg_replace_callback(
		'/url\(\s*(["\']?)([^)"\']+)\1\s*\)/',
		function ( $matches ) use ( $file ) {
			// Use single quotes so URLs remain valid inside double-quoted style attributes.
			return "url('" . esc_url( unicancer_migrate_url( $matches[2], $file ) ) . "')";
		},
		$html
	);

	// Make every desktop/mobile floating contact button editable from WordPress.
	$html = preg_replace_callback(
		'/<a\b([^>]*\bdata-contact-type=(["\'])(form|phone|email|zalo|whatsapp)\2[^>]*)>/i',
		function ( $matches ) {
			$url = unicancer_contact_url( strtolower( $matches[3] ) );
			$attributes = preg_replace( '/\s+href=(["\']).*?\1/i', '', $matches[1] );
			return '<a href="' . esc_url( $url, array( 'http', 'https', 'mailto', 'tel' ) ) . '"' . $attributes . '>';
		},
		$html
	);

	ob_start();
	wp_head();
	$wp_head = ob_get_clean();
	$html    = preg_replace( '#</head>#i', $wp_head . '</head>', $html, 1 );

	$body_attributes = ' ' . get_body_class_string();
	$html = preg_replace( '/<body([^>]*)>/i', '<body$1 class="' . esc_attr( trim( $body_attributes ) ) . '">', $html, 1 );

	ob_start();
	wp_body_open();
	$wp_body_open = ob_get_clean();
	$html         = preg_replace( '/<body([^>]*)>/i', '<body$1>' . $wp_body_open, $html, 1 );

	ob_start();
	wp_footer();
	$wp_footer = ob_get_clean();
	$html      = preg_replace( '#</body>#i', $wp_footer . '</body>', $html, 1 );

	return $html;
}

/**
 * Render a database-backed WordPress page inside the migrated site shell.
 */
function unicancer_post_breadcrumb( $post ) {
	$archives = array(
		'patient_story' => array( 'Câu chuyện bệnh nhân', '/patient-stories/' ),
		'doctor'        => array( 'Đội ngũ MDT', '/doctors/' ),
		'cancer'        => array( 'Các loại ung thư', '/cancers/' ),
		'treatment'     => array( 'Trung tâm điều trị', '/treatments/' ),
		'post'          => array( 'Tin tức & Sự kiện', '/news/' ),
	);
	if ( empty( $archives[ $post->post_type ] ) ) { return ''; }
	$archive = $archives[ $post->post_type ];
	return '<nav class="unicancer-breadcrumb" aria-label="Breadcrumb"><a href="' . esc_url( home_url( $archive[1] ) ) . '">' . esc_html( $archive[0] ) . '</a><span aria-hidden="true">›</span><span>' . esc_html( get_the_title( $post ) ) . '</span></nav>';
}

function unicancer_patient_story_value( $post_id, $field, $content, $pattern ) {
	$value = function_exists( 'get_field' ) ? get_field( $field, $post_id ) : '';
	if ( $value ) { return trim( wp_strip_all_tags( $value ) ); }
	return preg_match( $pattern, $content, $match ) ? trim( wp_strip_all_tags( $match[1] ) ) : '';
}

function unicancer_patient_video_embed( $url ) {
	if ( ! $url ) { return ''; }
	$video_id = '';
	if ( preg_match( '#(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{6,})#', $url, $match ) ) { $video_id = $match[1]; }
	if ( ! $video_id ) { return ''; }
	return '<iframe src="https://www.youtube.com/embed/' . esc_attr( $video_id ) . '" title="Video câu chuyện bệnh nhân" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
}

function unicancer_customize_patient_story( $content, $post ) {
	$name = unicancer_patient_story_value( $post->ID, 'uc_patient_name', $content, '#class="max-w-64[^>]*>([^<]+)#u' );
	$nationality = unicancer_patient_story_value( $post->ID, 'uc_patient_nationality', $content, '#Quốc tịch:</span>\s*([^<]+)#u' );
	$diagnosis = unicancer_patient_story_value( $post->ID, 'uc_patient_diagnosis', $content, '#Chẩn đoán:</div>\s*<div[^>]*>\s*<div>([^<]+)#u' );
	$treatment = unicancer_patient_story_value( $post->ID, 'uc_patient_treatment', $content, '#Phác đồ điều trị:</div>\s*<div[^>]*>\s*<div>([^<]+)#u' );
	$zalo = function_exists( 'get_field' ) && get_field( 'uc_patient_zalo', $post->ID ) ? get_field( 'uc_patient_zalo', $post->ID ) : unicancer_contact_url( 'zalo' );
	$whatsapp = function_exists( 'get_field' ) && get_field( 'uc_patient_whatsapp', $post->ID ) ? get_field( 'uc_patient_whatsapp', $post->ID ) : unicancer_contact_url( 'whatsapp' );
	$video = function_exists( 'get_field' ) ? get_field( 'uc_patient_video', $post->ID ) : '';
	$media = unicancer_patient_video_embed( $video );
	if ( ! $media ) {
		$image = get_the_post_thumbnail_url( $post->ID, 'large' );
		if ( ! $image && preg_match( '#<div class="flex justify-center xl:justify-end items-center">\s*<img[^>]+src="([^"]+)"#i', $content, $match ) ) { $image = $match[1]; }
		$media = $image ? '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $name ) . '">' : '<div class="uc-patient-media-empty">Chưa có Featured Image</div>';
	}
	$hero = '<div class="uc-patient-profile"><div class="uc-patient-details"><h2>' . esc_html( $name ?: get_the_title( $post ) ) . '</h2><dl><div><dt>Quốc tịch:</dt><dd>' . esc_html( $nationality ) . '</dd></div><div><dt>Chẩn đoán:</dt><dd>' . esc_html( $diagnosis ) . '</dd></div><div><dt>Phác đồ điều trị:</dt><dd>' . esc_html( $treatment ) . '</dd></div></dl><div class="uc-patient-contacts"><a class="is-zalo" href="' . esc_url( $zalo ) . '" target="_blank" rel="noopener">Zalo</a><a class="is-whatsapp" href="' . esc_url( $whatsapp ) . '" target="_blank" rel="noopener">WhatsApp</a></div></div><div class="uc-patient-media">' . $media . '</div></div>';
	libxml_use_internal_errors( true );
	$dom = new DOMDocument();
	$dom->loadHTML( '<?xml encoding="utf-8" ?><div id="uc-patient-root">' . $content . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	$xpath = new DOMXPath( $dom );
	$grids = $xpath->query( '//div[contains(concat(" ", normalize-space(@class), " "), " grid ") and contains(concat(" ", normalize-space(@class), " "), " pt-4 ")]' );
	if ( $grids->length ) {
		$fragment = new DOMDocument(); $fragment->loadHTML( '<?xml encoding="utf-8" ?>' . $hero, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		$new_node = $fragment->getElementsByTagName( 'div' )->item( 0 );
		$grids->item( 0 )->parentNode->replaceChild( $dom->importNode( $new_node, true ), $grids->item( 0 ) );
	}
	$root = $dom->getElementById( 'uc-patient-root' ); $output = '';
	foreach ( $root->childNodes as $node ) { $output .= $dom->saveHTML( $node ); }
	return $output;
}

function unicancer_news_page_content() {
	$parent = get_term_by( 'slug', 'tin-tuc', 'category' );
	$children = $parent ? get_terms( array( 'taxonomy' => 'category', 'parent' => $parent->term_id, 'hide_empty' => false ) ) : array();
	$selected = isset( $_GET['news_category'] ) ? sanitize_title( wp_unslash( $_GET['news_category'] ) ) : '';
	$args = array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 20, 'orderby' => 'date', 'order' => 'DESC' );
	if ( $selected ) { $args['category_name'] = $selected; }
	$posts = get_posts( $args );
	ob_start(); ?>
	<section class="uc-news-page"><header><h1>Tin tức</h1><p>Cập nhật thông tin bệnh viện, truyền thông đưa tin và các hoạt động trao đổi học thuật của UNI-ASIA.</p></header>
	<nav class="uc-news-tabs" aria-label="Danh mục tin tức"><a class="<?php echo $selected ? '' : 'is-active'; ?>" href="<?php echo esc_url( home_url( '/news/' ) ); ?>">Tất cả tin tức</a><?php foreach ( $children as $child ) : ?><a class="<?php echo $selected === $child->slug ? 'is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'news_category', $child->slug, home_url( '/news/' ) ) ); ?>"><?php echo esc_html( $child->name ); ?></a><?php endforeach; ?></nav>
	<div class="uc-news-grid"><?php foreach ( $posts as $news ) : $image = get_the_post_thumbnail_url( $news->ID, 'large' ); if ( ! $image && preg_match( '/<img[^>]+src=["\']([^"\']+)/i', $news->post_content, $match ) ) { $image = $match[1]; } $terms = $parent ? wp_get_post_terms( $news->ID, 'category', array( 'parent' => $parent->term_id ) ) : array(); ?><article><a href="<?php echo esc_url( get_permalink( $news ) ); ?>"><?php if ( $image ) : ?><img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $news->post_title ); ?>"><?php endif; ?><div class="uc-news-card-body"><?php if ( $terms && ! is_wp_error( $terms ) ) : ?><span><?php echo esc_html( $terms[0]->name ); ?></span><?php endif; ?><h2><?php echo esc_html( $news->post_title ); ?></h2><p><?php echo esc_html( wp_trim_words( $news->post_excerpt ?: wp_strip_all_tags( $news->post_content ), 24, '…' ) ); ?></p><small><?php echo esc_html( get_the_date( 'd/m/Y', $news ) ); ?></small></div></a></article><?php endforeach; ?></div></section>
	<?php return ob_get_clean();
}

function unicancer_render_wordpress_page( $post ) {
	$shell_file = UNICANCER_MIRROR_DIR . '/uniasiacancer.com/vi/index.html';
	$html = file_get_contents( $shell_file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( false === $html ) { return ''; }
	$lang = function_exists( 'pll_get_post_language' ) ? pll_get_post_language( $post->ID, 'slug' ) : 'vi';
	$lang = $lang ?: 'vi';
	$html_lang = 'zh-cn' === $lang ? 'zh-CN' : $lang;
	$locale = array( 'en' => 'en_US', 'id' => 'id_ID', 'zh-cn' => 'zh_CN', 'vi' => 'vi_VN' );
	$canonical = function_exists( 'pll_get_post' ) && (int) $post->ID === (int) pll_get_post( (int) get_option( 'page_on_front' ), $lang )
		? ( function_exists( 'pll_home_url' ) ? pll_home_url( $lang ) : home_url( '/' . $lang . '/' ) )
		: get_permalink( $post );
	$html = preg_replace( '#<html\b[^>]*\blang=["\'][^"\']*["\']#i', '<html lang="' . esc_attr( $html_lang ) . '"', $html, 1 );
	$html = preg_replace( '#<link\s+rel=["\']canonical["\'][^>]*>#i', '<link rel="canonical" href="' . esc_url( $canonical ) . '">', $html, 1 );
	$html = preg_replace( '#<meta\s+property=["\']og:locale["\'][^>]*>#i', '<meta property="og:locale" content="' . esc_attr( $locale[ $lang ] ?? 'vi_VN' ) . '">', $html, 1 );
	$raw_content = $post->post_content;
	// Script tags were intentionally stripped while importing official pages,
	// but their minified JavaScript bodies remained visible as paragraphs.
	$raw_content = preg_replace( '#<p>\s*const\s+.*?</p>#isu', '', $raw_content );
	$has_embedded_home_scripts = false !== strpos( $raw_content, '<script' );
	$raw_content = preg_replace( '#(</button>)\s*</p>#i', '$1', $raw_content );
	if ( 'page' === $post->post_type && 'news' === $post->post_name ) { $raw_content = unicancer_news_page_content(); }
	if ( 'patient_story' === $post->post_type && 'vi' === $lang ) { $raw_content = unicancer_customize_patient_story( $raw_content, $post ); }
	if ( in_array( $post->post_type, array( 'patient_story', 'doctor', 'cancer', 'treatment', 'post' ), true ) ) {
		$raw_content = unicancer_replace_article_sidebar( $raw_content );
	}
	// Imported mirror pages already contain complete HTML. wpautop inserts paragraph
	// tags between linked card elements and produces invalid markup on mobile.
	$wpautop_priority = has_filter( 'the_content', 'wpautop' );
	if ( false !== $wpautop_priority ) { remove_filter( 'the_content', 'wpautop', $wpautop_priority ); }
	$content = apply_filters( 'the_content', $raw_content );
	if ( false !== $wpautop_priority ) { add_filter( 'the_content', 'wpautop', $wpautop_priority ); }
	$breadcrumb = unicancer_post_breadcrumb( $post );
	$gallery_viewer = '';
	if ( false !== strpos( $content, 'data-img-viewer-trigger' ) ) {
		$gallery_viewer = '<div class="uc-gallery-viewer" data-uc-gallery-viewer hidden role="dialog" aria-modal="true" aria-label="Xem ảnh">'
			. '<button type="button" class="uc-gallery-close" aria-label="Đóng">&#215;</button>'
			. '<button type="button" class="uc-gallery-prev" aria-label="Ảnh trước">&#8249;</button>'
			. '<img class="uc-gallery-image" alt=""><button type="button" class="uc-gallery-next" aria-label="Ảnh tiếp theo">&#8250;</button>'
			. '<div class="uc-gallery-footer"><span class="uc-gallery-count"></span><div class="uc-gallery-thumbs"></div></div></div>'
			. '<script src="' . esc_url( get_template_directory_uri() . '/assets/js/gallery-viewer-101.js' ) . '"></script>';
	}
	$page = '<main id="primary" class="unicancer-wordpress-page"><div class="unicancer-wordpress-page__inner">' . $breadcrumb . $content . '</div>' . $gallery_viewer . '</main>';
	if ( ! $has_embedded_home_scripts && 'page' === $post->post_type && (int) $post->ID === (int) ( function_exists( 'pll_get_post' ) ? pll_get_post( (int) get_option( 'page_on_front' ), $lang ) : 0 ) ) {
		$page .= '<script>(function(){document.querySelectorAll(".home-carousel").forEach(function(root){var track=root.querySelector(".home-carousel-track"),slides=[].slice.call(root.querySelectorAll(".home-carousel-slide:not([data-clone=true])")),dots=[].slice.call(root.querySelectorAll(".home-carousel-dot")),prev=root.querySelector(".home-carousel-prev"),next=root.querySelector(".home-carousel-next"),index=0,timer;function show(i){if(!slides.length||!track)return;index=(i+slides.length)%slides.length;var cloneOffset=root.querySelector(".home-carousel-slide[data-clone=true]")?1:0;track.style.transform="translateX(-"+((index+cloneOffset)*100)+"%)";dots.forEach(function(dot,n){dot.classList.toggle("bg-primary",n===index);dot.classList.toggle("bg-primary/30",n!==index)})}function start(){clearInterval(timer);timer=setInterval(function(){show(index+1)},4500)}if(prev)prev.addEventListener("click",function(){show(index-1);start()});if(next)next.addEventListener("click",function(){show(index+1);start()});dots.forEach(function(dot,n){dot.addEventListener("click",function(){show(n);start()})});show(0);start()});document.querySelectorAll("[data-faq-trigger]").forEach(function(trigger){trigger.addEventListener("click",function(){var item=trigger.closest("[data-faq-item]"),panel=item&&item.querySelector("[data-faq-panel]"),open=trigger.getAttribute("aria-expanded")==="true";document.querySelectorAll("[data-faq-trigger]").forEach(function(other){other.setAttribute("aria-expanded","false");var otherItem=other.closest("[data-faq-item]"),otherPanel=otherItem&&otherItem.querySelector("[data-faq-panel]");if(otherPanel)otherPanel.classList.add("hidden")});if(!open){trigger.setAttribute("aria-expanded","true");if(panel)panel.classList.remove("hidden")}})})})();</script>';
	}
	$html = preg_replace( '#<main\b[^>]*>.*?</main>#s', $page, $html, 1 );
	$html = preg_replace( '#<title>.*?</title>#s', '<title>' . esc_html( get_the_title( $post ) ) . ' | ' . esc_html( get_bloginfo( 'name' ) ) . '</title>', $html, 1 );
	$extra_css = '<style>.unicancer-wordpress-page{background:#fff;min-height:55vh}.unicancer-wordpress-page__inner{max-width:1536px;margin:0 auto;padding:24px 16px 55px}.unicancer-breadcrumb{display:flex;align-items:center;gap:9px;margin:0 0 18px;font:400 13px/1.5 Inter,Roboto,Arial,sans-serif;color:#333;white-space:nowrap;overflow:hidden}.unicancer-breadcrumb a{color:#1685d1;text-decoration:none}.unicancer-breadcrumb a:hover{text-decoration:underline}.unicancer-breadcrumb span:last-child{overflow:hidden;text-overflow:ellipsis}.uc-patient-profile{display:grid;grid-template-columns:minmax(0,1fr) minmax(360px,1fr);gap:42px;align-items:center;padding:18px 0 28px}.uc-patient-details h2{display:inline-block;margin:0 0 18px!important;border-bottom:3px solid #ffc400;padding-bottom:7px;font:700 30px/1.2 Inter,Arial,sans-serif!important}.uc-patient-details dl{margin:0}.uc-patient-details dl>div{display:flex;gap:8px;margin:0 0 18px;font-size:17px}.uc-patient-details dt{font-weight:700}.uc-patient-details dd{margin:0}.uc-patient-contacts{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:22px}.uc-patient-contacts a{padding:11px;border-radius:8px;color:#fff!important;text-align:center;text-decoration:none!important;font-weight:700;box-shadow:0 5px 12px #0002}.uc-patient-contacts .is-zalo{background:#1577f8}.uc-patient-contacts .is-whatsapp{background:#20b75a}.uc-patient-media{height:270px;border-radius:12px;overflow:hidden;background:#f3f4f6}.uc-patient-media img,.uc-patient-media iframe{display:block;width:100%;height:100%;object-fit:cover;border:0}.uc-patient-media-empty{display:grid;place-items:center;height:100%;color:#777}.uc-gallery-viewer[hidden]{display:none!important}.uc-gallery-viewer{position:fixed;inset:0;z-index:99999;display:grid;grid-template-columns:64px minmax(0,1fr) 64px;grid-template-rows:minmax(0,1fr) 92px;align-items:center;background:#000d;padding:60px 18px 0}.uc-gallery-image{grid-column:2;grid-row:1;display:block;max-width:100%;max-height:calc(100vh - 170px);margin:auto;border-radius:8px;object-fit:contain}.uc-gallery-close,.uc-gallery-prev,.uc-gallery-next{z-index:2;border:0;border-radius:50%;background:#fff2;color:#fff;cursor:pointer}.uc-gallery-close{position:absolute;right:20px;top:16px;width:44px;height:44px;font-size:34px;line-height:1}.uc-gallery-prev,.uc-gallery-next{width:48px;height:48px;font-size:42px;line-height:1}.uc-gallery-prev{grid-column:1;grid-row:1}.uc-gallery-next{grid-column:3;grid-row:1}.uc-gallery-prev:disabled,.uc-gallery-next:disabled{opacity:.25;cursor:default}.uc-gallery-footer{grid-column:1/4;grid-row:2;display:flex;align-items:center;gap:14px;min-width:0;color:#fff}.uc-gallery-count{width:55px;text-align:center}.uc-gallery-thumbs{display:flex;gap:8px;min-width:0;overflow-x:auto;padding:6px 0}.uc-gallery-thumbs button{flex:0 0 72px;height:58px;padding:0;border:2px solid transparent;border-radius:5px;overflow:hidden;opacity:.6;cursor:pointer}.uc-gallery-thumbs button.is-active{border-color:#fff;opacity:1}.uc-gallery-thumbs img{width:100%;height:100%;object-fit:cover}@media(max-width:800px){.uc-patient-profile{grid-template-columns:1fr;gap:22px}.uc-patient-media{height:230px}.uc-patient-contacts{grid-template-columns:1fr}.uc-patient-details dl>div{display:block}.uc-patient-details dd{margin-top:4px}.uc-gallery-viewer{grid-template-columns:46px minmax(0,1fr) 46px;padding-left:5px;padding-right:5px}.uc-gallery-prev,.uc-gallery-next{width:40px;height:40px}}.unicancer-wordpress-page .alignwide{max-width:1536px;margin-left:auto;margin-right:auto}.unicancer-wordpress-page .alignfull{width:100%}</style>';
	$extra_css = str_replace( '</style>', '.uc-news-page{font-family:Inter,Roboto,Arial,sans-serif}.uc-news-page>header{text-align:center;padding:12px 0 26px}.uc-news-page>header h1{font-size:38px;margin:0 0 10px}.uc-news-page>header p{color:#666}.uc-news-tabs{display:flex;justify-content:center;gap:12px;flex-wrap:wrap;margin-bottom:30px}.uc-news-tabs a{padding:10px 20px;border-radius:8px;background:#edf7ff;color:#0875ce;text-decoration:none}.uc-news-tabs a.is-active,.uc-news-tabs a:hover{background:#ff8617;color:#fff}.uc-news-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:24px}.uc-news-grid article{overflow:hidden;border-radius:14px;background:#fff;box-shadow:0 4px 14px #0002}.uc-news-grid article>a{display:flex;flex-direction:column;height:100%;color:#222;text-decoration:none}.uc-news-grid img{width:100%;height:210px;object-fit:cover}.uc-news-card-body{display:flex;flex-direction:column;flex:1;padding:18px}.uc-news-card-body>span{align-self:flex-start;border-radius:14px;background:#e6f4ff;color:#0875ce;padding:4px 10px;font-size:13px}.uc-news-card-body h2{font-size:20px;line-height:1.35;margin:12px 0}.uc-news-card-body p{color:#666;line-height:1.55;margin:0 0 15px}.uc-news-card-body small{margin-top:auto;color:#888}@media(max-width:900px){.uc-news-grid{grid-template-columns:repeat(2,1fr)}}@media(max-width:600px){.uc-news-grid{grid-template-columns:1fr}.uc-news-grid img{height:200px}}</style>', $extra_css );
	if ( 'post' === $post->post_type ) {
		$extra_css = str_replace( '</style>', '.unicancer-wordpress-page section.max-w-384.flex{align-items:flex-start;gap:24px}.unicancer-wordpress-page section.max-w-384.flex>.grow{min-width:0;max-width:calc(100% - 280px)}.unicancer-wordpress-page section.max-w-384.flex>.grow>h1{max-width:1050px;margin-left:auto!important;margin-right:auto!important;font-size:clamp(28px,3vw,42px)!important;line-height:1.25!important}.unicancer-wordpress-page .lexical-rich-text{max-width:980px;margin-left:auto;margin-right:auto;font-size:17px;line-height:1.75;overflow-wrap:anywhere}.unicancer-wordpress-page .lexical-rich-text p{margin:0 0 18px}.unicancer-wordpress-page .lexical-rich-text img{display:block;width:auto!important;max-width:100%!important;max-height:680px!important;height:auto!important;object-fit:contain;margin:22px auto;border-radius:10px}.unicancer-wordpress-page .lexical-rich-text iframe,.unicancer-wordpress-page .lexical-rich-text video{display:block;width:100%;max-width:900px;aspect-ratio:16/9;height:auto;margin:22px auto}.unicancer-wordpress-page .lexical-rich-text table{display:block;max-width:100%;overflow-x:auto}.unicancer-wordpress-page .lexical-rich-text>*{min-width:0;max-width:100%;box-sizing:border-box}@media(max-width:1023px){.unicancer-wordpress-page section.max-w-384.flex>.grow{max-width:100%;padding-left:0!important}.unicancer-wordpress-page section.max-w-384.flex{display:block}.unicancer-wordpress-page section.max-w-384.flex>.uc-article-sidebar{display:none}}@media(max-width:600px){.unicancer-wordpress-page section.max-w-384.flex>.grow>h1{font-size:26px!important}.unicancer-wordpress-page .lexical-rich-text{font-size:16px;line-height:1.65}.unicancer-wordpress-page .lexical-rich-text img{max-height:520px!important}}</style>', $extra_css );
		$extra_css = str_replace( '</style>', '.unicancer-wordpress-page section.max-w-384.flex>.grow>h1{max-width:1000px;font-size:clamp(26px,2.2vw,34px)!important;line-height:1.3!important}</style>', $extra_css );
	}
	$html = preg_replace( '#</head>#i', $extra_css . '</head>', $html, 1 );
	return unicancer_render_mirror( $shell_file, $html );
}

/**
 * The legacy mirror router treats a Polylang language homepage as a mirror
 * directory before WordPress can resolve its translated front page. Render the
 * linked database page for language roots that already have real content.
 */
function unicancer_render_language_homepage() {
	if ( is_admin() || ! function_exists( 'pll_get_post' ) ) { return; }
	$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ), '/' );
	if ( ! in_array( $path, array( 'en', 'id', 'zh-cn' ), true ) ) { return; }
	$post_id = (int) pll_get_post( (int) get_option( 'page_on_front' ), $path );
	if ( ! $post_id ) { return; }
	$post = get_post( $post_id );
	if ( ! $post || 'publish' !== $post->post_status || '' === trim( (string) $post->post_content ) ) { return; }
	status_header( 200 );
	echo unicancer_render_wordpress_page( $post ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit;
}
add_action( 'template_redirect', 'unicancer_render_language_homepage', 0 );

/**
 * Keep translated front pages on their canonical Polylang language roots.
 * The imported English page originally exposed /en/home/, which duplicates
 * /en/ and carries stale metadata from the source website.
 */
function unicancer_redirect_translated_front_page_aliases() {
	if ( is_admin() ) { return; }
	$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ), '/' );
	$aliases = array(
		'en/home'       => 'en',
		'id/home'       => 'id',
		'zh-cn/home'    => 'zh-cn',
		'en/index'      => 'en',
		'id/index'      => 'id',
		'zh-cn/index'   => 'zh-cn',
	);
	if ( ! isset( $aliases[ $path ] ) ) { return; }
	$lang = $aliases[ $path ];
	$url  = function_exists( 'pll_home_url' ) ? pll_home_url( $lang ) : home_url( '/' . $lang . '/' );
	wp_safe_redirect( $url, 301, 'UNI-ASIA canonical language homepage' );
	exit;
}
add_action( 'template_redirect', 'unicancer_redirect_translated_front_page_aliases', -10 );

/**
 * A translated static front page belongs at /{language}/. Do not expose the
 * temporary title-derived slug generated by the Polylang setup wizard.
 */
function unicancer_translated_front_page_link( $url, $post_id ) {
	if ( ! function_exists( 'pll_get_post_translations' ) || ! function_exists( 'pll_home_url' ) ) { return $url; }
	$front_id = (int) get_option( 'page_on_front' );
	if ( ! $front_id ) { return $url; }
	$translations = pll_get_post_translations( $front_id );
	$lang = array_search( (int) $post_id, array_map( 'intval', $translations ), true );
	return false !== $lang ? trailingslashit( untrailingslashit( get_option( 'home' ) ) . '/' . $lang ) : $url;
}
add_filter( 'page_link', 'unicancer_translated_front_page_link', 20, 2 );

function unicancer_sidebar_image( $post_id ) {
	$acf_image = function_exists( 'get_field' ) ? get_field( 'uc_card_image', $post_id ) : 0;
	if ( $acf_image && wp_get_attachment_image_url( (int) $acf_image, 'medium' ) ) { return wp_get_attachment_image_url( (int) $acf_image, 'medium' ); }
	if ( has_post_thumbnail( $post_id ) ) { return get_the_post_thumbnail_url( $post_id, 'medium' ); }
	$content = get_post_field( 'post_content', $post_id );
	if ( ! preg_match( '/<img[^>]+src=["\']([^"\']+)/i', $content, $match ) ) { return ''; }
	$image = $match[1];
	$post  = get_post( $post_id );
	if ( $post && 'doctor' === $post->post_type ) {
		$source = UNICANCER_MIRROR_DIR . '/uniasiacancer.com/vi/doctors/' . $post->post_name . '/index.html';
		if ( is_file( $source ) ) { $image = unicancer_migrate_url( $image, $source ); }
	}
	return $image;
}

function unicancer_sidebar_menu( $location, $post_type, $count ) {
	$locations = get_nav_menu_locations();
	if ( ! empty( $locations[ $location ] ) ) {
		$items = array_slice( wp_get_nav_menu_items( $locations[ $location ] ) ?: array(), 0, $count );
		return array_map( fn( $item ) => array( 'title' => $item->title, 'url' => $item->url ), $items );
	}
	$posts = get_posts( array( 'post_type' => $post_type, 'post_status' => 'publish', 'posts_per_page' => $count, 'post_parent' => 0, 'orderby' => 'title', 'order' => 'ASC' ) );
	$base = 'cancer' === $post_type ? 'cancers' : 'treatments';
	return array_map( fn( $post ) => array( 'title' => $post->post_title, 'url' => home_url( '/' . $base . '/' . $post->post_name . '/' ) ), $posts );
}

function unicancer_dynamic_article_sidebar() {
	if ( ! get_theme_mod( 'unicancer_sidebar_enabled', true ) ) { return ''; }
	$menu_count = max( 1, min( 15, (int) get_theme_mod( 'unicancer_sidebar_menu_count', 8 ) ) );
	$doctor_slugs = array( 'liao-zheng-yin', 'zhang-jin-shan', 'xiao-yue-yong', 'hu-xiao-kun', 'luo-xiao-ping', 'yi-cheng' );
	$doctor_ids = array();
	foreach ( $doctor_slugs as $slug ) { $doctor = get_page_by_path( $slug, OBJECT, 'doctor' ); if ( $doctor ) { $doctor_ids[] = $doctor->ID; } }
	$doctors = get_posts( array( 'post_type' => 'doctor', 'post_status' => 'publish', 'posts_per_page' => 6, 'post__in' => $doctor_ids, 'orderby' => 'post__in' ) );
	$cancers = unicancer_sidebar_menu( 'sidebar_cancers', 'cancer', $menu_count );
	$treatments = unicancer_sidebar_menu( 'sidebar_treatments', 'treatment', $menu_count );
	ob_start(); ?>
	<aside class="uc-article-sidebar" data-uc-sidebar-slider data-speed="<?php echo esc_attr( (int) get_theme_mod( 'unicancer_sidebar_speed', 3500 ) ); ?>">
		<section class="uc-side-box uc-side-mtd"><header><strong><?php echo esc_html( get_theme_mod( 'unicancer_sidebar_mdt_title', 'Đội ngũ MDT' ) ); ?></strong><a href="<?php echo esc_url( home_url( '/doctors/' ) ); ?>">Thêm</a></header><div class="uc-side-slides">
		<?php foreach ( $doctors as $index => $doctor ) : $doctor_name = trim( explode( '|', $doctor->post_title )[0] ); ?><article class="uc-side-doctor<?php echo 0 === $index ? ' is-active' : ''; ?>"><img src="<?php echo esc_url( unicancer_sidebar_image( $doctor->ID ) ); ?>" alt="<?php echo esc_attr( $doctor_name ); ?>"><div><h3><?php echo esc_html( $doctor_name ); ?></h3><strong><?php echo esc_html( wp_trim_words( get_post_meta( $doctor->ID, 'uc_card_subtitle', true ) ?: $doctor->post_excerpt, 12 ) ); ?></strong><p><?php echo esc_html( wp_trim_words( get_post_meta( $doctor->ID, 'uc_card_description', true ) ?: wp_strip_all_tags( $doctor->post_content ), 20 ) ); ?></p><a class="uc-side-detail" href="<?php echo esc_url( home_url( '/doctors/' . $doctor->post_name . '/' ) ); ?>">Chi tiết</a></div></article><?php endforeach; ?>
		</div><div class="uc-side-dots"><?php foreach ( $doctors as $index => $doctor ) : ?><button type="button" class="<?php echo 0 === $index ? 'is-active' : ''; ?>" data-index="<?php echo esc_attr( $index ); ?>" aria-label="Bác sĩ <?php echo esc_attr( $index + 1 ); ?>"></button><?php endforeach; ?></div></section>
		<?php foreach ( array( array( get_theme_mod( 'unicancer_sidebar_cancer_title', 'Các loại ung thư' ), home_url( '/cancers/' ), $cancers ), array( get_theme_mod( 'unicancer_sidebar_treatment_title', 'Phương pháp điều trị' ), home_url( '/treatments/' ), $treatments ) ) as $box ) : ?><section class="uc-side-box uc-side-menu"><header><strong><?php echo esc_html( $box[0] ); ?></strong><a href="<?php echo esc_url( $box[1] ); ?>">Thêm</a></header><nav><?php foreach ( $box[2] as $item ) : ?><a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a><?php endforeach; ?></nav></section><?php endforeach; ?>
	</aside>
	<?php return ob_get_clean();
}

function unicancer_replace_article_sidebar( $content ) {
	if ( false === strpos( $content, 'max-w-384' ) ) { return $content; }
	libxml_use_internal_errors( true );
	$dom = new DOMDocument();
	$dom->loadHTML( '<?xml encoding="utf-8" ?><div id="uc-content-root">' . $content . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	$xpath = new DOMXPath( $dom );
	$sections = $xpath->query( '//section[contains(concat(" ", normalize-space(@class), " "), " max-w-384 ") and contains(concat(" ", normalize-space(@class), " "), " flex ")]' );
	if ( $sections->length ) {
		$section = $sections->item( 0 );
		foreach ( iterator_to_array( $section->childNodes ) as $child ) {
			if ( XML_ELEMENT_NODE === $child->nodeType && false !== strpos( ' ' . $child->getAttribute( 'class' ) . ' ', ' w-64 ' ) ) { $section->removeChild( $child ); break; }
		}
		$fragment_doc = new DOMDocument();
		$fragment_doc->loadHTML( '<?xml encoding="utf-8" ?>' . unicancer_dynamic_article_sidebar(), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		$aside = $fragment_doc->getElementsByTagName( 'aside' )->item( 0 );
		if ( $aside ) { $section->insertBefore( $dom->importNode( $aside, true ), $section->firstChild ); }
	}
	$root = $dom->getElementById( 'uc-content-root' ); $output = '';
	foreach ( $root->childNodes as $node ) { $output .= $dom->saveHTML( $node ); }
	return $output;
}

function unicancer_sidebar_assets() {
	if ( ! is_singular( array( 'patient_story', 'doctor', 'cancer', 'treatment', 'post' ) ) ) { return; }
	wp_enqueue_style( 'unicancer-article-sidebar', get_template_directory_uri() . '/assets/article-sidebar.css', array(), UNICANCER_VERSION );
	wp_enqueue_script( 'unicancer-article-sidebar', get_template_directory_uri() . '/assets/article-sidebar.js', array(), UNICANCER_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'unicancer_sidebar_assets' );

function unicancer_customize_footer_html( $match ) {
	$footer = $match[0];
	$values = array(
		'logo' => get_theme_mod( 'unicancer_footer_logo', get_template_directory_uri() . '/mirror/huan-ya.oss-ap-southeast-1.aliyuncs.com/frontend/logo-white.png' ),
		'partner' => get_theme_mod( 'unicancer_footer_partner_logo', get_template_directory_uri() . '/mirror/huan-ya.oss-ap-southeast-1.aliyuncs.com/frontend/layout/wata-white.png' ),
		'wa_qr' => get_theme_mod( 'unicancer_footer_whatsapp_qr', get_template_directory_uri() . '/mirror/huan-ya.oss-ap-southeast-1.aliyuncs.com/frontend/footer/wa-vi.jpg' ),
		'zalo_qr' => get_theme_mod( 'unicancer_footer_zalo_qr', get_template_directory_uri() . '/mirror/huan-ya.oss-ap-southeast-1.aliyuncs.com/frontend/footer/zalo.jpg' ),
	);
	$footer = preg_replace( '/(<img\b[^>]*src=")[^"]*("[^>]*alt="UNI-ASIA Logo")/i', '$1' . esc_url( $values['logo'] ) . '$2', $footer, 1 );
	$footer = preg_replace( '/(<img\b[^>]*src=")[^"]*("[^>]*alt="WATA Logo")/i', '$1' . esc_url( $values['partner'] ) . '$2', $footer, 1 );
	$footer = preg_replace( '/(<img\b[^>]*src=")[^"]*("[^>]*alt="whatsapp")/i', '$1' . esc_url( $values['wa_qr'] ) . '$2', $footer, 1 );
	$footer = preg_replace( '/(<img\b[^>]*src=")[^"]*("[^>]*alt="zalo")/i', '$1' . esc_url( $values['zalo_qr'] ) . '$2', $footer, 1 );
	$replacements = array(
		'Trung tâm Dịch vụ Hà Nội, Việt Nam' => get_theme_mod( 'unicancer_footer_office_title', 'Trung tâm Dịch vụ Hà Nội, Việt Nam' ),
		'Tòa nhà Tràng An Complex, số 1 Phùng Chí Kiên, phường Nghĩa Đô, Hà Nội (Tòa nhà Trung tâm Dịch vụ thị thực Trung Quốc)' => get_theme_mod( 'unicancer_footer_address', 'Tòa nhà Tràng An Complex, số 1 Phùng Chí Kiên, phường Nghĩa Đô, Hà Nội (Tòa nhà Trung tâm Dịch vụ thị thực Trung Quốc)' ),
		'Về chúng tôi' => get_theme_mod( 'unicancer_footer_about_title', 'Về chúng tôi' ), 'Loại ung thư' => get_theme_mod( 'unicancer_footer_cancer_title', 'Loại ung thư' ),
		'Phương pháp điều trị' => get_theme_mod( 'unicancer_footer_treatment_title', 'Phương pháp điều trị' ), 'Tư vấn' => get_theme_mod( 'unicancer_footer_consult_title', 'Tư vấn' ),
		'Theo dõi chúng tôi' => get_theme_mod( 'unicancer_footer_follow_title', 'Theo dõi chúng tôi' ),
	);
	foreach ( $replacements as $old => $new ) { $footer = str_replace( $old, esc_html( $new ), $footer ); }
	$footer = preg_replace( '#(<a\b[^>]*data-contact-type="phone"[^>]*>.*?<span class="ml-2">).*?(</span>)#s', '$1' . esc_html( get_theme_mod( 'unicancer_footer_phone', '+84 388925161' ) ) . '$2', $footer, 1 );
	$footer = preg_replace( '#(<a\b[^>]*data-contact-type="email"[^>]*>.*?<span class="ml-2">).*?(</span>)#s', '$1' . esc_html( get_theme_mod( 'unicancer_footer_email', 'service@uniasiacancer.com' ) ) . '$2', $footer, 1 );
	$footer = preg_replace( '#<p class="w-full md:w-auto mb-2">.*?</p>#s', '<p class="w-full md:w-auto mb-2">' . esc_html( get_theme_mod( 'unicancer_footer_copyright', '© 2026 UNI-ASIA Cancer Hospital. All rights reserved' ) ) . '</p>', $footer, 1 );
	$footer = preg_replace( '#<div>\s*\*Tuyên bố:.*?</div>#s', '<div>' . nl2br( esc_html( get_theme_mod( 'unicancer_footer_disclaimer', '*Tuyên bố: Thông tin trên website chỉ mang tính chất tham khảo, không thay thế chẩn đoán và điều trị của bác sĩ.' ) ) ) . '</div>', $footer, 1 );
	foreach ( array( 'facebook', 'tiktok', 'instagram', 'youtube' ) as $social ) {
		$url = get_theme_mod( 'unicancer_footer_' . $social, '' );
		if ( $url ) { $footer = preg_replace( '/(<a\b[^>]*href=")[^"]*("[^>]*aria-label="' . $social . '")/i', '$1' . esc_url( $url ) . '$2', $footer, 1 ); }
	}
	$footer = preg_replace( '#(<a\b[^>]*href=")[^"]*("[^>]*>\s*WhatsApp\s*</a>)#i', '$1' . esc_url( unicancer_contact_url( 'whatsapp' ) ) . '$2', $footer, 1 );
	$footer = preg_replace( '#(<a\b[^>]*href=")[^"]*("[^>]*>\s*Zalo\s*</a>)#i', '$1' . esc_url( unicancer_contact_url( 'zalo' ) ) . '$2', $footer, 1 );
	return $footer;
}

/**
 * Compatibility helper because get_body_class() returns an array only.
 */
function get_body_class_string() {
	return implode( ' ', get_body_class() );
}
