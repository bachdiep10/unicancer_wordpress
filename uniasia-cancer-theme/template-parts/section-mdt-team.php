<?php
/**
 * Template Part: MDT Team Section
 *
 * @package UNI_ASIA
 */
$doctors = uniasia_get_doctors( array( 'featured' => true, 'posts_per_page' => 4 ) );
?>
<section class="mdt-section section-padding" id="mdt-team">
	<div class="container">
		<div class="section-header">
			<span class="section-tag"><?php esc_html_e( 'Đội ngũ MDT', 'uniasia' ); ?></span>
			<h2 class="section-title">
				<?php esc_html_e( 'Chuyên gia hàng đầu trong lĩnh vực can thiệp xâm lấn tối thiểu', 'uniasia' ); ?>
			</h2>
			<p class="section-subtitle">
				<?php esc_html_e( 'Quy tụ chuyên gia giàu kinh nghiệm hàng đầu Trung Quốc, mang đến dịch vụ điều trị ung thư xâm lấn tối thiểu hàng đầu thế giới.', 'uniasia' ); ?>
			</p>
		</div>

		<?php if ( $doctors->have_posts() ) : ?>
			<div class="mdt-slider swiper" id="mdtSwiper">
				<div class="swiper-wrapper">
					<?php while ( $doctors->have_posts() ) : $doctors->the_post();
						$degree    = uniasia_field( 'doctor_degree' );
						$position  = uniasia_field( 'doctor_position' );
						$short_bio = uniasia_field( 'doctor_short_bio' );
						$idx       = $doctors->current_post;
					?>
						<div class="swiper-slide">
							<article class="doctor-card" itemscope itemtype="https://schema.org/Physician">
								<a href="<?php the_permalink(); ?>" class="doctor-card-link">
									<div class="doctor-card-image">
										<?php if ( has_post_thumbnail() ) : ?>
											<?php the_post_thumbnail( 'uniasia-doctor', array( 'itemprop' => 'image', 'loading' => 'lazy' ) ); ?>
										<?php else : ?>
											<img src="<?php echo esc_url( uniasia_doctor_photo( $idx % 6 ) ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" itemprop="image">
										<?php endif; ?>
										<div class="doctor-card-overlay"></div>
									</div>

									<div class="doctor-card-body">
										<?php if ( $degree ) : ?>
											<div class="doctor-card-degree" itemprop="jobTitle"><?php echo esc_html( $degree ); ?></div>
										<?php endif; ?>

										<h3 class="doctor-card-name" itemprop="name"><?php the_title(); ?></h3>

										<?php if ( $short_bio ) : ?>
											<p class="doctor-card-bio" itemprop="description"><?php echo esc_html( uniasia_truncate( $short_bio, 120 ) ); ?></p>
										<?php endif; ?>

										<?php if ( $position ) : ?>
											<div class="doctor-card-position" itemprop="worksFor"><?php echo esc_html( $position ); ?></div>
										<?php endif; ?>

										<div class="doctor-card-action">
											<span class="doctor-card-link-text"><?php esc_html_e( 'Chi tiết', 'uniasia' ); ?></span>
											<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
										</div>
									</div>
								</a>
							</article>
						</div>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>

				<div class="swiper-pagination"></div>
				<div class="swiper-button-prev"></div>
				<div class="swiper-button-next"></div>
			</div>

			<div class="mdt-section-footer">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'doctor' ) ); ?>" class="btn btn-primary">
					<?php esc_html_e( 'Xem tất cả bác sĩ', 'uniasia' ); ?>
					<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
				</a>
			</div>
		<?php else : ?>
			<div class="no-content">
				<p><?php esc_html_e( 'Chưa có bác sĩ nào được thêm. Vui lòng thêm bác sĩ trong Admin → Bác sĩ.', 'uniasia' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>