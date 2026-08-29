<?php
/**
 * Homepage "Why Choose Us" / Authority Content section — Ultra-Modern DentalKart Style.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="dm-section dm-authority-section" id="why-dentomart" aria-labelledby="dm-authority-title">
	<div class="dm-container">
		
		<!-- Section Header -->
		<div class="dm-authority-header">
			<span class="dm-authority-badge">
				<?php echo dentomart_icon( 'badge-check', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php esc_html_e( "India's Trusted Dental Supply Partner", 'dentomart' ); ?>
			</span>
			<h2 class="dm-authority-main-title" id="dm-authority-title">
				<?php esc_html_e( 'Why 50,000+ Dental Surgeons & Clinics Choose DentoMart', 'dentomart' ); ?>
			</h2>
			<p class="dm-authority-main-subtitle">
				<?php esc_html_e( 'Direct manufacturer sourcing, temperature-regulated cold chain logistics, and automated wholesale pricing engineered for modern clinical practices.', 'dentomart' ); ?>
			</p>
		</div>

		<!-- 4 Modern Authority Cards -->
		<div class="dm-authority-modern-grid">
			
			<div class="dm-auth-card dm-auth-card--blue">
				<div class="dm-auth-card__icon-wrap">
					<?php echo dentomart_icon( 'shield', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
				<div class="dm-auth-card__content">
					<span class="dm-auth-card__pill"><?php esc_html_e( 'AUTHENTICITY GUARANTEE', 'dentomart' ); ?></span>
					<h3 class="dm-auth-card__title"><?php esc_html_e( '100% Genuine Direct Brand Sourcing', 'dentomart' ); ?></h3>
					<p class="dm-auth-card__text"><?php esc_html_e( 'Factory-sealed genuine stock direct from authorized manufacturers like 3M, Dentsply Sirona, Mani, GDC, Woodpecker, and Kerr. Zero counterfeit risk with verified hologram batch freshness.', 'dentomart' ); ?></p>
					<div class="dm-auth-card__feature-tag">
						<?php echo dentomart_icon( 'check', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span><?php esc_html_e( 'Full Manufacturer Warranty on Handpieces & Equipment', 'dentomart' ); ?></span>
					</div>
				</div>
			</div>

			<div class="dm-auth-card dm-auth-card--teal">
				<div class="dm-auth-card__icon-wrap">
					<?php echo dentomart_icon( 'tag', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
				<div class="dm-auth-card__content">
					<span class="dm-auth-card__pill"><?php esc_html_e( 'CLINIC SAVINGS', 'dentomart' ); ?></span>
					<h3 class="dm-auth-card__title"><?php esc_html_e( 'Tiered Wholesale Pricing & GST Credit', 'dentomart' ); ?></h3>
					<p class="dm-auth-card__text"><?php esc_html_e( 'Save 2% on 5+ units and 4% on 10+ units automatically on daily practice consumables. Full GST Input Tax Credit invoices provided with every single shipment to optimize your clinic costs.', 'dentomart' ); ?></p>
					<div class="dm-auth-card__feature-tag">
						<?php echo dentomart_icon( 'check', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span><?php esc_html_e( 'Instant GST Tax Invoicing on Checkout', 'dentomart' ); ?></span>
					</div>
				</div>
			</div>

			<div class="dm-auth-card dm-auth-card--orange">
				<div class="dm-auth-card__icon-wrap">
					<?php echo dentomart_icon( 'truck', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
				<div class="dm-auth-card__content">
					<span class="dm-auth-card__pill"><?php esc_html_e( 'EXPRESS DISPATCH', 'dentomart' ); ?></span>
					<h3 class="dm-auth-card__title"><?php esc_html_e( 'Temperature-Regulated Logistics', 'dentomart' ); ?></h3>
					<p class="dm-auth-card__text"><?php esc_html_e( 'Guaranteed potency and viscosity for light-cure composites, bonding adhesives, and biologicals. Doorstep delivery across 19,000+ PIN codes with live WhatsApp tracking.', 'dentomart' ); ?></p>
					<div class="dm-auth-card__feature-tag">
						<?php echo dentomart_icon( 'check', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span><?php esc_html_e( 'Hassle-Free Transit Replacement Guarantee', 'dentomart' ); ?></span>
					</div>
				</div>
			</div>

			<div class="dm-auth-card dm-auth-card--navy">
				<div class="dm-auth-card__icon-wrap">
					<?php echo dentomart_icon( 'phone', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
				<div class="dm-auth-card__content">
					<span class="dm-auth-card__pill"><?php esc_html_e( 'CLINICAL SUPPORT', 'dentomart' ); ?></span>
					<h3 class="dm-auth-card__title"><?php esc_html_e( 'Doctor-to-Doctor Technical Desk', 'dentomart' ); ?></h3>
					<p class="dm-auth-card__text"><?php esc_html_e( 'Consult directly with our in-house dental surgeons for material recommendations, bulk clinic setups, and free on-site installation of autoclaves, dental chairs, and digital scanners.', 'dentomart' ); ?></p>
					<div class="dm-auth-card__feature-tag">
						<?php echo dentomart_icon( 'check', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span><?php esc_html_e( 'Biomedical Engineer Installation & Demo', 'dentomart' ); ?></span>
					</div>
				</div>
			</div>

		</div>

		<!-- Trust Metrics Ribbon -->
		<div class="dm-authority-metrics-strip">
			<div class="dm-auth-metric-item">
				<strong class="dm-auth-metric-val">50,000+</strong>
				<span class="dm-auth-metric-lbl"><?php esc_html_e( 'Dentists & Clinics Served', 'dentomart' ); ?></span>
			</div>
			<div class="dm-auth-metric-sep"></div>
			<div class="dm-auth-metric-item">
				<strong class="dm-auth-metric-val">20,000+</strong>
				<span class="dm-auth-metric-lbl"><?php esc_html_e( 'Certified Dental SKUs', 'dentomart' ); ?></span>
			</div>
			<div class="dm-auth-metric-sep"></div>
			<div class="dm-auth-metric-item">
				<strong class="dm-auth-metric-val">400+</strong>
				<span class="dm-auth-metric-lbl"><?php esc_html_e( 'Authorized Global Brands', 'dentomart' ); ?></span>
			</div>
			<div class="dm-auth-metric-sep"></div>
			<div class="dm-auth-metric-item">
				<strong class="dm-auth-metric-val">99.4%</strong>
				<span class="dm-auth-metric-lbl"><?php esc_html_e( 'On-Time Doorstep Delivery', 'dentomart' ); ?></span>
			</div>
		</div>

	</div>
</section>
