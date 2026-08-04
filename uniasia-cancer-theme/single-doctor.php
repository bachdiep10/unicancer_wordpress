<?php
/**
 * Single: Doctor
 *
 * @package UNI_ASIA
 */
get_header();

while ( have_posts() ) : the_post();
	$degree      = uniasia_field( 'doctor_degree' );
	$position    = uniasia_field( 'doctor_position' );
	$experience  = uniasia_field( 'doctor_experience' );
	$hospital    = uniasia_field( 'doctor_hospital' );
	$education   = uniasia_field( 'doctor_education' );
	$specialties = uniasia_field( 'doctor_specialties' );
	$bio         = uniasia_field( 'doctor_short_bio' );
	$languages   = uniasia_field( 'doctor_languages' );
?>

<main id="main-content" class="site-main single-doctor" itemscope itemtype="https://schema.org/Physician">
	<div class="container">
		<?php if ( function_exists( 'yoast_breadcrumb' ) ) yoast_breadcrumb(); ?>

		<div class="doctor-detail-grid">
			<aside class="doctor-detail-sidebar">
				<div class="doctor-detail-image">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'uniasia-doctor', array( 'itemprop' => 'image' ) ); ?>
					<?php else : ?>
						<?php
						$idx = get_the_ID() % 6;
						?>
						<img src="<?php echo esc_url( uniasia_doctor_photo( $idx ) ); ?>" alt="<?php the_title_attribute(); ?>" itemprop="image">
					<?php endif; ?>
				</div>

				<div class="doctor-detail-meta">
					<?php if ( $experience ) : ?>
						<div class="meta-item">
							<strong><?php esc_html_e( 'Kinh nghiệm:', 'uniasia' ); ?></strong>
							<span><?php
								printf(
									/* translators: %d: number of years */
									esc_html__( '%d năm', 'uniasia' ),
									(int) $experience
								);
							?></span>
						</div>
					<?php endif; ?>

					<?php if ( $hospital ) : ?>
						<div class="meta-item">
							<strong><?php esc_html_e( 'Cơ sở:', 'uniasia' ); ?></strong>
							<span><?php echo esc_html( $hospital ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( $languages ) : ?>
						<div class="meta-item">
							<strong><?php esc_html_e( 'Ngôn ngữ:', 'uniasia' ); ?></strong>
							<span><?php echo esc_html( $languages ); ?></span>
						</div>
					<?php endif; ?>
				</div>

				<a href="#contact-form" class="btn btn-primary btn-block">
					<?php esc_html_e( 'Đặt lịch tư vấn', 'uniasia' ); ?>
				</a>
			</aside>

			<div class="doctor-detail-content">
				<header class="doctor-detail-header">
					<?php if ( $degree ) : ?>
						<div class="doctor-detail-degree" itemprop="jobTitle"><?php echo esc_html( $degree ); ?></div>
					<?php endif; ?>

					<h1 class="doctor-detail-name" itemprop="name"><?php the_title(); ?></h1>

					<?php if ( $position ) : ?>
						<div class="doctor-detail-position" itemprop="worksFor">
							<?php echo esc_html( $position ); ?>
						</div>
					<?php endif; ?>
				</header>

				<?php if ( $bio ) : ?>
					<section class="doctor-detail-section">
						<h2 class="section-title-sm"><?php esc_html_e( 'Giới thiệu', 'uniasia' ); ?></h2>
						<p class="doctor-detail-bio" itemprop="description"><?php echo nl2br( esc_html( $bio ) ); ?></p>
					</section>
				<?php endif; ?>

				<?php if ( $education ) : ?>
					<section class="doctor-detail-section">
						<h2 class="section-title-sm"><?php esc_html_e( 'Học vị & Đào tạo', 'uniasia' ); ?></h2>
						<p><?php echo nl2br( esc_html( $education ) ); ?></p>
					</section>
				<?php endif; ?>

				<?php if ( $specialties ) : ?>
					<section class="doctor-detail-section">
						<h2 class="section-title-sm"><?php esc_html_e( 'Lĩnh vực chuyên môn', 'uniasia' ); ?></h2>
						<p itemprop="medicalSpecialty"><?php echo nl2br( esc_html( $specialties ) ); ?></p>
					</section>
				<?php endif; ?>

				<section class="doctor-detail-section">
					<h2 class="section-title-sm"><?php esc_html_e( 'Thông tin chi tiết', 'uniasia' ); ?></h2>
					<div class="doctor-content">
						<?php the_content(); ?>
					</div>
				</section>
			</div>
		</div>

		<section class="related-doctors">
			<h2 class="section-title-sm"><?php esc_html_e( 'Bác sĩ khác', 'uniasia' ); ?></h2>
			<?php
			$related = new WP_Query( array(
				'post_type'      => 'doctor',
				'posts_per_page' => 4,
				'post__not_in'   => array( get_the_ID() ),
				'orderby'        => 'rand',
			) );

			if ( $related->have_posts() ) : ?>
				<div class="doctors-grid">
					<?php while ( $related->have_posts() ) : $related->the_post(); ?>
						<article class="doctor-card">
							<a href="<?php the_permalink(); ?>" class="doctor-card-link">
								<div class="doctor-card-image">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail( 'uniasia-doctor' ); ?>
									<?php endif; ?>
								</div>
								<div class="doctor-card-body">
									<h3 class="doctor-card-name"><?php the_title(); ?></h3>
								</div>
							</a>
						</article>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			<?php endif; ?>
		</section>
	</div>
</main>

<?php
endwhile;
get_footer();