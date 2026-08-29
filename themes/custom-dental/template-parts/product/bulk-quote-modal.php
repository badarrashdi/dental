<?php
/**
 * Template part for Bulk Quote Modal.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="dm-modal" id="dmBulkQuoteModal" aria-hidden="true" role="dialog" aria-labelledby="dmBulkQuoteTitle">
	<div class="dm-modal__backdrop" data-dm-close-quote></div>
	<div class="dm-modal__dialog">
		<button type="button" class="dm-modal__close" data-dm-close-quote aria-label="<?php esc_attr_e( 'Close Modal', 'dentomart' ); ?>">
			&times;
		</button>

		<div class="dm-modal__header">
			<div class="dm-modal__badge">
				<?php echo dentomart_icon( 'tag', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span><?php esc_html_e( 'Institutional Wholesale Quote', 'dentomart' ); ?></span>
			</div>
			<h3 class="dm-modal__title" id="dmBulkQuoteTitle"><?php esc_html_e( 'Get Custom Bulk Pricing', 'dentomart' ); ?></h3>
			<p class="dm-modal__subtitle"><?php esc_html_e( 'Get personalized tier discount pricing for clinic, hospital, or distributor volume orders.', 'dentomart' ); ?></p>
		</div>

		<div class="dm-modal__body">
			<!-- ATTACHED PRODUCT CARD PREVIEW -->
			<div class="dm-quote-product-card" data-quote-product-card>
				<div class="dm-quote-product-card__thumb">
					<img src="" alt="" data-quote-product-img />
				</div>
				<div class="dm-quote-product-card__meta">
					<span class="dm-quote-product-card__brand" data-quote-product-brand></span>
					<h4 class="dm-quote-product-card__title" data-quote-product-title></h4>
					<div class="dm-quote-product-card__details">
						<span class="dm-quote-product-card__sku"><?php esc_html_e( 'SKU:', 'dentomart' ); ?> <strong data-quote-product-sku>-</strong></span>
						<span class="dm-quote-product-card__price" data-quote-product-price></span>
					</div>
				</div>
			</div>

			<!-- SUCCESS NOTICE (Hidden by default) -->
			<div class="dm-quote-success" data-quote-success style="display: none;">
				<div class="dm-quote-success__icon">
					<?php echo dentomart_icon( 'check', 32 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
				<h4 class="dm-quote-success__title"><?php esc_html_e( 'Quote Request Received!', 'dentomart' ); ?></h4>
				<p class="dm-quote-success__text" data-quote-success-msg></p>
				<button type="button" class="dm-btn dm-btn--accent dm-btn--sm" data-dm-close-quote>
					<?php esc_html_e( 'Done', 'dentomart' ); ?>
				</button>
			</div>

			<!-- BULK QUOTE FORM -->
			<form class="dm-quote-form" data-quote-form>
				<input type="hidden" name="product_id" data-quote-input-id value="" />
				<input type="hidden" name="product_title" data-quote-input-title value="" />

				<div class="dm-quote-form__grid">
					<div class="dm-quote-form__group">
						<label for="dm_quote_name"><?php esc_html_e( 'Full Name / Doctor Name *', 'dentomart' ); ?></label>
						<input type="text" id="dm_quote_name" name="name" required placeholder="<?php esc_attr_e( 'e.g. Dr. Rajesh Sharma', 'dentomart' ); ?>" />
					</div>

					<div class="dm-quote-form__group">
						<label for="dm_quote_clinic"><?php esc_html_e( 'Clinic / Hospital / College Name *', 'dentomart' ); ?></label>
						<input type="text" id="dm_quote_clinic" name="clinic" required placeholder="<?php esc_attr_e( 'e.g. Apex Dental Care & Clinic', 'dentomart' ); ?>" />
					</div>

					<div class="dm-quote-form__group">
						<label for="dm_quote_phone"><?php esc_html_e( 'Mobile / WhatsApp Number *', 'dentomart' ); ?></label>
						<input type="tel" id="dm_quote_phone" name="phone" required placeholder="<?php esc_attr_e( 'e.g. 9876543210', 'dentomart' ); ?>" />
					</div>

					<div class="dm-quote-form__group">
						<label for="dm_quote_email"><?php esc_html_e( 'Email Address *', 'dentomart' ); ?></label>
						<input type="email" id="dm_quote_email" name="email" required placeholder="<?php esc_attr_e( 'e.g. doctor@clinic.com', 'dentomart' ); ?>" />
					</div>

					<div class="dm-quote-form__group">
						<label for="dm_quote_qty"><?php esc_html_e( 'Estimated Quantity Required (Units) *', 'dentomart' ); ?></label>
						<input type="number" id="dm_quote_qty" name="quantity" min="1" required value="50" placeholder="50" />
					</div>

					<div class="dm-quote-form__group">
						<label for="dm_quote_pincode"><?php esc_html_e( 'Delivery City / Pincode', 'dentomart' ); ?></label>
						<input type="text" id="dm_quote_pincode" name="pincode" placeholder="<?php esc_attr_e( 'e.g. Mumbai, 400001', 'dentomart' ); ?>" />
					</div>
				</div>

				<div class="dm-quote-form__group dm-quote-form__group--full">
					<label for="dm_quote_notes"><?php esc_html_e( 'Additional Notes / GST Requirements', 'dentomart' ); ?></label>
					<textarea id="dm_quote_notes" name="notes" rows="2" placeholder="<?php esc_attr_e( 'Specify any custom packaging, delivery schedule, or target unit pricing...', 'dentomart' ); ?>"></textarea>
				</div>

				<div class="dm-quote-form__footer">
					<button type="submit" class="dm-btn dm-btn--accent dm-btn--lg dm-quote-form__submit">
						<?php echo dentomart_icon( 'tag', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span><?php esc_html_e( 'Submit Institutional Quote Request', 'dentomart' ); ?></span>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
