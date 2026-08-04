<?php
/**
 * Archive: FAQ
 *
 * @package UNI_ASIA
 */
get_header();
?>

<main id="main-content" class="site-main archive-faq">
	<div class="archive-header">
		<div class="container">
			<?php if ( function_exists( 'yoast_breadcrumb' ) ) yoast_breadcrumb(); ?>
			<h1 class="archive-title"><?php post_type_archive_title(); ?></h1>
		</div>
	</div>

	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="faq-accordion" itemscope itemtype="https://schema.org/FAQPage">
				<?php $i = 0; while ( have_posts() ) : the_post();
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
				<?php $i++; endwhile; ?>
			</div>
		<?php else : ?>
			<div class="no-content">
				<p><?php esc_html_e( 'Chưa có FAQ nào.', 'uniasia' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();