<?php
/**
 * One-time, repeatable patient story cleanup and Yoast metadata migration.
 *
 * Run from the WordPress root:
 * php /path/to/optimize-patient-stories.php
 */

if ( PHP_SAPI !== 'cli' ) {
	exit( "CLI only.\n" );
}

require getcwd() . '/wp-load.php';

function uc_seo_compact( $value ) {
	return trim( preg_replace( '/\s+/u', ' ', html_entity_decode( wp_strip_all_tags( (string) $value ), ENT_QUOTES, 'UTF-8' ) ) );
}

function uc_seo_limit( $value, $limit ) {
	$value = uc_seo_compact( $value );
	if ( mb_strlen( $value ) <= $limit ) { return $value; }
	$value = mb_substr( $value, 0, $limit + 1 );
	$value = preg_replace( '/\s+\S*$/u', '', $value );
	return rtrim( $value, " ,.;:|-–—" ) . '…';
}

function uc_clean_patient_story_content( $content, $title ) {
	libxml_use_internal_errors( true );
	$dom = new DOMDocument();
	$dom->loadHTML( '<?xml encoding="utf-8" ?><div id="uc-clean-root">' . $content . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	$xpath = new DOMXPath( $dom );
	$title_key = preg_replace( '/[^\p{L}\p{N}]+/u', '', mb_strtolower( html_entity_decode( $title, ENT_QUOTES, 'UTF-8' ) ) );
	$nodes = array();
	foreach ( $xpath->query( '//h1' ) as $node ) { $nodes[] = $node; }
	foreach ( $nodes as $node ) {
		$node_key = preg_replace( '/[^\p{L}\p{N}]+/u', '', mb_strtolower( trim( $node->textContent ) ) );
		if ( $node_key === $title_key && $node->parentNode ) {
			$node->parentNode->removeChild( $node );
		} else {
			$replacement = $dom->createElement( 'h2' );
			while ( $node->firstChild ) { $replacement->appendChild( $node->firstChild ); }
			if ( $node->parentNode ) { $node->parentNode->replaceChild( $replacement, $node ); }
		}
	}
	$nodes = array();
	foreach ( $xpath->query( '//*[not(*)]' ) as $node ) { $nodes[] = $node; }
	foreach ( $nodes as $node ) {
		$value = uc_seo_compact( $node->textContent );
		if ( preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value ) || preg_match( '/^(?:Chia sẻ tới|Share to|Bagikan ke|分享到)\s*:?$/iu', $value ) ) {
			if ( $node->parentNode ) { $node->parentNode->removeChild( $node ); }
		}
	}
	$root = $dom->getElementById( 'uc-clean-root' );
	$output = '';
	foreach ( $root->childNodes as $node ) { $output .= $dom->saveHTML( $node ); }
	libxml_clear_errors();
	return $output;
}

