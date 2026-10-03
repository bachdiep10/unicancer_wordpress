<?php
/**
 * WordPress content model for UNI-ASIA.
 *
 * @package Unicancer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function unicancer_register_content_model() {
	$types = array(
		'doctor' => array( 'Bác sĩ', 'Bác sĩ', 'bac-si', 'dashicons-businessperson', true ),
		'cancer' => array( 'Loại ung thư', 'Ung thư', 'ung-thu', 'dashicons-heart', true ),
		'treatment' => array( 'Phương pháp điều trị', 'Điều trị', 'phuong-phap-dieu-tri', 'dashicons-shield-alt', true ),
		'patient_story' => array( 'Câu chuyện bệnh nhân', 'Câu chuyện bệnh nhân', 'cau-chuyen-benh-nhan', 'dashicons-format-quote', true ),
		'special_topic' => array( 'Chuyên đề', 'Chuyên đề ung thư', 'chu-de-ung-thu', 'dashicons-media-document', true ),
		'home_slide' => array( 'Slide', 'Slide trang chủ', 'home-slide', 'dashicons-images-alt2', false ),
		'consultation' => array( 'Yêu cầu tư vấn', 'Yêu cầu tư vấn', 'consultation', 'dashicons-phone', false ),
	);

	foreach ( $types as $type => $data ) {
		register_post_type(
			$type,
			array(
				'labels' => array(
					'name'          => $data[1],
					'singular_name' => $data[0],
					'add_new_item'  => 'Thêm ' . mb_strtolower( $data[0] ),
					'edit_item'     => 'Sửa ' . mb_strtolower( $data[0] ),
				),
				'public'       => $data[4],
				'show_ui'      => true,
				'show_in_rest' => true,
				// Dedicated editable WordPress pages own the Vietnamese listing URLs.
				// Keeping a CPT archive on the same slug makes those pages return 404.
				'has_archive'  => false,
				'rewrite'      => $data[4] ? array( 'slug' => $data[2], 'with_front' => false ) : false,
				'hierarchical' => in_array( $type, array( 'cancer', 'special_topic' ), true ),
				'menu_icon'    => $data[3],
				'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'custom-fields', 'revisions' ),
			)
		);
	}

	register_taxonomy(
		'medical_topic',
		array( 'doctor', 'cancer', 'treatment', 'patient_story', 'post' ),
		array(
			'labels'       => array( 'name' => 'Chủ đề y khoa', 'singular_name' => 'Chủ đề y khoa' ),
			'public'       => true,
			'hierarchical' => true,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'chu-de-y-khoa' ),
		)
	);
}
add_action( 'init', 'unicancer_register_content_model' );

function unicancer_register_settings() {
	register_setting( 'unicancer_theme_settings', 'unicancer_theme_settings', array( 'sanitize_callback' => 'unicancer_sanitize_settings' ) );
	add_settings_section( 'unicancer_contact', 'Thông tin dùng chung', '__return_false', 'unicancer-theme-settings' );
	$fields = array(
		'contact_form_url' => 'Liên kết Form tư vấn',
		'hotline'         => 'Số điện thoại',
		'email'           => 'Email',
		'zalo'            => 'Số Zalo hoặc liên kết Zalo',
		'whatsapp'        => 'Số WhatsApp hoặc liên kết WhatsApp',
		'whatsapp_message'=> 'Tin nhắn WhatsApp mặc định',
		'address'         => 'Địa chỉ',
		'facebook'        => 'Facebook',
		'copyright'       => 'Bản quyền footer',
	);
	foreach ( $fields as $key => $label ) {
		add_settings_field( $key, $label, 'unicancer_setting_field', 'unicancer-theme-settings', 'unicancer_contact', array( 'key' => $key ) );
	}
}
add_action( 'admin_init', 'unicancer_register_settings' );

function unicancer_sanitize_settings( $values ) {
	$clean = array();
	foreach ( (array) $values as $key => $value ) {
		$clean[ sanitize_key( $key ) ] = in_array( $key, array( 'email', 'hotline', 'zalo', 'whatsapp', 'whatsapp_message', 'address', 'copyright' ), true ) ? sanitize_text_field( $value ) : esc_url_raw( $value );
	}
	return $clean;
}

function unicancer_setting_field( $args ) {
	$settings = unicancer_get_contact_settings();
	$key = $args['key'];
	$type = 'email' === $key ? 'email' : ( in_array( $key, array( 'contact_form_url', 'facebook' ), true ) ? 'url' : 'text' );
	printf( '<input type="%3$s" class="regular-text" name="unicancer_theme_settings[%1$s]" value="%2$s">', esc_attr( $key ), esc_attr( $settings[ $key ] ?? '' ), esc_attr( $type ) );
	if ( in_array( $key, array( 'hotline', 'zalo', 'whatsapp' ), true ) ) {
		echo '<p class="description">Có thể nhập số quốc tế, ví dụ: +84388925161.</p>';
	}
}

function unicancer_get_contact_settings() {
	$defaults = array(
		'contact_form_url' => home_url( '/contact-us/#consultation-form' ),
		'hotline'          => '+84388925161',
		'email'            => 'service@uniasiacancer.com',
		'zalo'             => '84388925161',
		'whatsapp'         => '+84388925161',
		'whatsapp_message' => 'hello',
		'address'          => '',
		'facebook'         => '',
		'copyright'        => '',
	);
	return wp_parse_args( get_option( 'unicancer_theme_settings', array() ), $defaults );
}

function unicancer_contact_url( $type ) {
	$settings = unicancer_get_contact_settings();
	$phone = preg_replace( '/[^0-9+]/', '', $settings['hotline'] );
	if ( 'form' === $type ) {
		return $settings['contact_form_url'];
	}
	if ( 'phone' === $type ) {
		return 'tel:' . $phone;
	}
	if ( 'email' === $type ) {
		return 'mailto:' . sanitize_email( $settings['email'] );
	}
	if ( 'zalo' === $type ) {
		return preg_match( '#^https?://#i', $settings['zalo'] ) ? $settings['zalo'] : 'https://zalo.me/' . preg_replace( '/\D/', '', $settings['zalo'] );
	}
	if ( 'whatsapp' === $type ) {
		if ( preg_match( '#^https?://#i', $settings['whatsapp'] ) ) {
			return $settings['whatsapp'];
		}
		return add_query_arg( array( 'phone' => preg_replace( '/[^0-9+]/', '', $settings['whatsapp'] ), 'text' => $settings['whatsapp_message'] ), 'https://api.whatsapp.com/send' );
	}
	return '';
}

function unicancer_settings_page() {
	add_theme_page( 'Thiết lập UNI-ASIA', 'Thiết lập UNI-ASIA', 'manage_options', 'unicancer-theme-settings', 'unicancer_render_settings_page' );
}
add_action( 'admin_menu', 'unicancer_settings_page' );

function unicancer_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'unicancer_header',
		array( 'title' => 'Header UNI-ASIA', 'priority' => 30, 'description' => 'Thay logo hiển thị trên đầu website.' )
	);
	$logos = array(
		'unicancer_header_logo'  => array( 'Logo chính (desktop)', '/mirror/huan-ya.oss-ap-southeast-1.aliyuncs.com/frontend/logo.png' ),
		'unicancer_mobile_logo'  => array( 'Logo mobile', '/mirror/huan-ya.oss-ap-southeast-1.aliyuncs.com/frontend/layout/logo-short-b.png' ),
		'unicancer_partner_logo' => array( 'Logo WATA', '/mirror/huan-ya.oss-ap-southeast-1.aliyuncs.com/frontend/layout/wata-g.png' ),
	);
	foreach ( $logos as $setting => $data ) {
		$wp_customize->add_setting( $setting, array( 'default' => get_template_directory_uri() . $data[1], 'sanitize_callback' => 'esc_url_raw', 'transport' => 'refresh' ) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $setting, array( 'label' => $data[0], 'section' => 'unicancer_header', 'settings' => $setting ) ) );
	}
	$wp_customize->add_setting( 'unicancer_show_partner_logo', array( 'default' => true, 'sanitize_callback' => 'wp_validate_boolean', 'transport' => 'refresh' ) );
	$wp_customize->add_control( 'unicancer_show_partner_logo', array( 'type' => 'checkbox', 'label' => 'Hiển thị logo WATA', 'section' => 'unicancer_header' ) );

	$wp_customize->add_section( 'unicancer_footer', array( 'title' => 'Footer UNI-ASIA', 'priority' => 31, 'description' => 'Chỉnh thông tin, logo, QR và mạng xã hội ở chân trang.' ) );
	$wp_customize->add_section( 'unicancer_article_sidebar', array( 'title' => 'Sidebar bài viết', 'priority' => 32, 'description' => 'Sidebar dùng chung cho các bài chi tiết.' ) );
	$sidebar_settings = array(
		'unicancer_sidebar_enabled' => array( 'Hiển thị sidebar', true, 'checkbox', 'wp_validate_boolean' ),
		'unicancer_sidebar_mdt_title' => array( 'Tiêu đề MDT', 'Đội ngũ MDT', 'text', 'sanitize_text_field' ),
		'unicancer_sidebar_cancer_title' => array( 'Tiêu đề menu ung thư', 'Các loại ung thư', 'text', 'sanitize_text_field' ),
		'unicancer_sidebar_treatment_title' => array( 'Tiêu đề menu điều trị', 'Phương pháp điều trị', 'text', 'sanitize_text_field' ),
		'unicancer_sidebar_menu_count' => array( 'Số mục mỗi menu', 8, 'number', 'absint' ),
		'unicancer_sidebar_speed' => array( 'Tốc độ slider (mili giây)', 3500, 'number', 'absint' ),
	);
	foreach ( $sidebar_settings as $setting => $data ) { $wp_customize->add_setting( $setting, array( 'default' => $data[1], 'sanitize_callback' => $data[3], 'transport' => 'refresh' ) ); $wp_customize->add_control( $setting, array( 'label' => $data[0], 'section' => 'unicancer_article_sidebar', 'type' => $data[2], 'input_attrs' => 'number' === $data[2] ? array( 'min' => 1, 'max' => 15000 ) : array() ) ); }
	$footer_images = array(
		'unicancer_footer_logo' => array( 'Logo footer', '/mirror/huan-ya.oss-ap-southeast-1.aliyuncs.com/frontend/logo-white.png' ),
		'unicancer_footer_partner_logo' => array( 'Logo WATA footer', '/mirror/huan-ya.oss-ap-southeast-1.aliyuncs.com/frontend/layout/wata-white.png' ),
		'unicancer_footer_whatsapp_qr' => array( 'QR WhatsApp', '/mirror/huan-ya.oss-ap-southeast-1.aliyuncs.com/frontend/footer/wa-vi.jpg' ),
		'unicancer_footer_zalo_qr' => array( 'QR Zalo', '/mirror/huan-ya.oss-ap-southeast-1.aliyuncs.com/frontend/footer/zalo.jpg' ),
	);
	foreach ( $footer_images as $setting => $data ) {
		$wp_customize->add_setting( $setting, array( 'default' => get_template_directory_uri() . $data[1], 'sanitize_callback' => 'esc_url_raw', 'transport' => 'refresh' ) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $setting, array( 'label' => $data[0], 'section' => 'unicancer_footer' ) ) );
	}
	$footer_text = array(
		'unicancer_footer_office_title' => array( 'Tên văn phòng', 'Trung tâm Dịch vụ Hà Nội, Việt Nam', 'text' ),
		'unicancer_footer_address' => array( 'Địa chỉ', 'Tòa nhà Tràng An Complex, số 1 Phùng Chí Kiên, phường Nghĩa Đô, Hà Nội (Tòa nhà Trung tâm Dịch vụ thị thực Trung Quốc)', 'textarea' ),
		'unicancer_footer_phone' => array( 'Số điện thoại hiển thị', '+84 388925161', 'text' ),
		'unicancer_footer_email' => array( 'Email hiển thị', 'service@uniasiacancer.com', 'email' ),
		'unicancer_footer_about_title' => array( 'Tiêu đề cột giới thiệu', 'Về chúng tôi', 'text' ),
		'unicancer_footer_cancer_title' => array( 'Tiêu đề cột ung thư', 'Loại ung thư', 'text' ),
		'unicancer_footer_treatment_title' => array( 'Tiêu đề cột điều trị', 'Phương pháp điều trị', 'text' ),
		'unicancer_footer_consult_title' => array( 'Tiêu đề cột tư vấn', 'Tư vấn', 'text' ),
		'unicancer_footer_follow_title' => array( 'Tiêu đề mạng xã hội', 'Theo dõi chúng tôi', 'text' ),
		'unicancer_footer_copyright' => array( 'Bản quyền', '© 2026 UNI-ASIA Cancer Hospital. All rights reserved', 'text' ),
		'unicancer_footer_disclaimer' => array( 'Nội dung miễn trừ trách nhiệm', '*Tuyên bố: Hiệu quả điều trị có thể khác nhau tùy theo từng người. Thông tin trên website chỉ mang tính chất tham khảo, không thay thế chẩn đoán và điều trị của bác sĩ.', 'textarea' ),
		'unicancer_footer_facebook' => array( 'Facebook URL', 'https://www.facebook.com/profile.php?id=61590458637448', 'url' ),
		'unicancer_footer_tiktok' => array( 'TikTok URL', 'https://www.tiktok.com/@asiacancer', 'url' ),
		'unicancer_footer_instagram' => array( 'Instagram URL', 'https://www.instagram.com/rumahsakit596', 'url' ),
		'unicancer_footer_youtube' => array( 'YouTube URL', 'https://www.youtube.com/@Uniasiacancer', 'url' ),
	);
	foreach ( $footer_text as $setting => $data ) {
		$sanitize = 'url' === $data[2] ? 'esc_url_raw' : ( 'email' === $data[2] ? 'sanitize_email' : 'sanitize_textarea_field' );
		$wp_customize->add_setting( $setting, array( 'default' => $data[1], 'sanitize_callback' => $sanitize, 'transport' => 'refresh' ) );
		$wp_customize->add_control( $setting, array( 'label' => $data[0], 'section' => 'unicancer_footer', 'type' => $data[2] ) );
	}
}
add_action( 'customize_register', 'unicancer_customize_register' );

function unicancer_consultation_meta_boxes() {
	add_meta_box( 'unicancer_consultation_details', 'Thông tin khách hàng', 'unicancer_consultation_meta_box', 'consultation', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'unicancer_consultation_meta_boxes' );

function unicancer_consultation_meta_box( $post ) {
	wp_nonce_field( 'unicancer_consultation_admin', 'unicancer_consultation_admin_nonce' );
	$fields = array( 'age' => 'Tuổi', 'phone' => 'Điện thoại', 'email' => 'Email', 'source_url' => 'Trang gửi', 'submitted_at' => 'Thời gian gửi' );
	foreach ( $fields as $key => $label ) {
		printf( '<p><strong>%s:</strong> %s</p>', esc_html( $label ), esc_html( get_post_meta( $post->ID, '_unicancer_' . $key, true ) ) );
	}
	$status = get_post_meta( $post->ID, '_unicancer_status', true ) ?: 'new';
	echo '<p><label><strong>Trạng thái:</strong> <select name="unicancer_consultation_status">';
	foreach ( array( 'new' => 'Mới', 'contacted' => 'Đã liên hệ', 'completed' => 'Hoàn tất', 'spam' => 'Spam' ) as $value => $label ) {
		printf( '<option value="%s" %s>%s</option>', esc_attr( $value ), selected( $status, $value, false ), esc_html( $label ) );
	}
	echo '</select></label></p><p><strong>Câu hỏi:</strong></p><div style="padding:12px;background:#f6f7f7">' . nl2br( esc_html( $post->post_content ) ) . '</div>';
}

function unicancer_save_consultation_status( $post_id ) {
	if ( ! isset( $_POST['unicancer_consultation_admin_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['unicancer_consultation_admin_nonce'] ) ), 'unicancer_consultation_admin' ) ) { return; }
	if ( isset( $_POST['unicancer_consultation_status'] ) ) { update_post_meta( $post_id, '_unicancer_status', sanitize_key( $_POST['unicancer_consultation_status'] ) ); }
}
add_action( 'save_post_consultation', 'unicancer_save_consultation_status' );

function unicancer_consultation_columns( $columns ) {
	return array( 'cb' => $columns['cb'], 'title' => 'Khách hàng', 'phone' => 'Điện thoại', 'email' => 'Email', 'status' => 'Trạng thái', 'date' => 'Ngày gửi' );
}
add_filter( 'manage_consultation_posts_columns', 'unicancer_consultation_columns' );

function unicancer_consultation_column( $column, $post_id ) {
	if ( in_array( $column, array( 'phone', 'email' ), true ) ) { echo esc_html( get_post_meta( $post_id, '_unicancer_' . $column, true ) ); }
	if ( 'status' === $column ) { $labels = array( 'new' => 'Mới', 'contacted' => 'Đã liên hệ', 'completed' => 'Hoàn tất', 'spam' => 'Spam' ); $status = get_post_meta( $post_id, '_unicancer_status', true ) ?: 'new'; echo esc_html( $labels[ $status ] ?? $status ); }
}
add_action( 'manage_consultation_posts_custom_column', 'unicancer_consultation_column', 10, 2 );

function unicancer_submit_consultation() {
	check_ajax_referer( 'unicancer_consultation', 'nonce' );
	if ( ! empty( $_POST['website'] ) ) { wp_send_json_success(); }
	$name = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$age = sanitize_text_field( wp_unslash( $_POST['age'] ?? '' ) );
	$phone = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$question = sanitize_textarea_field( wp_unslash( $_POST['question'] ?? '' ) );
	if ( ! $name || ! $age || ! preg_match( '/[0-9]{6,20}/', preg_replace( '/\D/', '', $phone ) ) || empty( $_POST['agreementAccepted'] ) ) {
		wp_send_json_error( array( 'message' => 'Vui lòng kiểm tra lại họ tên, tuổi, số điện thoại và xác nhận đồng ý.' ), 422 );
	}
	$id = wp_insert_post( array( 'post_type' => 'consultation', 'post_status' => 'publish', 'post_title' => $name . ' – ' . $phone, 'post_content' => $question ), true );
	if ( is_wp_error( $id ) ) { wp_send_json_error( array( 'message' => 'Không thể lưu yêu cầu. Vui lòng thử lại.' ), 500 ); }
	$meta = array( 'age' => $age, 'phone' => $phone, 'email' => $email, 'source_url' => esc_url_raw( wp_unslash( $_POST['source_url'] ?? wp_get_referer() ) ), 'submitted_at' => current_time( 'mysql' ), 'status' => 'new' );
	foreach ( $meta as $key => $value ) { update_post_meta( $id, '_unicancer_' . $key, $value ); }
	wp_mail( get_option( 'admin_email' ), 'Yêu cầu tư vấn mới: ' . $name, "Điện thoại: {$phone}\nEmail: {$email}\nCâu hỏi: {$question}" );
	wp_send_json_success( array( 'message' => 'Chúng tôi đã nhận được yêu cầu tư vấn của bạn. Chuyên viên sẽ liên hệ trong thời gian sớm nhất.' ) );
}
add_action( 'wp_ajax_unicancer_submit_consultation', 'unicancer_submit_consultation' );
add_action( 'wp_ajax_nopriv_unicancer_submit_consultation', 'unicancer_submit_consultation' );

function unicancer_render_settings_page() {
	?>
	<div class="wrap"><h1>Thiết lập Header & Footer</h1><form method="post" action="options.php">
		<?php settings_fields( 'unicancer_theme_settings' ); do_settings_sections( 'unicancer-theme-settings' ); submit_button(); ?>
	</form></div>
	<?php
}
