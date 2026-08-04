<?php
/**
 * The Footer
 *
 * @package UNI_ASIA
 */
?>
	</div><!-- #content -->

	<footer id="colophon" class="site-footer" itemscope itemtype="https://schema.org/WPFooter">
		<div class="footer-main">
			<div class="container">
				<div class="footer-grid">
					<div class="footer-col footer-col-about">
						<div class="footer-logo">
							<?php
							if ( has_custom_logo() ) {
								the_custom_logo();
							} else {
								?>
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
									<img src="<?php echo esc_url( uniasia_logo_white() ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="footer-logo-img" loading="lazy">
								</a>
								<?php
							}
							?>
						</div>
						<p class="footer-about">
							<?php echo esc_html( get_bloginfo( 'description' ) ); ?>
						</p>
						<div class="footer-stats">
							<?php
							$stats = uniasia_get_stats();
							foreach ( $stats as $stat ) : ?>
								<div class="footer-stat">
									<div class="footer-stat-number"><?php echo esc_html( $stat['number'] ); ?></div>
									<div class="footer-stat-label"><?php echo esc_html( $stat['label'] ); ?></div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<div class="footer-col footer-col-services">
						<h4 class="footer-title"><?php esc_html_e( 'Dịch vụ', 'uniasia' ); ?></h4>
						<?php
						wp_nav_menu( array(
							'theme_location' => 'footer',
							'menu_class'     => 'footer-menu',
							'container'      => false,
							'depth'          => 1,
							'fallback_cb'    => false,
						) );
						?>
						<ul class="footer-menu footer-menu-default">
							<li><a href="<?php echo esc_url( home_url( '/cancer-types/' ) ); ?>"><?php esc_html_e( 'Loại ung thư', 'uniasia' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/technologies/' ) ); ?>"><?php esc_html_e( 'Kỹ thuật điều trị', 'uniasia' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/doctors/' ) ); ?>"><?php esc_html_e( 'Đội ngũ bác sĩ', 'uniasia' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/international-guide/' ) ); ?>"><?php esc_html_e( 'Hướng dẫn quốc tế', 'uniasia' ); ?></a></li>
						</ul>
					</div>

					<div class="footer-col footer-col-links">
						<h4 class="footer-title"><?php esc_html_e( 'Liên kết nhanh', 'uniasia' ); ?></h4>
						<ul class="footer-menu">
							<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Trang chủ', 'uniasia' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>"><?php esc_html_e( 'Giới thiệu', 'uniasia' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/patient-stories/' ) ); ?>"><?php esc_html_e( 'Câu chuyện bệnh nhân', 'uniasia' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/faqs/' ) ); ?>"><?php esc_html_e( 'Câu hỏi thường gặp', 'uniasia' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Liên hệ', 'uniasia' ); ?></a></li>
						</ul>
					</div>

					<div class="footer-col footer-col-contact">
						<h4 class="footer-title"><?php esc_html_e( 'Liên hệ', 'uniasia' ); ?></h4>
						<div class="footer-contact-info">
							<div class="footer-contact-item">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
								<div>
									<small><?php esc_html_e( 'Hotline', 'uniasia' ); ?></small>
									<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', uniasia_get_contact( 'contact_phone_vi' ) ) ); ?>"><?php echo esc_html( uniasia_get_contact( 'contact_phone_vi', '+84 28 9999 9999' ) ); ?></a>
								</div>
							</div>

							<div class="footer-contact-item">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
								<div>
									<small><?php esc_html_e( 'Email', 'uniasia' ); ?></small>
									<a href="mailto:<?php echo esc_attr( uniasia_get_contact( 'contact_email' ) ); ?>"><?php echo esc_html( uniasia_get_contact( 'contact_email', 'info@uniasia-cancer.com' ) ); ?></a>
								</div>
							</div>

							<div class="footer-contact-item">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
								<div>
									<small><?php esc_html_e( 'Địa chỉ', 'uniasia' ); ?></small>
									<span><?php echo nl2br( esc_html( uniasia_get_contact( 'contact_address', 'Số XX, Đường XXX, Thành Đô, Trung Quốc' ) ) ); ?></span>
								</div>
							</div>
						</div>

						<div class="footer-social">
							<?php
							$fb   = uniasia_get_contact( 'social_facebook' );
							$yt   = uniasia_get_contact( 'social_youtube' );
							$ig   = uniasia_get_contact( 'social_instagram' );
							if ( $fb ) : ?>
								<a href="<?php echo esc_url( $fb ); ?>" target="_blank" rel="noopener" class="social-link" aria-label="Facebook">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
								</a>
							<?php endif;
							if ( $yt ) : ?>
								<a href="<?php echo esc_url( $yt ); ?>" target="_blank" rel="noopener" class="social-link" aria-label="YouTube">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
								</a>
							<?php endif;
							if ( $ig ) : ?>
								<a href="<?php echo esc_url( $ig ); ?>" target="_blank" rel="noopener" class="social-link" aria-label="Instagram">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
								</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="footer-bottom">
			<div class="container">
				<div class="footer-bottom-inner">
					<div class="footer-copyright">
						<?php
						printf(
							/* translators: 1: Copyright year, 2: Site title */
							esc_html__( '© %1$s %2$s. Bảo lưu mọi quyền.', 'uniasia' ),
							date( 'Y' ),
							get_bloginfo( 'name' )
						);
						?>
					</div>
					<div class="footer-bottom-links">
						<a href="<?php echo esc_url( home_url( '/chinh-sach-bao-mat/' ) ); ?>"><?php esc_html_e( 'Chính sách bảo mật', 'uniasia' ); ?></a>
						<a href="<?php echo esc_url( home_url( '/dieu-khoan/' ) ); ?>"><?php esc_html_e( 'Điều khoản sử dụng', 'uniasia' ); ?></a>
						<a href="<?php echo esc_url( home_url( '/lien-he/' ) ); ?>"><?php esc_html_e( 'Liên hệ', 'uniasia' ); ?></a>
					</div>
				</div>
			</div>
		</div>
	</footer>
</div><!-- #page -->

<?php
$whatsapp_float = uniasia_get_contact( 'contact_whatsapp', '' );
if ( $whatsapp_float ) : ?>
<a href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $whatsapp_float ) ); ?>" class="floating-btn floating-btn-whatsapp" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
	<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</a>
<?php endif; ?>

<a href="#contact-form" class="floating-btn floating-btn-contact" aria-label="<?php esc_attr_e( 'Tư vấn nhanh', 'uniasia' ); ?>">
	<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-7 12h-2v-2h2v2zm0-4h-2V6h2v4z"/></svg>
</a>

<button class="back-to-top" aria-label="<?php esc_attr_e( 'Lên đầu trang', 'uniasia' ); ?>">
	<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"/></svg>
</button>

<?php wp_footer(); ?>
</body>
</html>