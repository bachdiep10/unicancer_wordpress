<?php
/**
 * Template Part: FAQ Section
 *
 * @package UNI_ASIA
 */
$faqs = uniasia_get_faqs_grouped();
?>
<section class="faq-section section-padding" id="faqs">
	<div class="container">
		<div class="section-header">
			<span class="section-tag"><?php esc_html_e( 'Câu hỏi thường gặp', 'uniasia' ); ?></span>
			<h2 class="section-title">
				<?php esc_html_e( 'Giải đáp thắc mắc về phòng ngừa, chẩn đoán và điều trị ung thư', 'uniasia' ); ?>
			</h2>
			<p class="section-subtitle">
				<?php esc_html_e( 'Chúng tôi trả lời các câu hỏi thường gặp để giúp bạn hiểu rõ hơn và ứng phó với ung thư.', 'uniasia' ); ?>
			</p>
		</div>

		<?php if ( $faqs->have_posts() ) : ?>
			<div class="faq-accordion" itemscope itemtype="https://schema.org/FAQPage">
				<?php $i = 0; while ( $faqs->have_posts() ) : $faqs->the_post();
					$open = ( 0 === $i ) ? ' is-open' : '';
				?>
					<div class="faq-item<?php echo esc_attr( $open ); ?>" itemscope itemtype="https://schema.org/Question" itemprop="mainEntity">
						<button class="faq-question" type="button" aria-expanded="<?php echo $open ? 'true' : 'false'; ?>">
							<span class="faq-question-text" itemprop="name"><?php the_title(); ?></span>
							<span class="faq-question-icon">
								<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6z"/></svg>
							</span>
						</button>
						<div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
							<div class="faq-answer-inner" itemprop="text">
								<?php the_content(); ?>
							</div>
						</div>
					</div>
				<?php $i++; endwhile; wp_reset_postdata(); ?>
			</div>

			<div class="section-footer">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'faq' ) ); ?>" class="btn btn-outline">
					<?php esc_html_e( 'Xem tất cả câu hỏi', 'uniasia' ); ?>
				</a>
			</div>
		<?php else : ?>
			<div class="no-content">
				<p><?php esc_html_e( 'Chưa có FAQ nào. Vui lòng thêm trong Admin → FAQ.', 'uniasia' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>