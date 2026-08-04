<?php
/**
 * Elementor Support & Integration
 *
 * @package UNI_ASIA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Đăng ký Elementor Pro theme locations
 */
add_action( 'elementor/theme/register_locations', 'uniasia_elementor_locations' );
function uniasia_elementor_locations( $elementor_theme_manager ) {
	$elementor_theme_manager->register_all_core_location();
}

/**
 * Set default Kit cho theme
 */
add_action( 'elementor/init', 'uniasia_elementor_init' );
function uniasia_elementor_init() {
	\Elementor\Plugin::$instance->elements_manager->add_category(
		'uniasia-widgets',
		array(
			'title' => __( 'UNI-ASIA Widgets', 'uniasia' ),
			'icon'  => 'fa fa-medkit',
		),
		1
	);
}

/**
 * Set Canvas template cho các trang đặc biệt
 */
add_filter( 'template_include', 'uniasia_elementor_canvas_template' );
function uniasia_elementor_canvas_template( $template ) {
	if ( is_singular( 'doctor' ) || is_singular( 'patient_story' ) ) {
		$elementor_template = UNIASIA_THEME_DIR . 'template-elementor/elementor-templates/single-' . get_post_type() . '.json';
	}

	return $template;
}

/**
 * Enqueue Elementor custom styles
 */
add_action( 'elementor/frontend/after_enqueue_styles', 'uniasia_elementor_enqueue' );
function uniasia_elementor_enqueue() {
	wp_enqueue_style(
		'uniasia-elementor-custom',
		UNIASIA_ASSETS_URI . 'css/elementor-overrides.css',
		array(),
		UNIASIA_THEME_VERSION
	);
}

/**
 * Import Elementor templates programmatically (sử dụng cho việc setup ban đầu)
 */
function uniasia_import_elementor_template( $file_path, $post_id = 0 ) {
	if ( ! class_exists( '\Elementor\Plugin' ) ) {
		return false;
	}

	if ( ! file_exists( $file_path ) ) {
		return false;
	}

	$template = json_decode( file_get_contents( $file_path ), true );
	if ( ! $template ) {
		return false;
	}

	return \Elementor\Plugin::$instance->templates_manager->import_template( $template );
}

/**
 * Đăng ký CPT archive với Elementor Theme Builder
 */
add_filter( 'elementor/theme/get_location_templates', 'uniasia_register_archive_templates', 10, 3 );
function uniasia_register_archive_templates( $templates, $location, $post_type ) {
	if ( 'archive' === $location && in_array( $post_type, array( 'doctor', 'patient_story', 'cancer_type', 'technology', 'faq' ) ) ) {
		$templates[] = array(
			'id'   => 'uniasia-archive-' . $post_type,
			'name' => sprintf( __( 'UNI-ASIA Archive %s', 'uniasia' ), $post_type ),
		);
	}
	return $templates;
}

/**
 * Force Elementor Pro cho các CPT
 */
add_action( 'elementor_pro/init', 'uniasia_elementor_pro_init' );
function uniasia_elementor_pro_init() {
	$cpts = array( 'doctor', 'patient_story', 'cancer_type', 'technology', 'faq' );

	foreach ( $cpts as $cpt ) {
		add_post_type_support( $cpt, 'elementor' );
	}
}

/**
 * Tạo page templates cho Elementor
 */
add_filter( 'theme_page_templates', 'uniasia_register_page_templates' );
function uniasia_register_page_templates( $templates ) {
	$templates['elementor_canvas']  = __( 'Elementor Canvas (full width)', 'uniasia' );
	$templates['elementor_header_footer'] = __( 'Elementor Full Width', 'uniasia' );
	return $templates;
}

/**
 * Load Elementor Canvas template
 */
add_filter( 'template_include', 'uniasia_load_canvas_template' );
function uniasia_load_canvas_template( $template ) {
	$page_template = get_post_meta( get_the_ID(), '_wp_page_template', true );

	if ( 'elementor_canvas' === $page_template ) {
		$new_template = locate_template( array( 'template-elementor/elementor-canvas.php' ) );
		if ( $new_template ) {
			return $new_template;
		}
	}

	if ( 'elementor_header_footer' === $page_template ) {
		$new_template = locate_template( array( 'template-elementor/elementor-full-width.php' ) );
		if ( $new_template ) {
			return $new_template;
		}
	}

	return $template;
}