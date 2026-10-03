<?php
/**
 * Plugin Name: UNI-ASIA ACF Blocks
 * Description: Four reusable ACF blocks for the UNI-ASIA website.
 * Version: 1.0.0
 * Author: UNI-ASIA
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'UC_BLOCKS_DIR', plugin_dir_path( __FILE__ ) );
define( 'UC_BLOCKS_URL', plugin_dir_url( __FILE__ ) );

function uc_blocks_register_assets() {
	wp_register_style( 'unicancer-acf-blocks', UC_BLOCKS_URL . 'assets/blocks.css', array(), '1.0.1' );
	wp_add_inline_style( 'unicancer-acf-blocks', '.ucb-section--treatments .ucb-card--treatment{max-height:430px}.ucb-section--treatments .ucb-card--treatment>a>img{height:204px;object-fit:cover}.ucb-section--treatments .ucb-card--treatment .ucb-card__body{min-height:0;padding:18px 20px 22px;overflow:hidden}.ucb-section--treatments .ucb-card--treatment .ucb-card__body h3{line-height:1.28!important;display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden;min-height:64px}.ucb-section--treatments .ucb-card--treatment .ucb-card__body p{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:3;overflow:hidden;margin-top:8px!important}' );
	wp_add_inline_style( 'unicancer-acf-blocks', '.ucb-section--stories .ucb-card>a>img{height:200px;object-fit:cover}.ucb-section--stories .ucb-card__tag{margin:10px 12px 0}.ucb-section--stories .ucb-card__body{padding:14px 20px 20px}.ucb-story-name{display:flex;align-items:center;gap:14px}.ucb-section .ucb-story-name h3{font-size:20px!important;margin:0!important;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.ucb-story-name span{flex:none;background:#1785d1;color:#fff;border-radius:16px;padding:3px 10px;font-size:13px;font-weight:600}.ucb-section--stories .ucb-card__body p{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:3;overflow:hidden}' );
	wp_register_script( 'unicancer-block-editor', UC_BLOCKS_URL . 'assets/editor.js', array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor', 'wp-server-side-render', 'wp-i18n' ), '1.0.0', true );
	wp_register_script( 'unicancer-block-frontend', UC_BLOCKS_URL . 'assets/frontend.js', array(), '1.0.0', true );
}
add_action( 'init', 'uc_blocks_register_assets', 5 );

function uc_blocks_register() {
	$blocks = array(
		'patient-stories' => array( 'Câu chuyện bệnh nhân', 'format-quote', 'patient-stories.php', 'patient_story' ),
		'consultation-form' => array( 'Form tư vấn miễn phí', 'feedback', 'consultation-form.php', '' ),
		'treatments' => array( 'Kỹ thuật điều trị', 'shield-alt', 'treatments.php', 'treatment' ),
		'mdt-doctors' => array( 'Đội ngũ MDT', 'groups', 'mdt-doctors.php', 'doctor' ),
	);
	foreach ( $blocks as $name => $block ) {
		register_block_type( 'unicancer/' . $name, array(
			'api_version' => 2, 'title' => $block[0], 'description' => 'Block UNI-ASIA: ' . $block[0], 'category' => 'unicancer', 'icon' => $block[1],
			'editor_script' => 'unicancer-block-editor', 'style' => 'unicancer-acf-blocks', 'editor_style' => 'unicancer-acf-blocks',
			'attributes' => array(
				'title' => array( 'type' => 'string', 'default' => uc_blocks_default( $name, 'title' ) ),
				'description' => array( 'type' => 'string', 'default' => uc_blocks_default( $name, 'description' ) ),
				'linkText' => array( 'type' => 'string', 'default' => uc_blocks_default( $name, 'link_text' ) ),
				'linkUrl' => array( 'type' => 'string', 'default' => uc_blocks_default( $name, 'link_url' ) ),
				'itemsCount' => array( 'type' => 'number', 'default' => 4 ),
				'buttonText' => array( 'type' => 'string', 'default' => 'Đặt lịch với chuyên gia ngay' ),
			),
			'render_callback' => function( $attributes ) use ( $block ) { return uc_blocks_render( $attributes, $block[2] ); },
		) );
	}
}
add_action( 'init', 'uc_blocks_register', 20 );

function uc_blocks_frontend_assets() {
	if ( has_block( 'unicancer/consultation-form' ) ) {
		wp_enqueue_script( 'unicancer-block-frontend' );
		wp_localize_script( 'unicancer-block-frontend', 'unicancerBlockForm', array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'unicancer_consultation' ) ) );
	}
}
add_action( 'wp_enqueue_scripts', 'uc_blocks_frontend_assets' );

function uc_blocks_default( $block, $field ) {
	$defaults = array(
		'patient-stories' => array( 'title' => 'Câu Chuyện bệnh nhân', 'description' => 'Tại Bệnh viện Uni-Asia Thành Đô, mỗi hành trình tìm kiếm cơ hội điều trị đều khắc ghi lòng dũng cảm và hy vọng.', 'link_text' => 'Thêm ca bệnh', 'link_url' => '/patient-stories/' ),
		'treatments' => array( 'title' => 'Kỹ thuật điều trị', 'description' => 'Cung cấp các giải pháp điều trị chính xác, có mục tiêu và đạt chất lượng quốc tế cho bệnh nhân trên toàn cầu.', 'link_text' => 'Công nghệ điều trị', 'link_url' => '/phuong-phap-dieu-tri/' ),
		'mdt-doctors' => array( 'title' => 'Đội ngũ MDT', 'description' => 'Quy tụ các chuyên gia giàu kinh nghiệm hàng đầu trong lĩnh vực can thiệp xâm lấn tối thiểu và xạ trị ung thư.', 'link_text' => 'Thêm bác sĩ', 'link_url' => '/doctors/' ),
		'consultation-form' => array( 'title' => 'Tư Vấn Miễn Phí', 'description' => '', 'link_text' => '', 'link_url' => '' ),
	);
	$value = $defaults[ $block ][ $field ] ?? '';
	return 'link_url' === $field && $value ? home_url( $value ) : $value;
}

function uc_blocks_render( $attributes, $template ) {
	global $uc_current_block_attributes;
	$uc_current_block_attributes = $attributes;
	ob_start(); include UC_BLOCKS_DIR . 'templates/' . $template; $html = ob_get_clean();
	$uc_current_block_attributes = null;
	return $html;
}

function uc_blocks_category( $categories ) {
	array_unshift( $categories, array( 'slug' => 'unicancer', 'title' => 'UNI-ASIA', 'icon' => 'heart' ) );
	return $categories;
}
add_filter( 'block_categories_all', 'uc_blocks_category' );

function uc_blocks_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) { return; }
	$common = function( $prefix, $block, $defaults, $post_type = '' ) {
		$fields = array(
			array( 'key' => "field_{$prefix}_title", 'label' => 'Tiêu đề', 'name' => 'title', 'type' => 'text', 'default_value' => $defaults[0] ),
			array( 'key' => "field_{$prefix}_description", 'label' => 'Mô tả', 'name' => 'description', 'type' => 'textarea', 'rows' => 3, 'default_value' => $defaults[1] ),
			array( 'key' => "field_{$prefix}_link_text", 'label' => 'Chữ liên kết', 'name' => 'link_text', 'type' => 'text', 'default_value' => $defaults[2] ),
			array( 'key' => "field_{$prefix}_link_url", 'label' => 'Liên kết', 'name' => 'link_url', 'type' => 'url', 'default_value' => $defaults[3] ),
			array( 'key' => "field_{$prefix}_count", 'label' => 'Số mục hiển thị', 'name' => 'items_count', 'type' => 'number', 'default_value' => 4, 'min' => 1, 'max' => 8 ),
		);
		if ( $post_type ) {
			$fields[] = array( 'key' => "field_{$prefix}_items", 'label' => 'Chọn nội dung (để trống sẽ lấy mới nhất)', 'name' => 'selected_items', 'type' => 'relationship', 'post_type' => array( $post_type ), 'filters' => array( 'search' ), 'return_format' => 'id' );
		}
		acf_add_local_field_group( array( 'key' => "group_{$prefix}", 'title' => $defaults[0], 'fields' => $fields, 'location' => array( array( array( 'param' => 'block', 'operator' => '==', 'value' => 'acf/unicancer-' . $block ) ) ) ) );
	};
	$common( 'uc_story', 'patient-stories', array( 'Câu Chuyện bệnh nhân', 'Tại Bệnh viện Uni-Asia Thành Đô, mỗi hành trình tìm kiếm cơ hội điều trị đều khắc ghi lòng dũng cảm và hy vọng.', 'Thêm ca bệnh', home_url( '/cau-chuyen-benh-nhan/' ) ), 'patient_story' );
	$common( 'uc_treatment', 'treatments', array( 'Kỹ thuật điều trị', 'Cung cấp các giải pháp điều trị chính xác, có mục tiêu và đạt chất lượng quốc tế cho bệnh nhân trên toàn cầu.', 'Công nghệ điều trị', home_url( '/phuong-phap-dieu-tri/' ) ), 'treatment' );
	$common( 'uc_doctor', 'mdt-doctors', array( 'Đội ngũ MDT', 'Quy tụ các chuyên gia giàu kinh nghiệm hàng đầu trong lĩnh vực can thiệp xâm lấn tối thiểu và xạ trị ung thư.', 'Thêm bác sĩ', home_url( '/doctors/' ) ), 'doctor' );
	acf_add_local_field_group( array(
		'key' => 'group_uc_form', 'title' => 'Form tư vấn miễn phí',
		'fields' => array(
			array( 'key' => 'field_uc_form_title', 'label' => 'Tiêu đề', 'name' => 'title', 'type' => 'text', 'default_value' => 'Tư Vấn Miễn Phí' ),
			array( 'key' => 'field_uc_form_button', 'label' => 'Nhãn nút gửi', 'name' => 'button_text', 'type' => 'text', 'default_value' => 'Đặt lịch với chuyên gia ngay' ),
			array( 'key' => 'field_uc_form_success', 'label' => 'Thông báo thành công', 'name' => 'success_message', 'type' => 'textarea', 'default_value' => 'Chúng tôi đã nhận được yêu cầu tư vấn của bạn. Chuyên viên sẽ liên hệ trong thời gian sớm nhất.' ),
		),
		'location' => array( array( array( 'param' => 'block', 'operator' => '==', 'value' => 'acf/unicancer-consultation-form' ) ) ),
	) );

	// Editable card presentation for content used by the dynamic blocks.
	acf_add_local_field_group( array(
		'key' => 'group_uc_card_content',
		'title' => 'Thông tin hiển thị trên block UNI-ASIA',
		'fields' => array(
			array( 'key' => 'field_uc_card_image', 'label' => 'Ảnh thẻ', 'name' => 'uc_card_image', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all' ),
			array( 'key' => 'field_uc_card_label', 'label' => 'Nhãn màu xanh', 'name' => 'uc_card_label', 'type' => 'text', 'instructions' => 'Ví dụ: Ung thư gan, Chuyên gia MDT. Để trống sẽ dùng nhãn mặc định.' ),
			array( 'key' => 'field_uc_card_description', 'label' => 'Mô tả ngắn trên thẻ', 'name' => 'uc_card_description', 'type' => 'textarea', 'rows' => 4, 'instructions' => 'Để trống sẽ dùng phần Tóm tắt hoặc nội dung bài.' ),
			array( 'key' => 'field_uc_card_subtitle', 'label' => 'Chức danh / dòng phụ', 'name' => 'uc_card_subtitle', 'type' => 'text', 'instructions' => 'Dùng cho thẻ bác sĩ; có thể để trống.' ),
		),
		'location' => array(
			array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'patient_story' ) ),
			array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'doctor' ) ),
			array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'treatment' ) ),
		),
		'position' => 'side',
		'style' => 'default',
	) );
	acf_add_local_field_group( array(
		'key' => 'group_uc_patient_profile',
		'title' => 'Thông tin bệnh nhân đầu bài',
		'fields' => array(
			array( 'key' => 'field_uc_patient_name', 'label' => 'Tên bệnh nhân', 'name' => 'uc_patient_name', 'type' => 'text' ),
			array( 'key' => 'field_uc_patient_nationality', 'label' => 'Quốc tịch', 'name' => 'uc_patient_nationality', 'type' => 'text' ),
			array( 'key' => 'field_uc_patient_diagnosis', 'label' => 'Chẩn đoán', 'name' => 'uc_patient_diagnosis', 'type' => 'textarea', 'rows' => 2 ),
			array( 'key' => 'field_uc_patient_treatment', 'label' => 'Phác đồ điều trị', 'name' => 'uc_patient_treatment', 'type' => 'textarea', 'rows' => 2 ),
			array( 'key' => 'field_uc_patient_zalo', 'label' => 'Zalo liên hệ', 'name' => 'uc_patient_zalo', 'type' => 'url', 'instructions' => 'Để trống sẽ dùng Zalo chung của website.' ),
			array( 'key' => 'field_uc_patient_whatsapp', 'label' => 'WhatsApp liên hệ', 'name' => 'uc_patient_whatsapp', 'type' => 'url', 'instructions' => 'Để trống sẽ dùng WhatsApp chung của website.' ),
			array( 'key' => 'field_uc_patient_video', 'label' => 'Link video', 'name' => 'uc_patient_video', 'type' => 'url', 'instructions' => 'Hỗ trợ link YouTube. Nếu để trống sẽ hiển thị Featured Image.' ),
		),
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'patient_story' ) ) ),
		'position' => 'normal',
		'style' => 'default',
	) );
}
add_action( 'acf/init', 'uc_blocks_fields' );

function uc_block_field( $name, $default = '' ) {
	global $uc_current_block_attributes;
	$map = array( 'title' => 'title', 'description' => 'description', 'link_text' => 'linkText', 'link_url' => 'linkUrl', 'items_count' => 'itemsCount', 'button_text' => 'buttonText' );
	if ( $uc_current_block_attributes && isset( $map[ $name ], $uc_current_block_attributes[ $map[ $name ] ] ) ) {
		$value = $uc_current_block_attributes[ $map[ $name ] ];
		return 'link_url' === $name && function_exists( 'unicancer_localize_internal_url' ) ? unicancer_localize_internal_url( $value, 'vi' ) : $value;
	}
	$value = function_exists( 'get_field' ) ? get_field( $name ) : null;
	$value = ( null === $value || '' === $value ) ? $default : $value;
	return 'link_url' === $name && function_exists( 'unicancer_localize_internal_url' ) ? unicancer_localize_internal_url( $value, 'vi' ) : $value;
}

/** Resolve cards after title-based Vietnamese slugs replaced legacy import slugs. */
function uc_block_post_by_legacy_slug( $slug, $post_type ) {
	$post = get_page_by_path( $slug, OBJECT, $post_type );
	if ( $post ) { return $post; }
	$matches = get_posts( array(
		'post_type' => $post_type,
		'post_status' => 'publish',
		'posts_per_page' => 1,
		'meta_key' => '_unicancer_pre_vi_slug',
		'meta_value' => $slug,
		'suppress_filters' => true,
	) );
	return $matches ? $matches[0] : null;
}

