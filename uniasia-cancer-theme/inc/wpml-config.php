<?php
/**
 * WPML Configuration & Multilingual Support
 *
 * @package UNI_ASIA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Set Vietnamese as default language
 */
add_action( 'init', 'uniasia_wpml_setup', 5 );
function uniasia_wpml_setup() {
	if ( ! did_action( 'wpml_loaded' ) ) {
		return;
	}

	global $sitepress;
	if ( $sitepress ) {
		$current_lang = $sitepress->get_current_language();
		if ( ! $current_lang ) {
			$sitepress->set_default_language( 'vi' );
		}
	}
}

/**
 * WPML String Translation config
 */
add_filter( 'wpml_config_array', 'uniasia_wpml_config' );
function uniasia_wpml_config( $config ) {
	$config['wpml-config'] = array(
		'custom-fields' => array(
			'doctor_degree'        => 'Line',
			'doctor_position'      => 'Line',
			'doctor_experience'    => 'Line',
			'doctor_hospital'      => 'Line',
			'doctor_education'     => 'Line',
			'doctor_specialties'   => 'Line',
			'doctor_short_bio'     => 'Line',
			'doctor_languages'     => 'Line',
			'story_patient_name'   => 'Line',
			'story_country'        => 'Line',
			'story_cancer_type'    => 'Line',
			'story_treatment'      => 'Line',
			'story_summary'        => 'Line',
			'caller_phone'         => 'Line',
			'caller_email'         => 'Line',
		),
		'admin-texts' => array(
			'theme_mods_uniasia-cancer-theme' => array(
				'header_text'        => 'Line',
				'footer_text'        => 'Line',
				'phone_vi'           => 'Line',
				'phone_en'           => 'Line',
				'email'              => 'Line',
				'address'            => 'Line',
			),
		),
	);

	return $config;
}

/**
 * Language switcher shortcode
 */
function uniasia_language_switcher( $atts = array() ) {
	if ( ! function_exists( 'icl_get_languages' ) ) {
		return '';
	}

	$atts = shortcode_atts( array(
		'class' => 'uniasia-lang-switcher',
	), $atts );

	$languages = icl_get_languages( 'skip_missing=0&orderby=code' );

	if ( empty( $languages ) ) {
		return '';
	}

	$output = '<ul class="' . esc_attr( $atts['class'] ) . '">';
	foreach ( $languages as $lang ) {
		$flag = isset( $lang['country_flag_url'] ) ? $lang['country_flag_url'] : '';
		$is_current = $lang['active'] ? ' current' : '';
		$output .= '<li class="lang-item' . $is_current . '">';
		$output .= '<a href="' . esc_url( $lang['url'] ) . '" hreflang="' . esc_attr( $lang['language_code'] ) . '">';
		if ( $flag ) {
			$output .= '<img src="' . esc_url( $flag ) . '" alt="' . esc_attr( $lang['native_name'] ) . '" />';
		}
		$output .= '<span>' . esc_html( strtoupper( $lang['language_code'] ) ) . '</span>';
		$output .= '</a></li>';
	}
	$output .= '</ul>';

	return $output;
}
add_shortcode( 'uniasia_lang_switcher', 'uniasia_language_switcher' );

/**
 * Translate ACF text fields
 */
add_filter( 'acf/load_value', 'uniasia_translate_acf_fields', 10, 3 );
function uniasia_translate_acf_fields( $value, $post_id, $field ) {
	if ( ! function_exists( 'icl_translate' ) ) {
		return $value;
	}

	$translatable_fields = array(
		'doctor_short_bio', 'doctor_specialties', 'doctor_education',
		'story_summary',
	);

	if ( in_array( $field['name'], $translatable_fields ) && is_string( $value ) && ! empty( $value ) ) {
		$value = icl_translate( 'uniasia', $field['name'] . '_' . $post_id, $value );
	}

	return $value;
}