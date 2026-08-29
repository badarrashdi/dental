<?php
/**
 * Template part for Header Pincode Modal.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="dm-modal" id="dmPincodeHeaderModal" aria-hidden="true" role="dialog" aria-labelledby="dmPincodeModalTitle">
	<div class="dm-modal__backdrop" data-dm-close-pincode></div>
	<div class="dm-modal__dialog dm-modal__dialog--sm">
		<button type="button" class="dm-modal__close" data-dm-close-pincode aria-label="<?php esc_attr_e( 'Close Modal', 'dentomart' ); ?>">
			&times;
		</button>

		<div class="dm-modal__header">
			<div class="dm-modal__badge">
				<?php echo dentomart_icon( 'pin', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span><?php esc_html_e( 'Select Clinic Location', 'dentomart' ); ?></span>
			</div>
			<h3 class="dm-modal__title" id="dmPincodeModalTitle"><?php esc_html_e( 'Enter Delivery Pincode', 'dentomart' ); ?></h3>
			<p class="dm-modal__subtitle"><?php esc_html_e( 'Verify dispatch speeds, express delivery options, and clinic lead times for your area.', 'dentomart' ); ?></p>
		</div>

		<div class="dm-modal__body">
			<form class="dm-pincode-modal-form" data-pincode-modal-form>
				<div class="dm-pincode-modal-input-wrap">
					<input type="text" id="dm_header_pincode_input" maxlength="6" inputmode="numeric" placeholder="<?php esc_attr_e( 'e.g. 110001 or 400001', 'dentomart' ); ?>" required />
					<button type="submit" class="dm-btn dm-btn--accent dm-btn--sm">
						<?php esc_html_e( 'Verify PIN', 'dentomart' ); ?>
					</button>
				</div>
				<p class="dm-pincode-modal-result" data-pincode-modal-result style="display: none;"></p>

				<div class="dm-pincode-modal-footer">
					<button type="button" class="dm-pincode-reset-btn" data-pincode-reset style="display: none;">
						<?php esc_html_e( 'Clear Saved Location', 'dentomart' ); ?>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
