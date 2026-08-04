<?php
/**
 * Template Part: Treatment Tech Section
 *
 * @package UNI_ASIA
 */
$techs = uniasia_get_technologies( array( 'posts_per_page' => 7 ) );
?>
<section class="treatment-tech-section section-padding" id="treatment-tech">
	<div class="container">
		<div class="section-header">
			<span class="section-tag"><?php esc_html_e( 'Trung tâm điều trị', 'uniasia' ); ?></span>
			<h2 class="section-title">
				<?php esc_html_e( 'Các kỹ thuật điều trị tiên tiến', 'uniasia' ); ?>
			</h2>
			<p class="section-subtitle">
				<?php esc_html_e( 'Cung cấp các giải pháp điều trị chính xác, có mục tiêu và đạt chất lượng quốc tế cho bệnh nhân trên toàn cầu.', 'uniasia' ); ?>
			</p>
		</div>

		<?php if ( $techs->have_posts() ) : ?>
			<div class="tech-tabs">
				<div class="tech-tabs-nav">
					<?php $i = 0; while ( $techs->have_posts() ) : $techs->the_post();
						$active = ( 0 === $i ) ? ' active' : '';
					?>
						<button class="tech-tab-btn<?php echo esc_attr( $active ); ?>" data-tab="tech-<?php echo get_the_ID(); ?>">
							<span class="tech-tab-title"><?php the_title(); ?></span>
						</button>
					<?php $i++; endwhile; ?>
				</div>

				<div class="tech-tabs-content">
					<?php $i = 0; while ( $techs->have_posts() ) : $techs->the_post();
						$active = ( 0 === $i ) ? ' active' : '';
						$short  = uniasia_field( 'tech_short_name' );
						$full   = uniasia_field( 'tech_full_name' );
						$summary = uniasia_field( 'tech_summary' );
						$icon   = uniasia_field( 'tech_icon' );
						if ( ! $icon ) {
							$icon = uniasia_tech_icon( get_post_field( 'post_name', get_the_ID() ) );
						}
					?>
						<div class="tech-tab-pane<?php echo esc_attr( $active ); ?>" id="tech-<?php echo get_the_ID(); ?>">
							<div class="tech-tab-grid">
								<div class="tech-tab-image">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?>
									<?php else : ?>
										<img src="<?php echo esc_url( $icon ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
									<?php endif; ?>
								</div>

								<div class="tech-tab-content">
									<?php if ( $short ) : ?>
										<span class="tech-short-name"><?php echo esc_html( $short ); ?></span>
									<?php endif; ?>

									<h3 class="tech-tab-title"><?php the_title(); ?></h3>

									<?php if ( $summary ) : ?>
										<div class="tech-tab-summary"><?php echo wp_kses_post( wpautop( $summary ) ); ?></div>
									<?php else : ?>
										<div class="tech-tab-summary"><?php the_content(); ?></div>
									<?php endif; ?>

									<a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm">
										<?php esc_html_e( 'Tìm hiểu thêm', 'uniasia' ); ?>
									</a>
								</div>
							</div>
						</div>
					<?php $i++; endwhile; wp_reset_postdata(); ?>
				</div>
			</div>
		<?php else : ?>
			<div class="no-content">
				<p><?php esc_html_e( 'Chưa có kỹ thuật nào. Vui lòng thêm trong Admin → Kỹ thuật điều trị.', 'uniasia' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>