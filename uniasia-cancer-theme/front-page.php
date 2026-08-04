<?php
/**
 * Front Page Template (Trang chủ)
 *
 * @package UNI_ASIA
 */
get_header();
?>

<main id="main-content" class="site-main front-page">
	<?php
	get_template_part( 'template-parts/section', 'hero' );
	get_template_part( 'template-parts/section', 'why-choose' );
	get_template_part( 'template-parts/section', 'mdt-team' );
	get_template_part( 'template-parts/section', 'cancer-types' );
	get_template_part( 'template-parts/section', 'treatment-tech' );
	get_template_part( 'template-parts/section', 'patient-stories' );
	get_template_part( 'template-parts/section', 'international-guide' );
	get_template_part( 'template-parts/section', 'faqs' );
	get_template_part( 'template-parts/section', 'contact-form' );
	?>
</main>

<?php
get_footer();