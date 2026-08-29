<?php
/**
 * Mobile slide-in menu.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="dm-mobile-menu" id="dmMobileMenu" aria-hidden="true">
	<div class="dm-mobile-menu__overlay" data-dm-close></div>
	<div class="dm-mobile-menu__panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Menu', 'dentomart' ); ?>">
		<div class="dm-mobile-menu__head">
			<?php echo dentomart_logo(); // phpcs:ignore WordPress.Security.EscapeOutput -- static markup. ?>
			<button class="dm-mobile-close dm-icon-btn" type="button" data-dm-close aria-label="<?php esc_attr_e( 'Close menu', 'dentomart' ); ?>">
				<?php echo dentomart_icon( 'close', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
			</button>
		</div>

		<nav class="dm-mobile-menu__nav" aria-label="<?php esc_attr_e( 'Mobile', 'dentomart' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'menu_class'     => 'dm-mobile-menu__list',
					'container'      => false,
					'depth'          => 2,
					'fallback_cb'    => false,
				) );
			}
			?>
		</nav>

		<div class="dm-mobile-menu__foot">
			<?php
			$account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
			$cart_url    = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '';
			?>
			<a class="dm-mobile-menu__link" href="<?php echo esc_url( $account_url ); ?>"><?php echo dentomart_icon( 'user', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php esc_html_e( 'My Account', 'dentomart' ); ?></a>
			<?php if ( $cart_url ) : ?>
				<a class="dm-mobile-menu__link" href="<?php echo esc_url( $cart_url ); ?>"><?php echo dentomart_icon( 'cart', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php esc_html_e( 'Cart', 'dentomart' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</div>
