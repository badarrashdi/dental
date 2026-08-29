<?php
/**
 * Homepage "Top Categories" Carousel / Slider — DentalKart style.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow  = dentomart_home_field( 'categories_eyebrow', __( 'Explore Dental Departments', 'dentomart' ) );
$title    = dentomart_home_field( 'categories_title', __( 'Top Categories', 'dentomart' ) );
$subtitle = dentomart_home_field( 'categories_subtitle', __( 'Find everything from endodontic rotary files to heavy clinic equipment.', 'dentomart' ) );

$selected = dentomart_home_field( 'categories_list', array() );
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

// If terms is still empty, get top product categories
if ( empty( $terms ) ) {
	$terms = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'number'     => 12,
		'orderby'    => 'count',
		'order'      => 'DESC',
	) );
}

if ( empty( $terms ) || is_wp_error( $terms ) ) {
	return;
}

$images = dentomart_terms_images( wp_list_pluck( $terms, 'term_id' ) );
$track_id = 'track-categories-slider';
?>
<section class="dm-section dm-categories-slider-section" id="top-categories" aria-labelledby="dm-top-categories-title">
	<div class="dm-container">
		<div class="dm-slider-header">
			<div class="dm-slider-header__left">
				<?php if ( $eyebrow ) : ?>
					<span class="dm-slider-eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
				<?php endif; ?>
				<h2 class="dm-slider-title" id="dm-top-categories-title"><?php echo esc_html( $title ); ?></h2>
				<?php if ( $subtitle ) : ?>
					<p class="dm-slider-subtitle"><?php echo esc_html( $subtitle ); ?></p>
				<?php endif; ?>
			</div>

			<div class="dm-slider-header__right">
				<a class="dm-slider-more-link" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
					<span><?php esc_html_e( 'View All Categories', 'dentomart' ); ?></span>
					<?php echo dentomart_icon( 'chevron-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</a>
				<div class="dm-slider-nav" data-dm-scroll-controls="<?php echo esc_attr( $track_id ); ?>">
					<button type="button" class="dm-slider-nav__btn dm-slider-nav__btn--prev" data-dm-scroll="prev" aria-label="<?php esc_attr_e( 'Previous categories', 'dentomart' ); ?>" disabled>
						<?php echo dentomart_icon( 'chevron-left', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</button>
					<button type="button" class="dm-slider-nav__btn dm-slider-nav__btn--next" data-dm-scroll="next" aria-label="<?php esc_attr_e( 'Next categories', 'dentomart' ); ?>">
						<?php echo dentomart_icon( 'chevron-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</button>
				</div>
			</div>
		</div>

		<div class="dm-slider-wrapper">
			<div class="dm-categories-track" id="<?php echo esc_attr( $track_id ); ?>" tabindex="0">
				<?php foreach ( $terms as $term ) : ?>
					<?php
					$image_id  = isset( $images[ $term->term_id ] ) ? $images[ $term->term_id ] : 0;
					$has_img   = $image_id ? wp_get_attachment_image_src( $image_id, 'dentomart-category' ) : false;
					$term_link = get_term_link( $term );
					?>
					<a class="dm-category-circle-card" href="<?php echo esc_url( $term_link ); ?>">
						<div class="dm-category-circle-card__media">
							<?php if ( $has_img ) : ?>
								<?php echo wp_get_attachment_image( $image_id, 'dentomart-category', false, array( 'class' => 'dm-category-circle-card__img', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<?php else : ?>
								<div class="dm-category-circle-card__icon-fallback">
									<svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.75">
										<path d="M12 2C9 2 7 3.5 5.5 5.5 4 7.5 3 10 3 13.5c0 2 .5 4 1.5 5.5.7 1.1 1.7 1.5 2.4 1.5.9 0 1.3-.6 2-1.3.7-.8 1.5-1.7 3.1-1.7s2.4.9 3.1 1.7c.7.7 1.1 1.3 2 1.3.7 0 1.7-.4 2.4-1.5 1-1.5 1.5-3.5 1.5-5.5 0-3.5-1-6-2.5-8C17 3.5 15 2 12 2z"/>
									</svg>
								</div>
							<?php endif; ?>
						</div>
						<span class="dm-category-circle-card__name"><?php echo esc_html( $term->name ); ?></span>
						<?php if ( ! empty( $term->count ) ) : ?>
							<span class="dm-category-circle-card__count"><?php echo sprintf( esc_html__( '%s Items', 'dentomart' ), number_format_i18n( $term->count ) ); ?></span>
						<?php endif; ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
