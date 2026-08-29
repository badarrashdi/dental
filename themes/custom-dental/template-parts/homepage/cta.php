<?php
/**
 * Homepage closing CTA band — "Didn't find what you were looking for? Suggest a Product".
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="dm-section dm-homepage-suggest-section" aria-label="<?php esc_attr_e( 'Suggest a product', 'dentomart' ); ?>">
	<div class="dm-container">
		<div class="dm-homepage-suggest-banner">
			<div class="dm-homepage-suggest-copy">
				<span class="dm-homepage-suggest-badge"><?php esc_html_e( 'Custom Dental Procurement', 'dentomart' ); ?></span>
				<h2 class="dm-homepage-suggest-title"><?php esc_html_e( "Didn't Find What You Were Looking For?", 'dentomart' ); ?></h2>
				<p class="dm-homepage-suggest-desc"><?php esc_html_e( 'Tell us which dental material, instrument or equipment model you need. Our clinical procurement team will source it at direct wholesale rates.', 'dentomart' ); ?></p>
			</div>
			<div class="dm-homepage-suggest-action">
				<button type="button" class="dm-btn dm-btn--accent dm-btn--lg" data-action="suggest">
					<?php echo dentomart_icon( 'tag', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span><?php esc_html_e( 'SUGGEST A PRODUCT', 'dentomart' ); ?></span>
					<?php echo dentomart_icon( 'arrow-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</button>
			</div>
		</div>
	</div>
</section>
