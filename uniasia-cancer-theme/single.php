<?php
/**
 * Single Post (default)
 *
 * @package UNI_ASIA
 */
get_header();
?>

<main id="main-content" class="site-main single-post">
	<div class="container">
		<?php
		while ( have_posts() ) : the_post();
			if ( function_exists( 'yoast_breadcrumb' ) ) yoast_breadcrumb();
		?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="https://schema.org/Article">
				<header class="entry-header">
					<h1 class="entry-title" itemprop="headline"><?php the_title(); ?></h1>
					<div class="entry-meta">
						<time class="entry-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" itemprop="datePublished">
							<?php echo get_the_date(); ?>
						</time>
						<span class="entry-author" itemprop="author">
							<?php the_author(); ?>
						</span>
					</div>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="entry-image" itemprop="image">
						<?php the_post_thumbnail( 'large' ); ?>
					</div>
				<?php endif; ?>

				<div class="entry-content" itemprop="articleBody">
					<?php
					the_content();
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Trang:', 'uniasia' ),
						'after'  => '</div>',
					) );
					?>
				</div>

				<footer class="entry-footer">
					<?php
					$tags_list = get_the_tag_list( '', ', ' );
					if ( $tags_list ) : ?>
						<div class="tags-links">
							<strong><?php esc_html_e( 'Tags:', 'uniasia' ); ?></strong>
							<?php echo $tags_list; ?>
						</div>
					<?php endif; ?>
				</footer>
			</article>

			<?php
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			?>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();