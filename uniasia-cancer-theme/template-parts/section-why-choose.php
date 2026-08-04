<?php
/**
 * Template Part: Why Choose Us Section
 *
 * @package UNI_ASIA
 */
$items = uniasia_get_why_choose();
?>
<section class="why-choose-section section-padding" id="about-section">
	<div class="container">
		<div class="section-header">
			<span class="section-tag"><?php esc_html_e( 'Tại sao chọn chúng tôi', 'uniasia' ); ?></span>
			<h2 class="section-title">
				<?php esc_html_e( 'Hệ thống hàng đầu trong phòng ngừa, chẩn đoán và điều trị ung thư', 'uniasia' ); ?>
			</h2>
		</div>

		<div class="why-choose-grid">
			<?php foreach ( $items as $index => $item ) : ?>
				<div class="why-choose-card" data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $index * 100 ); ?>">
					<div class="why-choose-icon">
						<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<?php
							$icons = array(
								'doctor'       => '<path d="M12 2a3 3 0 00-3 3v1H7a2 2 0 00-2 2v3a2 2 0 002 2h10a2 2 0 002-2V8a2 2 0 00-2-2h-2V5a3 3 0 00-3-3zm0 2a1 1 0 011 1v1h-2V5a1 1 0 011-1z"/><path d="M9 16v3a3 3 0 006 0v-3"/>',
								'diagnosis'    => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/><line x1="11" y1="8" x2="11" y2="14"/>',
								'personalized' => '<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/><path d="M9 12l2 2 4-4"/>',
								'globe'        => '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>',
							);
							echo $icons[ $item['icon'] ] ?? $icons['doctor'];
							?>
						</svg>
					</div>
					<h3 class="why-choose-title"><?php echo esc_html( $item['title'] ); ?></h3>
					<p class="why-choose-desc"><?php echo esc_html( $item['desc'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="why-choose-certifications">
			<h4 class="why-choose-cert-title"><?php esc_html_e( 'Chứng nhận & Thành tựu', 'uniasia' ); ?></h4>
			<ul class="certifications-list">
				<li><?php esc_html_e( 'Một trong những Trung tâm Mẫu lâm sàng đầu tiên tại Trung Quốc về đốt u bằng NanoKnife (IRE)', 'uniasia' ); ?></li>
				<li><?php esc_html_e( 'Trung tâm đào tạo Thành Đô về công nghệ NanoKnife ứng dụng cho u tụy', 'uniasia' ); ?></li>
				<li><?php esc_html_e( 'Cơ sở trình diễn ứng dụng đổi mới chụp mạch DSA Philips', 'uniasia' ); ?></li>
				<li><?php esc_html_e( 'Bệnh viện trình diễn của Hiệp hội Điều trị Đốt u Thế giới', 'uniasia' ); ?></li>
			</ul>
		</div>
	</div>
</section>