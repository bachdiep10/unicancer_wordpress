<?php
/**
 * The Header
 *
 * @package UNI_ASIA
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="profile" href="https://gmpg.org/xfn/11">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> itemscope itemtype="https://schema.org/WebPage">
<?php wp_body_open(); ?>

<?php if ( function_exists( 'uniasia_output_schema' ) && is_front_page() ) : ?>
<!-- Schema.org WebSite -->
<?php endif; ?>

<a class="skip-link screen-reader-text" href="#main-content"><?php esc_html_e( 'Bỏ qua đến nội dung chính', 'uniasia' ); ?></a>

<?php
/**
 * Top Bar
 */
$phone_vi = uniasia_get_contact( 'contact_phone_vi', '+84 28 9999 9999' );
$phone_en = uniasia_get_contact( 'contact_phone_en', '+86 28 9999 9999' );
$email    = uniasia_get_contact( 'contact_email', 'info@uniasia-cancer.com' );
?>
<div class="top-bar">
	<div class="container">
		<div class="top-bar-inner">
			<div class="top-bar-left">
				<span class="top-bar-item top-bar-phone">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
					<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone_vi ) ); ?>"><?php echo esc_html( $phone_vi ); ?></a>
				</span>
				<span class="top-bar-item top-bar-email">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
					<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
				</span>
			</div>
			<div class="top-bar-right">
				<?php if ( function_exists( 'uniasia_language_switcher' ) ) : ?>
					<?php echo uniasia_language_switcher(); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<header id="masthead" class="site-header" itemscope itemtype="https://schema.org/WPHeader">
	<div class="container">
		<div class="header-inner">
			<div class="site-branding">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} else {
					?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" rel="home">
						<img src="<?php echo esc_url( uniasia_logo() ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="site-logo-img" loading="eager">
					</a>
					<?php
				}
				?>
			</div>

			<nav id="site-navigation" class="main-navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<span class="menu-toggle-icon"></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'uniasia' ); ?></span>
				</button>

				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'menu_class'     => 'nav-menu primary-menu',
					'container'      => false,
					'fallback_cb'    => 'uniasia_fallback_menu',
					'depth'          => 2,
					'link_class'     => 'nav-link',
				) );
				?>
			</nav>

			<div class="header-actions">
				<?php
				$whatsapp = uniasia_get_contact( 'contact_whatsapp', '' );
				if ( $whatsapp ) : ?>
					<a href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $whatsapp ) ); ?>" class="header-btn header-btn-whatsapp" target="_blank" rel="noopener noreferrer">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
						<span><?php esc_html_e( 'WhatsApp', 'uniasia' ); ?></span>
					</a>
				<?php endif; ?>

				<a href="#contact-form" class="header-btn header-btn-primary">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 002 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zM5 8V6h14v2H5zm2 4h5v5H7v-5z"/></svg>
					<span><?php esc_html_e( 'Đặt lịch', 'uniasia' ); ?></span>
				</a>
			</div>
		</div>
	</div>
</header>

<div id="page" class="site">
	<?php
	/**
	 * Hook: uniasia_before_content
	 */
	do_action( 'uniasia_before_content' );
	?>