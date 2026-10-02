<?php
/**
 * Apply the approved multilingual SEO keyword architecture.
 *
 * Dry-run by default:
 *   php tools/implement-multilingual-seo-architecture.php
 * Apply:
 *   php tools/implement-multilingual-seo-architecture.php --apply
 */

if ( PHP_SAPI !== 'cli' ) { exit( "CLI only.\n" ); }
require getcwd() . '/wp-load.php';

$apply = in_array( '--apply', $argv, true );
$stamp = '20261002';
$stats = array( 'seen' => 0, 'changed' => 0, 'slug' => 0, 'seo' => 0, 'skipped' => 0 );

function ucseo_clean( $value ) {
	return trim( preg_replace( '/\s+/u', ' ', html_entity_decode( wp_strip_all_tags( (string) $value ), ENT_QUOTES, 'UTF-8' ) ) );
}

function ucseo_limit( $value, $limit ) {
	$value = ucseo_clean( $value );
	if ( mb_strlen( $value ) <= $limit ) { return $value; }
	$value = mb_substr( $value, 0, $limit + 1 );
	$value = preg_replace( '/\s+\S*$/u', '', $value );
	return rtrim( $value, " ,.;:|-–—" );
}

function ucseo_lang( $post_id ) {
	$lang = function_exists( 'pll_get_post_language' ) ? pll_get_post_language( $post_id, 'slug' ) : 'vi';
	return in_array( $lang, array( 'vi', 'en', 'id', 'zh-cn' ), true ) ? $lang : $lang;
}

function ucseo_backup( $post_id, $key, $value ) {
	global $stamp;
	$backup = '_uc_pre_seo_architecture_' . $stamp . '_' . sanitize_key( ltrim( $key, '_' ) );
	if ( ! metadata_exists( 'post', $post_id, $backup ) ) {
		add_post_meta( $post_id, $backup, $value, true );
	}
}

function ucseo_set( $post, $data ) {
	global $apply, $stats;
	$stats['seen']++;
	$focus = ucseo_clean( $data['focus'] ?? '' );
	$title = ucseo_limit( $data['title'] ?? '', 60 );
	$desc  = ucseo_limit( $data['desc'] ?? '', 155 );
	$slug  = sanitize_title( $data['slug'] ?? $post->post_name );
	$changes = array();
	if ( $slug && $slug !== $post->post_name ) { $changes['slug'] = array( $post->post_name, $slug ); }
	foreach ( array( '_yoast_wpseo_focuskw' => $focus, '_yoast_wpseo_title' => $title, '_yoast_wpseo_metadesc' => $desc ) as $key => $value ) {
		if ( $value && (string) get_post_meta( $post->ID, $key, true ) !== $value ) { $changes[ $key ] = array( get_post_meta( $post->ID, $key, true ), $value ); }
	}
	if ( ! $changes ) { return; }
	$stats['changed']++;
	echo sprintf( "%s #%d [%s] %s\n", $apply ? 'APPLY' : 'DRY', $post->ID, ucseo_lang( $post->ID ), $post->post_title );
	foreach ( $changes as $key => $pair ) { echo "  {$key}: {$pair[0]} => {$pair[1]}\n"; }
	if ( ! $apply ) { return; }
	ucseo_backup( $post->ID, 'post_name', $post->post_name );
	if ( isset( $changes['slug'] ) ) {
		wp_update_post( array( 'ID' => $post->ID, 'post_name' => $slug ) );
		$stats['slug']++;
	}
	foreach ( array( '_yoast_wpseo_focuskw' => $focus, '_yoast_wpseo_title' => $title, '_yoast_wpseo_metadesc' => $desc ) as $key => $value ) {
		if ( ! $value ) { continue; }
		ucseo_backup( $post->ID, $key, get_post_meta( $post->ID, $key, true ) );
		update_post_meta( $post->ID, $key, $value );
	}
	$stats['seo']++;
}

function ucseo_data( $focus, $slug, $lang, $kind = 'guide' ) {
	$brand = 'UNI-ASIA';
	if ( 'vi' === $lang ) {
		$title = ( 'doctor' === $kind ? $focus . ' | Bác sĩ ung bướu ' . $brand : ucfirst( $focus ) . ' | ' . $brand );
		$desc = ucfirst( $focus ) . ' tại Bệnh viện Ung thư UNI-ASIA: thông tin chuyên môn, phương pháp điều trị và tư vấn phù hợp cho từng người bệnh.';
	} elseif ( 'id' === $lang ) {
		$title = ucfirst( $focus ) . ' | ' . $brand;
		$desc = ucfirst( $focus ) . ' di Rumah Sakit Kanker UNI-ASIA: informasi medis, pilihan perawatan, dan konsultasi yang disesuaikan dengan pasien.';
	} elseif ( 'zh-cn' === $lang ) {
		$title = $focus . ' | 成都寰亚肿瘤医院';
		$desc = '成都寰亚肿瘤医院提供' . $focus . '相关专业信息、微创治疗方案及个体化医疗咨询。';
	} else {
		$title = ucfirst( $focus ) . ' | ' . $brand . ' Cancer Hospital';
		$desc = ucfirst( $focus ) . ' at UNI-ASIA Cancer Hospital: specialist information, treatment options, and personalized consultation for international patients.';
	}
	return array( 'focus' => $focus, 'slug' => $slug, 'title' => $title, 'desc' => $desc );
}

