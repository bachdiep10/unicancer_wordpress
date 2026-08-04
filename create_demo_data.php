<?php
/**
 * Script to create demo data for UNI-ASIA Cancer Theme
 * Run: php create_demo_data.php
 */

// Load WordPress
require_once 'C:\laragon\www\uniasia\wp-load.php';

echo "===== Creating demo data =====\n\n";

// ACF must be installed for proper function
// If not, fallback to native meta fields

// 1. Create Site Settings (Default contact info)
echo "1. Setting up contact info...\n";
update_option( 'options_contact_phone_vi', '+84 28 9999 9999' );
update_option( 'options_contact_phone_en', '+86 28 8888 8888' );
update_option( 'options_contact_whatsapp', '+84979999999' );
update_option( 'options_contact_email', 'info@uniasia-cancer.com' );
update_option( 'options_contact_address', "Số 198, Đại lộ Đông Tam Kinh\nThành Đô, Tứ Xuyên, Trung Quốc" );
update_option( 'options_social_facebook', 'https://facebook.com/uniasia.cancer' );
update_option( 'options_social_youtube', 'https://youtube.com/@uniasia.cancer' );
update_option( 'options_social_instagram', 'https://instagram.com/uniasia.cancer' );
update_option( 'options_stat_surgeries', '750,000+' );
update_option( 'options_stat_patients_year', '20,000+' );
update_option( 'options_stat_visits', '1,000,000+' );
echo "  - Contact info set\n";
echo "  - Stats set\n\n";

// 2. Create Doctors
echo "2. Creating doctors...\n";
$doctors = array(
    array(
        'name' => 'GS. BS. Trương Hiểu Bình',
        'degree' => 'Giáo sư, Bác sĩ trưởng',
        'position' => 'Trưởng khoa Can thiệp xâm lấn tối thiểu',
        'specialty' => 'interventional',
        'hospital' => 'Bệnh viện Ung thư UNI-ASIA',
        'experience' => 42,
        'languages' => 'Tiếng Trung, Tiếng Anh',
        'short_bio' => 'Hơn 40 năm kinh nghiệm trong lĩnh vực can thiệp xâm lấn tối thiểu điều trị ung thư. Từng giữ chức Phó Giám đốc Bệnh viện Ung bướu Hàng Hà (Trung Quốc).',
        'education' => 'Tiến sĩ Y khoa - Đại học Y khoa Hoa Trung (1998)\nThạc sĩ - Đại học Tây An (1990)\nBác sĩ nội trú - Bệnh viện Ung bướu Bắc Kinh (1985)',
        'specialties' => 'Ung thư gan, ung thư phổi, ung thư tuyến tụy bằng dao Nano và vi sóng\nCấy ghép hóa chất động mạch gan\nĐốt u bằng sóng cao tần (RFA)',
        'order' => 1,
        'featured' => 1,
    ),
    array(
        'name' => 'PGS. TS. Lý Văn Kiệt',
        'degree' => 'Phó Giáo sư, Tiến sĩ',
        'position' => 'Phó khoa Can thiệp xâm lấn tối thiểu',
        'specialty' => 'interventional',
        'hospital' => 'Bệnh viện Ung thư UNI-ASIA',
        'experience' => 25,
        'languages' => 'Tiếng Trung, Tiếng Anh',
        'short_bio' => 'Chuyên gia hàng đầu về điều trị ung thư gan bằng dao Nano. Đã công bố hơn 60 bài báo khoa học trên các tạp chí quốc tế.',
        'education' => 'Tiến sĩ - Đại học Y Harvard (2005)\nThạc sĩ - Đại học Y khoa Hoa Trung (2000)',
        'specialties' => 'Dao Nano điều trị ung thư tụy\nĐốt u bằng vi sóng\nHóa trị động mạch gan',
        'order' => 2,
        'featured' => 1,
    ),
    array(
        'name' => 'TS. BS. Trần Minh Hùng',
        'degree' => 'Tiến sĩ Y khoa',
        'position' => 'Trưởng khoa Xạ trị',
        'specialty' => 'radiation',
        'hospital' => 'Bệnh viện Ung thư UNI-ASIA',
        'experience' => 18,
        'languages' => 'Tiếng Trung, Tiếng Việt',
        'short_bio' => 'Chuyên gia xạ trị ung thư gan, phổi, vú với 18 năm kinh nghiệm thực tiễn lâm sàng.',
        'education' => 'Tiến sĩ Xạ trị ung thư - Đại học Y Harvard (2012)',
        'specialties' => 'Xạ trị định vị (SBRT)\nXạ trị điều biến liều (IMRT)\nXạ phẫu (Gamma Knife)',
        'order' => 3,
        'featured' => 1,
    ),
    array(
        'name' => 'TS. BS. Vương Gia Nghĩa',
        'degree' => 'Tiến sĩ Y khoa',
        'position' => 'Trưởng khoa Hóa trị',
        'specialty' => 'chemotherapy',
        'hospital' => 'Bệnh viện Ung thư UNI-ASIA',
        'experience' => 20,
        'languages' => 'Tiếng Trung, Tiếng Anh',
        'short_bio' => 'Chuyên gia hóa trị ung thư đường tiêu hóa, gan, tụy và ung thư vú. Thành viên Hiệp hội Ung thư Lâm sàng Hoa Kỳ (ASCO).',
        'education' => 'Tiến sĩ - MD Anderson Cancer Center (2010)',
        'specialties' => 'Hóa trị ung thư gan, tụy\nHóa trị ung thư vú\nLiệu pháp miễn dịch',
        'order' => 4,
        'featured' => 1,
    ),
);

