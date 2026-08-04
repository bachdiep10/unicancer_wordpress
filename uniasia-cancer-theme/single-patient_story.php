<?php
/**
 * Single: Patient Story
 *
 * @package UNI_ASIA
 */
get_header();

while ( have_posts() ) : the_post();
	$patient_name = uniasia_field( 'story_patient_name' );
	$age          = uniasia_field( 'story_age' );
	$country      = uniasia_field( 'story_country' );
	$cancer_type  = uniasia_field( 'story_cancer_type' );
	$treatment    = uniasia_field( 'story_treatment' );
	$summary      = uniasia_field( 'story_summary' );
	$video_url    = uniasia_field( 'story_video_url' );
?>

<main id="main-content" class="site-main single-story" itemscope itemtype="https://schema.org/MedicalCondition">
	<div class="container">
		<?php if ( function_exists( 'yoast_breadcrumb' ) ) yoast_breadcrumb(); ?>

		<article class="story-detail">
			<header class="story-detail-header">
				<?php if ( $cancer_type ) : ?>
					<span class="story-detail-tag"><?php echo esc_html( $cancer_type ); ?></span>
				<?php endif; ?>

				<h1 class="story-detail-title" itemprop="name">
					<?php echo $patient_name ? esc_html( $patient_name ) : the_title(); ?>
				</h1>

				<div class="story-detail-meta">
					<?php if ( $country ) : ?>
						<span class="meta-item">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
							<?php echo esc_html( $country ); ?>
						</span>
					<?php endif; ?>

					<?php if ( $age ) : ?>
						<span class="meta-item">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
							<?php printf( esc_html__( '%d tuổi', 'uniasia' ), (int) $age ); ?>
						</span>
					<?php endif; ?>

					<?php if ( $treatment ) : ?>
						<span class="meta-item">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M19 8l-4 4h3c0 3.31-2.69 6-6 6-1.01 0-1.97-.25-2.8-.7l-1.46 1.46C8.97 19.54 10.43 20 12 20c4.42 0 8-3.58 8-8h3l-4-4z"/></svg>
							<?php echo esc_html( $treatment ); ?>
						</span>
					<?php endif; ?>
				</div>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="story-detail-image">
					<?php the_post_thumbnail( 'large' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $video_url ) : ?>
				<div class="story-detail-video">
					<?php
					if ( strpos( $video_url, 'youtube' ) !== false ) {
						preg_match( '/[?&]v=([^&]+)/', $video_url, $matches );
						$video_id = $matches[1] ?? '';
						if ( $video_id ) {
							echo '<iframe src="https://www.youtube.com/embed/' . esc_attr( $video_id ) . '" frameborder="0" allowfullscreen></iframe>';
						}
					} elseif ( strpos( $video_url, 'vimeo' ) !== false ) {
						preg_match( '/vimeo\.com\/(\d+)/', $video_url, $matches );
						$video_id = $matches[1] ?? '';
						if ( $video_id ) {
							echo '<iframe src="https://player.vimeo.com/video/' . esc_attr( $video_id ) . '" frameborder="0" allowfullscreen></iframe>';
						}
					}
					?>
				</div>
			<?php endif; ?>

			<div class="story-detail-content" itemprop="description">
				<?php the_content(); ?>
			</div>

			<div class="story-detail-cta">
				<h3><?php esc_html_e( 'Bạn hoặc người thân đang gặp tình trạng tương tự?', 'uniasia' ); ?></h3>
				<a href="#contact-form" class="btn btn-primary btn-lg">
					<?php esc_html_e( 'Tư vấn miễn phí', 'uniasia' ); ?>
				</a>
			</div>
		</article>

		<?php
		$related = new WP_Query( array(
			'post_type'      => 'patient_story',
			'posts_per_page' => 3,
			'post__not_in'   => array( get_the_ID() ),
			'orderby'        => 'rand',
		) );

		if ( $related->have_posts() ) : ?>
			<section class="related-stories">
				<h2 class="section-title"><?php esc_html_e( 'Câu chuyện khác', 'uniasia' ); ?></h2>
				<div class="stories-grid">
					<?php while ( $related->have_posts() ) : $related->the_post();
						$rn = uniasia_field( 'story_patient_name' );
						$rc = uniasia_field( 'story_cancer_type' );
					?>
						<article class="story-card">
							<a href="<?php the_permalink(); ?>" class="story-card-link">
								<?php if ( has_post_thumbnail() ) : ?>
									<div class="story-card-image"><?php the_post_thumbnail( 'uniasia-story' ); ?></div>
								<?php endif; ?>
								<div class="story-card-body">
									<?php if ( $rc ) : ?>
										<span class="story-card-tag"><?php echo esc_html( $rc ); ?></span>
									<?php endif; ?>
									<h3 class="story-card-name"><?php echo $rn ? esc_html( $rn ) : the_title(); ?></h3>
								</div>
							</a>
						</article>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			</section>
		<?php endif; ?>
	</div>
</main>

<?php
endwhile;
get_footer();