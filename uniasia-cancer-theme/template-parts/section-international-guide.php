<?php
/**
 * Template Part: International Patient Guide Section
 *
 * @package UNI_ASIA
 */
$steps = uniasia_get_intl_steps();
?>
<section class="international-guide-section section-padding" id="international-guide">
	<div class="container">
		<div class="section-header">
			<span class="section-tag"><?php esc_html_e( 'Hướng dẫn quốc tế', 'uniasia' ); ?></span>
			<h2 class="section-title">
				<?php esc_html_e( 'Dịch vụ một cửa cho bệnh nhân quốc tế', 'uniasia' ); ?>
			</h2>
			<p class="section-subtitle">
				<?php esc_html_e( 'Bệnh viện cung cấp dịch vụ khám chữa bệnh trọn gói, thuận tiện và không rào cản. Quy trình tinh gọn, hiệu quả, với chuyên viên riêng hỗ trợ xuyên suốt.', 'uniasia' ); ?>
			</p>
		</div>

		<div class="process-steps">
			<?php foreach ( $steps as $index => $step ) : ?>
				<div class="process-step" data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $index * 100 ); ?>">
					<div class="process-step-number"><?php echo esc_html( $step['number'] ); ?></div>

					<div class="process-step-icon">
						<svg width="36" height="36" viewBox="0 0 24 24" fill="currentColor">
							<?php
							$icons = array(
								'consultation' => '<path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>',
								'travel'       => '<path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>',
								'reception'    => '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>',
								'treatment'    => '<path d="M19 8l-4 4h3c0 3.31-2.69 6-6 6-1.01 0-1.97-.25-2.8-.7l-1.46 1.46C8.97 19.54 10.43 20 12 20c4.42 0 8-3.58 8-8h3l-4-4zM6 12c0-3.31 2.69-6 6-6 1.01 0 1.97.25 2.8.7l1.46-1.46C15.03 4.46 13.57 4 12 4c-4.42 0-8 3.58-8 8H1l4 4 4-4H6z"/>',
								'follow-up'    => '<path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>',
							);
							echo $icons[ $step['icon'] ] ?? $icons['consultation'];
							?>
						</svg>
					</div>

					<div class="process-step-content">
						<h3 class="process-step-title"><?php echo esc_html( $step['title'] ); ?></h3>
						<p class="process-step-desc"><?php echo esc_html( $step['desc'] ); ?></p>
					</div>

					<?php if ( $index < count( $steps ) - 1 ) : ?>
						<div class="process-step-connector"></div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="section-footer">
			<a href="<?php echo esc_url( home_url( '/international-guide/' ) ); ?>" class="btn btn-outline">
				<?php esc_html_e( 'Chi tiết dịch vụ', 'uniasia' ); ?>
			</a>
		</div>
	</div>
</section>