<?php
$treatment_cards = array(
	'intra-arterial-therapy' => array( 'title' => 'Kỹ thuật can thiệp qua động mạch', 'image' => 'mqgacfe7-08328f28.png' ),
	'nanoknife' => array( 'title' => 'Kỹ thuật tiêu hủy u bằng Dao Nano (IRE)', 'image' => 'mqgaddjx-68e70e4a.png' ),
	'iodine-125-seed-implantation' => array( 'title' => 'Kỹ thuật cấy hạt phóng xạ Iod-125', 'image' => 'mpmfi7hi-855f3e3e.jpg' ),
	'argon-helium-cryoablation' => array( 'title' => 'Kỹ thuật áp lạnh bằng dao Argon-Helium', 'image' => 'mqgae5lz-1f5e56e8.png' ),
);
$items = array();
foreach ( $treatment_cards as $slug => $card ) {
	$post = uc_block_post_by_legacy_slug( $slug, 'treatment' );
	if ( $post && 'publish' === $post->post_status ) { $items[] = array( 'post' => $post, 'card' => $card ); }
}
?>
<section class="ucb-section ucb-section--treatments"><div class="ucb-heading"><div class="ucb-heading__copy"><h2><?php echo esc_html( uc_block_field( 'title', 'Kỹ thuật điều trị' ) ); ?></h2><p><?php echo esc_html( uc_block_field( 'description' ) ); ?></p></div><a href="<?php echo esc_url( uc_block_field( 'link_url', home_url( '/phuong-phap-dieu-tri/' ) ) ); ?>"><?php echo esc_html( uc_block_field( 'link_text', 'Công nghệ điều trị' ) ); ?> <b>→</b></a></div><div class="ucb-grid">
<?php foreach ( $items as $entry ) : $item = $entry['post']; $card = $entry['card']; $description = uc_block_card_value( $item->ID, 'uc_card_description', $item->post_excerpt ?: wp_strip_all_tags( $item->post_content ) ); $image_url = get_template_directory_uri() . '/mirror/huan-ya.oss-ap-southeast-1.aliyuncs.com/media/' . $card['image']; ?><article class="ucb-card ucb-card--treatment"><a href="<?php echo esc_url( get_permalink( $item ) ); ?>"><img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $card['title'] ); ?>"><div class="ucb-card__body"><h3><?php echo esc_html( $card['title'] ); ?></h3><p><?php echo esc_html( wp_trim_words( $description, 24, '…' ) ); ?></p></div></a></article><?php endforeach; ?>
</div></section>
