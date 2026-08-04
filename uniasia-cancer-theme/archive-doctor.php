<?php
/**
 * Archive: Doctor
 *
 * @package UNI_ASIA
 */
get_header();
?>

<main id="main-content" class="site-main archive-doctor">
	<div class="archive-header">
		<div class="container">
			<?php if ( function_exists( 'yoast_breadcrumb' ) ) yoast_breadcrumb(); ?>
			<h1 class="archive-title">
				<?php post_type_archive_title(); ?>
			</h1>
			<p class="archive-subtitle">
				<?php esc_html_e( 'Quy tụ các chuyên gia giàu kinh nghiệm hàng đầu Trung Quốc, dẫn đầu điều trị ít xâm lấn, chia sẻ cùng thế giới.', 'uniasia' ); ?>
			</p>
		</div>
	</div>

	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="doctors-grid">
				<?php $di = 0; while ( have_posts() ) : the_post();
					$degree   = uniasia_field( 'doctor_degree' );
					$position = uniasia_field( 'doctor_position' );
					$bio      = uniasia_field( 'doctor_short_bio' );
				?>
					<article class="doctor-card" itemscope itemtype="https://schema.org/Physician">
						<a href="<?php the_permalink(); ?>" class="doctor-card-link">
							<div class="doctor-card-image">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'uniasia-doctor', array( 'itemprop' => 'image' ) ); ?>
								<?php else : ?>
									<img src="<?php echo esc_url( uniasia_doctor_photo( $di % 6 ) ); ?>" alt="<?php the_title_attribute(); ?>" itemprop="image">
								<?php endif; ?>
							</div>

							<div class="doctor-card-body">
								<?php if ( $degree ) : ?>
									<div class="doctor-card-degree" itemprop="jobTitle"><?php echo esc_html( $degree ); ?></div>
								<?php endif; ?>

								<h2 class="doctor-card-name" itemprop="name"><?php the_title(); ?></h2>

								<?php if ( $bio ) : ?>
									<p class="doctor-card-bio" itemprop="description"><?php echo esc_html( uniasia_truncate( $bio, 150 ) ); ?></p>
								<?php endif; ?>

								<?php if ( $position ) : ?>
									<div class="doctor-card-position"><?php echo esc_html( $position ); ?></div>
								<?php endif; ?>

								<div class="doctor-card-action">
									<span><?php esc_html_e( 'Xem chi tiết', 'uniasia' ); ?></span>
									<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
								</div>
							</div>
						</a>
					</article>
				<?php $di++; endwhile; ?>
			</div>

			<?php uniasia_pagination(); ?>
		<?php else : ?>
			<div class="no-content">
				<p><?php esc_html_e( 'Chưa có bác sĩ nào.', 'uniasia' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();