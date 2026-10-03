<?php
$items = array();
foreach ( array( 'liao-zheng-yin', 'zhang-jin-shan', 'xiao-yue-yong', 'hu-xiao-kun' ) as $doctor_slug ) {
	$doctor = get_page_by_path( $doctor_slug, OBJECT, 'doctor' );
	if ( $doctor && 'publish' === $doctor->post_status ) { $items[] = $doctor; }
}
?>
<section class="ucb-section"><div class="ucb-heading"><div class="ucb-heading__copy"><h2><?php echo esc_html( uc_block_field( 'title', 'Đội ngũ MDT' ) ); ?></h2><p><?php echo esc_html( uc_block_field( 'description' ) ); ?></p></div><a href="<?php echo esc_url( uc_block_field( 'link_url', home_url( '/doctors/' ) ) ); ?>"><?php echo esc_html( uc_block_field( 'link_text', 'Thêm bác sĩ' ) ); ?> <b>→</b></a></div><div class="ucb-grid">
<?php foreach ( $items as $item ) : $doctor_name = trim( explode( '|', $item->post_title )[0] ); $description = uc_block_card_value( $item->ID, 'uc_card_description', wp_strip_all_tags( $item->post_content ) ); $subtitle = uc_block_card_value( $item->ID, 'uc_card_subtitle', $item->post_excerpt ); ?><article class="ucb-card ucb-card--doctor"><img src="<?php echo esc_url( uc_block_image( $item->ID ) ); ?>" alt="<?php echo esc_attr( $doctor_name ); ?>"><div class="ucb-card__body"><h3><?php echo esc_html( $doctor_name ); ?></h3><strong><?php echo esc_html( wp_trim_words( $subtitle, 16 ) ); ?></strong><p><?php echo esc_html( wp_trim_words( $description, 24 ) ); ?></p><a class="ucb-detail" href="<?php echo esc_url( home_url( '/doctors/' . $item->post_name . '/' ) ); ?>">Chi tiết</a></div></article><?php endforeach; ?>
</div></section>