function uc_first_attachment_id( $content ) {
	libxml_use_internal_errors( true );
	$dom = new DOMDocument();
	$dom->loadHTML( '<?xml encoding="utf-8" ?><div id="uc-image-root">' . $content . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	$xpath = new DOMXPath( $dom );
	$images = $xpath->query( '(//*[contains(concat(" ", normalize-space(@class), " "), " lexical-rich-text ")]//img)[1]' );
	if ( ! $images->length ) {
		$images = $xpath->query( '(//img[not(contains(translate(@src,"ZALOWHTSPQR","zalowhtspqr"),"zalo")) and not(contains(translate(@src,"ZALOWHTSPQR","zalowhtspqr"),"whatsapp")) and not(contains(translate(@src,"ZALOWHTSPQR","zalowhtspqr"),"logo"))])[1]' );
	}
	if ( ! $images->length ) { return 0; }
	$url = html_entity_decode( $images->item( 0 )->getAttribute( 'src' ), ENT_QUOTES, 'UTF-8' );
	$id = attachment_url_to_postid( $url );
	if ( $id ) { return (int) $id; }
	global $wpdb;
	$path = (string) wp_parse_url( $url, PHP_URL_PATH );
	$name = wp_basename( $path );
	if ( ! $name ) { return 0; }
	return (int) $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type='attachment' AND guid LIKE %s ORDER BY ID DESC LIMIT 1", '%' . $wpdb->esc_like( $name ) ) );
}

$posts = get_posts(
	array(
		'post_type'      => 'patient_story',
		'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
		'posts_per_page' => -1,
		'orderby'        => 'ID',
		'order'          => 'ASC',
	)
);

$stats = array( 'posts' => 0, 'cleaned' => 0, 'featured' => 0, 'yoast' => 0 );
foreach ( $posts as $post ) {
	$lang = function_exists( 'pll_get_post_language' ) ? pll_get_post_language( $post->ID, 'slug' ) : 'vi';
	$lang = $lang ?: 'vi';
	$original = $post->post_content;
	$cleaned = uc_clean_patient_story_content( $original, $post->post_title );
	if ( ! metadata_exists( 'post', $post->ID, '_uc_pre_seo_content_20261002' ) ) {
		add_post_meta( $post->ID, '_uc_pre_seo_content_20261002', $original, true );
	}
	if ( $cleaned !== $original ) {
		wp_update_post( array( 'ID' => $post->ID, 'post_content' => wp_slash( $cleaned ) ) );
		$stats['cleaned']++;
	}
	$attachment_id = uc_first_attachment_id( $cleaned );
	if ( $attachment_id && (int) get_post_thumbnail_id( $post->ID ) !== $attachment_id ) {
		set_post_thumbnail( $post->ID, $attachment_id );
		$stats['featured']++;
	}
	$name = uc_seo_compact( get_post_meta( $post->ID, 'uc_patient_name', true ) );
	$diagnosis = uc_seo_compact( get_post_meta( $post->ID, 'uc_patient_diagnosis', true ) );
	$treatment = uc_seo_compact( get_post_meta( $post->ID, 'uc_patient_treatment', true ) );
	if ( function_exists( 'unicancer_patient_story_value' ) ) {
		$name = $name ?: uc_seo_compact( unicancer_patient_story_value( $post->ID, 'uc_patient_name', $cleaned, '#class="max-w-64[^>]*>([^<]+)#u' ) );
		$diagnosis = $diagnosis ?: uc_seo_compact( unicancer_patient_story_value( $post->ID, 'uc_patient_diagnosis', $cleaned, '#Chẩn đoán:</div>\s*<div[^>]*>\s*<div>([^<]+)#u' ) );
		$treatment = $treatment ?: uc_seo_compact( unicancer_patient_story_value( $post->ID, 'uc_patient_treatment', $cleaned, '#Phác đồ điều trị:</div>\s*<div[^>]*>\s*<div>([^<]+)#u' ) );
	}
	if ( $name ) { update_post_meta( $post->ID, 'uc_patient_name', $name ); }
	if ( $diagnosis ) { update_post_meta( $post->ID, 'uc_patient_diagnosis', $diagnosis ); }
	if ( $treatment ) { update_post_meta( $post->ID, 'uc_patient_treatment', $treatment ); }
	$diagnosis = $diagnosis ?: ( 'vi' === $lang ? 'ung thư' : ( 'zh-cn' === $lang ? '癌症' : 'cancer' ) );
	if ( 'en' === $lang ) {
		$focus = $diagnosis . ( $treatment ? ' treatment with ' . $treatment : ' treatment' );
		$seo_title = $name ? $diagnosis . ' Treatment Story: ' . $name . ' | UNI-ASIA' : $diagnosis . ( $treatment ? ' Treatment with ' . $treatment : ' Patient Story' ) . ' | UNI-ASIA';
	} elseif ( 'id' === $lang ) {
		$focus = 'Pengobatan ' . $diagnosis . ( $treatment ? ' dengan ' . $treatment : '' );
		$seo_title = $name ? 'Kisah Pengobatan ' . $diagnosis . ': ' . $name . ' | UNI-ASIA' : 'Pengobatan ' . $diagnosis . ( $treatment ? ' dengan ' . $treatment : '' ) . ' | UNI-ASIA';
	} elseif ( 'zh-cn' === $lang ) {
		$focus = $diagnosis . ( $treatment ?: '' ) . '治疗';
		$seo_title = ( $name ? $name : '' ) . $diagnosis . ( $treatment ?: '' ) . '治疗故事 | UNI-ASIA';
	} else {
		$focus = 'Điều trị ' . mb_strtolower( $diagnosis ) . ( $treatment ? ' bằng ' . mb_strtolower( $treatment ) : '' );
		$seo_title = $name ? 'Điều trị ' . mb_strtolower( $diagnosis ) . ': Câu chuyện của ' . $name . ' | UNI-ASIA' : 'Điều trị ' . mb_strtolower( $diagnosis ) . ( $treatment ? ' bằng ' . mb_strtolower( $treatment ) : '' ) . ' | UNI-ASIA';
	}
	$description = get_post_meta( $post->ID, 'uc_card_description', true );
	$description = $description ?: $post->post_excerpt;
	$description = $description ?: uc_seo_compact( $cleaned );
	$description = str_ireplace( array( $post->post_title, 'Chia sẻ tới:', 'Share to:', 'Bagikan ke:' ), '', $description );
	if ( ! uc_seo_compact( $description ) ) {
		$description = 'Câu chuyện điều trị ' . mb_strtolower( $diagnosis ) . ( $name ? ' của ' . $name : '' ) . ' tại Bệnh viện Ung thư UNI-ASIA, với phác đồ cá nhân hóa và kỹ thuật ít xâm lấn.';
	}
	update_post_meta( $post->ID, '_yoast_wpseo_focuskw', uc_seo_limit( $focus, 90 ) );
	update_post_meta( $post->ID, '_yoast_wpseo_title', uc_seo_limit( $seo_title, 60 ) );
	update_post_meta( $post->ID, '_yoast_wpseo_metadesc', uc_seo_limit( $description, 158 ) );
	// Trigger post-save integrations after the new Yoast fields are available.
	wp_update_post( array( 'ID' => $post->ID ) );
	$stats['yoast']++;
	$stats['posts']++;
}

echo wp_json_encode( $stats, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . PHP_EOL;
