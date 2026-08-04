<?php
/**
 * Template Part: Contact Form Section
 *
 * @package UNI_ASIA
 */
?>
<section class="contact-form-section section-padding" id="contact-form">
	<div class="container">
		<div class="contact-grid">
			<div class="contact-info-col">
				<span class="section-tag"><?php esc_html_e( 'Tư vấn miễn phí', 'uniasia' ); ?></span>
				<h2 class="section-title">
					<?php esc_html_e( 'Đặt lịch với chuyên gia ngay', 'uniasia' ); ?>
				</h2>
				<p class="section-subtitle">
					<?php esc_html_e( 'Chuyên viên sẽ liên hệ với bạn trong vòng 72 giờ. Vui lòng điền đầy đủ thông tin bên dưới.', 'uniasia' ); ?>
				</p>

				<div class="contact-info-items">
					<div class="contact-info-item">
						<div class="contact-info-icon">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
						</div>
						<div class="contact-info-content">
							<h4><?php esc_html_e( 'Hotline', 'uniasia' ); ?></h4>
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', uniasia_get_contact( 'contact_phone_vi' ) ) ); ?>"><?php echo esc_html( uniasia_get_contact( 'contact_phone_vi', '+84 28 9999 9999' ) ); ?></a>
						</div>
					</div>

					<div class="contact-info-item">
						<div class="contact-info-icon">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
						</div>
						<div class="contact-info-content">
							<h4><?php esc_html_e( 'Email', 'uniasia' ); ?></h4>
							<a href="mailto:<?php echo esc_attr( uniasia_get_contact( 'contact_email' ) ); ?>"><?php echo esc_html( uniasia_get_contact( 'contact_email', 'info@uniasia-cancer.com' ) ); ?></a>
						</div>
					</div>

					<div class="contact-info-item">
						<div class="contact-info-icon">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
						</div>
						<div class="contact-info-content">
							<h4><?php esc_html_e( 'Địa chỉ', 'uniasia' ); ?></h4>
							<p><?php echo nl2br( esc_html( uniasia_get_contact( 'contact_address', 'Thành Đô, Trung Quốc' ) ) ); ?></p>
						</div>
					</div>
				</div>
			</div>

			<div class="contact-form-col">
				<form id="uniasia-consultation-form" class="consultation-form" novalidate>
					<?php wp_nonce_field( 'uniasia_nonce', 'nonce' ); ?>

					<div class="form-row">
						<div class="form-group">
							<label for="cf-name"><?php esc_html_e( 'Họ và tên *', 'uniasia' ); ?></label>
							<input type="text" id="cf-name" name="name" required placeholder="<?php esc_attr_e( 'Nhập họ tên của bạn', 'uniasia' ); ?>">
						</div>

						<div class="form-group">
							<label for="cf-age"><?php esc_html_e( 'Tuổi', 'uniasia' ); ?></label>
							<input type="number" id="cf-age" name="age" min="0" max="120" placeholder="<?php esc_attr_e( 'Tuổi', 'uniasia' ); ?>">
						</div>
					</div>

					<div class="form-row">
						<div class="form-group">
							<label for="cf-phone"><?php esc_html_e( 'Số điện thoại *', 'uniasia' ); ?></label>
							<input type="tel" id="cf-phone" name="phone" required placeholder="<?php esc_attr_e( 'Số điện thoại', 'uniasia' ); ?>">
						</div>

						<div class="form-group">
							<label for="cf-email"><?php esc_html_e( 'Email', 'uniasia' ); ?></label>
							<input type="email" id="cf-email" name="email" placeholder="<?php esc_attr_e( 'Email của bạn', 'uniasia' ); ?>">
						</div>
					</div>

					<div class="form-group">
						<label for="cf-message"><?php esc_html_e( 'Câu hỏi của bạn', 'uniasia' ); ?></label>
						<textarea id="cf-message" name="message" rows="4" placeholder="<?php esc_attr_e( 'Mô tả tình trạng hoặc câu hỏi của bạn...', 'uniasia' ); ?>"></textarea>
					</div>

					<div class="form-group form-checkbox">
						<label class="checkbox-label">
							<input type="checkbox" name="agree" required>
							<span><?php esc_html_e( 'Tôi đã đọc và đồng ý với thỏa thuận', 'uniasia' ); ?></span>
						</label>
						<a href="<?php echo esc_url( home_url( '/chinh-sach-bao-mat/' ) ); ?>" class="form-policy-link">
							<?php esc_html_e( 'Chính sách bảo mật và Miễn trừ trách nhiệm', 'uniasia' ); ?>
						</a>
					</div>

					<button type="submit" class="btn btn-primary btn-lg btn-submit">
						<span class="btn-text"><?php esc_html_e( 'Đặt lịch với chuyên gia ngay', 'uniasia' ); ?></span>
						<span class="btn-loading" style="display:none;">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="spin"><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8z"/></svg>
							<?php esc_html_e( 'Đang gửi...', 'uniasia' ); ?>
						</span>
					</button>

					<div class="form-message" style="display:none;"></div>
				</form>
			</div>
		</div>
	</div>
</section>