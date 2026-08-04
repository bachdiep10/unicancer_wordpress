<?php
/**
 * Sidebar
 *
 * @package UNI_ASIA
 */
?>
<aside id="secondary" class="widget-area sidebar">
	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	<?php else : ?>
		<div class="widget widget-search">
			<h3 class="widget-title"><?php esc_html_e( 'Tìm kiếm', 'uniasia' ); ?></h3>
			<?php get_search_form(); ?>
		</div>

		<div class="widget widget-recent-posts">
			<h3 class="widget-title"><?php esc_html_e( 'Bài viết mới nhất', 'uniasia' ); ?></h3>
			<ul>
				<?php
				$recent = new WP_Query( array(
					'post_type'      => 'post',
					'posts_per_page' => 5,
				) );
				while ( $recent->have_posts() ) : $recent->the_post(); ?>
					<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
				<?php endwhile; wp_reset_postdata(); ?>
			</ul>
		</div>

		<div class="widget widget-cta">
			<h3 class="widget-title"><?php esc_html_e( 'Cần tư vấn?', 'uniasia' ); ?></h3>
			<p><?php esc_html_e( 'Liên hệ với chúng tôi để được tư vấn miễn phí.', 'uniasia' ); ?></p>
			<a href="#contact-form" class="btn btn-primary btn-block"><?php esc_html_e( 'Tư vấn ngay', 'uniasia' ); ?></a>
		</div>
	<?php endif; ?>
</aside>