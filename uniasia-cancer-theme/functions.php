<?php
/**
 * UNI-ASIA Cancer Theme - functions and definitions
 *
 * @package UNI_ASIA
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Constants
 */
define( 'UNIASIA_THEME_VERSION', '1.0.0' );
define( 'UNIASIA_THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'UNIASIA_THEME_URI', trailingslashit( get_template_directory_uri() ) );
define( 'UNIASIA_INC_DIR', UNIASIA_THEME_DIR . 'inc/' );
define( 'UNIASIA_ASSETS_URI', UNIASIA_THEME_URI . 'assets/' );

/**
 * Original site CDN URL base (for static assets used as fallbacks)
 */
define( 'UNIASIA_CDN_BASE', 'https://huan-ya.oss-ap-southeast-1.aliyuncs.com/' );

/**
 * Load helper modules
 */
require_once UNIASIA_INC_DIR . 'original-images.php';

/**
 * Theme Setup
 */
if ( ! function_exists( 'uniasia_setup' ) ) {
	function uniasia_setup() {

		load_theme_textdomain( 'uniasia', UNIASIA_THEME_DIR . 'languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list',
			'gallery', 'caption', 'style', 'script', 'navigation-widgets',
		) );

		add_theme_support( 'custom-logo', array(
			'height'      => 60,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		) );

		add_theme_support( 'custom-background', array(
			'default-color' => 'ffffff',
		) );

		add_theme_support( 'editor-styles' );

		add_image_size( 'uniasia-hero', 1920, 800, true );
		add_image_size( 'uniasia-doctor', 480, 600, true );
		add_image_size( 'uniasia-story', 600, 400, true );
		add_image_size( 'uniasia-thumb', 400, 300, true );

		register_nav_menus( array(
			'primary'      => esc_html__( 'Primary Menu (Tiếng Việt)', 'uniasia' ),
			'primary-en'   => esc_html__( 'Primary Menu (English)', 'uniasia' ),
			'primary-id'   => esc_html__( 'Primary Menu (Indonesia)', 'uniasia' ),
			'primary-zh'   => esc_html__( 'Primary Menu (中文)', 'uniasia' ),
			'footer'       => esc_html__( 'Footer Menu', 'uniasia' ),
			'footer-links' => esc_html__( 'Footer Quick Links', 'uniasia' ),
		) );

		add_theme_support( 'elementor' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
}
add_action( 'after_setup_theme', 'uniasia_setup' );

/**
 * Set content width
 */
function uniasia_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'uniasia_content_width', 1200 );
}
add_action( 'after_setup_theme', 'uniasia_content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
function uniasia_scripts() {
	wp_enqueue_style(
		'uniasia-main',
		UNIASIA_ASSETS_URI . 'css/main.css',
		array(),
		UNIASIA_THEME_VERSION
	);

	wp_enqueue_style(
		'uniasia-responsive',
		UNIASIA_ASSETS_URI . 'css/responsive.css',
		array( 'uniasia-main' ),
		UNIASIA_THEME_VERSION,
		'all'
	);

	wp_enqueue_style(
		'uniasia-elementor',
		UNIASIA_ASSETS_URI . 'css/elementor-overrides.css',
		array(),
		UNIASIA_THEME_VERSION
	);

	wp_enqueue_style(
		'uniasia-google-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Sans:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_script(
		'uniasia-swiper',
		'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
		array(),
		'11.0.5',
		true
	);

	wp_enqueue_style(
		'uniasia-swiper',
		'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
		array(),
		'11.0.5'
	);

	wp_enqueue_script(
		'uniasia-main',
		UNIASIA_ASSETS_URI . 'js/main.js',
		array( 'jquery', 'uniasia-swiper' ),
		UNIASIA_THEME_VERSION,
		true
	);

	wp_enqueue_script(
		'uniasia-swiper-init',
		UNIASIA_ASSETS_URI . 'js/swiper-init.js',
		array( 'uniasia-swiper' ),
		UNIASIA_THEME_VERSION,
		true
	);

	wp_enqueue_script(
		'uniasia-faq',
		UNIASIA_ASSETS_URI . 'js/faq-accordion.js',
		array( 'jquery' ),
		UNIASIA_THEME_VERSION,
		true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_localize_script( 'uniasia-main', 'uniasiaData', array(
		'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'uniasia_nonce' ),
		'homeUrl'  => home_url( '/' ),
		'themeUrl' => UNIASIA_THEME_URI,
		'i18n'     => array(
			'submitting'    => esc_html__( 'Đang gửi...', 'uniasia' ),
			'submitSuccess' => esc_html__( 'Gửi thành công!', 'uniasia' ),
			'submitError'   => esc_html__( 'Có lỗi, vui lòng thử lại.', 'uniasia' ),
		),
	) );
}
add_action( 'wp_enqueue_scripts', 'uniasia_scripts' );

/**
 * Include required files
 */
require_once UNIASIA_INC_DIR . 'custom-post-types.php';
require_once UNIASIA_INC_DIR . 'custom-taxonomies.php';
require_once UNIASIA_INC_DIR . 'acf-fields.php';
require_once UNIASIA_INC_DIR . 'elementor-support.php';
require_once UNIASIA_INC_DIR . 'wpml-config.php';
require_once UNIASIA_INC_DIR . 'seo-schema.php';
require_once UNIASIA_INC_DIR . 'form-handler.php';
require_once UNIASIA_INC_DIR . 'template-helpers.php';

/**
 * Elementor custom widgets
 */
add_action( 'elementor/widgets/register', 'uniasia_register_elementor_widgets' );
function uniasia_register_elementor_widgets( $widgets_manager ) {
	$widgets = array(
		'stats-counter',
		'doctor-card',
		'patient-story-card',
		'faq-accordion',
		'step-process',
		'cancer-type-card',
		'technology-card',
	);

	foreach ( $widgets as $widget ) {
		$file = UNIASIA_THEME_DIR . 'template-elementor/elementor-custom-widgets/widget-' . $widget . '.php';
		if ( file_exists( $file ) ) {
			require_once $file;
			$class_name = 'Uniasia_Widget_' . str_replace( '-', '_', ucwords( $widget, '-' ) );
			if ( class_exists( $class_name ) ) {
				$widgets_manager->register( new $class_name() );
			}
		}
	}
}

/**
 * Add Elementor custom widget categories
 */
add_action( 'elementor/elements/categories_registered', 'uniasia_add_elementor_category' );
function uniasia_add_elementor_category( $elements_manager ) {
	$elements_manager->add_category(
		'uniasia-widgets',
		array(
			'title' => esc_html__( 'UNI-ASIA Widgets', 'uniasia' ),
			'icon'  => 'fa fa-medkit',
		)
	);
}

/**
 * Add support for Elementor Pro theme builder locations
 */
add_action( 'elementor/theme/register_locations', 'uniasia_register_elementor_locations' );
function uniasia_register_elementor_locations( $elementor_theme_manager ) {
	$elementor_theme_manager->register_all_core_location();
}

/**
 * Widgets areas
 */
function uniasia_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar chính', 'uniasia' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Widget area cho sidebar.', 'uniasia' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer 1', 'uniasia' ),
		'id'            => 'footer-1',
		'description'   => esc_html__( 'Cột footer 1.', 'uniasia' ),
		'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="footer-widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer 2', 'uniasia' ),
		'id'            => 'footer-2',
		'description'   => esc_html__( 'Cột footer 2.', 'uniasia' ),
		'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="footer-widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer 3', 'uniasia' ),
		'id'            => 'footer-3',
		'description'   => esc_html__( 'Cột footer 3.', 'uniasia' ),
		'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="footer-widget-title">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'uniasia_widgets_init' );

