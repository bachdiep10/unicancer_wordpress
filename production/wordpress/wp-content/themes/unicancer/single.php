<?php
/** Database-backed single content template. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
while ( have_posts() ) {
	the_post();
	status_header( 200 );
	echo unicancer_render_wordpress_page( get_post() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