foreach ( $doctors as $doc ) {
    $post_id = wp_insert_post( array(
        'post_title'  => $doc['name'],
        'post_status' => 'publish',
        'post_type'   => 'doctor',
    ) );
    if ( $post_id ) {
        update_post_meta( $post_id, 'doctor_degree', $doc['degree'] );
        update_post_meta( $post_id, 'doctor_position', $doc['position'] );
        update_post_meta( $post_id, 'doctor_specialty', $doc['specialty'] );
        update_post_meta( $post_id, 'doctor_hospital', $doc['hospital'] );
        update_post_meta( $post_id, 'doctor_experience', $doc['experience'] );
        update_post_meta( $post_id, 'doctor_languages', $doc['languages'] );
        update_post_meta( $post_id, 'doctor_short_bio', $doc['short_bio'] );
        update_post_meta( $post_id, 'doctor_education', $doc['education'] );
        update_post_meta( $post_id, 'doctor_specialties', $doc['specialties'] );
        update_post_meta( $post_id, 'doctor_order', $doc['order'] );
        update_post_meta( $post_id, 'doctor_is_featured', $doc['featured'] );
        echo "  Created: {$doc['name']}\n";
    }
}
echo "\n";

// 3. Create Cancer Types
echo "3. Creating cancer types...\n";
$cancers = array(
    array(
        'name' => 'Ung thư gan',
        'slug' => 'liver-cancer',
        'symptoms' => 'Đau hoặc khó chịu ở vùng bụng trên bên phải\nSút cân không rõ nguyên nhân\nChán ăn, buồn nôn\nVàng da, vàng mắt\nMệt mỏi, yếu sức\nBụng sưng phù',
        'diagnosis' => 'Xét nghiệm máu (AFP)\nSiêu âm bụng\nCT scan hoặc MRI\nSinh thiết gan qua da',
        'treatment' => 'Dao Nano (IRE)\nĐốt u bằng vi sóng (MWA)\nNút mạch hóa chất (TACE)\nCấy ghép hạt phóng xạ',
        'color' => '#cc6600',
        'order' => 1,
    ),
    array(
        'name' => 'Ung thư phổi',
        'slug' => 'lung-cancer',
        'symptoms' => 'Ho kéo dài không rõ nguyên nhân\nHo ra máu\nKhó thở, đau ngực\nSút cân, mệt mỏi',
        'diagnosis' => 'Chụp CT ngực\nSinh thiết qua phế quản\nPET-CT\nXét nghiệm đột biến gen',
        'treatment' => 'Phẫu thuật cắt thùy phổi\nXạ trị định vị\nHóa trị\nLiệu pháp miễn dịch (PD-1)',
        'color' => '#336699',
        'order' => 2,
    ),
    array(
        'name' => 'Ung thư tuyến tụy',
        'slug' => 'pancreatic-cancer',
        'symptoms' => 'Đau bụng dữ dội vùng thượng vị\nVàng da\nSút cân nhanh chóng\nRối loạn tiêu hóa',
        'diagnosis' => 'CA 19-9 trong máu\nCT scanner nhiều dãy\nEUS (siêu âm nội soi)\nSinh thiết qua EUS',
        'treatment' => 'Dao Nano (IRE)\nPhẫu thuật Whipple\nHóa trị (FOLFIRINOX)\nXạ trị',
        'color' => '#993366',
        'order' => 3,
    ),
    array(
        'name' => 'Ung thư vú',
        'slug' => 'breast-cancer',
        'symptoms' => 'Khối u ở vú hoặc nách\nThay đổi hình dạng núm vú\nDa vú sần vỏ cam\nTiết dịch núm vú',
        'diagnosis' => 'Chụp nhũ ảnh\nSiêu âm vú\nSinh thiết\nXét nghiệm HER2, ER, PR',
        'treatment' => 'Phẫu thuật bảo tồn\nHóa trị bổ trợ\nLiệu pháp nội tiết\nTrastuzumab',
        'color' => '#cc3366',
        'order' => 4,
    ),
    array(
        'name' => 'Ung thư cổ tử cung',
        'slug' => 'cervical-cancer',
        'symptoms' => 'Chảy máu âm đạo bất thường\nTiết dịch bất thường\nĐau khi quan hệ\nĐau vùng chậu',
        'diagnosis' => 'Pap smear\nHPV test\nSoi cổ tử cung\nSinh thiết',
        'treatment' => 'Phẫu thuật cắt tử cung\nXạ trị kết hợp hóa trị\nLiệu pháp miễn dịch',
        'color' => '#993399',
        'order' => 5,
    ),
    array(
        'name' => 'Ung thư đại trực tràng',
        'slug' => 'colon-rectal',
        'symptoms' => 'Thay đổi thói quen đi cầu\nMáu trong phân\nĐau bụng\nSút cân',
        'diagnosis' => 'Nội soi đại tràng\nCT scan bụng chậu\nCEA trong máu\nSinh thiết',
        'treatment' => 'Phẫu thuật cắt đoạn ruột\nHóa trị bổ trợ\nLiệu pháp nhắm trúng đích',
        'color' => '#666699',
        'order' => 6,
    ),
);

