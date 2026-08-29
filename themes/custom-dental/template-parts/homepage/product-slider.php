<?php
/**
 * Dynamic Product Category Slider template part — DentalKart style.
 * Uses brand-diverse product queries & distinct exclusion so each section has unique products.
 *
 * Can be called with $args via get_template_part( 'template-parts/homepage/product-slider', null, $args )
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$id            = ! empty( $args['id'] ) ? $args['id'] : 'slider-' . wp_rand( 100, 999 );
$title         = ! empty( $args['title'] ) ? $args['title'] : __( 'Featured Products', 'dentomart' );
$eyebrow       = ! empty( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$subtitle      = ! empty( $args['subtitle'] ) ? $args['subtitle'] : '';
$cat_slug      = ! empty( $args['category'] ) ? $args['category'] : '';
$orderby       = ! empty( $args['orderby'] ) ? $args['orderby'] : 'popularity';
$order         = ! empty( $args['order'] ) ? $args['order'] : 'DESC';
$count         = ! empty( $args['count'] ) ? (int) $args['count'] : 10;
$theme_class   = ! empty( $args['theme_class'] ) ? $args['theme_class'] : '';
$badge_icon    = ! empty( $args['badge_icon'] ) ? $args['badge_icon'] : '';
$view_all      = ! empty( $args['view_all'] ) ? $args['view_all'] : '';
$manual_ids         = ! empty( $args['post__in'] ) ? (array) $args['post__in'] : array();
$placement_meta_key = ! empty( $args['placement_meta_key'] ) ? $args['placement_meta_key'] : '';
$one_per_brand      = isset( $args['one_per_brand'] ) ? (bool) $args['one_per_brand'] : true;

// Build Diverse Query
$query = dentomart_query_diverse_products( array(
	'category'           => $cat_slug,
	'posts_per_page'     => $count,
	'orderby'            => $orderby,
	'order'              => $order,
	'post__in'           => $manual_ids,
	'placement_meta_key' => $placement_meta_key,
	'one_per_brand'      => $one_per_brand,
	'exclude_rendered'   => true,
) );

if ( ! $query || ! $query->have_posts() ) {
	return;
}

// Resolve view all URL if not given
if ( empty( $view_all ) && $cat_slug ) {
	$term = is_numeric( $cat_slug ) ? get_term( (int) $cat_slug, 'product_cat' ) : get_term_by( 'slug', $cat_slug, 'product_cat' );
	if ( $term && ! is_wp_error( $term ) ) {
		$view_all = get_term_link( $term );
	}
}

if ( empty( $view_all ) ) {
	$view_all = wc_get_page_permalink( 'shop' );
}

dentomart_render_product_slider_section( array(
	'id'          => $id,
	'title'       => $title,
	'eyebrow'     => $eyebrow,
	'subtitle'    => $subtitle,
	'view_all'    => $view_all,
	'query'       => $query,
	'theme_class' => $theme_class,
	'badge_icon'  => $badge_icon,
) );
