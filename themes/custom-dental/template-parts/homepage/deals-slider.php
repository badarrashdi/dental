<?php
/**
 * Homepage "Flash Deals & Limited Time Offers" slider — DentalKart style.
 * Supports manual product selection & diverse brand deals.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title       = dentomart_home_field( 'deals_title', __( 'Flash Deals & Today\'s Offers', 'dentomart' ) );
$subtitle    = dentomart_home_field( 'deals_subtitle', __( 'Exclusive clinical practice discounts ending soon. Limited stock available.', 'dentomart' ) );
$count       = (int) dentomart_home_field( 'deals_count', 10 );
$manual_ids  = dentomart_home_field( 'deals_products', array() );

if ( is_string( $manual_ids ) && ! empty( $manual_ids ) ) {
	$manual_ids = array_filter( array_map( 'trim', explode( ',', $manual_ids ) ) );
}

// Query diverse brand deals
$query = dentomart_query_diverse_products( array(
	'posts_per_page'     => $count > 0 ? $count : 10,
	'orderby'            => 'popularity',
	'order'              => 'DESC',
	'post__in'           => ! empty( $manual_ids ) ? (array) $manual_ids : array(),
	'placement_meta_key' => '_dm_show_on_flash_deals',
	'one_per_brand'      => true,
	'exclude_rendered'   => true,
) );

if ( ! $query || ! $query->have_posts() ) {
	return;
}

$track_id = 'track-deals-slider';
?>
<section class="dm-section dm-deals-slider-section" id="flash-deals" aria-labelledby="dm-deals-title">
	<div class="dm-container">
		<div class="dm-deals-header">
			<div class="dm-deals-header__left">
				<div class="dm-deals-badge">
					<span class="dm-deals-badge__icon"><?php echo dentomart_icon( 'fire', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<span class="dm-deals-badge__text"><?php esc_html_e( 'DEALS OF THE DAY', 'dentomart' ); ?></span>
				</div>
				<h2 class="dm-deals-title" id="dm-deals-title"><?php echo esc_html( $title ); ?></h2>
				<?php if ( $subtitle ) : ?>
					<p class="dm-deals-subtitle"><?php echo esc_html( $subtitle ); ?></p>
				<?php endif; ?>
			</div>

			<div class="dm-deals-header__center">
				<div class="dm-countdown-widget" data-dm-countdown>
					<span class="dm-countdown-label"><?php echo dentomart_icon( 'clock', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php esc_html_e( 'Ends in:', 'dentomart' ); ?></span>
					<div class="dm-countdown-boxes">
						<span class="dm-countdown-box"><strong data-dm-hours>08</strong><small>Hrs</small></span>
						<span class="dm-countdown-sep">:</span>
						<span class="dm-countdown-box"><strong data-dm-minutes>42</strong><small>Min</small></span>
						<span class="dm-countdown-sep">:</span>
						<span class="dm-countdown-box"><strong data-dm-seconds>19</strong><small>Sec</small></span>
					</div>
				</div>
			</div>

			<div class="dm-deals-header__right">
				<a class="dm-slider-more-link" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) . '?on_sale=1' ); ?>">
					<span><?php esc_html_e( 'View All Deals', 'dentomart' ); ?></span>
					<?php echo dentomart_icon( 'chevron-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</a>
				<div class="dm-slider-nav" data-dm-scroll-controls="<?php echo esc_attr( $track_id ); ?>">
					<button type="button" class="dm-slider-nav__btn dm-slider-nav__btn--prev" data-dm-scroll="prev" aria-label="<?php esc_attr_e( 'Previous deals', 'dentomart' ); ?>" disabled>
						<?php echo dentomart_icon( 'chevron-left', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</button>
					<button type="button" class="dm-slider-nav__btn dm-slider-nav__btn--next" data-dm-scroll="next" aria-label="<?php esc_attr_e( 'Next deals', 'dentomart' ); ?>">
						<?php echo dentomart_icon( 'chevron-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</button>
				</div>
			</div>
		</div>

		<div class="dm-slider-wrapper">
			<div class="dm-slider-track dm-slider-track--deals" id="<?php echo esc_attr( $track_id ); ?>" tabindex="0" aria-label="<?php esc_attr_e( 'Flash Deals', 'dentomart' ); ?>">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					?>
					<div class="dm-slider-item">
						<?php dentomart_product_card( $query->post, 'dm-product-card--deal' ); ?>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</div>
</section>
