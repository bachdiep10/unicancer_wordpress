<?php
/**
 * Archive: Technology
 *
 * @package UNI_ASIA
 */
get_header();
?>

<main id="main-content" class="site-main archive-technology">
	<div class="archive-header">
		<div class="container">
			<?php if ( function_exists( 'yoast_breadcrumb' ) ) yoast_breadcrumb(); ?>
			<h1 class="archive-title"><?php post_type_archive_title(); ?></h1>
		</div>
	</div>

	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="technology-grid">
				<?php while ( have_posts() ) : the_post();
					$short = uniasia_field( 'tech_short_name' );
					$icon  = uniasia_tech_icon( get_post_field( 'post_name', get_the_ID() ) );
				?>
					<a href="<?php the_permalink(); ?>" class="technology-card">
						<div class="technology-image">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'uniasia-thumb' ); ?>
							<?php else : ?>
								<img src="<?php echo esc_url( $icon ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
							<?php endif; ?>
						</div>

						<div class="technology-body">
							<?php if ( $short ) : ?>
								<span class="technology-short"><?php echo esc_html( $short ); ?></span>
							<?php endif; ?>
							<h2 class="technology-name"><?php the_title(); ?></h2>
							<div class="technology-excerpt"><?php the_excerpt(); ?></div>
						</div>
					</a>
				<?php endwhile; ?>
			</div>

			<?php uniasia_pagination(); ?>
		<?php else : ?>
			<div class="no-content">
				<p><?php esc_html_e( 'Chưa có kỹ thuật nào.', 'uniasia' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();