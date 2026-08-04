<?php
/**
 * 404 Template
 *
 * @package UNI_ASIA
 */
get_header();
?>

<main id="main-content" class="site-main error-404">
	<div class="container">
		<div class="error-404-content">
			<div class="error-404-code">404</div>
			<h1 class="error-404-title"><?php esc_html_e( 'Không tìm thấy trang', 'uniasia' ); ?></h1>
			<p class="error-404-desc">
				<?php esc_html_e( 'Trang bạn đang tìm kiếm không tồn tại hoặc đã được di chuyển.', 'uniasia' ); ?>
			</p>

			<div class="error-404-actions">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
					<?php esc_html_e( 'Về trang chủ', 'uniasia' ); ?>
				</a>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline">
					<?php esc_html_e( 'Liên hệ với chúng tôi', 'uniasia' ); ?>
				</a>
			</div>

			<?php get_search_form(); ?>
		</div>
	</div>
</main>

<?php
get_footer();