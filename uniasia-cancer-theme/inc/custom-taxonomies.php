<?php
/**
 * Custom Taxonomies Registration
 *
 * @package UNI_ASIA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Taxonomy: Cancer Category (Loại ung thư - cho Patient Story)
 */
function uniasia_register_cancer_category_tax() {
	$labels = array(
		'name'              => _x( 'Loại ung thư', 'taxonomy general name', 'uniasia' ),
		'singular_name'     => _x( 'Loại ung thư', 'taxonomy singular name', 'uniasia' ),
		'search_items'      => __( 'Tìm kiếm', 'uniasia' ),
		'all_items'         => __( 'Tất cả', 'uniasia' ),
		'parent_item'       => __( 'Cha', 'uniasia' ),
		'parent_item_colon' => __( 'Cha:', 'uniasia' ),
		'edit_item'         => __( 'Sửa', 'uniasia' ),
		'update_item'       => __( 'Cập nhật', 'uniasia' ),
		'add_new_item'      => __( 'Thêm mới', 'uniasia' ),
		'new_item_name'     => __( 'Tên mới', 'uniasia' ),
		'menu_name'         => __( 'Loại ung thư', 'uniasia' ),
	);

	register_taxonomy( 'cancer_category', array( 'patient_story', 'cancer_type' ), array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'cancer-category' ),
	) );
}
add_action( 'init', 'uniasia_register_cancer_category_tax' );

/**
 * Taxonomy: Doctor Specialty (Chuyên môn bác sĩ)
 */
function uniasia_register_doctor_specialty_tax() {
	$labels = array(
		'name'              => _x( 'Chuyên môn', 'taxonomy general name', 'uniasia' ),
		'singular_name'     => _x( 'Chuyên môn', 'taxonomy singular name', 'uniasia' ),
		'search_items'      => __( 'Tìm kiếm', 'uniasia' ),
		'all_items'         => __( 'Tất cả', 'uniasia' ),
		'edit_item'         => __( 'Sửa', 'uniasia' ),
		'update_item'       => __( 'Cập nhật', 'uniasia' ),
		'add_new_item'      => __( 'Thêm mới', 'uniasia' ),
		'new_item_name'     => __( 'Tên mới', 'uniasia' ),
		'menu_name'         => __( 'Chuyên môn', 'uniasia' ),
	);

	register_taxonomy( 'doctor_specialty', array( 'doctor' ), array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'chuyen-mon' ),
	) );
}
add_action( 'init', 'uniasia_register_doctor_specialty_tax' );

/**
 * Taxonomy: FAQ Group (Nhóm FAQ)
 */
function uniasia_register_faq_group_tax() {
	$labels = array(
		'name'              => _x( 'Nhóm FAQ', 'taxonomy general name', 'uniasia' ),
		'singular_name'     => _x( 'Nhóm FAQ', 'taxonomy singular name', 'uniasia' ),
		'search_items'      => __( 'Tìm kiếm', 'uniasia' ),
		'all_items'         => __( 'Tất cả', 'uniasia' ),
		'edit_item'         => __( 'Sửa', 'uniasia' ),
		'update_item'       => __( 'Cập nhật', 'uniasia' ),
		'add_new_item'      => __( 'Thêm nhóm mới', 'uniasia' ),
		'new_item_name'     => __( 'Tên nhóm mới', 'uniasia' ),
		'menu_name'         => __( 'Nhóm FAQ', 'uniasia' ),
	);

	register_taxonomy( 'faq_group', array( 'faq' ), array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'faq-group' ),
	) );
}
add_action( 'init', 'uniasia_register_faq_group_tax' );

/**
 * Pre-populate default FAQ groups on theme activation
 */
function uniasia_create_default_faq_groups() {
	$groups = array(
		'general'         => __( 'Câu hỏi chung', 'uniasia' ),
		'treatment'       => __( 'Điều trị', 'uniasia' ),
		'international'   => __( 'Bệnh nhân quốc tế', 'uniasia' ),
		'insurance'       => __( 'Bảo hiểm', 'uniasia' ),
		'documents'       => __( 'Hồ sơ cần thiết', 'uniasia' ),
	);

	foreach ( $groups as $slug => $name ) {
		if ( ! term_exists( $slug, 'faq_group' ) ) {
			wp_insert_term( $name, 'faq_group', array( 'slug' => $slug ) );
		}
	}

	$specialties = array(
		'interventional'   => __( 'Can thiệp', 'uniasia' ),
		'radiation'        => __( 'Xạ trị', 'uniasia' ),
		'chemotherapy'     => __( 'Hóa trị', 'uniasia' ),
		'immunotherapy'    => __( 'Liệu pháp miễn dịch', 'uniasia' ),
		'targeted-therapy' => __( 'Điều trị nhắm trúng đích', 'uniasia' ),
		'cryoablation'     => __( 'Áp lạnh', 'uniasia' ),
		'nano-knife'       => __( 'Dao Nano', 'uniasia' ),
		'microwave'        => __( 'Vi sóng', 'uniasia' ),
	);

	foreach ( $specialties as $slug => $name ) {
		if ( ! term_exists( $slug, 'doctor_specialty' ) ) {
			wp_insert_term( $name, 'doctor_specialty', array( 'slug' => $slug ) );
		}
	}

	$cancers = array(
		'liver-cancer'      => __( 'Ung thư gan', 'uniasia' ),
		'lung-cancer'       => __( 'Ung thư phổi', 'uniasia' ),
		'breast-cancer'     => __( 'Ung thư vú', 'uniasia' ),
		'colon-rectal'      => __( 'Ung thư đại trực tràng', 'uniasia' ),
		'cervical-cancer'   => __( 'Ung thư cổ tử cung', 'uniasia' ),
		'thyroid-cancer'    => __( 'Ung thư tuyến giáp', 'uniasia' ),
		'pancreatic-cancer' => __( 'Ung thư tuyến tụy', 'uniasia' ),
		'stomach-cancer'    => __( 'Ung thư dạ dày', 'uniasia' ),
		'esophageal-cancer' => __( 'Ung thư thực quản', 'uniasia' ),
		'nasopharyngeal'    => __( 'Ung thư vòm họng', 'uniasia' ),
		'bladder-cancer'    => __( 'Ung thư bàng quang', 'uniasia' ),
		'prostate-cancer'   => __( 'Ung thư tuyến tiền liệt', 'uniasia' ),
	);

	foreach ( $cancers as $slug => $name ) {
		if ( ! term_exists( $slug, 'cancer_category' ) ) {
			wp_insert_term( $name, 'cancer_category', array( 'slug' => $slug ) );
		}
	}
}
add_action( 'after_switch_theme', 'uniasia_create_default_faq_groups' );