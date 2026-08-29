<?php
/**
 * Template Name: About Us Page
 * Template for the About Us page — DentalKart style.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$team_img_url = get_template_directory_uri() . '/assets/images/about-team.jpg';
$lab_img_url  = get_template_directory_uri() . '/assets/images/about-lab.jpg';
?>

<div class="dm-about-page">

	<!-- Hero Header -->
	<section class="dm-about-hero">
		<div class="dm-container">
			<div class="dm-about-hero__inner">
				<span class="dm-about-hero__tag">
					<?php echo dentomart_icon( 'badge-check', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php esc_html_e( "India's Leading Dental Supply Partner", 'dentomart' ); ?>
				</span>
				<h1 class="dm-about-hero__title"><?php esc_html_e( 'Empowering Dental Practices with Genuine Supplies & Clinical Precision', 'dentomart' ); ?></h1>
				<p class="dm-about-hero__subtitle"><?php esc_html_e( 'Founded by dental specialists and healthcare supply chain experts, DentoMart delivers 20,000+ authentic dental consumables, premium instruments, and heavy clinic machinery directly to thousands of practices across India.', 'dentomart' ); ?></p>
			</div>

			<!-- Stats Ribbon -->
			<div class="dm-about-stats">
				<div class="dm-about-stat-item">
					<strong>50,000+</strong>
					<span><?php esc_html_e( 'Dentists & Clinics Served', 'dentomart' ); ?></span>
				</div>
				<div class="dm-about-stat-item">
					<strong>20,000+</strong>
					<span><?php esc_html_e( 'Genuine Products in Stock', 'dentomart' ); ?></span>
				</div>
				<div class="dm-about-stat-item">
					<strong>400+</strong>
					<span><?php esc_html_e( 'Authorized Global Brands', 'dentomart' ); ?></span>
				</div>
				<div class="dm-about-stat-item">
					<strong>99.4%</strong>
					<span><?php esc_html_e( 'On-Time Doorstep Delivery', 'dentomart' ); ?></span>
				</div>
			</div>
		</div>
	</section>

	<!-- Story & Team Section -->
	<section class="dm-about-section">
		<div class="dm-container">
			<div class="dm-about-grid">
				<div class="dm-about-text">
					<span class="dm-slider-eyebrow"><?php esc_html_e( 'OUR STORY & PURPOSE', 'dentomart' ); ?></span>
					<h2 class="dm-about-heading"><?php esc_html_e( 'Transforming Dental Procurement from Complex to Seamless', 'dentomart' ); ?></h2>
					<p><?php esc_html_e( 'For years, dental surgeons and clinic owners struggled with fragmented local dealer networks, inconsistent product batch freshness, and lack of transparent pricing. We built DentoMart to revolutionize how dental materials are sourced.', 'dentomart' ); ?></p>
					<p><?php esc_html_e( 'By eliminating multiple tiers of middlemen and partnering directly with leading global dental manufacturers, we guarantee 100% genuine products, assured manufacturer warranties, and automatic wholesale discounts for dental clinics.', 'dentomart' ); ?></p>

					<div class="dm-about-features-list">
						<div class="dm-about-feature">
							<?php echo dentomart_icon( 'check', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<div>
								<strong><?php esc_html_e( 'Direct Manufacturer Sourcing', 'dentomart' ); ?></strong>
								<p><?php esc_html_e( 'Zero counterfeit risk with verified batch certificates and manufacturer seal.', 'dentomart' ); ?></p>
							</div>
						</div>
						<div class="dm-about-feature">
							<?php echo dentomart_icon( 'check', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<div>
								<strong><?php esc_html_e( 'Pan-India Cold Chain & Express Logistics', 'dentomart' ); ?></strong>
								<p><?php esc_html_e( 'Temperature-controlled transport for sensitive composites, bonding agents, and biologicals.', 'dentomart' ); ?></p>
							</div>
						</div>
					</div>
				</div>

				<div class="dm-about-media">
					<div class="dm-about-media__card">
						<img src="<?php echo esc_url( $team_img_url ); ?>" alt="<?php esc_attr_e( 'DentoMart Dental Team and Warehouse Hub', 'dentomart' ); ?>" class="dm-about-img" loading="lazy" />
						<div class="dm-about-media__badge">
							<?php echo dentomart_icon( 'shield', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<div>
								<strong><?php esc_html_e( 'Certified Central Hub', 'dentomart' ); ?></strong>
								<span><?php esc_html_e( 'ISO 9001 Quality Certified Storage', 'dentomart' ); ?></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- 4 Core Pillars -->
	<section class="dm-about-pillars">
		<div class="dm-container">
			<div class="dm-slider-header" style="text-align:center; display:block; margin-bottom:36px;">
				<span class="dm-slider-eyebrow"><?php esc_html_e( 'WHY DOCTORS TRUST US', 'dentomart' ); ?></span>
				<h2 class="dm-slider-title" style="font-size:28px;"><?php esc_html_e( 'The 4 Pillars of DentoMart Excellence', 'dentomart' ); ?></h2>
			</div>

			<div class="dm-pillars-grid">
				<div class="dm-pillar-card">
					<div class="dm-pillar-card__icon dm-pillar-card__icon--blue">
						<?php echo dentomart_icon( 'badge-check', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
					<h3><?php esc_html_e( '100% Genuine Authenticity', 'dentomart' ); ?></h3>
					<p><?php esc_html_e( 'Every single item is authorized directly from brands like 3M, Dentsply, Mani, GDC, Woodpecker, and Kerr. Full manufacturer warranty on all handpieces and clinical machines.', 'dentomart' ); ?></p>
				</div>

				<div class="dm-pillar-card">
					<div class="dm-pillar-card__icon dm-pillar-card__icon--teal">
						<?php echo dentomart_icon( 'percent', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
					<h3><?php esc_html_e( 'Wholesale Clinic Pricing', 'dentomart' ); ?></h3>
					<p><?php esc_html_e( 'Automatic tiered discounts for dental clinics: save 2% on 5+ units and 4% on 10+ units on daily consumables. GST Input Tax Credit invoices provided with every single order.', 'dentomart' ); ?></p>
				</div>

				<div class="dm-pillar-card">
					<div class="dm-pillar-card__icon dm-pillar-card__icon--orange">
						<?php echo dentomart_icon( 'truck', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
					<h3><?php esc_html_e( 'Express 24-48h Dispatch', 'dentomart' ); ?></h3>
					<p><?php esc_html_e( 'Fast doorstep shipping to over 19,000+ PIN codes across India. Live SMS and WhatsApp tracking from our centralized fulfillment center to your clinic.', 'dentomart' ); ?></p>
				</div>

				<div class="dm-pillar-card">
					<div class="dm-pillar-card__icon dm-pillar-card__icon--navy">
						<?php echo dentomart_icon( 'phone', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
					<h3><?php esc_html_e( 'Dedicated Dental Desk', 'dentomart' ); ?></h3>
					<p><?php esc_html_e( 'Speak directly with certified dental specialists for technical advice, clinic setup consultations, equipment demos, and bulk procurement assistance.', 'dentomart' ); ?></p>
				</div>
			</div>
		</div>
	</section>

	<!-- Quality Lab & Product Inspection -->
	<section class="dm-about-section dm-about-section--alt">
		<div class="dm-container">
			<div class="dm-about-grid dm-about-grid--reverse">
				<div class="dm-about-text">
					<span class="dm-slider-eyebrow"><?php esc_html_e( 'QUALITY ASSURANCE', 'dentomart' ); ?></span>
					<h2 class="dm-about-heading"><?php esc_html_e( 'Rigorous Quality Checks for Zero Clinic Downtime', 'dentomart' ); ?></h2>
					<p><?php esc_html_e( 'Every rotary file, bur, curing light, and composite batch undergoes strict visual and barcode verification before leaving our temperature-regulated warehouse.', 'dentomart' ); ?></p>
					<p><?php esc_html_e( 'We understand that your clinical procedure depends on product precision. That is why we provide hassle-free transit insurance, instant batch replacements, and dedicated post-purchase technical support.', 'dentomart' ); ?></p>
					<div style="margin-top: 24px;">
						<a class="dm-btn dm-btn--accent dm-btn--lg" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
							<span><?php esc_html_e( 'Explore Dental Catalogue', 'dentomart' ); ?></span>
							<?php echo dentomart_icon( 'arrow-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</a>
					</div>
				</div>

				<div class="dm-about-media">
					<div class="dm-about-media__card">
						<img src="<?php echo esc_url( $lab_img_url ); ?>" alt="<?php esc_attr_e( 'DentoMart High Precision Lab & Quality Inspection', 'dentomart' ); ?>" class="dm-about-img" loading="lazy" />
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Closing CTA -->
	<?php get_template_part( 'template-parts/homepage/cta' ); ?>

</div>

<?php
get_footer();