// Page hubs: each owns a different search intent.
$pages = array(
	6   => array(
		'vi' => array( 'bệnh viện ung thư tại Trung Quốc', 'trang-chu' ), 'en' => array( 'cancer hospital in China', 'home' ),
		'id' => array( 'rumah sakit kanker di China', 'beranda' ), 'zh-cn' => array( '成都肿瘤医院', 'shou-ye' ),
	),
	7   => array( 'vi' => array( 'Bệnh viện Ung thư UNI-ASIA', 've-uni-asia' ), 'en' => array( 'UNI-ASIA Cancer Hospital', 'about-us' ), 'id' => array( 'Rumah Sakit Kanker UNI-ASIA', 'tentang-kami' ), 'zh-cn' => array( '成都寰亚肿瘤医院', 'guan-yu-wo-men' ) ),
	9   => array( 'vi' => array( 'tư vấn điều trị ung thư', 'lien-he' ), 'en' => array( 'cancer treatment consultation', 'contact' ), 'id' => array( 'konsultasi pengobatan kanker', 'kontak' ), 'zh-cn' => array( '肿瘤治疗咨询', 'lian-xi-wo-men' ) ),
	14  => array( 'vi' => array( 'dịch vụ y tế cho bệnh nhân ung thư', 'dich-vu-y-te' ), 'en' => array( 'medical services for cancer patients', 'medical-services' ), 'id' => array( 'layanan medis pasien kanker', 'layanan-medis' ), 'zh-cn' => array( '肿瘤患者医疗服务', 'yi-liao-fu-wu' ) ),
	180 => array( 'vi' => array( 'các loại ung thư', 'ung-thu' ), 'en' => array( 'types of cancer', 'cancers' ), 'id' => array( 'jenis kanker', 'kanker' ), 'zh-cn' => array( '癌症种类', 'ai-zheng' ) ),
	182 => array( 'vi' => array( 'bác sĩ ung bướu tại Trung Quốc', 'doi-ngu-bac-si' ), 'en' => array( 'cancer specialists in China', 'doctors' ), 'id' => array( 'dokter spesialis kanker di China', 'dokter' ), 'zh-cn' => array( '中国肿瘤专家', 'zhong-liu-zhuan-jia' ) ),
	183 => array( 'vi' => array( 'tin tức ung thư', 'tin-tuc' ), 'en' => array( 'cancer news', 'news' ), 'id' => array( 'berita kanker', 'berita' ), 'zh-cn' => array( '肿瘤资讯', 'xin-wen' ) ),
	184 => array( 'vi' => array( 'câu chuyện bệnh nhân ung thư', 'cau-chuyen-benh-nhan' ), 'en' => array( 'cancer patient stories', 'patient-stories' ), 'id' => array( 'kisah pasien kanker', 'kisah-pasien' ), 'zh-cn' => array( '癌症患者故事', 'huan-zhe-gu-shi' ) ),
	188 => array( 'vi' => array( 'phương pháp điều trị ung thư ít xâm lấn', 'phuong-phap-dieu-tri' ), 'en' => array( 'minimally invasive cancer treatments', 'treatments' ), 'id' => array( 'pengobatan kanker minimal invasif', 'perawatan-kanker' ), 'zh-cn' => array( '肿瘤微创治疗', 'wei-chuang-zhi-liao' ) ),
);

foreach ( $pages as $source_id => $map ) {
	$translations = function_exists( 'pll_get_post_translations' ) ? pll_get_post_translations( $source_id ) : array( 'vi' => $source_id );
	foreach ( $translations as $lang => $id ) {
		if ( empty( $map[ $lang ] ) || ! ( $post = get_post( $id ) ) ) { continue; }
		ucseo_set( $post, ucseo_data( $map[ $lang ][0], $map[ $lang ][1], $lang ) );
	}
}

