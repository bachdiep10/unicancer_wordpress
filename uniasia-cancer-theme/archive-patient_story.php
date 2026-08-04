<?php
/**
 * Archive: Patient Story
 *
 * @package UNI_ASIA
 */
get_header();

$queried           = get_queried_object();
$current_term_slug = ( isset( $queried->slug ) ) ? $queried->slug : '';
$categories        = get_terms( array(
	'taxonomy'   => 'cancer_category',
	'hide_empty' => false,
) );
?>

<main id="main-content" class="site-main archive-stories">
	<div class="archive-header">
		<div class="container">
			<?php if ( function_exists( 'yoast_breadcrumb' ) ) yoast_breadcrumb(); ?>
			<h1 class="archive-title">
				<?php
				if ( is_tax() ) {
					single_term_title();
				} else {
					post_type_archive_title();
				}
				?>
			</h1>

			<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
				<div class="archive-filters">
					<a href="<?php echo esc_url( get_post_type_archive_link( 'patient_story' ) ); ?>" class="filter-btn <?php echo is_post_type_archive() ? 'active' : ''; ?>">
						<?php esc_html_e( 'Tất cả', 'uniasia' ); ?>
					</a>
					<?php foreach ( $categories as $cat ) : ?>
						<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="filter-btn <?php echo ( $current_term_slug === $cat->slug ) ? 'active' : ''; ?>">
							<?php echo esc_html( $cat->name ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="stories-grid">
				<?php $si = 0; while ( have_posts() ) : the_post();
					$patient_name = uniasia_field( 'story_patient_name' );
					$age          = uniasia_field( 'story_age' );
					$country      = uniasia_field( 'story_country' );
					$cancer_type  = uniasia_field( 'story_cancer_type' );
					$summary      = uniasia_field( 'story_summary' );
				?>
					<article class="story-card" itemscope itemtype="https://schema.org/MedicalCondition">
						<a href="<?php the_permalink(); ?>" class="story-card-link">
							<div class="story-card-image">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'uniasia-story' ); ?>
								<?php else : ?>
									<img src="<?php echo esc_url( uniasia_patient_image( $si % 4 ) ); ?>" alt="<?php echo esc_attr( $patient_name ?: get_the_title() ); ?>" loading="lazy">
								<?php endif; ?>
							</div>

							<div class="story-card-body">
								<?php if ( $cancer_type ) : ?>
									<span class="story-card-tag"><?php echo esc_html( $cancer_type ); ?></span>
								<?php endif; ?>

								<h2 class="story-card-name" itemprop="name">
									<?php echo $patient_name ? esc_html( $patient_name ) : the_title(); ?>
								</h2>

								<div class="story-card-meta">
									<?php if ( $country ) : ?>
										<span><?php echo esc_html( $country ); ?></span>
									<?php endif; ?>
									<?php if ( $age ) : ?>
										<span><?php printf( esc_html__( '%d tuổi', 'uniasia' ), (int) $age ); ?></span>
									<?php endif; ?>
								</div>

								<?php if ( $summary ) : ?>
									<p class="story-card-excerpt" itemprop="description">
										<?php echo esc_html( uniasia_truncate( $summary, 150 ) ); ?>
									</p>
								<?php endif; ?>

								<span class="story-card-action">
									<?php esc_html_e( 'Đọc câu chuyện', 'uniasia' ); ?>
								</span>
							</div>
						</a>
					</article>
				<?php $si++; endwhile; ?>
			</div>

			<?php uniasia_pagination(); ?>
		<?php else : ?>
			<div class="no-content">
				<p><?php esc_html_e( 'Chưa có câu chuyện nào.', 'uniasia' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();