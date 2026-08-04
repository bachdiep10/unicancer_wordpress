<?php
/**
 * Single: Technology
 *
 * @package UNI_ASIA
 */
get_header();

while ( have_posts() ) : the_post();
	$short_name = uniasia_field( 'tech_short_name' );
	$full_name  = uniasia_field( 'tech_full_name' );
	$summary    = uniasia_field( 'tech_summary' );
?>

<main id="main-content" class="site-main single-technology">
	<div class="container">
		<?php if ( function_exists( 'yoast_breadcrumb' ) ) yoast_breadcrumb(); ?>

		<header class="technology-detail-header">
			<?php if ( $short_name ) : ?>
				<span class="technology-detail-short"><?php echo esc_html( $short_name ); ?></span>
			<?php endif; ?>

			<h1 class="technology-detail-title"><?php the_title(); ?></h1>

			<?php if ( $full_name ) : ?>
				<div class="technology-detail-full"><?php echo esc_html( $full_name ); ?></div>
			<?php endif; ?>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="technology-detail-image"><?php the_post_thumbnail( 'large' ); ?></div>
		<?php endif; ?>

		<div class="technology-detail-grid">
			<div class="technology-detail-content">
				<?php if ( $summary ) : ?>
					<section class="technology-summary-section">
						<h2 class="section-title-sm"><?php esc_html_e( 'Tổng quan', 'uniasia' ); ?></h2>
						<div class="technology-summary"><?php echo wp_kses_post( wpautop( $summary ) ); ?></div>
					</section>
				<?php endif; ?>

				<section class="technology-detail-section">
					<h2 class="section-title-sm"><?php esc_html_e( 'Chi tiết kỹ thuật', 'uniasia' ); ?></h2>
					<div class="technology-content"><?php the_content(); ?></div>
				</section>

				<div class="technology-cta">
					<h3><?php esc_html_e( 'Tìm hiểu thêm về kỹ thuật này?', 'uniasia' ); ?></h3>
					<a href="#contact-form" class="btn btn-primary btn-lg">
						<?php esc_html_e( 'Tư vấn với chuyên gia', 'uniasia' ); ?>
					</a>
				</div>
			</div>

			<aside class="technology-detail-sidebar">
				<div class="technology-meta-box">
					<h3><?php esc_html_e( 'Thông tin nhanh', 'uniasia' ); ?></h3>
					<div class="meta-item">
						<strong><?php esc_html_e( 'Mã:', 'uniasia' ); ?></strong>
						<span><?php echo esc_html( $short_name ?: '—' ); ?></span>
					</div>
					<div class="meta-item">
						<strong><?php esc_html_e( 'Tên đầy đủ:', 'uniasia' ); ?></strong>
						<span><?php echo esc_html( $full_name ?: '—' ); ?></span>
					</div>
				</div>
			</aside>
		</div>
	</div>
</main>

<?php
endwhile;
get_footer();