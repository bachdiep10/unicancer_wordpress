<?php
/**
 * Complete Yoast on-page optimization for Vietnamese patient stories.
 * Repeatable: generated sections are replaced rather than duplicated.
 */

if ( PHP_SAPI !== 'cli' ) { exit( "CLI only.\n" ); }
require getcwd() . '/wp-load.php';

function uc_onpage_text( $value ) {
	return trim( preg_replace( '/\s+/u', ' ', html_entity_decode( wp_strip_all_tags( (string) $value ), ENT_QUOTES, 'UTF-8' ) ) );
}

function uc_onpage_limit( $value, $limit ) {
	$value = uc_onpage_text( $value );
	if ( mb_strlen( $value ) <= $limit ) { return $value; }
	$value = mb_substr( $value, 0, $limit + 1 );
	$value = preg_replace( '/\s+\S*$/u', '', $value );
	return rtrim( $value, " ,.;:|-–—" );
}

function uc_onpage_cancer_slug( $diagnosis ) {
	$map = array(
		'ung thư bàng quang'       => 'bladder-cancer',
		'ung thư vú'               => 'breast-cancer',
		'ung thư cổ tử cung'        => 'cervical-cancer',
		'ung thư đại trực tràng'    => 'colorectal-cancer',
		'ung thư gan'               => 'liver-cancer',
		'ung thư phổi'              => 'lung-cancer',
		'ung thư vòm họng'          => 'nasopharyngeal-cancer',
		'ung thư tuyến tụy'         => 'pancreatic-cancer',
		'ung thư tuyến tiền liệt'   => 'prostate-cancer',
		'ung thư dạ dày'            => 'stomach-cancer',
	);
	return $map[ mb_strtolower( uc_onpage_text( $diagnosis ) ) ] ?? '';
}

function uc_onpage_treatment_slug( $treatment ) {
	$value = mb_strtolower( uc_onpage_text( $treatment ) );
	if ( false !== mb_strpos( $value, 'vi sóng' ) ) { return 'microwave-ablation'; }
	if ( false !== mb_strpos( $value, 'can thiệp qua động mạch' ) ) { return 'intra-arterial-therapy'; }
	if ( false !== mb_strpos( $value, 'dao vina' ) ) { return 'treatments-nanoknife-wknife'; }
	if ( false !== mb_strpos( $value, 'tạo hình thân đốt sống' ) ) { return 'vertebroplasty'; }
	if ( false !== mb_strpos( $value, 'argon-helium' ) ) { return 'argon-helium-cryoablation'; }
	if ( false !== mb_strpos( $value, 'dao nano' ) || false !== mb_strpos( $value, 'nanoknife' ) ) { return 'nanoknife'; }
	return '';
}

