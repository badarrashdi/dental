<?php
/**
 * Homepage "New Arrivals & Wholesale Deals" product grid — DentalKart style (4-column clean grid).
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = array(
	'posts_per_page' => 8,
	'orderby'        => 'date',
	'order'          => 'DESC',
);

$query = dentomart_product_query( $args );

if ( ! $query->have_posts() ) {
	return;
}
?>
<section class="dm-section dm-product-grid-section dm-deals-section" id="new-arrivals" aria-labelledby="dm-newarrivals-title">
	<div class="dm-container">
		<header class="dm-section__head">
			<div>
				<p class="dm-section__eyebrow"><?php esc_html_e( 'Direct Wholesale Supplies', 'dentomart' ); ?></p>
				<h2 class="dm-section__title" id="dm-newarrivals-title"><?php esc_html_e( 'New Arrivals & Wholesale Deals', 'dentomart' ); ?></h2>
				<p class="dm-section__subtitle"><?php esc_html_e( 'Fresh inventory arrivals with automatic tiered volume discounts for bulk clinic orders.', 'dentomart' ); ?></p>
			</div>
			<a class="dm-section__more" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) . '?orderby=date' ); ?>">
				<span><?php esc_html_e( 'View All', 'dentomart' ); ?></span>
				<?php echo dentomart_icon( 'chevron-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</a>
		</header>

		<div class="dm-products-grid">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				dentomart_product_card( $query->post );
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
