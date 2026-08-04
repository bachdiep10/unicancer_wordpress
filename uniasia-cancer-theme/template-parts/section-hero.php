<?php
/**
 * Template Part: Hero Section (Trang chủ)
 *
 * @package UNI_ASIA
 */
$stats = uniasia_get_stats();
?>
<section class="hero-section" id="hero">
	<div class="hero-bg" style="background-image: linear-gradient(rgba(0, 60, 100, 0.85), rgba(0, 60, 100, 0.7)), url('<?php echo esc_url( uniasia_hero_bg() ); ?>');"></div>
	<div class="container">
		<div class="hero-content">
			<div class="hero-tag">
				<span class="hero-tag-badge"><?php esc_html_e( 'Bệnh viện Ung thư hàng đầu', 'uniasia' ); ?></span>
			</div>

			<h1 class="hero-title">
				<span class="hero-title-line1"><?php esc_html_e( 'Bệnh viện Ung thư', 'uniasia' ); ?></span>
				<span class="hero-title-line2"><?php esc_html_e( 'UNI-ASIA', 'uniasia' ); ?></span>
			</h1>

			<p class="hero-subtitle">
				<?php esc_html_e( 'Quy tụ chuyên gia ung bướu hàng đầu Trung Quốc, dẫn đầu điều trị ung thư chính xác ít xâm lấn quốc tế', 'uniasia' ); ?>
			</p>

			<div class="hero-actions">
				<a href="#contact-form" class="btn btn-primary btn-lg">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 002 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10z"/></svg>
					<?php esc_html_e( 'Đặt lịch tư vấn miễn phí', 'uniasia' ); ?>
				</a>
				<a href="#about-section" class="btn btn-outline btn-lg">
					<?php esc_html_e( 'Tìm hiểu thêm', 'uniasia' ); ?>
					<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
				</a>
			</div>

			<div class="hero-stats">
				<?php foreach ( $stats as $stat ) : ?>
					<div class="hero-stat">
						<div class="hero-stat-number"><?php echo esc_html( $stat['number'] ); ?></div>
						<div class="hero-stat-label"><?php echo esc_html( $stat['label'] ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>