foreach ( $cancers as $cancer ) {
    $post_id = wp_insert_post( array(
        'post_title'  => $cancer['name'],
        'post_name'   => $cancer['slug'],
        'post_status' => 'publish',
        'post_type'   => 'cancer_type',
        'post_content' => $cancer['symptoms'] . "\n\n" . $cancer['diagnosis'] . "\n\n" . $cancer['treatment'],
    ) );
    if ( $post_id ) {
        update_post_meta( $post_id, 'cancer_symptoms', $cancer['symptoms'] );
        update_post_meta( $post_id, 'cancer_diagnosis', $cancer['diagnosis'] );
        update_post_meta( $post_id, 'cancer_treatment', $cancer['treatment'] );
        update_post_meta( $post_id, 'cancer_color', $cancer['color'] );
        update_post_meta( $post_id, 'cancer_order', $cancer['order'] );
        // Set as cancer_category term too
        wp_set_object_terms( $post_id, $cancer['slug'], 'cancer_category' );
        echo "  Created: {$cancer['name']}\n";
    }
}
echo "\n";

// 4. Create Technologies
echo "4. Creating technologies...\n";
$techs = array(
    array(
        'name' => 'Dao Nano (IRE)',
        'short_name' => 'IRE',
        'full_name' => 'Irreversible Electroporation - Nano Knife',
        'summary' => 'Kỹ thuật sử dụng dòng điện cao áp để phá hủy tế bào u mà không gây tổn thương mạch máu, ống mật. Đặc biệt hiệu quả cho ung thư tuyến tụy và gan.',
        'order' => 1,
    ),
    array(
        'name' => 'Đốt u vi sóng (MWA)',
        'short_name' => 'MWA',
        'full_name' => 'Microwave Ablation',
        'summary' => 'Sử dụng sóng vi ba năng lượng cao để đốt nóng và phá hủy khối u. Thời gian điều trị ngắn, hiệu quả cao cho u gan, phổi, thận.',
        'order' => 2,
    ),
    array(
        'name' => 'Đốt u cao tần (RFA)',
        'short_name' => 'RFA',
        'full_name' => 'Radiofrequency Ablation',
        'summary' => 'Kỹ thuật xâm lấn tối thiểu dùng dòng điện tần số radio tạo nhiệt phá hủy khối u. Kinh điển và hiệu quả cho u gan dưới 5cm.',
        'order' => 3,
    ),
    array(
        'name' => 'Áp lạnh (Cryoablation)',
        'short_name' => 'Cryo',
        'full_name' => 'Cryoablation',
        'summary' => 'Dùng cực lạnh (nitơ lỏng) để đông và phá hủy khối u. Ưu điểm: ít đau, bảo tồn mô xung quanh.',
        'order' => 4,
    ),
    array(
        'name' => 'Hóa trị nút mạch (TACE)',
        'short_name' => 'TACE',
        'full_name' => 'Transarterial Chemoembolization',
        'summary' => 'Bơm thuốc hóa trị trực tiếp vào động mạch nuôi u, kết hợp nút mạch. Điều trị ung thư gan nguyên phát và di căn.',
        'order' => 5,
    ),
    array(
        'name' => 'Liệu pháp miễn dịch',
        'short_name' => 'Immunotherapy',
        'full_name' => 'Cancer Immunotherapy',
        'summary' => 'Sử dụng thuốc kích thích hệ miễn dịch tự nhiên của cơ thể nhận diện và tiêu diệt tế bào ung thư. Bao gồm các thuốc PD-1, PD-L1, CAR-T.',
        'order' => 6,
    ),
    array(
        'name' => 'Xạ trị định vị (SBRT)',
        'short_name' => 'SBRT',
        'full_name' => 'Stereotactic Body Radiation Therapy',
        'summary' => 'Xạ trị chính xác cao, tập trung liều cao vào khối u với số lần điều trị ít (3-5 lần). Hiệu quả cho u phổi, gan, xương.',
        'order' => 7,
    ),
);

