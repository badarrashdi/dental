<?php
/**
 * Homepage "Best Sellers" product grid — DentalKart style (4-column clean grid).
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow  = dentomart_home_field( 'products_eyebrow', __( 'Most Ordered by Doctors', 'dentomart' ) );
$title    = dentomart_home_field( 'products_title', __( 'Best Sellers', 'dentomart' ) );
$subtitle = dentomart_home_field( 'products_subtitle', __( 'Proven clinical performance and high-demand daily practice consumables.', 'dentomart' ) );
$count    = (int) dentomart_home_field( 'products_count', 8 );

$args = array(
	'posts_per_page' => $count > 0 ? $count : 8,
	'orderby'        => 'popularity',
	'order'          => 'DESC',
);

$query = dentomart_product_query( $args );

if ( ! $query->have_posts() ) {
	// Fallback to recent products
	$query = dentomart_product_query( array( 'posts_per_page' => 8, 'orderby' => 'date', 'order' => 'DESC' ) );
}

if ( ! $query->have_posts() ) {
	return;
}
?>
<section class="dm-section dm-product-grid-section" id="best-sellers" aria-labelledby="dm-bestsellers-title">
	<div class="dm-container">
		<header class="dm-section__head">
			<div>
				<?php if ( $eyebrow ) : ?>
					<p class="dm-section__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
				<?php endif; ?>
				<h2 class="dm-section__title" id="dm-bestsellers-title"><?php echo esc_html( $title ); ?></h2>
				<?php if ( $subtitle ) : ?>
					<p class="dm-section__subtitle"><?php echo esc_html( $subtitle ); ?></p>
				<?php endif; ?>
			</div>
			<a class="dm-section__more" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) . '?orderby=popularity' ); ?>">
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
