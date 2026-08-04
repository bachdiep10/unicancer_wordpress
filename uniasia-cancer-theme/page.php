<?php
/**
 * Default Page Template
 *
 * @package UNI_ASIA
 */
get_header();
?>

<main id="main-content" class="site-main page-default">
	<div class="container">
		<div class="page-header">
			<?php if ( function_exists( 'yoast_breadcrumb' ) ) : ?>
				<div class="breadcrumbs"><?php yoast_breadcrumb(); ?></div>
			<?php endif; ?>
			<h1 class="page-title"><?php the_title(); ?></h1>
		</div>

		<article class="page-content">
			<?php
			while ( have_posts() ) : the_post();
				the_content();
			endwhile;
			?>
		</article>
	</div>
</main>

<?php
get_footer();