function uc_block_posts( $post_type, $count = 4 ) {
	$selected = uc_block_field( 'selected_items', array() );
	$args = array( 'post_type' => $post_type, 'post_status' => 'publish', 'posts_per_page' => max( 1, min( 8, (int) $count ) ) );
	if ( $selected ) { $args['post__in'] = array_map( 'intval', $selected ); $args['orderby'] = 'post__in'; }
	return get_posts( $args );
}

function uc_block_image( $post_id ) {
	$acf_image = function_exists( 'get_field' ) ? get_field( 'uc_card_image', $post_id ) : 0;
	if ( $acf_image ) {
		$url = wp_get_attachment_image_url( (int) $acf_image, 'large' );
		if ( $url ) { return $url; }
	}
	if ( has_post_thumbnail( $post_id ) ) { return get_the_post_thumbnail_url( $post_id, 'large' ); }
	$content = get_post_field( 'post_content', $post_id );
	if ( preg_match( '/<img[^>]+src=["\']([^"\']+)/i', $content, $match ) ) { return $match[1]; }
	return UC_BLOCKS_URL . 'assets/placeholder.svg';
}

function uc_block_card_value( $post_id, $field, $default = '' ) {
	$value = function_exists( 'get_field' ) ? get_field( $field, $post_id ) : '';
	return '' !== (string) $value ? $value : $default;
}

function uc_blocks_admin_notice() {
	if ( current_user_can( 'activate_plugins' ) && ! function_exists( 'acf_add_local_field_group' ) ) {
		echo '<div class="notice notice-warning"><p><strong>UNI-ASIA Blocks:</strong> Vui lòng kích hoạt Advanced Custom Fields.</p></div>';
	}
}
add_action( 'admin_notices', 'uc_blocks_admin_notice' );