function uc_onpage_enhance_content( $content, $focus, $diagnosis, $treatment, $cancer_url, $treatment_url ) {
	libxml_use_internal_errors( true );
	$dom = new DOMDocument();
	$dom->loadHTML( '<?xml encoding="utf-8" ?><div id="uc-onpage-root">' . $content . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	$xpath = new DOMXPath( $dom );
	foreach ( array( 'uc-seo-intro', 'uc-seo-links' ) as $class ) {
		$nodes = array();
		foreach ( $xpath->query( '//*[contains(concat(" ", normalize-space(@class), " "), " ' . $class . ' ")]' ) as $node ) { $nodes[] = $node; }
		foreach ( $nodes as $node ) { if ( $node->parentNode ) { $node->parentNode->removeChild( $node ); } }
	}
	$rich = $xpath->query( '//*[contains(concat(" ", normalize-space(@class), " "), " lexical-rich-text ")]' )->item( 0 );
	if ( ! $rich ) { $rich = $dom->getElementById( 'uc-onpage-root' ); }
	$intro_doc = new DOMDocument();
	$intro_html = '<div class="uc-seo-intro"><p><strong>' . esc_html( ucfirst( $focus ) ) . '</strong> cần được đánh giá toàn diện và lựa chọn phác đồ phù hợp với từng người bệnh. Câu chuyện dưới đây chia sẻ quá trình thăm khám, điều trị và hồi phục thực tế tại Bệnh viện Ung thư UNI-ASIA.</p><h2>Hành trình ' . esc_html( $focus ) . '</h2></div>';
	$intro_doc->loadHTML( '<?xml encoding="utf-8" ?>' . $intro_html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	$intro = $dom->importNode( $intro_doc->documentElement, true );
	$rich->insertBefore( $intro, $rich->firstChild );
	$links = array();
	if ( $cancer_url ) { $links[] = '<a href="' . esc_url( $cancer_url ) . '">Thông tin về ' . esc_html( mb_strtolower( $diagnosis ) ) . '</a>'; }
	if ( $treatment_url ) { $links[] = '<a href="' . esc_url( $treatment_url ) . '">' . esc_html( $treatment ) . '</a>'; }
	if ( $links ) {
		$link_doc = new DOMDocument();
		$link_doc->loadHTML( '<?xml encoding="utf-8" ?><p class="uc-seo-links"><strong>Tìm hiểu thêm:</strong> ' . implode( ' · ', $links ) . '</p>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		$rich->appendChild( $dom->importNode( $link_doc->documentElement, true ) );
	}
	$image = $xpath->query( '(//*[contains(concat(" ", normalize-space(@class), " "), " lexical-rich-text ")]//img)[1]' )->item( 0 );
	if ( ! $image ) {
		$image = $xpath->query( '(//img[not(contains(translate(@src,"ZALOWHTSPLOG","zalowhtsplog"),"zalo")) and not(contains(translate(@src,"ZALOWHTSPLOG","zalowhtsplog"),"whatsapp")) and not(contains(translate(@src,"ZALOWHTSPLOG","zalowhtsplog"),"logo"))])[1]' )->item( 0 );
	}
	if ( $image ) { $image->setAttribute( 'alt', ucfirst( $focus ) . ' tại Bệnh viện Ung thư UNI-ASIA' ); }
	$root = $dom->getElementById( 'uc-onpage-root' );
	$output = '';
	foreach ( $root->childNodes as $node ) { $output .= $dom->saveHTML( $node ); }
	libxml_clear_errors();
	return $output;
}

$posts = get_posts( array( 'post_type' => 'patient_story', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'ID', 'order' => 'ASC' ) );
$stats = array( 'posts' => 0, 'content' => 0, 'slugs' => 0, 'metadata' => 0, 'internal_links' => 0, 'image_alt' => 0 );
foreach ( $posts as $post ) {
	$lang = function_exists( 'pll_get_post_language' ) ? pll_get_post_language( $post->ID, 'slug' ) : 'vi';
	if ( $lang && 'vi' !== $lang ) { continue; }
	$diagnosis = uc_onpage_text( get_post_meta( $post->ID, 'uc_patient_diagnosis', true ) );
	$treatment = uc_onpage_text( get_post_meta( $post->ID, 'uc_patient_treatment', true ) );
	if ( ! $diagnosis ) { continue; }
	$focus = mb_strtolower( $diagnosis );
	$cancer_slug = uc_onpage_cancer_slug( $diagnosis );
	$treatment_slug = uc_onpage_treatment_slug( $treatment );
	$cancer_url = $cancer_slug ? home_url( '/vi/cancers/' . $cancer_slug . '/' ) : '';
	$treatment_url = $treatment_slug ? home_url( '/vi/treatments/' . $treatment_slug . '/' ) : '';
	$content = uc_onpage_enhance_content( $post->post_content, $focus, $diagnosis, $treatment, $cancer_url, $treatment_url );
	if ( ! metadata_exists( 'post', $post->ID, '_uc_pre_onpage_content_20261002' ) ) {
		add_post_meta( $post->ID, '_uc_pre_onpage_content_20261002', $post->post_content, true );
	}
	$slug = sanitize_title( $focus . '-ca-benh-' . $post->ID );
	$update = array( 'ID' => $post->ID, 'post_content' => wp_slash( $content ) );
	if ( $post->post_name !== $slug ) { $update['post_name'] = $slug; $stats['slugs']++; }
	wp_update_post( $update );
	$seo_title = uc_onpage_limit( ucfirst( $focus ) . ': câu chuyện điều trị tại UNI-ASIA', 60 );
	$existing = uc_onpage_text( get_post_meta( $post->ID, '_yoast_wpseo_metadesc', true ) );
	$meta = ucfirst( $focus ) . ' được điều trị tại Bệnh viện Ung thư UNI-ASIA. ' . $existing;
	$meta = uc_onpage_limit( $meta, 155 );
	update_post_meta( $post->ID, '_yoast_wpseo_focuskw', $focus );
	update_post_meta( $post->ID, '_yoast_wpseo_title', $seo_title );
	update_post_meta( $post->ID, '_yoast_wpseo_metadesc', $meta );
	wp_update_post( array( 'ID' => $post->ID ) );
	$stats['posts']++;
	$stats['content']++;
	$stats['metadata']++;
	$stats['internal_links'] += (int) (bool) $cancer_url + (int) (bool) $treatment_url;
	$stats['image_alt']++;
}

echo wp_json_encode( $stats, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . PHP_EOL;
