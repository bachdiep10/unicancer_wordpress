<?php
/**
 * Single FAQ
 *
 * @package UNI_ASIA
 */
get_header();
?>

<main id="main-content" class="site-main single-faq" itemscope itemtype="https://schema.org/Question">
	<div class="container">
		<?php if ( function_exists( 'yoast_breadcrumb' ) ) yoast_breadcrumb(); ?>

		<article class="faq-detail">
			<header class="faq-detail-header">
				<span class="faq-detail-tag"><?php
					$terms = get_the_terms( get_the_ID(), 'faq_group' );
					if ( $terms && ! is_wp_error( $terms ) ) {
						echo esc_html( $terms[0]->name );
					}
				?></span>

				<h1 class="faq-detail-title" itemprop="name"><?php the_title(); ?></h1>
			</header>

			<div class="faq-detail-content" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
				<div class="faq-detail-answer" itemprop="text">
					<?php the_content(); ?>
				</div>
			</div>

			<div class="faq-detail-cta">
				<h3><?php esc_html_e( 'Bạn có câu hỏi khác?', 'uniasia' ); ?></h3>
				<p><?php esc_html_e( 'Liên hệ với chúng tôi để được tư vấn miễn phí.', 'uniasia' ); ?></p>
				<a href="#contact-form" class="btn btn-primary">
					<?php esc_html_e( 'Đặt câu hỏi', 'uniasia' ); ?>
				</a>
			</div>
		</article>

		<section class="related-faqs">
			<h2 class="section-title-sm"><?php esc_html_e( 'Câu hỏi liên quan', 'uniasia' ); ?></h2>

			<?php
			$related = new WP_Query( array(
				'post_type'      => 'faq',
				'posts_per_page' => 5,
				'post__not_in'   => array( get_the_ID() ),
				'orderby'        => 'rand',
			) );

			if ( $related->have_posts() ) : ?>
				<div class="faq-accordion">
					<?php while ( $related->have_posts() ) : $related->the_post(); ?>
						<div class="faq-item" itemscope itemtype="https://schema.org/Question" itemprop="mainEntity">
							<button class="faq-question" type="button" aria-expanded="false">
								<span class="faq-question-text" itemprop="name"><?php the_title(); ?></span>
								<span class="faq-question-icon">
									<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6z"/></svg>
								</span>
							</button>
							<div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
								<div class="faq-answer-inner" itemprop="text"><?php the_content(); ?></div>
							</div>
						</div>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			<?php endif; ?>
		</section>
	</div>
</main>

<?php
get_footer();