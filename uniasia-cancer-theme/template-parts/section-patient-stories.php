<?php
/**
 * Template Part: Patient Stories Section
 *
 * @package UNI_ASIA
 */
$stories = uniasia_get_stories( array( 'featured' => true, 'posts_per_page' => 8 ) );
?>
<section class="patient-stories-section section-padding" id="patient-stories">
	<div class="container">
		<div class="section-header">
			<span class="section-tag"><?php esc_html_e( 'Câu chuyện bệnh nhân', 'uniasia' ); ?></span>
			<h2 class="section-title">
				<?php esc_html_e( 'Mỗi hành trình tìm kiếm cơ hội điều trị đều khắc ghi lòng dũng cảm và hy vọng', 'uniasia' ); ?>
			</h2>
			<p class="section-subtitle">
				<?php esc_html_e( 'Mỗi câu chuyện là minh chứng cho sự đồng lòng giữa đội ngũ y bác sĩ và bệnh nhân trong hành trình cùng nhau chiến thắng bệnh tật.', 'uniasia' ); ?>
			</p>
		</div>

		<?php if ( $stories->have_posts() ) : ?>
			<div class="patient-stories-slider swiper" id="storiesSwiper">
				<div class="swiper-wrapper">
					<?php
					$pi = 0;
					while ( $stories->have_posts() ) : $stories->the_post();
						$patient_name = uniasia_field( 'story_patient_name' );
						$age          = uniasia_field( 'story_age' );
						$country      = uniasia_field( 'story_country' );
						$cancer_type  = uniasia_field( 'story_cancer_type' );
						$summary      = uniasia_field( 'story_summary' );
					?>
						<div class="swiper-slide">
							<article class="story-card" itemscope itemtype="https://schema.org/MedicalCondition">
								<div class="story-card-header">
									<?php if ( $cancer_type ) : ?>
										<span class="story-card-tag"><?php echo esc_html( $cancer_type ); ?></span>
									<?php endif; ?>
									<div class="story-card-image">
										<?php if ( has_post_thumbnail() ) : ?>
											<?php the_post_thumbnail( 'uniasia-story', array( 'loading' => 'lazy' ) ); ?>
										<?php else : ?>
											<img src="<?php echo esc_url( uniasia_patient_image( $pi % 4 ) ); ?>" alt="<?php echo esc_attr( $patient_name ?: get_the_title() ); ?>" loading="lazy">
										<?php endif; ?>
									</div>
								</div>

								<div class="story-card-body">
									<?php if ( $patient_name ) : ?>
										<h3 class="story-card-name" itemprop="name"><?php echo esc_html( $patient_name ); ?></h3>
									<?php else : ?>
										<h3 class="story-card-name"><?php the_title(); ?></h3>
									<?php endif; ?>

									<div class="story-card-meta">
										<?php if ( $country ) : ?>
											<span class="story-meta-item">
												<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
												<?php echo esc_html( $country ); ?>
											</span>
										<?php endif; ?>

										<?php if ( $age ) : ?>
											<span class="story-meta-item">
												<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
												<?php
												printf(
													/* translators: %d: age */
													esc_html__( '%d tuổi', 'uniasia' ),
													(int) $age
												);
												?>
											</span>
										<?php endif; ?>
									</div>

									<p class="story-card-excerpt" itemprop="description">
										<?php echo esc_html( uniasia_truncate( $summary ?: wp_strip_all_tags( get_the_content() ), 200 ) ); ?>
									</p>

									<a href="<?php the_permalink(); ?>" class="story-card-link">
										<?php esc_html_e( 'Đọc tiếp', 'uniasia' ); ?>
										<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
									</a>
								</div>
							</article>
						</div>
					<?php $pi++; endwhile; wp_reset_postdata(); ?>
				</div>

				<div class="swiper-pagination"></div>
			</div>

			<div class="section-footer">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'patient_story' ) ); ?>" class="btn btn-primary">
					<?php esc_html_e( 'Xem thêm câu chuyện', 'uniasia' ); ?>
				</a>
			</div>
		<?php else : ?>
			<div class="no-content">
				<p><?php esc_html_e( 'Chưa có câu chuyện nào. Vui lòng thêm trong Admin → Câu chuyện bệnh nhân.', 'uniasia' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>