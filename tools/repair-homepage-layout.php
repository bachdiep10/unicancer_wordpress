<?php
/**
 * Restore the three homepage sections whose link/card wrappers were stripped.
 *
 * Run with: wp eval-file tools/repair-homepage-layout.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 1 );
}

global $wpdb;

$front_id = (int) get_option( 'page_on_front' );
$front    = get_post( $front_id );
if ( ! $front ) {
	throw new RuntimeException( 'Front page not found.' );
}

$content = $front->post_content;
if ( ! metadata_exists( 'post', $front_id, '_unicancer_pre_home_layout_repair_20261003' ) ) {
	add_post_meta( $front_id, '_unicancer_pre_home_layout_repair_20261003', $content, true );
}

$replace_section = static function ( $html, $start, $end, $replacement ) {
	$from = strpos( $html, $start );
	if ( false === $from ) {
		throw new RuntimeException( 'Start marker not found: ' . $start );
	}
	$to = strpos( $html, $end, $from + strlen( $start ) );
	if ( false === $to ) {
		throw new RuntimeException( 'End marker not found: ' . $end );
	}
	return substr( $html, 0, $from ) . $replacement . substr( $html, $to );
};

$u = static function ( $path ) {
	return esc_url( home_url( $path ) );
};

$cancers = array(
	array( 'ung-thu-phoi', 'mpmdt7b2-70c79622.png', 'lung-cancer', 'Ung thư phổi' ),
	array( 'ung-thu-gan', 'mpmf0u7n-50f05703.png', 'liver-cancer', 'Ung thư gan' ),
	array( 'ung-thu-vu', 'mpmf2o7o-1066a5a0.png', 'breast-cancer', 'Ung thư vú' ),
	array( 'ung-thu-dai-truc-trang', 'mpmf4f80-baef9f4b.png', 'colorectal-cancer', 'Ung thư đại trực tràng' ),
	array( 'ung-thu-da-day', 'mpmf8p4f-17dd24bf.png', 'stomach-cancer', 'Ung thư dạ dày' ),
	array( 'ung-thu-co-tu-cung', 'mpnfcdao-46357914.png', 'cervical-cancer', 'Ung thư cổ tử cung' ),
	array( 'ung-thu-tuyen-tien-liet', 'mpmf7rsr-493023d6.png', 'prostate-cancer', 'Ung thư tuyến tiền liệt' ),
	array( 'ung-thu-thuc-quan', 'mpmf9pzq-e08b7f62.png', 'esophageal-cancer', 'Ung thư thực quản' ),
);

$cancer_cards = '';
foreach ( $cancers as $item ) {
	$cancer_cards .= '<a href="' . $u( '/ung-thu/' . $item[0] . '/' ) . '" class="flex pl-10 items-center h-17 bg-[#f5f5f5] rounded-lg hover:bg-[#C7E6FF] cursor-pointer">'
		. '<div class="flex justify-center items-center flex-none size-9 mr-6"><img loading="lazy" decoding="async" src="' . $u( '/wp-content/uploads/2026/09/' . $item[1] ) . '" width="36" height="36" alt="' . esc_attr( $item[2] ) . '"></div>'
		. '<div>' . esc_html( $item[3] ) . '</div></a>';
}

$cancer_section = '<section class="max-w-384 mx-auto px-4 py-6 lg:py-12">'
	. '<div class="text-2xl lg:text-4xl font-bold text-center">Phân loại Ung bướu</div>'
	. '<div class="text-md text-gray-550 text-center mt-4">Hầu hết các khối u ác tính ở giai đoạn sớm không có triệu chứng rõ ràng. Phát hiện sớm, chẩn đoán sớm và điều trị sớm là chiến lược then chốt để chiến thắng bệnh ung thư.</div>'
	. '<div class="mt-5 lg:mt-11 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 lg:gap-6 text-lg">' . $cancer_cards . '</div>'
	. '<a href="' . $u( '/ung-thu/' ) . '" class="group flex cursor-pointer justify-center mt-4 lg:mt-8 transition-all duration-300 ease-out hover:translate-x-0.5">'
	. '<div class="text-success mr-2 group-hover:text-primary">Xem thêm loại ung thư</div><div class="size-6 rounded-full bg-success flex justify-center items-center group-hover:bg-primary"><span class="size-5 icon-[lucide--arrow-right] text-white"></span></div></a></section>';

$treatments = get_posts(
	array(
		'post_type'      => 'treatment',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'ID',
		'order'          => 'ASC',
	)
);
$treatment_labels = array(
	array( 'Kỹ thuật truyền thuốc và nút mạch qua đường động mạch', 'động mạch' ),
	array( 'Kỹ thuật tiêu hủy khối u bằng dao nano', 'dao nano' ),
	array( 'Kỹ thuật cấy hạt phóng xạ I-125', 'cấy hạt' ),
	array( 'Kỹ thuật áp lạnh khối u bằng dao Argon-Helium', 'Argon-Helium' ),
	array( 'Kỹ thuật đốt u bằng sóng cao tần', 'sóng cao tần' ),
	array( 'Kỹ thuật đốt u bằng vi sóng', 'vi sóng' ),
	array( 'Kỹ thuật đặt stent', 'đặt stent' ),
);
$treatment_cards = '';
foreach ( $treatment_labels as $definition ) {
	$target = $u( '/phuong-phap-dieu-tri/' );
	foreach ( $treatments as $treatment ) {
		if ( false !== mb_stripos( $treatment->post_title, $definition[1] ) ) {
			$target = get_permalink( $treatment );
			break;
		}
	}
	$treatment_cards .= '<a href="' . esc_url( $target ) . '" class="min-h-20 shadow-md rounded-lg p-2 lg:p-4 flex justify-center items-center text-center hover:text-white hover:bg-success bg-white">' . esc_html( $definition[0] ) . '</a>';
}
$treatment_cards .= '<a href="' . $u( '/phuong-phap-dieu-tri/#treatment-list' ) . '" class="min-h-20 shadow-md rounded-lg p-2 lg:p-4 flex justify-center items-center text-center hover:text-white hover:bg-success text-white bg-success hover:underline">Xem thêm kỹ thuật</a>';

$treatment_section = '<section class="bg-[#FAFAFA]"><div class="max-w-384 mx-auto px-4 py-6 lg:py-16 flex flex-col lg:flex-row">'
	. '<div class="lg:w-1/2 lg:order-1 flex flex-col lg:pl-16 mb-5 lg:mb-0"><div class="h-13 border-l-8 border-warning pl-5 flex items-center lg:mt-6"><div class="text-2xl lg:text-4xl font-bold">Trung tâm Điều trị Ung bướu</div></div>'
	. '<div class="bg-white rounded-xl shadow-sm px-4 lg:px-8 py-2 lg:py-6 mt-4 lg:mt-8 leading-loose grow lg:text-justify"><div class="lg:indent-8 mt-1">Trung tâm Ung bướu của Bệnh viện Uni-Asia Thành Đô cam kết cung cấp cho bệnh nhân dịch vụ điều trị ung thư toàn diện, từ phòng ngừa, sàng lọc, chẩn đoán, điều trị đến hỗ trợ phục hồi chức năng. Trung tâm áp dụng mô hình chẩn đoán và điều trị đa chuyên khoa (MDT), kết hợp kỹ thuật điều trị ung thư xâm lấn tối thiểu tiên tiến với phác đồ điều trị cá thể hóa, nhằm cung cấp dịch vụ xuyên suốt toàn bộ quá trình điều trị cho từng bệnh nhân đến khám, để đáp ứng nhu cầu của bệnh nhân và những người có nguy cơ mắc ung thư.</div>'
	. '<div class="lg:indent-8 my-2 lg:my-6">Ung thư là một bệnh lý phức tạp, đòi hỏi phác đồ chăm sóc và điều trị mang tính cá thể hóa. Trung tâm Ung bướu của Bệnh viện Uni-Asia Thành Đô cam kết không ngừng nâng cao năng lực chăm sóc dự phòng ung thư cũng như chất lượng điều trị cho bệnh nhân ung thư, mang đến cho bệnh nhân trên toàn cầu các phác đồ điều trị chính xác, an toàn, phù hợp với từng tình trạng bệnh và đạt tiêu chuẩn quốc tế.</div>'
	. '<a href="' . $u( '/phuong-phap-dieu-tri/' ) . '" class="button text-sm">Xem chi tiết</a></div></div>'
	. '<div class="flex-1 flex flex-col"><img loading="lazy" decoding="async" class="w-full mb-4 rounded-3xl" src="' . $u( '/wp-content/uploads/2026/09/treatment1.jpg' ) . '" width="630" height="318" alt="treatment center">'
	. '<div class="grid grid-cols-2 xl:grid-cols-4 grow gap-4">' . $treatment_cards . '</div></div></div></section>';

$service_items = array(
	array( 's1.png', 'Tư vấn trực tuyến và đặt lịch khám', 'Thông qua các kênh như mục quốc tế trên website chính thức, đường dây nóng quốc tế và email tiếng Anh, bệnh nhân có thể gửi thông tin cơ bản và nhu cầu thăm khám, xác nhận thời gian khám và chuyên gia phụ trách, đồng thời nhận thư xác nhận đặt lịch.' ),
	array( 's2.png', 'Hỗ trợ trước chuyến đi', 'Có thể cung cấp thư mời xin thị thực y tế khi cần. Đội ngũ hỗ trợ kết nối phương tiện đi lại và chỗ ở, đồng thời hướng dẫn rõ các giấy tờ và hồ sơ bệnh án cần chuẩn bị trước chuyến đi.' ),
	array( 's3.png', 'Xác nhận và kiểm tra thông tin tại bệnh viện', 'Sau khi đến bệnh viện, bệnh nhân sẽ được chuyên viên quốc tế tiếp đón, xác thực danh tính và thông tin đặt lịch hẹn, lập hồ sơ bệnh án riêng, đồng thời được giải thích rõ về mức giá dịch vụ.' ),
	array( 's4.png', 'Tiến hành điều trị', 'Nhân viên hướng dẫn đa ngôn ngữ sẽ đồng hành cùng bệnh nhân trong suốt quá trình thăm khám, cung cấp dịch vụ phiên dịch y khoa chuyên nghiệp, ưu tiên sắp xếp các hạng mục kiểm tra liên quan, giải thích kết quả và xác định phác đồ chẩn đoán điều trị phù hợp. Theo phác đồ đã được xác định, người bệnh sẽ được thực hiện điều trị ngoại trú, nhập viện hoặc phẫu thuật, với sự đồng hành xuyên suốt của chuyên viên phụ trách và đội ngũ điều dưỡng, bảo đảm quá trình giao tiếp thông suốt, không rào cản.' ),
	array( 's5.png', 'Hoàn tất thanh toán, xuất viện và theo dõi tái khám', 'Hỗ trợ thực hiện thanh toán chi phí và chuẩn bị hồ sơ yêu cầu bồi thường bảo hiểm, hướng dẫn các lưu ý chăm sóc sau khi xuất viện, đồng thời cung cấp dịch vụ theo dõi từ xa và tái khám sau khi điều trị.' ),
);
$service_list = '';
foreach ( $service_items as $item ) {
	$service_list .= '<div class="flex"><img loading="lazy" decoding="async" class="size-15 flex-none" src="' . $u( '/wp-content/uploads/2026/09/' . $item[0] ) . '" alt="service icon">'
		. '<div class="border-r border-border ml-2 lg:ml-4 mr-3 lg:mr-6 relative"><div class="size-2.5 bg-warning rounded-full absolute top-4 -translate-x-1/2"></div></div>'
		. '<div class="mb-4"><div class="text-xl font-bold pt-2 pb-3">' . esc_html( $item[1] ) . '</div><div class="lg:text-justify">' . esc_html( $item[2] ) . '</div></div></div>';
}

$guide_section = '<section class="max-w-384 mx-auto px-4 border-t border-border py-6 lg:py-12"><div class="flex flex-wrap"><div class="flex"><div class="w-2 h-20 bg-warning flex-none"></div><div class="pl-4 lg:pl-6">'
	. '<div class="text-2xl lg:text-4xl mb-2 lg:mb-4 font-bold">Hướng dẫn cho bệnh nhân quốc tế</div><div class="text-gray-550 max-w-205">Bệnh viện Uni-Asia Thành Đô cung cấp cho bệnh nhân quốc tế dịch vụ khám chữa bệnh trọn gói, thuận tiện và không rào cản. Quy trình cốt lõi tinh gọn, hiệu quả, với chuyên viên riêng hỗ trợ và kết nối xuyên suốt quá trình.</div></div></div>'
	. '<a href="' . $u( '/dich-vu-y-te/' ) . '" class="group flex items-end pl-8 pt-4 lg:pt-6 lg:ml-auto cursor-pointer flex-none w-full lg:w-auto transition-all duration-300 ease-out hover:translate-x-0.5"><div class="text-success mr-2 group-hover:text-primary">Chi tiết dịch vụ</div><div class="size-6 rounded-full bg-success flex justify-center items-center group-hover:bg-primary"><span class="size-5 icon-[lucide--arrow-right] text-white"></span></div></a></div>'
	. '<div class="flex flex-wrap mt-6 lg:mt-12"><div class="w-full lg:w-1/2 lg:px-4">' . $service_list . '</div><div class="w-full lg:w-1/2 flex items-center justify-center lg:justify-end mt-5 lg:mt-0"><img src="' . $u( '/wp-content/uploads/2026/10/uni-asia-international-patient-service.png' ) . '" alt="UNI-ASIA service" width="621" height="426" loading="lazy"></div></div></section>';

$content = $replace_section( $content, '<section class="max-w-384 mx-auto px-4 py-6 lg:py-12">', '<section class="bg-[#FAFAFA]">', $cancer_section );
$content = $replace_section( $content, '<section class="bg-[#FAFAFA]">', '<section class="overflow-hidden">', $treatment_section );
$content = $replace_section( $content, '<section class="max-w-384 mx-auto px-4 border-t border-border py-6 lg:py-12">', '<section class="max-w-384 mx-auto px-4 pt-6 lg:pt-12 pb-10 lg:pb-20 lg:px-16 border-t border-border">', $guide_section );

kses_remove_filters();
$result = wp_update_post(
	array(
		'ID'           => $front_id,
		'post_content' => wp_slash( $content ),
	),
	true
);
kses_init_filters();
if ( is_wp_error( $result ) ) {
	throw new RuntimeException( $result->get_error_message() );
}

clean_post_cache( $front_id );

// Register the locally hosted guide illustration in Media Library once.
$relative_guide_image = '2026/10/uni-asia-international-patient-service.png';
$attachment_id = $wpdb->get_var(
	$wpdb->prepare(
		"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value = %s LIMIT 1",
		$relative_guide_image
	)
);
if ( ! $attachment_id ) {
	$upload_dir = wp_upload_dir();
	$file_path  = trailingslashit( $upload_dir['basedir'] ) . $relative_guide_image;
	if ( file_exists( $file_path ) ) {
		$attachment_id = wp_insert_attachment(
			array(
				'post_mime_type' => 'image/png',
				'post_title'     => 'Hướng dẫn bệnh nhân quốc tế UNI-ASIA',
				'post_content'   => '',
				'post_status'    => 'inherit',
			),
			$file_path
		);
		if ( $attachment_id && ! is_wp_error( $attachment_id ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
			wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $file_path ) );
		}
	}
}
echo 'Homepage layout repaired for post ' . $front_id . ".\n";
