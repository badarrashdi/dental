<?php
/**
 * Masthead: logo, search, account, cart.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$account_url  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
$cart_url     = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '';
$cart_count   = dentomart_cart_count();
?>
<div class="dm-masthead">
	<div class="dm-container dm-masthead__inner">

		<button class="dm-mobile-toggle dm-icon-btn" type="button" aria-controls="dmMobileMenu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Open menu', 'dentomart' ); ?>">
			<?php echo dentomart_icon( 'menu', 26 ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
		</button>

		<div class="dm-masthead__brand">
			<?php echo dentomart_logo(); // phpcs:ignore WordPress.Security.EscapeOutput -- static markup. ?>
			<div class="dm-masthead__pincode" data-dm-pincode-header>
				<?php echo dentomart_icon( 'pin', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<div class="dm-masthead__pincode-text">
					<span class="dm-masthead__pincode-label"><?php esc_html_e( 'Delivering to', 'dentomart' ); ?></span>
					<button type="button" class="dm-masthead__pincode-btn"><?php esc_html_e( 'Enter Pincode', 'dentomart' ); ?></button>
				</div>
			</div>
		</div>

		<div class="dm-masthead__search">
			<?php get_search_form(); ?>
		</div>

		<div class="dm-masthead__actions">
			<a class="dm-action dm-action--account" href="<?php echo esc_url( $account_url ); ?>">
				<?php echo dentomart_icon( 'user', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
				<span class="dm-action__label">
					<span class="dm-action__top"><?php esc_html_e( 'Account', 'dentomart' ); ?></span>
					<span class="dm-action__sub"><?php echo is_user_logged_in() ? esc_html__( 'My Account', 'dentomart' ) : esc_html__( 'Sign in', 'dentomart' ); ?></span>
				</span>
			</a>

			<?php if ( $cart_url ) : ?>
			<a class="dm-action dm-action--cart" href="<?php echo esc_url( $cart_url ); ?>" aria-label="<?php esc_attr_e( 'View cart', 'dentomart' ); ?>">
				<span class="dm-action__icon">
					<?php echo dentomart_icon( 'cart', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG. ?>
					<span class="dm-cart-count" <?php echo 0 === $cart_count ? 'data-empty="1"' : ''; ?>><?php echo esc_html( $cart_count ); ?></span>
				</span>
				<span class="dm-action__label">
					<span class="dm-action__top"><?php esc_html_e( 'Cart', 'dentomart' ); ?></span>
					<span class="dm-action__sub dm-action__sub--cart"><?php echo 0 === $cart_count ? esc_html__( 'Empty', 'dentomart' ) : sprintf( esc_html( _n( '%s item', '%s items', $cart_count, 'dentomart' ) ), number_format_i18n( $cart_count ) ); ?></span>
				</span>
			</a>
			<?php endif; ?>
		</div>

	</div>
</div>