foreach ( $techs as $tech ) {
    $post_id = wp_insert_post( array(
        'post_title'  => $tech['name'],
        'post_status' => 'publish',
        'post_type'   => 'technology',
        'post_content' => $tech['summary'],
        'post_excerpt' => $tech['summary'],
    ) );
    if ( $post_id ) {
        update_post_meta( $post_id, 'tech_short_name', $tech['short_name'] );
        update_post_meta( $post_id, 'tech_full_name', $tech['full_name'] );
        update_post_meta( $post_id, 'tech_summary', $tech['summary'] );
        update_post_meta( $post_id, 'tech_order', $tech['order'] );
        echo "  Created: {$tech['name']}\n";
    }
}
echo "\n";

// 5. Create Patient Stories
echo "5. Creating patient stories...\n";
$stories = array(
    array(
        'name' => 'Ông Nguyễn Văn A',
        'age' => 58,
        'country' => 'Việt Nam',
        'cancer' => 'Ung thư gan',
        'treatment' => 'Dao Nano + Hóa trị nút mạch',
        'summary' => 'Được chẩn đoán ung thư gan giai đoạn II với khối u 5cm. Sau 3 tháng điều trị bằng Dao Nano kết hợp TACE, khối u đã giảm 60%. Bệnh nhân phục hồi tốt và trở về cuộc sống bình thường.',
        'order' => 1,
        'featured' => 1,
    ),
    array(
        'name' => 'Bà Trần Thị B',
        'age' => 52,
        'country' => 'Việt Nam',
        'cancer' => 'Ung thư vú',
        'treatment' => 'Phẫu thuật + Hóa trị + Liệu pháp nội tiết',
        'summary' => 'Phát hiện ung thư vú giai đoạn sớm qua tầm soát. Sau phẫu thuật bảo tồn kết hợp hóa trị và liệu pháp hormone, sức khỏe ổn định sau 2 năm.',
        'order' => 2,
        'featured' => 1,
    ),
    array(
        'name' => 'Anh John Smith',
        'age' => 45,
        'country' => 'USA',
        'cancer' => 'Ung thư phổi',
        'treatment' => 'Xạ trị định vị SBRT + Liệu pháp miễn dịch',
        'summary' => 'Từ Mỹ sang điều trị khi u phổi không thể phẫu thuật. Sau 3 tháng SBRT kết hợp Pembrolizumab, khối u ổn định, bệnh nhân bay về nước tiếp tục theo dõi từ xa.',
        'order' => 3,
        'featured' => 1,
    ),
    array(
        'name' => 'Bà Lee S.K.',
        'age' => 60,
        'country' => 'Singapore',
        'cancer' => 'Ung thư tụy',
        'treatment' => 'Dao Nano (IRE)',
        'summary' => 'Ung thư tụy giai đoạn III, không thể phẫu thuật cắt. Sau điều trị IRE (Dao Nano), u giảm kích thước và đủ điều kiện phẫu thuật triệt căn. Hiện sức khỏe ổn định 3 năm sau.',
        'order' => 4,
        'featured' => 1,
    ),
    array(
        'name' => 'Ông Phạm Văn C',
        'age' => 65,
        'country' => 'Indonesia',
        'cancer' => 'Ung thư đại tràng',
        'treatment' => 'Phẫu thuật + Hóa trị bổ trợ',
        'summary' => 'Đến từ Indonesia với u đại tràng sigmoid. Phẫu thuật nội soi kết hợp hóa trị bổ trợ. Bệnh nhân quay về nước với sức khỏe tốt.',
        'order' => 5,
        'featured' => 0,
    ),
);