// Disease pillars. The source IDs below are the canonical Vietnamese disease pages.
$diseases = array(
	17 => array( 'bladder-cancer', 'ung thư bàng quang', 'bladder cancer', 'kanker kandung kemih', '膀胱癌' ),
	18 => array( 'breast-cancer', 'ung thư vú', 'breast cancer', 'kanker payudara', '乳腺癌' ),
	20 => array( 'cervical-cancer', 'ung thư cổ tử cung', 'cervical cancer', 'kanker serviks', '宫颈癌' ),
	22 => array( 'cholangiocarcinoma', 'ung thư đường mật', 'bile duct cancer', 'kanker saluran empedu', '胆管癌' ),
	23 => array( 'colorectal-cancer', 'ung thư đại trực tràng', 'colorectal cancer', 'kanker kolorektal', '结直肠癌' ),
	24 => array( 'endometrial-cancer', 'ung thư nội mạc tử cung', 'endometrial cancer', 'kanker endometrium', '子宫内膜癌' ),
	25 => array( 'esophageal-cancer', 'ung thư thực quản', 'esophageal cancer', 'kanker esofagus', '食管癌' ),
	26 => array( 'gallbladder-cancer', 'ung thư túi mật', 'gallbladder cancer', 'kanker kandung empedu', '胆囊癌' ),
	27 => array( 'glioma', 'u thần kinh đệm não', 'glioma', 'glioma', '脑胶质瘤' ),
	28 => array( 'kidney-cancer', 'ung thư thận', 'kidney cancer', 'kanker ginjal', '肾癌' ),
	29 => array( 'laryngeal-cancer', 'ung thư thanh quản', 'laryngeal cancer', 'kanker laring', '喉癌' ),
	30 => array( 'liver-cancer', 'ung thư gan', 'liver cancer', 'kanker hati', '肝癌' ),
	31 => array( 'lung-cancer', 'ung thư phổi', 'lung cancer', 'kanker paru-paru', '肺癌' ),
	32 => array( 'lymphoma', 'u lympho', 'lymphoma', 'limfoma', '淋巴瘤' ),
	33 => array( 'melanoma', 'u hắc tố ác tính', 'melanoma', 'melanoma ganas', '恶性黑色素瘤' ),
	34 => array( 'multiple-myeloma', 'đa u tủy xương', 'multiple myeloma', 'multiple myeloma', '多发性骨髓瘤' ),
	35 => array( 'nasopharyngeal-cancer', 'ung thư vòm họng', 'nasopharyngeal cancer', 'kanker nasofaring', '鼻咽癌' ),
	36 => array( 'oral-cancer', 'ung thư khoang miệng', 'oral cancer', 'kanker mulut', '口腔癌' ),
	37 => array( 'osteosarcoma', 'sarcoma xương', 'osteosarcoma', 'osteosarkoma', '骨肉瘤' ),
	38 => array( 'ovarian-cancer', 'ung thư buồng trứng', 'ovarian cancer', 'kanker ovarium', '卵巢癌' ),
	39 => array( 'pancreatic-cancer', 'ung thư tuyến tụy', 'pancreatic cancer', 'kanker pankreas', '胰腺癌' ),
	40 => array( 'prostate-cancer', 'ung thư tuyến tiền liệt', 'prostate cancer', 'kanker prostat', '前列腺癌' ),
	41 => array( 'rectal-cancer', 'ung thư trực tràng', 'rectal cancer', 'kanker rektum', '直肠癌' ),
	42 => array( 'skin-cancer', 'ung thư da', 'skin cancer', 'kanker kulit', '皮肤癌' ),
	43 => array( 'soft-tissue-sarcoma', 'sarcoma mô mềm', 'soft tissue sarcoma', 'sarkoma jaringan lunak', '软组织肉瘤' ),
	44 => array( 'stomach-cancer', 'ung thư dạ dày', 'stomach cancer', 'kanker lambung', '胃癌' ),
	45 => array( 'testicular-cancer', 'ung thư tinh hoàn', 'testicular cancer', 'kanker testis', '睾丸癌' ),
	46 => array( 'thyroid-cancer', 'ung thư tuyến giáp', 'thyroid cancer', 'kanker tiroid', '甲状腺癌' ),
);
$lang_index = array( 'vi' => 1, 'en' => 2, 'id' => 3, 'zh-cn' => 4 );
foreach ( $diseases as $source_id => $row ) {
	$translations = function_exists( 'pll_get_post_translations' ) ? pll_get_post_translations( $source_id ) : array( 'vi' => $source_id );
	foreach ( $translations as $lang => $id ) {
		if ( ! isset( $lang_index[ $lang ] ) || ! ( $post = get_post( $id ) ) ) { continue; }
		$focus = $row[ $lang_index[ $lang ] ];
		$slug = 'vi' === $lang ? $row[0] : ( 'zh-cn' === $lang ? $row[0] : sanitize_title( $focus ) );
		ucseo_set( $post, ucseo_data( $focus, $slug, $lang ) );
	}
}

