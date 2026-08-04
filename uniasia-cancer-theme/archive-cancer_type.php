<?php
/**
 * Archive: Cancer Type
 *
 * @package UNI_ASIA
 */
get_header();
?>

<main id="main-content" class="site-main archive-cancer-type">
	<div class="archive-header">
		<div class="container">
			<?php if ( function_exists( 'yoast_breadcrumb' ) ) yoast_breadcrumb(); ?>
			<h1 class="archive-title"><?php post_type_archive_title(); ?></h1>
		</div>
	</div>

	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="cancer-types-grid">
				<?php while ( have_posts() ) : the_post();
					$color = uniasia_field( 'cancer_color', '#0066a4' );
					$img = uniasia_cancer_image( get_post_field( 'post_name', get_the_ID() ) );
				?>
					<a href="<?php the_permalink(); ?>" class="cancer-type-card" style="--cancer-color: <?php echo esc_attr( $color ); ?>">
						<div class="cancer-type-image">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'uniasia-thumb' ); ?>
							<?php else : ?>
								<img src="<?php echo esc_url( $img ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
							<?php endif; ?>
						</div>
						<h2 class="cancer-type-name"><?php the_title(); ?></h2>
						<div class="cancer-type-excerpt"><?php the_excerpt(); ?></div>
					</a>
				<?php endwhile; ?>
			</div>

			<?php uniasia_pagination(); ?>
		<?php else : ?>
			<div class="no-content">
				<p><?php esc_html_e( 'Chưa có loại ung thư nào.', 'uniasia' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();