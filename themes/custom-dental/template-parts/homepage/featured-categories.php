<?php
/**
 * Homepage "Featured Category Promotional Banners" — DentalKart style.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow  = dentomart_home_field( 'featured_cats_eyebrow', __( 'Curated Clinic Bundles', 'dentomart' ) );
$title    = dentomart_home_field( 'featured_cats_title', __( 'Featured Collections & Offers', 'dentomart' ) );
$subtitle = dentomart_home_field( 'featured_cats_subtitle', __( 'Top demanded dental materials with special clinic bundle discounts.', 'dentomart' ) );

$selected = dentomart_home_field( 'featured_cats_list', array() );
if ( empty( $selected ) ) {
	$selected = dentomart_default_categories();
}

$terms = array();
if ( ! empty( $selected ) ) {
	foreach ( $selected as $term_id ) {
		$term = get_term( (int) $term_id, 'product_cat' );
		if ( $term && ! is_wp_error( $term ) ) {
			$terms[] = $term;
		}
	}
}

if ( empty( $terms ) ) {
	$terms = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'number'     => 4,
		'orderby'    => 'count',
		'order'      => 'DESC',
	) );
}

if ( empty( $terms ) || is_wp_error( $terms ) ) {
	return;
}

$terms  = array_slice( $terms, 0, 4 );
$images = dentomart_terms_images( wp_list_pluck( $terms, 'term_id' ) );

$banner_themes = array(
	array( 'badge' => 'UP TO 35% OFF', 'accent' => 'dm-banner--blue',   'tag' => 'Endodontics Special' ),
	array( 'badge' => 'BULK VALUE PACK', 'accent' => 'dm-banner--teal',   'tag' => 'Restorative Materials' ),
	array( 'badge' => 'FREE INSTALLATION', 'accent' => 'dm-banner--navy',   'tag' => 'Clinical Equipment' ),
	array( 'badge' => 'EXTRA 15% OFF', 'accent' => 'dm-banner--orange', 'tag' => 'Daily Disposables' ),
);
?>
<section class="dm-section dm-promo-banners-section" id="featured-collections" aria-labelledby="dm-featured-banners-title">
	<div class="dm-container">
		<div class="dm-slider-header">
			<div class="dm-slider-header__left">
				<?php if ( $eyebrow ) : ?>
					<span class="dm-slider-eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
				<?php endif; ?>
				<h2 class="dm-slider-title" id="dm-featured-banners-title"><?php echo esc_html( $title ); ?></h2>
				<?php if ( $subtitle ) : ?>
					<p class="dm-slider-subtitle"><?php echo esc_html( $subtitle ); ?></p>
				<?php endif; ?>
			</div>
			<div class="dm-slider-header__right">
				<a class="dm-slider-more-link" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
					<span><?php esc_html_e( 'View All Deals', 'dentomart' ); ?></span>
					<?php echo dentomart_icon( 'chevron-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</a>
			</div>
		</div>

		<div class="dm-promo-banners-grid">
			<?php foreach ( $terms as $index => $term ) : ?>
				<?php
				$theme_data = isset( $banner_themes[ $index ] ) ? $banner_themes[ $index ] : $banner_themes[0];
				$image_id   = isset( $images[ $term->term_id ] ) ? $images[ $term->term_id ] : 0;
				$has_img    = $image_id ? wp_get_attachment_image_src( $image_id, 'dentomart-banner' ) : false;
				$term_link  = get_term_link( $term );
				?>
				<a class="dm-promo-banner-card <?php echo esc_attr( $theme_data['accent'] ); ?>" href="<?php echo esc_url( $term_link ); ?>">
					<div class="dm-promo-banner-card__content">
						<span class="dm-promo-banner-card__badge"><?php echo esc_html( $theme_data['badge'] ); ?></span>
						<span class="dm-promo-banner-card__tag"><?php echo esc_html( $theme_data['tag'] ); ?></span>
						<h3 class="dm-promo-banner-card__title"><?php echo esc_html( $term->name ); ?></h3>
						<span class="dm-promo-banner-card__cta">
							<span><?php esc_html_e( 'Shop Category', 'dentomart' ); ?></span>
							<?php echo dentomart_icon( 'arrow-right', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</span>
					</div>
					<div class="dm-promo-banner-card__media">
						<?php if ( $has_img ) : ?>
							<?php echo wp_get_attachment_image( $image_id, 'dentomart-banner', false, array( 'class' => 'dm-promo-banner-card__img', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<?php else : ?>
							<div class="dm-promo-banner-card__fallback">
								<svg viewBox="0 0 100 100" width="80" height="80" fill="none" opacity="0.4">
									<circle cx="50" cy="50" r="45" stroke="currentColor" stroke-width="6"/>
									<path d="M30 50L45 65L75 35" stroke="currentColor" stroke-width="8" stroke-linecap="round"/>
								</svg>
							</div>
						<?php endif; ?>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
