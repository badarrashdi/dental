<?php
/**
 * Homepage Hero Banner Slider — DentalKart style multi-slide carousel.
 * Equal height, full-width full-height image banners, no description, heading + CTA, outer controls.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$img_base = get_template_directory_uri() . '/assets/images/';

// Curated dental promotional banners with full-width product imagery & punchy headings + CTA
$slides = array(
	array(
		'tag'       => __( 'UP TO 35% OFF • ENDODONTICS & RESTORATIVES', 'dentomart' ),
		'title'     => __( 'ProTaper Gold Files, Universal Composites & LED Curing Lights', 'dentomart' ),
		'btn_label' => __( 'Shop Endodontics & Composites', 'dentomart' ),
		'btn_url'   => wc_get_page_permalink( 'shop' ) . '?on_sale=1',
		'img_url'   => $img_base . 'hero-composites.jpg',
	),
	array(
		'tag'       => __( 'SURGICAL PRECISION • 1-YEAR WARRANTY', 'dentomart' ),
		'title'     => __( 'Whisper-Quiet LED High-Speed Airotors & Micro-Motors', 'dentomart' ),
		'btn_label' => __( 'Explore Handpieces & Instruments', 'dentomart' ),
		'btn_url'   => wc_get_page_permalink( 'shop' ),
		'img_url'   => $img_base . 'hero-handpiece.jpg',
	),
	array(
		'tag'       => __( 'DIRECT FACTORY PRICING • GST INVOICING', 'dentomart' ),
		'title'     => __( 'Wholesale Bulk Dental Supplies for Clinics & Hospitals', 'dentomart' ),
		'btn_label' => __( 'View Wholesale Clinic Deals', 'dentomart' ),
		'btn_url'   => wc_get_page_permalink( 'shop' ),
		'img_url'   => $img_base . 'about-team.jpg',
	),
	array(
		'tag'       => __( 'CLINICAL INFRASTRUCTURE • ON-SITE DEMO', 'dentomart' ),
		'title'     => __( 'Next-Gen Class B Autoclaves, 3D Scanners & Apex Locators', 'dentomart' ),
		'btn_label' => __( 'Explore Clinical Equipment', 'dentomart' ),
		'btn_url'   => wc_get_page_permalink( 'shop' ),
		'img_url'   => $img_base . 'about-lab.jpg',
	),
);
?>
<section class="dm-hero-slider-section" aria-label="<?php esc_attr_e( 'Promotional Banners', 'dentomart' ); ?>">
	<div class="dm-container">
		<div class="dm-hero-slider-wrap" data-dm-hero-slider>
			
			<!-- Left Arrow (Outside of Slide) -->
			<button type="button" class="dm-hero-arrow dm-hero-arrow--prev" data-dm-hero-prev aria-label="<?php esc_attr_e( 'Previous slide', 'dentomart' ); ?>">
				<?php echo dentomart_icon( 'chevron-left', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</button>

			<!-- Slide Banner Stage (Equal Height, Full-Width Full-Height Images) -->
			<div class="dm-hero-slider-container">
				<div class="dm-hero-slider-track">
					<?php foreach ( $slides as $i => $slide ) : ?>
						<?php
						$img_url   = ! empty( $slide['img_url'] ) ? $slide['img_url'] : '';
						$is_active = ( 0 === $i );
						?>
						<div class="dm-hero-slide <?php echo $is_active ? 'is-active' : ''; ?>" data-slide-index="<?php echo esc_attr( $i ); ?>">
							
							<!-- Full Width Full Height Background Image -->
							<?php if ( $img_url ) : ?>
								<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $slide['title'] ); ?>" class="dm-hero-slide__full-bg" loading="<?php echo $is_active ? 'eager' : 'lazy'; ?>" />
							<?php endif; ?>

							<!-- High-Contrast Overlay Gradient -->
							<div class="dm-hero-slide__overlay"></div>

							<!-- Slide Content: Heading + CTA only -->
							<div class="dm-hero-slide__content">
								<?php if ( ! empty( $slide['tag'] ) ) : ?>
									<span class="dm-hero-slide__tag">
										<?php echo dentomart_icon( 'zap', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
										<?php echo esc_html( $slide['tag'] ); ?>
									</span>
								<?php endif; ?>

								<h1 class="dm-hero-slide__title"><?php echo esc_html( $slide['title'] ); ?></h1>

								<div class="dm-hero-slide__actions">
									<a class="dm-btn dm-btn--accent dm-btn--lg dm-hero-slide__cta" href="<?php echo esc_url( ! empty( $slide['btn_url'] ) ? $slide['btn_url'] : wc_get_page_permalink( 'shop' ) ); ?>">
										<span><?php echo esc_html( ! empty( $slide['btn_label'] ) ? $slide['btn_label'] : __( 'Shop Now', 'dentomart' ) ); ?></span>
										<?php echo dentomart_icon( 'arrow-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
									</a>
								</div>
							</div>

						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- Right Arrow (Outside of Slide) -->
			<button type="button" class="dm-hero-arrow dm-hero-arrow--next" data-dm-hero-next aria-label="<?php esc_attr_e( 'Next slide', 'dentomart' ); ?>">
				<?php echo dentomart_icon( 'chevron-right', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</button>

		</div>

		<!-- Dots Pagination (Outside of Slide below) -->
		<div class="dm-hero-slider-dots" role="tablist">
			<?php foreach ( $slides as $i => $slide ) : ?>
				<button type="button" class="dm-hero-dot <?php echo 0 === $i ? 'is-active' : ''; ?>" data-slide-target="<?php echo esc_attr( $i ); ?>" role="tab" aria-label="<?php echo esc_attr( sprintf( __( 'Slide %d', 'dentomart' ), $i + 1 ) ); ?>"></button>
			<?php endforeach; ?>
		</div>

	</div>
</section>
