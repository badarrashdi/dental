<?php
/**
 * Homepage promotional banners.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$clearance_url = home_url( '/product-category/clearance-sale/' );
$new_url       = home_url( '/product-category/new-arrivals/' );

$promos = array(
	array(
		'image' => (int) dentomart_home_field( 'promo_a_image', 0 ),
		'title' => dentomart_home_field( 'promo_a_title', __( 'Clearance Sale', 'dentomart' ) ),
		'text'  => dentomart_home_field( 'promo_a_text', __( 'Great prices on a wide range of dental products. While stocks last.', 'dentomart' ) ),
		'cta'   => dentomart_home_field( 'promo_a_cta', __( 'Shop Clearance', 'dentomart' ) ),
		'url'   => dentomart_home_field( 'promo_a_url', $clearance_url ),
		'class' => 'dm-promo--clearance',
	),
	array(
		'image' => (int) dentomart_home_field( 'promo_b_image', 0 ),
		'title' => dentomart_home_field( 'promo_b_title', __( 'New Arrivals', 'dentomart' ) ),
		'text'  => dentomart_home_field( 'promo_b_text', __( 'Fresh stock added every week — instruments, materials and equipment.', 'dentomart' ) ),
		'cta'   => dentomart_home_field( 'promo_b_cta', __( 'Explore New Arrivals', 'dentomart' ) ),
		'url'   => dentomart_home_field( 'promo_b_url', $new_url ),
		'class' => 'dm-promo--arrivals',
	),
);

$renderable = array_filter( $promos, function ( $p ) {
	return $p['title'] && $p['url'];
} );

if ( empty( $renderable ) ) {
	return;
}
?>
<section class="dm-section dm-promos" aria-label="<?php esc_attr_e( 'Special offers', 'dentomart' ); ?>">
	<div class="dm-container dm-promos__grid">
		<?php foreach ( $renderable as $index => $promo ) : ?>
			<a class="dm-promo <?php echo esc_attr( $promo['class'] ); ?>" href="<?php echo esc_url( $promo['url'] ); ?>">
				<?php if ( $promo['image'] && wp_get_attachment_image_url( $promo['image'], 'dentomart-banner' ) ) : ?>
					<?php echo wp_get_attachment_image( $promo['image'], 'dentomart-banner', false, array( 'class' => 'dm-promo__img', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php endif; ?>
				<span class="dm-promo__scrim" aria-hidden="true"></span>
				<span class="dm-promo__content">
					<span class="dm-promo__tag"><?php echo esc_html( sprintf( __( 'Offer %d', 'dentomart' ), $index + 1 ) ); ?></span>
					<span class="dm-promo__title"><?php echo esc_html( $promo['title'] ); ?></span>
					<?php if ( $promo['text'] ) : ?>
						<span class="dm-promo__text"><?php echo esc_html( $promo['text'] ); ?></span>
					<?php endif; ?>
					<span class="dm-promo__cta">
						<?php echo esc_html( $promo['cta'] ); ?>
						<?php echo dentomart_icon( 'arrow-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</span>
				</span>
			</a>
		<?php endforeach; ?>
	</div>
</section>
