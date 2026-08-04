<?php
/**
 * ACF Field Groups for Custom Post Types
 *
 * @package UNI_ASIA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fallback: nếu không có ACF thì dùng meta boxes native
 */
if ( ! class_exists( 'ACF' ) ) {
	add_action( 'admin_notices', 'uniasia_acf_notice' );
	function uniasia_acf_notice() {
		echo '<div class="notice notice-warning"><p><strong>UNI-ASIA Theme:</strong> Cài đặt plugin <a href="https://www.advancedcustomfields.com/" target="_blank">Advanced Custom Fields Pro</a> để sử dụng đầy đủ tính năng custom fields.</p></div>';
	}
}

/**
 * Doctor CPT - ACF Fields
 */
if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group( array(
		'key'      => 'group_doctor_info',
		'title'    => __( 'Thông tin bác sĩ', 'uniasia' ),
		'fields'   => array(
			array(
				'key'   => 'field_doctor_degree',
				'label' => __( 'Học vị', 'uniasia' ),
				'name'  => 'doctor_degree',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_doctor_position',
				'label' => __( 'Chức vụ', 'uniasia' ),
				'name'  => 'doctor_position',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_doctor_experience',
				'label' => __( 'Số năm kinh nghiệm', 'uniasia' ),
				'name'  => 'doctor_experience',
				'type'  => 'number',
			),
			array(
				'key'   => 'field_doctor_hospital',
				'label' => __( 'Bệnh viện / Cơ sở', 'uniasia' ),
				'name'  => 'doctor_hospital',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_doctor_education',
				'label' => __( 'Học vị / Đào tạo', 'uniasia' ),
				'name'  => 'doctor_education',
				'type'  => 'textarea',
			),
			array(
				'key'   => 'field_doctor_specialties',
				'label' => __( 'Lĩnh vực chuyên môn', 'uniasia' ),
				'name'  => 'doctor_specialties',
				'type'  => 'textarea',
			),
			array(
				'key'   => 'field_doctor_short_bio',
				'label' => __( 'Mô tả ngắn (hiển thị ở card)', 'uniasia' ),
				'name'  => 'doctor_short_bio',
				'type'  => 'textarea',
				'rows'  => 4,
			),
			array(
				'key'   => 'field_doctor_languages',
				'label' => __( 'Ngôn ngữ', 'uniasia' ),
				'name'  => 'doctor_languages',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_doctor_order',
				'label' => __( 'Thứ tự hiển thị', 'uniasia' ),
				'name'  => 'doctor_order',
				'type'  => 'number',
				'default_value' => 0,
			),
			array(
				'key'   => 'field_doctor_is_featured',
				'label' => __( 'Hiển thị ở trang chủ (MDT Team)', 'uniasia' ),
				'name'  => 'doctor_is_featured',
				'type'  => 'true_false',
				'ui'    => 1,
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'doctor',
				),
			),
		),
		'menu_order' => 0,
		'position'   => 'normal',
		'style'      => 'default',
		'label_placement' => 'top',
	) );

	/**
	 * Patient Story CPT - ACF Fields
	 */
	acf_add_local_field_group( array(
		'key'      => 'group_patient_story_info',
		'title'    => __( 'Thông tin bệnh nhân', 'uniasia' ),
		'fields'   => array(
			array(
				'key'   => 'field_story_patient_name',
				'label' => __( 'Tên bệnh nhân (hiển thị)', 'uniasia' ),
				'name'  => 'story_patient_name',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_story_age',
				'label' => __( 'Tuổi', 'uniasia' ),
				'name'  => 'story_age',
				'type'  => 'number',
			),
			array(
				'key'   => 'field_story_country',
				'label' => __( 'Quốc gia', 'uniasia' ),
				'name'  => 'story_country',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_story_cancer_type',
				'label' => __( 'Loại ung thư', 'uniasia' ),
				'name'  => 'story_cancer_type',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_story_treatment',
				'label' => __( 'Phương pháp điều trị', 'uniasia' ),
				'name'  => 'story_treatment',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_story_summary',
				'label' => __( 'Tóm tắt ngắn', 'uniasia' ),
				'name'  => 'story_summary',
				'type'  => 'textarea',
				'rows'  => 3,
			),
			array(
				'key'   => 'field_story_is_featured',
				'label' => __( 'Hiển thị ở trang chủ', 'uniasia' ),
				'name'  => 'story_is_featured',
				'type'  => 'true_false',
				'ui'    => 1,
			),
			array(
				'key'   => 'field_story_order',
				'label' => __( 'Thứ tự hiển thị', 'uniasia' ),
				'name'  => 'story_order',
				'type'  => 'number',
				'default_value' => 0,
			),
			array(
				'key'   => 'field_story_video_url',
				'label' => __( 'Video URL (YouTube/Vimeo)', 'uniasia' ),
				'name'  => 'story_video_url',
				'type'  => 'url',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'patient_story',
				),
			),
		),
		'position' => 'normal',
	) );

	/**
	 * Cancer Type CPT - ACF Fields
	 */
	acf_add_local_field_group( array(
		'key'      => 'group_cancer_type_info',
		'title'    => __( 'Thông tin loại ung thư', 'uniasia' ),
		'fields'   => array(
			array(
				'key'   => 'field_cancer_icon',
				'label' => __( 'Icon (SVG / Image URL)', 'uniasia' ),
				'name'  => 'cancer_icon',
				'type'  => 'image',
			),
			array(
				'key'   => 'field_cancer_symptoms',
				'label' => __( 'Triệu chứng', 'uniasia' ),
				'name'  => 'cancer_symptoms',
				'type'  => 'wysiwyg',
			),
			array(
				'key'   => 'field_cancer_diagnosis',
				'label' => __( 'Chẩn đoán', 'uniasia' ),
				'name'  => 'cancer_diagnosis',
				'type'  => 'wysiwyg',
			),
			array(
				'key'   => 'field_cancer_treatment',
				'label' => __( 'Phương pháp điều trị', 'uniasia' ),
				'name'  => 'cancer_treatment',
				'type'  => 'wysiwyg',
			),
			array(
				'key'   => 'field_cancer_order',
				'label' => __( 'Thứ tự', 'uniasia' ),
				'name'  => 'cancer_order',
				'type'  => 'number',
			),
			array(
				'key'   => 'field_cancer_color',
				'label' => __( 'Màu chủ đạo', 'uniasia' ),
				'name'  => 'cancer_color',
				'type'  => 'color_picker',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'cancer_type',
				),
			),
		),
	) );

	/**
	 * Technology CPT - ACF Fields
	 */
	acf_add_local_field_group( array(
		'key'      => 'group_technology_info',
		'title'    => __( 'Thông tin kỹ thuật', 'uniasia' ),
		'fields'   => array(
			array(
				'key'   => 'field_tech_short_name',
				'label' => __( 'Tên viết tắt', 'uniasia' ),
				'name'  => 'tech_short_name',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_tech_full_name',
				'label' => __( 'Tên đầy đủ (Tiếng Anh)', 'uniasia' ),
				'name'  => 'tech_full_name',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_tech_icon',
				'label' => __( 'Icon', 'uniasia' ),
				'name'  => 'tech_icon',
				'type'  => 'image',
			),
			array(
				'key'   => 'field_tech_summary',
				'label' => __( 'Mô tả ngắn', 'uniasia' ),
				'name'  => 'tech_summary',
				'type'  => 'textarea',
				'rows'  => 3,
			),
			array(
				'key'   => 'field_tech_order',
				'label' => __( 'Thứ tự hiển thị', 'uniasia' ),
				'name'  => 'tech_order',
				'type'  => 'number',
				'default_value' => 0,
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'technology',
				),
			),
		),
	) );

	/**
	 * Site options page - Thông tin liên hệ chung
	 */
	acf_add_local_field_group( array(
		'key'      => 'group_site_options',
		'title'    => __( 'Thông tin liên hệ & Cài đặt', 'uniasia' ),
		'fields'   => array(
			array(
				'key'   => 'field_contact_phone_vi',
				'label' => __( 'Hotline (Tiếng Việt)', 'uniasia' ),
				'name'  => 'contact_phone_vi',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_contact_phone_en',
				'label' => __( 'Hotline (English)', 'uniasia' ),
				'name'  => 'contact_phone_en',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_contact_whatsapp',
				'label' => __( 'WhatsApp', 'uniasia' ),
				'name'  => 'contact_whatsapp',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_contact_email',
				'label' => __( 'Email', 'uniasia' ),
				'name'  => 'contact_email',
				'type'  => 'email',
			),
			array(
				'key'   => 'field_contact_address',
				'label' => __( 'Địa chỉ bệnh viện', 'uniasia' ),
				'name'  => 'contact_address',
				'type'  => 'textarea',
			),
			array(
				'key'   => 'field_social_facebook',
				'label' => __( 'Facebook URL', 'uniasia' ),
				'name'  => 'social_facebook',
				'type'  => 'url',
			),
			array(
				'key'   => 'field_social_youtube',
				'label' => __( 'YouTube URL', 'uniasia' ),
				'name'  => 'social_youtube',
				'type'  => 'url',
			),
			array(
				'key'   => 'field_social_instagram',
				'label' => __( 'Instagram URL', 'uniasia' ),
				'name'  => 'social_instagram',
				'type'  => 'url',
			),
			array(
				'key'   => 'field_stat_surgeries',
				'label' => __( 'Số ca phẫu thuật', 'uniasia' ),
				'name'  => 'stat_surgeries',
				'type'  => 'text',
				'default_value' => '750,000+',
			),
			array(
				'key'   => 'field_stat_patients_year',
				'label' => __( 'Bệnh nhân / năm', 'uniasia' ),
				'name'  => 'stat_patients_year',
				'type'  => 'text',
				'default_value' => '20,000+',
			),
			array(
				'key'   => 'field_stat_visits',
				'label' => __( 'Lượt khám', 'uniasia' ),
				'name'  => 'stat_visits',
				'type'  => 'text',
				'default_value' => '1,000,000+',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'site-settings',
				),
			),
		),
	) );

endif;

/**
 * Đăng ký Options Page cho site settings
 */
if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page( array(
		'page_title' => __( 'Cài đặt site', 'uniasia' ),
		'menu_title' => __( 'Cài đặt site', 'uniasia' ),
		'menu_slug'  => 'site-settings',
		'capability' => 'edit_posts',
		'icon_url'   => 'dashicons-admin-generic',
		'position'   => 2,
	) );
}