foreach ( $stories as $story ) {
    $post_id = wp_insert_post( array(
        'post_title'  => 'Câu chuyện của ' . $story['name'],
        'post_status' => 'publish',
        'post_type'   => 'patient_story',
        'post_excerpt' => $story['summary'],
        'post_content' => '<p>' . $story['summary'] . '</p><p>Đây là câu chuyện chi tiết về hành trình điều trị của bệnh nhân. Thông tin được sự đồng ý của bệnh nhân.</p>',
    ) );
    if ( $post_id ) {
        update_post_meta( $post_id, 'story_patient_name', $story['name'] );
        update_post_meta( $post_id, 'story_age', $story['age'] );
        update_post_meta( $post_id, 'story_country', $story['country'] );
        update_post_meta( $post_id, 'story_cancer_type', $story['cancer'] );
        update_post_meta( $post_id, 'story_treatment', $story['treatment'] );
        update_post_meta( $post_id, 'story_summary', $story['summary'] );
        update_post_meta( $post_id, 'story_is_featured', $story['featured'] );
        update_post_meta( $post_id, 'story_order', $story['order'] );
        echo "  Created: {$story['name']}\n";
    }
}
echo "\n";

// 6. Create FAQs
echo "6. Creating FAQs...\n";
$faqs = array(
    array(
        'question' => 'Tôi nên mang theo những giấy tờ gì khi đến khám?',
        'answer' => 'Bạn cần mang theo: Hộ chiếu (còn hạn trên 6 tháng), visa y tế, kết quả xét nghiệm trước đó (nếu có), đơn thuốc đang dùng, và giấy giới thiệu của bác sĩ (nếu có). Bệnh viện sẽ hỗ trợ dịch công chứng các tài liệu y tế của bạn.',
        'group' => 'documents',
        'order' => 1,
    ),
    array(
        'question' => 'Chi phí điều trị tại bệnh viện là bao nhiêu?',
        'answer' => 'Chi phí phụ thuộc vào phương pháp điều trị, giai đoạn bệnh và tình trạng sức khỏe cụ thể. Bệnh viện sẽ cung cấp báo giá chi tiết sau khi hội chẩn và lập phác đồ. Vui lòng liên hệ phòng Quốc tế để được tư vấn chi tiết.',
        'group' => 'general',
        'order' => 2,
    ),
    array(
        'question' => 'Bệnh viện có chấp nhận bảo hiểm không?',
        'answer' => 'Có. Bệnh viện hợp tác với nhiều công ty bảo hiểm quốc tế như BUPA, Cigna, Aetna, Allianz. Phòng Quốc tế sẽ hỗ trợ làm thủ tục thanh toán trực tiếp với công ty bảo hiểm của bạn.',
        'group' => 'insurance',
        'order' => 3,
    ),
    array(
        'question' => 'Dao Nano (IRE) có thể điều trị loại ung thư nào?',
        'answer' => 'Dao Nano đặc biệt hiệu quả cho: Ung thư tuyến tụy (đặc biệt là giai đoạn III cục bộ), ung thư gan (gần mạch máu lớn), ung thư tiền liệt tuyến, và một số u gần cấu trúc quan trọng khác. Ưu điểm: bảo tồn mạch máu, ống mật, dây thần kinh.',
        'group' => 'treatment',
        'order' => 4,
    ),
    array(
        'question' => 'Bệnh nhân quốc tế cần bao lâu để hoàn tất điều trị?',
        'answer' => 'Thời gian trung bình cho một đợt điều trị khoảng 10-30 ngày tùy phương pháp. Với can thiệp xâm lấn tối thiểu, nhiều bệnh nhân chỉ cần 7-14 ngày. Sau đó có thể tiếp tục phục hồi tại nhà dưới sự theo dõi từ xa.',
        'group' => 'international',
        'order' => 5,
    ),
    array(
        'question' => 'Bệnh viện có hỗ trợ phiên dịch không?',
        'answer' => 'Có. Bệnh viện có đội ngũ phiên dịch chuyên nghiệp: Tiếng Anh, Indonesia, Việt, Nga, Thái, Mông Cổ. Phiên dịch y khoa đồng hành xuyên suốt quá trình khám và điều trị.',
        'group' => 'international',
        'order' => 6,
    ),
    array(
        'question' => 'Làm thế nào để đặt lịch tư vấn trực tuyến?',
        'answer' => 'Bạn có thể đặt lịch qua: 1) Hotline +84 28 9999 9999, 2) WhatsApp +84 97 999 9999, 3) Email info@uniasia-cancer.com, 4) Form trên website. Chuyên viên sẽ liên hệ trong vòng 24-72 giờ.',
        'group' => 'general',
        'order' => 7,
    ),
    array(
        'question' => 'Sau điều trị có cần quay lại tái khám không?',
        'answer' => 'Có. Tùy theo loại ung thư và phương pháp, bác sĩ sẽ đặt lịch tái khám định kỳ. Bệnh viện có chương trình theo dõi từ xa (Tele-medicine) cho bệnh nhân ở xa, kết hợp các xét nghiệm tại nước sở tại.',
        'group' => 'treatment',
        'order' => 8,
    ),
);

