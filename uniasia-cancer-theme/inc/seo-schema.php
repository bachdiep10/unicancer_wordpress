<?php
/**
 * SEO Schema Markup (JSON-LD)
 *
 * @package UNI_ASIA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Schema Output (in head)
 */
add_action( 'wp_head', 'uniasia_output_schema', 5 );
function uniasia_output_schema() {
	$schema = array();

	if ( is_front_page() || is_home() ) {
		$schema[] = uniasia_get_organization_schema();
		$schema[] = uniasia_get_website_schema();
	} elseif ( is_singular( 'doctor' ) ) {
		$schema[] = uniasia_get_doctor_schema();
	} elseif ( is_singular( 'patient_story' ) ) {
		$schema[] = uniasia_get_medical_condition_schema();
	} elseif ( is_singular( 'faq' ) || is_post_type_archive( 'faq' ) ) {
		$schema[] = uniasia_get_faq_schema();
	} elseif ( is_singular() ) {
		$schema[] = uniasia_get_article_schema();
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}

/**
 * Organization Schema (MedicalOrganization)
 */
function uniasia_get_organization_schema() {
	$phone = function_exists( 'get_field' ) ? get_field( 'contact_phone_vi', 'option' ) : get_option( 'options_contact_phone_vi' );
	$phone = $phone ?: '+84-28-9999-9999';
	$email = function_exists( 'get_field' ) ? get_field( 'contact_email', 'option' ) : get_option( 'options_contact_email' );
	$email = $email ?: 'info@uniasia-cancer.com';
	$fb    = function_exists( 'get_field' ) ? get_field( 'social_facebook', 'option' ) : get_option( 'options_social_facebook' );
	$yt    = function_exists( 'get_field' ) ? get_field( 'social_youtube', 'option' ) : get_option( 'options_social_youtube' );
	$ig    = function_exists( 'get_field' ) ? get_field( 'social_instagram', 'option' ) : get_option( 'options_social_instagram' );

	return array(
		'@context'    => 'https://schema.org',
		'@type'       => array( 'MedicalOrganization', 'Hospital' ),
		'name'        => get_bloginfo( 'name' ),
		'url'         => home_url( '/' ),
		'logo'        => UNIASIA_THEME_URI . 'assets/images/logo.png',
		'description' => get_bloginfo( 'description' ),
		'telephone'   => $phone,
		'email'       => $email,
		'address'     => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => '',
			'addressLocality' => 'Chengdu',
			'addressRegion'   => '',
			'postalCode'      => '',
			'addressCountry'  => 'CN',
		),
		'geo'         => array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => '30.5728',
			'longitude' => '104.0668',
		),
		'sameAs'      => array( $fb, $yt, $ig ),
		'medicalSpecialty' => array(
			'Oncology',
			'Interventional Radiology',
			'Radiation Oncology',
			'Minimally Invasive Surgery',
		),
		'priceRange' => '$$',
	);
}

/**
 * Website Schema
 */
function uniasia_get_website_schema() {
	return array(
		'@context'        => 'https://schema.org',
		'@type'           => 'WebSite',
		'name'            => get_bloginfo( 'name' ),
		'url'             => home_url( '/' ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => home_url( '/?s={search_term_string}' ),
			'query-input' => 'required name=search_term_string',
		),
	);
}

/**
 * Doctor Schema (Physician)
 */
function uniasia_get_doctor_schema() {
	$post_id   = get_the_ID();
	$degree    = get_field( 'doctor_degree', $post_id );
	$specialty = get_field( 'doctor_specialties', $post_id );
	$hospital  = get_field( 'doctor_hospital', $post_id );
	$image     = get_the_post_thumbnail_url( $post_id, 'full' );

	return array(
		'@context'         => 'https://schema.org',
		'@type'            => 'Physician',
		'name'             => get_the_title(),
		'givenName'        => get_the_title(),
		'alumniOf'         => $hospital,
		'jobTitle'         => $degree,
		'description'      => wp_strip_all_tags( get_the_content() ),
		'image'            => $image,
		'url'              => get_permalink(),
		'worksFor'         => array(
			'@type' => 'Hospital',
			'name'  => $hospital ?: 'UNI-ASIA Cancer Hospital',
		),
		'medicalSpecialty' => $specialty,
		'affiliation'      => array(
			'@type' => 'Hospital',
			'name'  => 'UNI-ASIA Cancer Hospital',
		),
	);
}

