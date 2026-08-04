<?php
/**
 * Template Helpers - Common functions for templates
 *
 * @package UNI_ASIA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get doctors (featured or all)
 */
function uniasia_get_doctors( $args = array() ) {
	$defaults = array(
		'post_type'      => 'doctor',
		'posts_per_page' => -1,
		'orderby'        => 'meta_value_num',
		'meta_key'       => 'doctor_order',
		'order'          => 'ASC',
	);
	$args = wp_parse_args( $args, $defaults );

	if ( ! empty( $args['featured'] ) ) {
		$args['meta_query'] = array(
			array(
				'key'     => 'doctor_is_featured',
				'value'   => '1',
				'compare' => '=',
			),
		);
	}

	return new WP_Query( $args );
}

/**
 * Get patient stories
 */
function uniasia_get_stories( $args = array() ) {
	$defaults = array(
		'post_type'      => 'patient_story',
		'posts_per_page' => -1,
		'orderby'        => 'meta_value_num',
		'meta_key'       => 'story_order',
		'order'          => 'ASC',
	);
	$args = wp_parse_args( $args, $defaults );

	if ( ! empty( $args['featured'] ) ) {
		$args['meta_query'] = array(
			array(
				'key'     => 'story_is_featured',
				'value'   => '1',
				'compare' => '=',
			),
		);
	}

	return new WP_Query( $args );
}

/**
 * Get cancer types
 */
function uniasia_get_cancer_types( $args = array() ) {
	$defaults = array(
		'post_type'      => 'cancer_type',
		'posts_per_page' => 12,
		'orderby'        => 'meta_value_num',
		'meta_key'       => 'cancer_order',
		'order'          => 'ASC',
	);
	$args = wp_parse_args( $args, $defaults );
	return new WP_Query( $args );
}

/**
 * Get technologies
 */
function uniasia_get_technologies( $args = array() ) {
	$defaults = array(
		'post_type'      => 'technology',
		'posts_per_page' => -1,
		'orderby'        => 'meta_value_num',
		'meta_key'       => 'tech_order',
		'order'          => 'ASC',
	);
	$args = wp_parse_args( $args, $defaults );
	return new WP_Query( $args );
}

/**
 * Get FAQs grouped
 */
function uniasia_get_faqs_grouped( $group_slug = '' ) {
	$args = array(
		'post_type'      => 'faq',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	);

	if ( $group_slug ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'faq_group',
				'field'    => 'slug',
				'terms'    => $group_slug,
			),
		);
	}

	return new WP_Query( $args );
}

/**
 * Get field with fallback
 */
function uniasia_field( $field_name, $post_id = null, $default = '' ) {
	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $field_name, $post_id );
		return $value ?: $default;
	}

	$post_id = $post_id ?: get_the_ID();
	$value   = get_post_meta( $post_id, $field_name, true );
	return $value ?: $default;
}

/**
 * Truncate text
 */
function uniasia_truncate( $text, $length = 100, $suffix = '...' ) {
	if ( mb_strlen( $text ) <= $length ) {
		return $text;
	}
	return mb_substr( $text, 0, $length ) . $suffix;
}

/**
 * Get contact info
 */
function uniasia_get_contact( $key, $default = '' ) {
	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $key, 'option' );
		if ( $value ) {
			return $value;
		}
	}
	return $default;
}

/**
 * Stats data
 */
function uniasia_get_stats() {
	return array(
		array(
			'number' => uniasia_get_contact( 'stat_surgeries', '750,000+' ),
			'label' => __( 'Ca phẫu thuật xâm lấn tối thiểu', 'uniasia' ),
			'icon'  => 'scalpel',
		),
		array(
			'number' => uniasia_get_contact( 'stat_patients_year', '20,000+' ),
			'label' => __( 'Bệnh nhân / năm', 'uniasia' ),
			'icon'  => 'patients',
		),
		array(
			'number' => uniasia_get_contact( 'stat_visits', '1,000,000+' ),
			'label' => __( 'Lượt khám', 'uniasia' ),
			'icon'  => 'visits',
		),
	);
}

/**
 * International process steps
 */
