<?php
/**
 * Main template: serves the corresponding migrated page.
 *
 * @package Unicancer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$mirror_file = unicancer_mirror_file();

if ( $mirror_file ) {
	status_header( 200 );
	echo unicancer_render_mirror( $mirror_file ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

status_header( 404 );
get_header();
?>
<main class="unicancer-not-found">
	<div class="unicancer-not-found__inner">
		<h1><?php esc_html_e( 'Không tìm thấy trang', 'unicancer' ); ?></h1>
		<p><?php esc_html_e( 'Trang bạn yêu cầu không tồn tại hoặc đã được chuyển.', 'unicancer' ); ?></p>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Về trang chủ', 'unicancer' ); ?></a>
	</div>
</main>
<?php
get_footer();