/**
 * Article Schema
 */
function uniasia_get_article_schema() {
	return array(
		'@context'         => 'https://schema.org',
		'@type'            => 'Article',
		'headline'         => get_the_title(),
		'description'      => wp_strip_all_tags( get_the_excerpt() ),
		'image'            => get_the_post_thumbnail_url(),
		'datePublished'    => get_the_date( 'c' ),
		'dateModified'     => get_the_modified_date( 'c' ),
		'author'           => array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
		),
		'publisher'        => array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
			'logo'  => array(
				'@type' => 'ImageObject',
				'url'   => UNIASIA_THEME_URI . 'assets/images/logo.png',
			),
		),
		'mainEntityOfPage' => get_permalink(),
	);
}

/**
 * FAQ Schema
 */
function uniasia_get_faq_schema() {
	$faqs = new WP_Query( array(
		'post_type'      => 'faq',
		'posts_per_page' => 50,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	) );

	if ( ! $faqs->have_posts() ) {
		return array();
	}

	$faq_array = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => array(),
	);

	while ( $faqs->have_posts() ) {
		$faqs->the_post();
		$faq_array['mainEntity'][] = array(
			'@type'          => 'Question',
			'name'           => get_the_title(),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => wp_strip_all_tags( get_the_content() ),
			),
		);
	}
	wp_reset_postdata();

	return $faq_array;
}

/**
 * Patient Story - MedicalEntity Schema
 */
function uniasia_get_medical_condition_schema() {
	return array(
		'@context'    => 'https://schema.org',
		'@type'       => 'MedicalCondition',
		'name'        => get_field( 'story_cancer_type', get_the_ID() ) ?: get_the_title(),
		'description' => wp_strip_all_tags( get_the_excerpt() ),
		'url'         => get_permalink(),
	);
}

/**
 * Breadcrumb Schema
 */
add_action( 'wp_head', 'uniasia_breadcrumb_schema' );
function uniasia_breadcrumb_schema() {
	if ( is_front_page() ) {
		return;
	}

	$crumbs = array( array(
		'@type'    => 'ListItem',
		'position' => 1,
		'name'     => __( 'Trang chủ', 'uniasia' ),
		'item'     => home_url( '/' ),
	) );

	if ( is_singular() ) {
		$post = get_post();
		if ( $post->post_parent ) {
			$parent = get_post( $post->post_parent );
			$crumbs[] = array(
				'@type'    => 'ListItem',
				'position' => count( $crumbs ) + 1,
				'name'     => $parent->post_title,
				'item'     => get_permalink( $parent ),
			);
		}
		$crumbs[] = array(
			'@type'    => 'ListItem',
			'position' => count( $crumbs ) + 1,
			'name'     => get_the_title(),
		);
	} elseif ( is_post_type_archive() ) {
		$crumbs[] = array(
			'@type'    => 'ListItem',
			'position' => 2,
			'name'     => post_type_archive_title( '', false ),
		);
	} elseif ( is_tax() ) {
		$crumbs[] = array(
			'@type'    => 'ListItem',
			'position' => 2,
			'name'     => single_term_title( '', false ),
		);
	}

	$schema = array(
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => $crumbs,
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}

/**
 * Open Graph meta tags
 */
add_action( 'wp_head', 'uniasia_og_meta', 1 );
function uniasia_og_meta() {
	if ( is_singular() ) {
		echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '" />' . "\n";
		echo '<meta property="og:description" content="' . esc_attr( wp_strip_all_tags( get_the_excerpt() ) ) . '" />' . "\n";
		echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '" />' . "\n";
		echo '<meta property="og:type" content="article" />' . "\n";
		$image = get_the_post_thumbnail_url();
		if ( $image ) {
			echo '<meta property="og:image" content="' . esc_url( $image ) . '" />' . "\n";
		}
	} elseif ( is_front_page() ) {
		echo '<meta property="og:title" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
		echo '<meta property="og:description" content="' . esc_attr( get_bloginfo( 'description' ) ) . '" />' . "\n";
		echo '<meta property="og:url" content="' . esc_url( home_url( '/' ) ) . '" />' . "\n";
		echo '<meta property="og:type" content="website" />' . "\n";
	}
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
	echo '<meta property="og:locale" content="vi_VN" />' . "\n";
}