// Treatment technique pillars: no disease head term is reused here.
$treatments = array(
	129 => array( 'argon-helium-cryoablation', 'áp lạnh Argon-Helium', 'Argon-Helium cryoablation', 'krioablasi Argon-Helium', '氩氦刀冷冻消融' ),
	130 => array( 'chemo-targeted-immunotherapy', 'hóa trị nhắm trúng đích miễn dịch', 'chemo targeted immunotherapy', 'kemoterapi target imunoterapi', '化疗靶向免疫治疗' ),
	131 => array( 'deb-tace', 'nút mạch vi cầu mang thuốc DEB-TACE', 'DEB-TACE', 'DEB-TACE', '载药微球栓塞 DEB-TACE' ),
	132 => array( 'high-intensity-focused-ultrasound', 'siêu âm hội tụ cường độ cao HIFU', 'high intensity focused ultrasound HIFU', 'ultrasonografi terfokus HIFU', '高强度聚焦超声 HIFU' ),
	133 => array( 'intra-arterial-therapy', 'nút mạch hóa chất TACE', 'transarterial chemoembolization TACE', 'kemoembolisasi transarteri TACE', '经动脉化疗栓塞术 TACE' ),
	134 => array( 'iodine-125-seed-implantation', 'cấy hạt phóng xạ Iod-125', 'Iodine-125 seed implantation', 'implantasi biji Iodium-125', '碘125粒子植入' ),
	135 => array( 'microwave-ablation', 'đốt u bằng vi sóng MWA', 'microwave ablation MWA', 'ablasi gelombang mikro MWA', '微波消融 MWA' ),
	136 => array( 'nanoknife', 'điều trị ung thư bằng NanoKnife', 'NanoKnife cancer treatment', 'pengobatan kanker NanoKnife', '纳米刀肿瘤消融' ),
	137 => array( 'radioembolization', 'thuyên tắc vi cầu phóng xạ', 'radioembolization', 'radioembolisasi', '放射性微球栓塞' ),
	138 => array( 'radiofrequency-ablation', 'đốt u bằng sóng cao tần RFA', 'radiofrequency ablation RFA', 'ablasi frekuensi radio RFA', '射频消融 RFA' ),
	139 => array( 'stent-placement', 'đặt stent điều trị tắc nghẽn do u', 'cancer stent placement', 'pemasangan stent kanker', '肿瘤支架置入' ),
	140 => array( 'wknife-ablation', 'tiêu hủy u bằng dao WKnife', 'WKnife tumor ablation', 'ablasi tumor WKnife', '陡脉冲肿瘤消融 WKnife' ),
	141 => array( 'vertebroplasty', 'tạo hình thân đốt sống qua da', 'percutaneous vertebroplasty', 'vertebroplasti perkutan', '经皮椎体成形术' ),
);
foreach ( $treatments as $source_id => $row ) {
	$translations = function_exists( 'pll_get_post_translations' ) ? pll_get_post_translations( $source_id ) : array( 'vi' => $source_id );
	foreach ( $translations as $lang => $id ) {
		if ( ! isset( $lang_index[ $lang ] ) || ! ( $post = get_post( $id ) ) ) { continue; }
		$focus = $row[ $lang_index[ $lang ] ];
		$slug = 'zh-cn' === $lang ? $row[0] : ( 'vi' === $lang ? $row[0] : sanitize_title( $focus ) );
		ucseo_set( $post, ucseo_data( $focus, $slug, $lang ) );
	}
}

// Doctors own a name + specialty intent, not a broad cancer keyword.
$doctors = get_posts( array( 'post_type' => 'doctor', 'post_status' => array( 'publish', 'draft' ), 'posts_per_page' => -1, 'orderby' => 'ID', 'order' => 'ASC', 'suppress_filters' => true ) );
foreach ( $doctors as $post ) {
	$lang = ucseo_lang( $post->ID );
	$name = trim( preg_replace( '/\s*[|｜].*$/u', '', ucseo_clean( $post->post_title ) ) );
	$source = function_exists( 'pll_get_post' ) ? pll_get_post( $post->ID, 'vi' ) : 0;
	$base = $source ? get_post_field( 'post_name', $source ) : $post->post_name;
	$focus = 'vi' === $lang ? 'bác sĩ ' . $name . ' chuyên khoa ung bướu' : ( 'id' === $lang ? 'Dokter ' . $name . ' spesialis kanker' : ( 'zh-cn' === $lang ? $name . ' 肿瘤专家' : 'Dr. ' . $name . ' cancer specialist' ) );
	ucseo_set( $post, ucseo_data( $focus, preg_replace( '/-(2|3|4)$/', '', $base ), $lang, 'doctor' ) );
}

echo wp_json_encode( array( 'mode' => $apply ? 'apply' : 'dry-run', 'stats' => $stats ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . PHP_EOL;

