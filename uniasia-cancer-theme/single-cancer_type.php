<?php
/**
 * Single: Cancer Type
 *
 * @package UNI_ASIA
 */
get_header();

while ( have_posts() ) : the_post();
	$symptoms  = uniasia_field( 'cancer_symptoms' );
	$diagnosis = uniasia_field( 'cancer_diagnosis' );
	$treatment = uniasia_field( 'cancer_treatment' );
	$color     = uniasia_field( 'cancer_color', '#0066a4' );
?>

<main id="main-content" class="site-main single-cancer-type">
	<div class="container">
		<?php if ( function_exists( 'yoast_breadcrumb' ) ) yoast_breadcrumb(); ?>

		<header class="cancer-detail-header" style="--cancer-color: <?php echo esc_attr( $color ); ?>">
			<h1 class="cancer-detail-title"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<div class="cancer-detail-excerpt"><?php the_excerpt(); ?></div>
			<?php endif; ?>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="cancer-detail-image"><?php the_post_thumbnail( 'large' ); ?></div>
		<?php endif; ?>

		<div class="cancer-detail-grid">
			<div class="cancer-detail-content">
				<section class="cancer-section">
					<h2 class="section-title-sm"><?php esc_html_e( 'Tổng quan', 'uniasia' ); ?></h2>
					<div class="cancer-content"><?php the_content(); ?></div>
				</section>

				<?php if ( $symptoms ) : ?>
					<section class="cancer-section">
						<h2 class="section-title-sm"><?php esc_html_e( 'Triệu chứng', 'uniasia' ); ?></h2>
						<div class="cancer-content"><?php echo wp_kses_post( $symptoms ); ?></div>
					</section>
				<?php endif; ?>

				<?php if ( $diagnosis ) : ?>
					<section class="cancer-section">
						<h2 class="section-title-sm"><?php esc_html_e( 'Chẩn đoán', 'uniasia' ); ?></h2>
						<div class="cancer-content"><?php echo wp_kses_post( $diagnosis ); ?></div>
					</section>
				<?php endif; ?>

				<?php if ( $treatment ) : ?>
					<section class="cancer-section">
						<h2 class="section-title-sm"><?php esc_html_e( 'Phương pháp điều trị', 'uniasia' ); ?></h2>
						<div class="cancer-content"><?php echo wp_kses_post( $treatment ); ?></div>
					</section>
				<?php endif; ?>
			</div>

			<aside class="cancer-detail-sidebar">
				<div class="cancer-cta-box">
					<h3><?php esc_html_e( 'Cần tư vấn?', 'uniasia' ); ?></h3>
					<p><?php esc_html_e( 'Liên hệ ngay để được chuyên gia tư vấn miễn phí.', 'uniasia' ); ?></p>
					<a href="#contact-form" class="btn btn-primary btn-block">
						<?php esc_html_e( 'Đặt lịch tư vấn', 'uniasia' ); ?>
					</a>
				</div>
			</aside>
		</div>
	</div>
</main>

<?php
endwhile;
get_footer();