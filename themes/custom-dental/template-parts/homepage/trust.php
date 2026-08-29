<?php
/**
 * Homepage Trust Badges Strip — DentalKart style.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="dm-trust-strip" aria-label="<?php esc_attr_e( 'Store Guarantees', 'dentomart' ); ?>">
	<div class="dm-container">
		<div class="dm-trust-strip__grid">
			<div class="dm-trust-item">
				<div class="dm-trust-item__icon dm-trust-item__icon--blue">
					<?php echo dentomart_icon( 'badge-check', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
				<div class="dm-trust-item__text">
					<strong class="dm-trust-item__title"><?php esc_html_e( '100% Genuine Products', 'dentomart' ); ?></strong>
					<span class="dm-trust-item__desc"><?php esc_html_e( 'Direct manufacturer warranty', 'dentomart' ); ?></span>
				</div>
			</div>

			<div class="dm-trust-item">
				<div class="dm-trust-item__icon dm-trust-item__icon--teal">
					<?php echo dentomart_icon( 'box', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
				<div class="dm-trust-item__text">
					<strong class="dm-trust-item__title"><?php esc_html_e( '400+ Dental Brands', 'dentomart' ); ?></strong>
					<span class="dm-trust-item__desc"><?php esc_html_e( 'Over 17,000 verified supplies', 'dentomart' ); ?></span>
				</div>
			</div>

			<div class="dm-trust-item">
				<div class="dm-trust-item__icon dm-trust-item__icon--orange">
					<?php echo dentomart_icon( 'percent', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
				<div class="dm-trust-item__text">
					<strong class="dm-trust-item__title"><?php esc_html_e( 'Best Price Guarantee', 'dentomart' ); ?></strong>
					<span class="dm-trust-item__desc"><?php esc_html_e( 'Tiered clinic bulk discounts', 'dentomart' ); ?></span>
				</div>
			</div>

			<div class="dm-trust-item">
				<div class="dm-trust-item__icon dm-trust-item__icon--navy">
					<?php echo dentomart_icon( 'truck', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
				<div class="dm-trust-item__text">
					<strong class="dm-trust-item__title"><?php esc_html_e( 'Express Pan-India Shipping', 'dentomart' ); ?></strong>
					<span class="dm-trust-item__desc"><?php esc_html_e( 'Secure doorstep dispatch', 'dentomart' ); ?></span>
				</div>
			</div>
		</div>
	</div>
</section>