foreach ( $faqs as $faq ) {
    $post_id = wp_insert_post( array(
        'post_title'  => $faq['question'],
        'post_status' => 'publish',
        'post_type'   => 'faq',
        'post_content' => $faq['answer'],
    ) );
    if ( $post_id ) {
        update_post_meta( $post_id, 'story_order', $faq['order'] );
        wp_set_object_terms( $post_id, $faq['group'], 'faq_group' );
        echo "  Created: {$faq['question']}\n";
    }
}
echo "\n";

// 7. Create Menu
echo "7. Creating primary menu...\n";
$menu_id = wp_create_nav_menu( 'Menu chính - Tiếng Việt' );
if ( is_wp_error( $menu_id ) ) {
    $menus = wp_get_nav_menus();
    foreach ( $menus as $m ) {
        if ( $m->name === 'Menu chính - Tiếng Việt' ) {
            $menu_id = $m->term_id;
            break;
        }
    }
}

if ( $menu_id ) {
    $items = array(
        array( 'title' => 'Trang chủ', 'url' => home_url( '/' ) ),
        array( 'title' => 'Giới thiệu', 'url' => home_url( '/about-us/' ) ),
        array( 'title' => 'Bác sĩ', 'url' => home_url( '/doctors/' ) ),
        array( 'title' => 'Kỹ thuật', 'url' => home_url( '/technologies/' ) ),
        array( 'title' => 'Câu chuyện', 'url' => home_url( '/patient-stories/' ) ),
        array( 'title' => 'FAQ', 'url' => home_url( '/faqs/' ) ),
        array( 'title' => 'Liên hệ', 'url' => '#contact-form' ),
    );

    foreach ( $items as $item ) {
        wp_update_nav_menu_item( $menu_id, 0, array(
            'menu-item-title'  => $item['title'],
            'menu-item-url'    => $item['url'],
            'menu-item-status' => 'publish',
        ) );
    }

    $locations = get_theme_mod( 'nav_menu_locations', array() );
    $locations['primary'] = $menu_id;
    set_theme_mod( 'nav_menu_locations', $locations );
    echo "  Menu created with " . count($items) . " items\n\n";
}

