<?php
/**
 * Homepage "Top Brands" Slider — DentalKart style.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow  = dentomart_home_field( 'brands_eyebrow', __( '400+ Brands in Stock', 'dentomart' ) );
$title    = dentomart_home_field( 'brands_title', __( 'Top Dental Brands', 'dentomart' ) );
$subtitle = dentomart_home_field( 'brands_subtitle', __( 'Direct authorized distribution from world-leading dental manufacturers.', 'dentomart' ) );

$selected = dentomart_home_field( 'brands_list', array() );
if ( empty( $selected ) ) {
	$selected = dentomart_default_brands();
}

$terms = array();
if ( ! empty( $selected ) ) {
	foreach ( $selected as $term_id ) {
		$term = get_term( (int) $term_id, 'pa_brand' );
		if ( $term && ! is_wp_error( $term ) ) {
			$terms[] = $term;
		}
	}
}

if ( empty( $terms ) ) {
	$terms = get_terms( array(
		'taxonomy'   => 'pa_brand',
		'hide_empty' => true,
		'number'     => 12,
		'orderby'    => 'count',
		'order'      => 'DESC',
	) );
}

if ( empty( $terms ) || is_wp_error( $terms ) ) {
	return;
}

$images   = dentomart_brand_term_images( wp_list_pluck( $terms, 'term_id' ) );
$track_id = 'track-brands-slider';
?>
<section class="dm-section dm-brands-slider-section" id="top-brands" aria-labelledby="dm-top-brands-title">
	<div class="dm-container">
		<div class="dm-slider-header">
			<div class="dm-slider-header__left">
				<?php if ( $eyebrow ) : ?>
					<span class="dm-slider-eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
				<?php endif; ?>
				<h2 class="dm-slider-title" id="dm-top-brands-title"><?php echo esc_html( $title ); ?></h2>
				<?php if ( $subtitle ) : ?>
					<p class="dm-slider-subtitle"><?php echo esc_html( $subtitle ); ?></p>
				<?php endif; ?>
			</div>

			<div class="dm-slider-header__right">
				<a class="dm-slider-more-link" href="<?php echo esc_url( home_url( '/brands/' ) ); ?>">
					<span><?php esc_html_e( 'View All Brands', 'dentomart' ); ?></span>
					<?php echo dentomart_icon( 'chevron-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</a>
				<div class="dm-slider-nav" data-dm-scroll-controls="<?php echo esc_attr( $track_id ); ?>">
					<button type="button" class="dm-slider-nav__btn dm-slider-nav__btn--prev" data-dm-scroll="prev" aria-label="<?php esc_attr_e( 'Previous brands', 'dentomart' ); ?>" disabled>
						<?php echo dentomart_icon( 'chevron-left', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</button>
					<button type="button" class="dm-slider-nav__btn dm-slider-nav__btn--next" data-dm-scroll="next" aria-label="<?php esc_attr_e( 'Next brands', 'dentomart' ); ?>">
						<?php echo dentomart_icon( 'chevron-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</button>
				</div>
			</div>
		</div>

		<div class="dm-slider-wrapper">
			<div class="dm-brands-track" id="<?php echo esc_attr( $track_id ); ?>" tabindex="0">
				<?php foreach ( $terms as $term ) : ?>
					<?php
					$image_id  = isset( $images[ $term->term_id ] ) ? $images[ $term->term_id ] : 0;
					$term_link = get_term_link( $term );
					$initial   = mb_substr( $term->name, 0, 1 );
					?>
					<a class="dm-brand-card" href="<?php echo esc_url( $term_link ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Shop %s products', 'dentomart' ), $term->name ) ); ?>">
						<div class="dm-brand-card__media">
							<?php if ( $image_id ) : ?>
								<?php echo wp_get_attachment_image( $image_id, 'dentomart-brand', false, array( 'class' => 'dm-brand-card__img', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<?php else : ?>
								<span class="dm-brand-card__placeholder" aria-hidden="true"><?php echo esc_html( $initial ); ?></span>
							<?php endif; ?>
						</div>
						<span class="dm-brand-card__name"><?php echo esc_html( $term->name ); ?></span>
						<?php if ( ! empty( $term->count ) ) : ?>
							<span class="dm-brand-card__count"><?php echo sprintf( esc_html__( '%s Products', 'dentomart' ), number_format_i18n( $term->count ) ); ?></span>
						<?php endif; ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
