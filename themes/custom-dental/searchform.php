<?php
/**
 * Custom search form — DentalKart style with Image Search button.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$placeholder = __( 'Search over 20,000 Dental Products...', 'dentomart' );
$shop_url    = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
?>
<form role="search" method="get" class="dm-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="dm-search__icon-wrap">
		<?php echo dentomart_icon( 'search', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
	<label class="screen-reader-text" for="dm-search-field"><?php esc_html_e( 'Search products', 'dentomart' ); ?></label>
	<input type="search" id="dm-search-field" class="dm-search__input" placeholder="<?php echo esc_attr( $placeholder ); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
	<input type="hidden" name="post_type" value="product" />
	
	<button type="button" class="dm-search__image-btn" aria-label="<?php esc_attr_e( 'Search by image', 'dentomart' ); ?>">
		<span><?php esc_html_e( 'Image Search', 'dentomart' ); ?></span>
		<?php echo dentomart_icon( 'camera', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</button>
	<button type="submit" class="dm-search__submit" aria-label="<?php esc_attr_e( 'Search', 'dentomart' ); ?>">
		<?php echo dentomart_icon( 'search', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</button>
</form>
