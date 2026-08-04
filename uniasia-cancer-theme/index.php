<?php
/**
 * Index Template (Fallback)
 *
 * @package UNI_ASIA
 */
get_header();
?>

<main id="main-content" class="site-main index-page">
	<div class="container">
		<?php if ( is_home() && ! is_front_page() ) : ?>
			<h1 class="page-title"><?php single_post_title(); ?></h1>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="posts-grid">
				<?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="post-card-image">
								<?php the_post_thumbnail( 'uniasia-thumb' ); ?>
							</a>
						<?php endif; ?>

						<div class="post-card-body">
							<h2 class="post-card-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>
							<div class="post-card-excerpt"><?php the_excerpt(); ?></div>
							<a href="<?php the_permalink(); ?>" class="post-card-link">
								<?php esc_html_e( 'Đọc tiếp', 'uniasia' ); ?>
							</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<?php uniasia_pagination(); ?>
		<?php else : ?>
			<div class="no-content">
				<h2><?php esc_html_e( 'Không tìm thấy nội dung', 'uniasia' ); ?></h2>
				<p><?php esc_html_e( 'Vui lòng quay lại sau.', 'uniasia' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();