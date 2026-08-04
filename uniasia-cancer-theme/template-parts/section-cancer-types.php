<?php
/**
 * Template Part: Cancer Types Section
 *
 * @package UNI_ASIA
 */
$cancers = uniasia_get_cancer_types( array( 'posts_per_page' => 8 ) );
?>
<section class="cancer-types-section section-padding" id="cancer-types">
	<div class="container">
		<div class="section-header">
			<span class="section-tag"><?php esc_html_e( 'Phân loại ung bướu', 'uniasia' ); ?></span>
			<h2 class="section-title">
				<?php esc_html_e( 'Hầu hết các khối u ác tính ở giai đoạn sớm không có triệu chứng rõ ràng', 'uniasia' ); ?>
			</h2>
			<p class="section-subtitle">
				<?php esc_html_e( 'Phát hiện sớm, chẩn đoán sớm và điều trị sớm là chiến lược then chốt để chiến thắng bệnh ung thư.', 'uniasia' ); ?>
			</p>
		</div>

		<?php if ( $cancers->have_posts() ) : ?>
			<div class="cancer-types-grid">
				<?php
				$ci = 0;
				while ( $cancers->have_posts() ) : $cancers->the_post();
					$color = uniasia_field( 'cancer_color', '#0066a4' );
					$icon  = uniasia_field( 'cancer_icon' );
					if ( ! $icon ) {
						$icon = uniasia_cancer_image( get_post_field( 'post_name', get_the_ID() ) );
					}
				?>
					<a href="<?php the_permalink(); ?>" class="cancer-type-card" style="--cancer-color: <?php echo esc_attr( $color ); ?>">
						<div class="cancer-type-icon">
							<img src="<?php echo esc_url( $icon ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
						</div>
						<h3 class="cancer-type-name"><?php the_title(); ?></h3>
						<div class="cancer-type-link">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
						</div>
					</a>
				<?php $ci++; endwhile; wp_reset_postdata(); ?>
			</div>

			<div class="section-footer">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'cancer_type' ) ); ?>" class="btn btn-outline">
					<?php esc_html_e( 'Xem thêm loại ung thư', 'uniasia' ); ?>
				</a>
			</div>
		<?php else : ?>
			<div class="no-content">
				<p><?php esc_html_e( 'Chưa có loại ung thư nào. Vui lòng thêm trong Admin → Loại ung thư.', 'uniasia' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>