function uniasia_get_intl_steps() {
	return array(
		array(
			'number' => '01',
			'title'  => __( 'Tư vấn trực tuyến & Đặt lịch', 'uniasia' ),
			'desc'   => __( 'Tư vấn qua hotline, email tiếng Anh hoặc WhatsApp. Xác nhận thời gian khám và chuyên gia phụ trách.', 'uniasia' ),
			'icon'   => 'consultation',
		),
		array(
			'number' => '02',
			'title'  => __( 'Hỗ trợ trước chuyến đi', 'uniasia' ),
			'desc'   => __( 'Cung cấp thư mời xin visa y tế. Hỗ trợ đặt vé máy bay, khách sạn và hướng dẫn hồ sơ cần thiết.', 'uniasia' ),
			'icon'   => 'travel',
		),
		array(
			'number' => '03',
			'title'  => __( 'Đón tiếp tại bệnh viện', 'uniasia' ),
			'desc'   => __( 'Chuyên viên quốc tế đón tiếp, xác minh thông tin, lập hồ sơ bệnh án và giải thích chi phí.', 'uniasia' ),
			'icon'   => 'reception',
		),
		array(
			'number' => '04',
			'title'  => __( 'Điều trị', 'uniasia' ),
			'desc'   => __( 'Phiên dịch y khoa chuyên nghiệp đồng hành. Ưu tiên sắp xếp xét nghiệm và thực hiện phác đồ điều trị.', 'uniasia' ),
			'icon'   => 'treatment',
		),
		array(
			'number' => '05',
			'title'  => __( 'Thanh toán & Theo dõi', 'uniasia' ),
			'desc'   => __( 'Hỗ trợ thanh toán, làm thủ tục bảo hiểm. Theo dõi từ xa và đặt lịch tái khám sau điều trị.', 'uniasia' ),
			'icon'   => 'follow-up',
		),
	);
}

/**
 * Why Choose Us items
 */
function uniasia_get_why_choose() {
	return array(
		array(
			'title' => __( 'Chuyên gia uy tín', 'uniasia' ),
			'desc'  => __( 'Quy tụ chuyên gia hàng đầu Trung Quốc với hơn 40 năm kinh nghiệm, từ các bệnh viện top đầu.', 'uniasia' ),
			'icon'  => 'doctor',
		),
		array(
			'title' => __( 'Chẩn đoán chính xác', 'uniasia' ),
			'desc'  => __( 'Kết hợp công nghệ xâm lấn tối thiểu với xét nghiệm phân tử, xây dựng lộ trình điều trị dựa trên bằng chứng.', 'uniasia' ),
			'icon'  => 'diagnosis',
		),
		array(
			'title' => __( 'Điều trị cá nhân hóa', 'uniasia' ),
			'desc'  => __( 'Phác đồ cá nhân hóa toàn diện, phối hợp can thiệp xâm lấn tối thiểu với hóa trị, xạ trị, miễn dịch.', 'uniasia' ),
			'icon'  => 'personalized',
		),
		array(
			'title' => __( 'Cơ sở đào tạo WATA đầu tiên', 'uniasia' ),
			'desc'  => __( 'Cơ sở đào tạo đốt u chính xác theo tiêu chuẩn Hiệp hội Điều trị Tiêu hủy u Thế giới.', 'uniasia' ),
			'icon'  => 'globe',
		),
	);
}

/**
 * Pagination
 */
function uniasia_pagination( $query = null ) {
	if ( ! $query ) {
		global $wp_query;
		$query = $wp_query;
	}

	$big   = 999999999;
	$links = paginate_links( array(
		'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format'    => '?paged=%#%',
		'current'   => max( 1, get_query_var( 'paged' ) ),
		'total'     => $query->max_num_pages,
		'prev_text' => '&laquo;',
		'next_text' => '&raquo;',
		'type'      => 'list',
	) );

	if ( $links ) {
		echo '<div class="uniasia-pagination">' . $links . '</div>';
	}
}

/**
 * Get current language code
 */
function uniasia_current_lang() {
	if ( function_exists( 'icl_get_current_language' ) ) {
		return icl_get_current_language();
	}
	return get_locale();
}

/**
 * Translate string helper
 */
function uniasia_t( $text ) {
	return __( $text, 'uniasia' );
}