/**
 * Custom excerpt length
 */
function uniasia_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'uniasia_excerpt_length' );

function uniasia_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'uniasia_excerpt_more' );

/**
 * Add preconnect for Google Fonts
 */
function uniasia_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'uniasia_resource_hints', 10, 2 );

/**
 * Default language: Vietnamese
 */
add_action( 'init', 'uniasia_set_default_language' );
function uniasia_set_default_language() {
	if ( function_exists( 'icl_register_string' ) ) {
		$settings = get_option( 'icl_sitepress_settings' );
		if ( empty( $settings['default_language'] ) ) {
			$settings['default_language'] = 'vi';
			update_option( 'icl_sitepress_settings', $settings );
		}
	}
}

/**
 * Fallback menu
 */
function uniasia_fallback_menu() {
	$home_url = esc_url( home_url( '/' ) );
	echo '<ul class="nav-menu fallback-menu">';
	echo '<li><a href="' . $home_url . '">' . esc_html__( 'Trang chủ', 'uniasia' ) . '</a></li>';
	echo '<li><a href="' . $home_url . 'about-us/">' . esc_html__( 'Giới thiệu', 'uniasia' ) . '</a></li>';
	echo '<li><a href="' . $home_url . 'doctors/">' . esc_html__( 'Bác sĩ', 'uniasia' ) . '</a></li>';
	echo '<li><a href="' . $home_url . 'patient-stories/">' . esc_html__( 'Câu chuyện', 'uniasia' ) . '</a></li>';
	echo '<li><a href="' . $home_url . 'contact/">' . esc_html__( 'Liên hệ', 'uniasia' ) . '</a></li>';
	echo '</ul>';
}