<?php
/**
 * Custom Post Types Registration
 *
 * @package UNI_ASIA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CPT: Doctor (Bác sĩ)
 */
function uniasia_register_doctor_cpt() {
	$labels = array(
		'name'                  => _x( 'Bác sĩ', 'Post type general name', 'uniasia' ),
		'singular_name'         => _x( 'Bác sĩ', 'Post type singular name', 'uniasia' ),
		'menu_name'             => _x( 'Bác sĩ', 'Admin Menu text', 'uniasia' ),
		'name_admin_bar'        => _x( 'Bác sĩ', 'Add New on Toolbar', 'uniasia' ),
		'add_new'               => __( 'Thêm mới', 'uniasia' ),
		'add_new_item'          => __( 'Thêm bác sĩ mới', 'uniasia' ),
		'new_item'              => __( 'Bác sĩ mới', 'uniasia' ),
		'edit_item'             => __( 'Sửa thông tin bác sĩ', 'uniasia' ),
		'view_item'             => __( 'Xem bác sĩ', 'uniasia' ),
		'all_items'             => __( 'Tất cả bác sĩ', 'uniasia' ),
		'search_items'          => __( 'Tìm bác sĩ', 'uniasia' ),
		'not_found'             => __( 'Không tìm thấy bác sĩ', 'uniasia' ),
		'featured_image'        => __( 'Ảnh đại diện', 'uniasia' ),
		'set_featured_image'    => __( 'Đặt ảnh đại diện', 'uniasia' ),
		'remove_featured_image' => __( 'Xóa ảnh đại diện', 'uniasia' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => true,
		'rest_base'           => 'doctors',
		'query_var'           => true,
		'rewrite'             => array( 'slug' => 'doctors', 'with_front' => false ),
		'capability_type'     => 'post',
		'has_archive'         => 'doctors',
		'hierarchical'        => false,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-businessman',
		'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'elementor', 'revisions' ),
		'taxonomies'          => array( 'doctor_specialty' ),
	);

	register_post_type( 'doctor', $args );
}
add_action( 'init', 'uniasia_register_doctor_cpt' );

/**
 * CPT: Patient Story (Câu chuyện bệnh nhân)
 */
function uniasia_register_patient_story_cpt() {
	$labels = array(
		'name'                  => _x( 'Câu chuyện bệnh nhân', 'Post type general name', 'uniasia' ),
		'singular_name'         => _x( 'Câu chuyện', 'Post type singular name', 'uniasia' ),
		'menu_name'             => _x( 'Câu chuyện BN', 'Admin Menu text', 'uniasia' ),
		'add_new'               => __( 'Thêm mới', 'uniasia' ),
		'add_new_item'          => __( 'Thêm câu chuyện', 'uniasia' ),
		'edit_item'             => __( 'Sửa câu chuyện', 'uniasia' ),
		'view_item'             => __( 'Xem câu chuyện', 'uniasia' ),
		'all_items'             => __( 'Tất cả câu chuyện', 'uniasia' ),
		'search_items'          => __( 'Tìm câu chuyện', 'uniasia' ),
		'not_found'             => __( 'Không tìm thấy', 'uniasia' ),
		'featured_image'        => __( 'Ảnh bệnh nhân', 'uniasia' ),
		'set_featured_image'    => __( 'Đặt ảnh', 'uniasia' ),
		'remove_featured_image' => __( 'Xóa ảnh', 'uniasia' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => true,
		'rest_base'           => 'patient-stories',
		'query_var'           => true,
		'rewrite'             => array( 'slug' => 'patient-stories', 'with_front' => false ),
		'capability_type'     => 'post',
		'has_archive'         => 'patient-stories',
		'hierarchical'        => false,
		'menu_position'       => 6,
		'menu_icon'           => 'dashicons-heart',
		'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'elementor', 'revisions' ),
		'taxonomies'          => array( 'cancer_category' ),
	);

	register_post_type( 'patient_story', $args );
}
add_action( 'init', 'uniasia_register_patient_story_cpt' );

/**
 * CPT: Cancer Type (Loại ung thư)
 */
function uniasia_register_cancer_type_cpt() {
	$labels = array(
		'name'              => _x( 'Loại ung thư', 'Post type general name', 'uniasia' ),
		'singular_name'     => _x( 'Loại ung thư', 'Post type singular name', 'uniasia' ),
		'menu_name'         => _x( 'Loại ung thư', 'Admin Menu text', 'uniasia' ),
		'add_new'           => __( 'Thêm mới', 'uniasia' ),
		'add_new_item'      => __( 'Thêm loại ung thư', 'uniasia' ),
		'edit_item'         => __( 'Sửa loại ung thư', 'uniasia' ),
		'view_item'         => __( 'Xem loại ung thư', 'uniasia' ),
		'all_items'         => __( 'Tất cả loại ung thư', 'uniasia' ),
		'search_items'      => __( 'Tìm kiếm', 'uniasia' ),
		'not_found'         => __( 'Không tìm thấy', 'uniasia' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'rest_base'          => 'cancer-types',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'cancer-types', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => 'cancer-types',
		'hierarchical'       => false,
		'menu_position'      => 7,
		'menu_icon'          => 'dashicons-pressthis',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'elementor', 'revisions' ),
	);

	register_post_type( 'cancer_type', $args );
}
add_action( 'init', 'uniasia_register_cancer_type_cpt' );

/**
 * CPT: Technology (Kỹ thuật điều trị)
 */
function uniasia_register_technology_cpt() {
	$labels = array(
		'name'              => _x( 'Kỹ thuật điều trị', 'Post type general name', 'uniasia' ),
		'singular_name'     => _x( 'Kỹ thuật', 'Post type singular name', 'uniasia' ),
		'menu_name'         => _x( 'Kỹ thuật điều trị', 'Admin Menu text', 'uniasia' ),
		'add_new'           => __( 'Thêm mới', 'uniasia' ),
		'add_new_item'      => __( 'Thêm kỹ thuật', 'uniasia' ),
		'edit_item'         => __( 'Sửa kỹ thuật', 'uniasia' ),
		'view_item'         => __( 'Xem kỹ thuật', 'uniasia' ),
		'all_items'         => __( 'Tất cả kỹ thuật', 'uniasia' ),
		'search_items'      => __( 'Tìm kiếm', 'uniasia' ),
		'not_found'         => __( 'Không tìm thấy', 'uniasia' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'rest_base'          => 'technologies',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'technologies', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => 'technologies',
		'hierarchical'       => false,
		'menu_position'      => 8,
		'menu_icon'          => 'dashicons-admin-tools',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'elementor', 'revisions' ),
	);

	register_post_type( 'technology', $args );
}
add_action( 'init', 'uniasia_register_technology_cpt' );

/**
 * CPT: FAQ (Câu hỏi thường gặp)
 */
function uniasia_register_faq_cpt() {
	$labels = array(
		'name'              => _x( 'Câu hỏi thường gặp', 'Post type general name', 'uniasia' ),
		'singular_name'     => _x( 'Câu hỏi', 'Post type singular name', 'uniasia' ),
		'menu_name'         => _x( 'FAQ', 'Admin Menu text', 'uniasia' ),
		'add_new'           => __( 'Thêm mới', 'uniasia' ),
		'add_new_item'      => __( 'Thêm câu hỏi', 'uniasia' ),
		'edit_item'         => __( 'Sửa câu hỏi', 'uniasia' ),
		'view_item'         => __( 'Xem câu hỏi', 'uniasia' ),
		'all_items'         => __( 'Tất cả FAQ', 'uniasia' ),
		'search_items'      => __( 'Tìm kiếm', 'uniasia' ),
		'not_found'         => __( 'Không tìm thấy', 'uniasia' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'rest_base'          => 'faqs',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'faqs', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => 'faqs',
		'hierarchical'       => false,
		'menu_position'      => 9,
		'menu_icon'          => 'dashicons-format-chat',
		'supports'           => array( 'title', 'editor', 'custom-fields', 'elementor', 'revisions' ),
		'taxonomies'         => array( 'faq_group' ),
	);

	register_post_type( 'faq', $args );
}
add_action( 'init', 'uniasia_register_faq_cpt' );

/**
 * Flush rewrite rules on theme activation
 */
function uniasia_rewrite_flush() {
	uniasia_register_doctor_cpt();
	uniasia_register_patient_story_cpt();
	uniasia_register_cancer_type_cpt();
	uniasia_register_technology_cpt();
	uniasia_register_faq_cpt();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'uniasia_rewrite_flush' );