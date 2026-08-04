<?php
/**
 * Form Handler - Xử lý form tư vấn miễn phí
 *
 * @package UNI_ASIA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AJAX handler cho form liên hệ
 */
add_action( 'wp_ajax_uniasia_submit_consultation', 'uniasia_handle_consultation' );
add_action( 'wp_ajax_nopriv_uniasia_submit_consultation', 'uniasia_handle_consultation' );
function uniasia_handle_consultation() {
	check_ajax_referer( 'uniasia_nonce', 'nonce' );

	$name  = sanitize_text_field( $_POST['name'] ?? '' );
	$age   = sanitize_text_field( $_POST['age'] ?? '' );
	$phone = sanitize_text_field( $_POST['phone'] ?? '' );
	$email = sanitize_email( $_POST['email'] ?? '' );
	$msg   = sanitize_textarea_field( $_POST['message'] ?? '' );

	if ( empty( $name ) || empty( $phone ) ) {
		wp_send_json_error( array( 'message' => __( 'Vui lòng nhập đầy đủ họ tên và số điện thoại.', 'uniasia' ) ) );
	}

	if ( ! is_email( $email ) && ! empty( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'Email không hợp lệ.', 'uniasia' ) ) );
	}

	$post_id = wp_insert_post( array(
		'post_title'   => sprintf( '%s - %s - %s', $name, $phone, current_time( 'mysql' ) ),
		'post_content' => $msg,
		'post_type'    => 'consultation',
		'post_status'  => 'private',
	) );

	if ( $post_id ) {
		update_post_meta( $post_id, '_caller_name', $name );
		update_post_meta( $post_id, '_caller_age', $age );
		update_post_meta( $post_id, '_caller_phone', $phone );
		update_post_meta( $post_id, '_caller_email', $email );
		update_post_meta( $post_id, '_caller_message', $msg );
	}

	$to      = get_field( 'contact_email', 'option' ) ?: get_option( 'admin_email' );
	$subject = sprintf( '[%s] Yêu cầu tư vấn mới từ %s', get_bloginfo( 'name' ), $name );

	$body  = sprintf( "Họ tên: %s\n", $name );
	$body .= sprintf( "Tuổi: %s\n", $age );
	$body .= sprintf( "Số điện thoại: %s\n", $phone );
	$body .= sprintf( "Email: %s\n", $email );
	$body .= sprintf( "Câu hỏi:\n%s\n", $msg );

	wp_mail( $to, $subject, $body );

	wp_send_json_success( array(
		'message' => __( 'Chúng tôi đã nhận được yêu cầu tư vấn. Chuyên viên sẽ liên hệ trong 24 giờ.', 'uniasia' ),
	) );
}

/**
 * Đăng ký CPT Consultation (lưu trữ yêu cầu tư vấn)
 */
add_action( 'init', 'uniasia_register_consultation_cpt' );
function uniasia_register_consultation_cpt() {
	register_post_type( 'consultation', array(
		'labels' => array(
			'name'          => __( 'Yêu cầu tư vấn', 'uniasia' ),
			'singular_name' => __( 'Yêu cầu tư vấn', 'uniasia' ),
			'menu_name'     => __( 'Yêu cầu tư vấn', 'uniasia' ),
			'all_items'     => __( 'Tất cả yêu cầu', 'uniasia' ),
		),
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => false,
		'exclude_from_search' => true,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-email-alt',
		'capability_type'     => 'post',
		'has_archive'         => false,
		'hierarchical'        => false,
		'supports'            => array( 'title', 'editor', 'custom-fields' ),
	) );
}

/**
 * Thêm cột thông tin vào admin
 */
add_filter( 'manage_consultation_posts_columns', 'uniasia_consultation_columns' );
function uniasia_consultation_columns( $columns ) {
	$new_columns = array();
	foreach ( $columns as $key => $value ) {
		$new_columns[ $key ] = $value;
		if ( 'title' === $key ) {
			$new_columns['caller_phone'] = __( 'Số điện thoại', 'uniasia' );
			$new_columns['caller_email'] = __( 'Email', 'uniasia' );
		}
	}
	return $new_columns;
}

add_action( 'manage_consultation_posts_custom_column', 'uniasia_consultation_column_data', 10, 2 );
function uniasia_consultation_column_data( $column, $post_id ) {
	if ( 'caller_phone' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_caller_phone', true ) );
	} elseif ( 'caller_email' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_caller_email', true ) );
	}
}