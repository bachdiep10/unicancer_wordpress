<?php
$title       = uc_block_field( 'title', 'Câu chuyện bệnh nhân' );
$description = uc_block_field( 'description' );
$story_cards = array(
	'liver-cancer-tace-treatment-patient-lw' => array( 'name' => 'Lão Vương', 'disease' => 'Ung thư gan', 'country' => 'Trung Quốc', 'image' => 'ms3eqalv-53930cb8.jpg' ),
	'lung-cancer-tace-treatment-patient-wxc' => array( 'name' => 'Wang Xiaocai', 'disease' => 'Ung thư phổi', 'country' => 'Trung Quốc', 'image' => 'mreretiq-76b2e192.jpg' ),
	'liver-cancer-tace-treatment-patient-lhy' => array( 'name' => 'Liu Jianjun', 'disease' => 'Ung thư gan', 'country' => 'Trung Quốc', 'image' => 'mr37w1jn-4c0d8553.jpg' ),
	'bladder-cancer-tace-treatment-patient-cd' => array( 'name' => 'Chen De', 'disease' => 'Ung thư bàng quang', 'country' => 'Trung Quốc', 'image' => 'mr1tz4v6-b8951a7b.jpg' ),
);
$items = array();
foreach ( $story_cards as $slug => $card ) {
	$post = uc_block_post_by_legacy_slug( $slug, 'patient_story' );
	if ( $post && 'publish' === $post->post_status ) { $items[] = array( 'post' => $post, 'card' => $card ); }
}
?>
<section class="ucb-section ucb-section--stories"><div class="ucb-heading"><div class="ucb-heading__copy"><h2><?php echo esc_html( $title ); ?></h2><p><?php echo esc_html( $description ); ?></p></div><a href="<?php echo esc_url( uc_block_field( 'link_url', home_url( '/cau-chuyen-benh-nhan/' ) ) ); ?>"><?php echo esc_html( uc_block_field( 'link_text', 'Thêm ca bệnh' ) ); ?> <b>→</b></a></div><div class="ucb-grid ucb-stories">
<?php foreach ( $items as $entry ) : $item = $entry['post']; $card = $entry['card']; $card_description = uc_block_card_value( $item->ID, 'uc_card_description', $item->post_excerpt ?: wp_strip_all_tags( $item->post_content ) ); $image_url = get_template_directory_uri() . '/mirror/huan-ya.oss-ap-southeast-1.aliyuncs.com/media/' . $card['image']; ?><article class="ucb-card"><a href="<?php echo esc_url( get_permalink( $item ) ); ?>"><img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $card['name'] ); ?>"><div class="ucb-card__tag"><?php echo esc_html( $card['disease'] ); ?></div><div class="ucb-card__body"><div class="ucb-story-name"><h3><?php echo esc_html( $card['name'] ); ?></h3><span><?php echo esc_html( $card['country'] ); ?></span></div><p><?php echo esc_html( wp_trim_words( $card_description, 25, '…' ) ); ?></p></div></a></article><?php endforeach; ?>
</div></section>
