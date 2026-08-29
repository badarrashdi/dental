<?php
/**
 * Footer with columns and bottom bar.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shop_links = array(
	array( 'label' => __( 'All Brands', 'dentomart' ), 'url' => home_url( '/brands/' ) ),
	array( 'label' => __( 'Orthodontics', 'dentomart' ), 'url' => home_url( '/product-category/orthodontics/' ) ),
	array( 'label' => __( 'Endodontics', 'dentomart' ), 'url' => home_url( '/product-category/endodontics/' ) ),
	array( 'label' => __( 'Restorative', 'dentomart' ), 'url' => home_url( '/product-category/restorative/' ) ),
	array( 'label' => __( 'Oral Surgery', 'dentomart' ), 'url' => home_url( '/product-category/oral-surgery/' ) ),
	array( 'label' => __( 'Implantology', 'dentomart' ), 'url' => home_url( '/product-category/implantology/' ) ),
	array( 'label' => __( 'Instruments', 'dentomart' ), 'url' => home_url( '/product-category/instruments/' ) ),
	array( 'label' => __( 'Equipments', 'dentomart' ), 'url' => home_url( '/product-category/equipments/' ) ),
	array( 'label' => __( 'Laboratory', 'dentomart' ), 'url' => home_url( '/product-category/laboratory/' ) ),
);

$support_links = array(
	array( 'label' => __( 'Shop', 'dentomart' ), 'url' => wc_get_page_permalink( 'shop' ) ),
	array( 'label' => __( 'Cart', 'dentomart' ), 'url' => wc_get_page_permalink( 'cart' ) ),
	array( 'label' => __( 'Checkout', 'dentomart' ), 'url' => wc_get_page_permalink( 'checkout' ) ),
	array( 'label' => __( 'My Account', 'dentomart' ), 'url' => wc_get_page_permalink( 'myaccount' ) ),
	array( 'label' => __( 'About Us', 'dentomart' ), 'url' => home_url( '/about-us/' ) ),
	array( 'label' => __( 'Contact Us', 'dentomart' ), 'url' => home_url( '/contact-us/' ) ),
);

$payments = array( 'UPI', 'RuPay', 'Visa', 'Mastercard', 'Paytm', 'PhonePe' );
?>
<footer class="site-footer">
	<div class="dm-container">
		<div class="dm-footer-grid">
			<div class="dm-footer-col dm-footer-col--brand">
				<?php echo dentomart_logo(); // phpcs:ignore WordPress.Security.EscapeOutput -- static markup. ?>
				<p class="dm-footer-about"><?php esc_html_e( 'India’s trusted online store for genuine dental products — instruments, materials, equipment and consumables from 400+ leading brands.', 'dentomart' ); ?></p>
				<ul class="dm-footer-social" aria-label="<?php esc_attr_e( 'Social media', 'dentomart' ); ?>">
					<li><a href="#" aria-label="<?php esc_attr_e( 'Facebook', 'dentomart' ); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a></li>
					<li><a href="#" aria-label="<?php esc_attr_e( 'Instagram', 'dentomart' ); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></a></li>
					<li><a href="#" aria-label="<?php esc_attr_e( 'YouTube', 'dentomart' ); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg></a></li>
					<li><a href="#" aria-label="<?php esc_attr_e( 'LinkedIn', 'dentomart' ); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4V9h4v1.2A6 6 0 0 1 16 8zM6 9H2v12h4zM4 6a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg></a></li>
				</ul>
			</div>

			<div class="dm-footer-col">
				<h4 class="dm-footer-title"><?php esc_html_e( 'Shop by Department', 'dentomart' ); ?></h4>
				<?php if ( has_nav_menu( 'footer_shop' ) ) : ?>
					<?php
					wp_nav_menu( array(
						'theme_location' => 'footer_shop',
						'menu_class'     => 'dm-footer-links',
						'container'      => false,
						'depth'          => 1,
						'fallback_cb'    => false,
					) );
					?>
				<?php else : ?>
					<ul class="dm-footer-links">
						<?php foreach ( $shop_links as $link ) : ?>
							<li><a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<div class="dm-footer-col">
				<h4 class="dm-footer-title"><?php esc_html_e( 'Customer Care', 'dentomart' ); ?></h4>
				<?php if ( has_nav_menu( 'footer_support' ) ) : ?>
					<?php
					wp_nav_menu( array(
						'theme_location' => 'footer_support',
						'menu_class'     => 'dm-footer-links',
						'container'      => false,
						'depth'          => 1,
						'fallback_cb'    => false,
					) );
					?>
				<?php else : ?>
					<ul class="dm-footer-links">
						<?php foreach ( $support_links as $link ) : ?>
							<li><a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<div class="dm-footer-col dm-footer-col--contact">
				<h4 class="dm-footer-title"><?php esc_html_e( 'Get in Touch', 'dentomart' ); ?></h4>
				<ul class="dm-footer-contact">
					<li><?php echo dentomart_icon( 'pin', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php esc_html_e( 'New Delhi, Delhi, India', 'dentomart' ); ?></span></li>
					<li><?php echo dentomart_icon( 'mail', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><a href="mailto:support@dentomart.in">support@dentomart.in</a></li>
					<li><?php echo dentomart_icon( 'phone', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><a href="tel:+917289999456">+91-728-999-9456</a></li>
				</ul>
				<p class="dm-footer-hours"><?php esc_html_e( 'Mon–Sat 9:00 AM – 7:00 PM', 'dentomart' ); ?></p>
			</div>
		</div>

		<div class="dm-footer-bottom">
			<p class="dm-footer-copy">&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. <?php esc_html_e( 'All rights reserved.', 'dentomart' ); ?></p>
			<ul class="dm-footer-payments" aria-label="<?php esc_attr_e( 'Accepted payment methods', 'dentomart' ); ?>">
				<?php foreach ( $payments as $method ) : ?>
					<li><span class="dm-pay-badge"><?php echo esc_html( $method ); ?></span></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</footer>