// 8. Set site icon and options
echo "8. Setting site options...\n";
update_option( 'site_icon', 0 );
update_option( 'blogdescription', 'Bệnh viện Ung thư hàng đầu với điều trị chính xác ít xâm lấn' );
update_option( 'permalink_structure', '/%postname%/' );
echo "  Site description set\n";

// 9. Create About Us page
$about_page_id = wp_insert_post( array(
    'post_title'   => 'Về chúng tôi',
    'post_name'    => 'about-us',
    'post_status'  => 'publish',
    'post_type'    => 'page',
    'post_content' => '<h1>Bệnh viện Ung thư UNI-ASIA</h1>
<h2>Sứ mệnh</h2>
<p>Trở thành bệnh viện ung thư chuẩn quốc tế hàng đầu châu Á, mang đến dịch vụ điều trị chất lượng cao, chính xác và cá nhân hóa cho bệnh nhân trên toàn cầu.</p>
<h2>Tầm nhìn</h2>
<p>Tiên phong trong ứng dụng công nghệ điều trị ung thư xâm lấn tối thiểu tiên tiến nhất, kết hợp y học cổ truyền và hiện đại.</p>
<h2>Giá trị cốt lõi</h2>
<ul>
<li>Chuyên nghiệp - Hợp tác - Sáng tạo - Nhân văn</li>
<li>Đặt bệnh nhân làm trung tâm</li>
<li>Cam kết chất lượng y khoa quốc tế</li>
</ul>

<h2>Lịch sử</h2>
<p>Thành lập từ năm 2001, Bệnh viện Ung thư UNI-ASIA tọa lạc tại thành phố Thành Đô - Tứ Xuyên - Trung Quốc. Với hơn 20 năm phát triển, bệnh viện đã trở thành một trong những trung tâm ung bướu hàng đầu Đông Nam Á.</p>

<h2>Thành tựu</h2>
<ul>
<li>Cơ sở đào tạo Hiệp hội Điều trị Đốt u Thế giới (WATA) đầu tiên tại khu vực</li>
<li>Hơn 750,000 ca phẫu thuật xâm lấn tối thiểu thành công</li>
<li>Hơn 20,000 bệnh nhân/ năm từ 30+ quốc gia</li>
</ul>',
) );
if ( $about_page_id ) {
    echo "  Created page: Về chúng tôi\n";
}

// 10. Set homepage
update_option( 'show_on_front', 'posts' );
echo "  Homepage set\n";

echo "\n===================================\n";
echo "Demo data created successfully!\n";
echo "===================================\n\n";

// Re-save permalinks
flush_rewrite_rules();
echo "✓ All done!\n";