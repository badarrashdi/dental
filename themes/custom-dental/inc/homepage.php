<?php
/**
 * Homepage content management.
 *
 * Registers an ACF field group on the "Home" page so every section of the
 * homepage is editable from WP Admin. All fields have sensible defaults so
 * the homepage renders fully even before a value is saved.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Small helper to read a homepage ACF field with a fallback.
 *
 * @param string $key      Field key (without 'field_').
 * @param mixed  $fallback Default when ACF is missing or the field is empty.
 * @return mixed
 */
function dentomart_home_field( $key, $fallback = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $fallback;
	}

	$value = get_field( 'home_' . $key, get_the_ID() );
	if ( '' === $value || null === $value || false === $value ) {
		return $fallback;
	}

	return $value;
}

/**
 * Populate category/brand choice lists from the live taxonomies.
 */
function dentomart_acf_load_choices( $field ) {
	if ( empty( $field['choices'] ) ) {
		if ( 'product_cat' === $field['name'] ) {
			$terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC' ) );
		} else {
			$terms = get_terms( array( 'taxonomy' => 'pa_brand', 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC' ) );
		}

		if ( ! is_wp_error( $terms ) ) {
			$choices = array();
			foreach ( $terms as $term ) {
				$choices[ $term->term_id ] = sprintf( '%s (%s)', $term->name, number_format_i18n( $term->count ) );
			}
			$field['choices'] = $choices;
		}
	}
	return $field;
}
add_filter( 'acf/load_field/name=categories_list', 'dentomart_acf_load_choices' );
add_filter( 'acf/load_field/name=featured_cats_list', 'dentomart_acf_load_choices' );
add_filter( 'acf/load_field/name=products_category', 'dentomart_acf_load_choices' );
add_filter( 'acf/load_field/name=brands_list', 'dentomart_acf_load_choices' );

/**
 * Register the homepage ACF field group.
 */
function dentomart_home_acf_group() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'      => 'group_dentomart_home',
		'title'    => __( 'Homepage Sections', 'dentomart' ),
		'fields'   => array(
			// ---------------------------------------------------------- Hero.
			array(
				'key'           => 'field_dm_hero_tab',
				'label'         => __( 'Hero', 'dentomart' ),
				'name'          => '',
				'type'          => 'tab',
				'placement'     => 'top',
			),
			array(
				'key'           => 'field_dm_hero_eyebrow',
				'label'         => __( 'Eyebrow text', 'dentomart' ),
				'name'          => 'home_hero_eyebrow',
				'type'          => 'text',
				'default_value' => __( 'Trusted by dental professionals across India', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_hero_title',
				'label'         => __( 'Headline', 'dentomart' ),
				'name'          => 'home_hero_title',
				'type'          => 'text',
				'default_value' => __( 'Everything Your Dental Practice Needs, Delivered', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_hero_subtitle',
				'label'         => __( 'Sub-headline', 'dentomart' ),
				'name'          => 'home_hero_subtitle',
				'type'          => 'textarea',
				'rows'          => 3,
				'default_value' => __( 'From everyday consumables to advanced equipment — 17,000+ genuine dental products from 400+ trusted brands at assured best prices.', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_hero_cta1_label',
				'label'         => __( 'Primary CTA label', 'dentomart' ),
				'name'          => 'home_hero_cta_primary_label',
				'type'          => 'text',
				'default_value' => __( 'Shop by Category', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_hero_cta1_url',
				'label'         => __( 'Primary CTA URL', 'dentomart' ),
				'name'          => 'home_hero_cta_primary_url',
				'type'          => 'url',
				'default_value' => '',
				'placeholder'   => home_url( '/shop/' ),
			),
			array(
				'key'           => 'field_dm_hero_cta2_label',
				'label'         => __( 'Secondary CTA label', 'dentomart' ),
				'name'          => 'home_hero_cta_secondary_label',
				'type'          => 'text',
				'default_value' => __( 'Explore New Arrivals', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_hero_cta2_url',
				'label'         => __( 'Secondary CTA URL', 'dentomart' ),
				'name'          => 'home_hero_cta_secondary_url',
				'type'          => 'url',
				'default_value' => '',
			),
			array(
				'key'           => 'field_dm_hero_image',
				'label'         => __( 'Hero image', 'dentomart' ),
				'name'          => 'home_hero_image',
				'type'          => 'image',
				'return_format' => 'id',
				'instructions'  => __( 'Recommended: a square product or clinic photo, min 800×800.', 'dentomart' ),
			),

			// ------------------------------------------------------ Flash Deals.
			array(
				'key'           => 'field_dm_deals_tab',
				'label'         => __( 'Flash Deals & Offers', 'dentomart' ),
				'name'          => '',
				'type'          => 'tab',
				'placement'     => 'top',
			),
			array(
				'key'           => 'field_dm_deals_title',
				'label'         => __( 'Deals Title', 'dentomart' ),
				'name'          => 'home_deals_title',
				'type'          => 'text',
				'default_value' => __( 'Flash Deals & Today\'s Offers', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_deals_subtitle',
				'label'         => __( 'Deals Subtitle', 'dentomart' ),
				'name'          => 'home_deals_subtitle',
				'type'          => 'textarea',
				'rows'          => 2,
				'default_value' => __( 'Exclusive clinical practice discounts ending soon. Limited stock available.', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_deals_count',
				'label'         => __( 'Number of Deal Products', 'dentomart' ),
				'name'          => 'home_deals_count',
				'type'          => 'number',
				'default_value' => 10,
				'min'           => 4,
				'max'           => 20,
			),
			array(
				'key'           => 'field_dm_deals_products',
				'label'         => __( 'Manual Products for Deals (Optional)', 'dentomart' ),
				'name'          => 'home_deals_products',
				'type'          => 'text',
				'instructions'  => __( 'Enter comma-separated Product IDs to manually pin specific products (e.g. 101, 105, 112). Leave empty for auto-selected 1-product-per-brand deals.', 'dentomart' ),
				'default_value' => '',
			),

			// ------------------------------------------------------ Categories.
			array(
				'key'           => 'field_dm_categories_tab',
				'label'         => __( 'Shop by Category', 'dentomart' ),
				'name'          => '',
				'type'          => 'tab',
				'placement'     => 'top',
			),
			array(
				'key'           => 'field_dm_categories_eyebrow',
				'label'         => __( 'Eyebrow text', 'dentomart' ),
				'name'          => 'home_categories_eyebrow',
				'type'          => 'text',
				'default_value' => __( 'Browse the catalogue', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_categories_title',
				'label'         => __( 'Title', 'dentomart' ),
				'name'          => 'home_categories_title',
				'type'          => 'text',
				'default_value' => __( 'Shop by Category', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_categories_subtitle',
				'label'         => __( 'Subtitle', 'dentomart' ),
				'name'          => 'home_categories_subtitle',
				'type'          => 'textarea',
				'rows'          => 2,
				'default_value' => __( 'Every department of your clinic, under one roof.', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_categories_list',
				'label'         => __( 'Featured categories', 'dentomart' ),
				'name'          => 'home_categories_list',
				'type'          => 'select',
				'multiple'      => 1,
				'ui'            => 1,
				'allow_null'    => 0,
				'choices'       => array(),
			),

			// ----------------------------------------- Featured Categories (banners).
			array(
				'key'           => 'field_dm_featured_cats_tab',
				'label'         => __( 'Featured Category Banners', 'dentomart' ),
				'name'          => '',
				'type'          => 'tab',
				'placement'     => 'top',
			),
			array(
				'key'           => 'field_dm_featured_cats_eyebrow',
				'label'         => __( 'Eyebrow text', 'dentomart' ),
				'name'          => 'home_featured_cats_eyebrow',
				'type'          => 'text',
				'default_value' => __( 'Explore by Category', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_featured_cats_title',
				'label'         => __( 'Title', 'dentomart' ),
				'name'          => 'home_featured_cats_title',
				'type'          => 'text',
				'default_value' => __( 'Featured Categories', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_featured_cats_subtitle',
				'label'         => __( 'Subtitle', 'dentomart' ),
				'name'          => 'home_featured_cats_subtitle',
				'type'          => 'textarea',
				'rows'          => 2,
				'default_value' => __( 'Discover our most popular dental categories with curated selections.', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_featured_cats_list',
				'label'         => __( 'Featured category banners', 'dentomart' ),
				'name'          => 'home_featured_cats_list',
				'type'          => 'select',
				'multiple'      => 1,
				'ui'            => 1,
				'allow_null'    => 0,
				'choices'       => array(),
				'instructions'  => __( 'Pick 3-6 categories to showcase with banner images.', 'dentomart' ),
			),

			// --------------------------------------------------------- Products.
			array(
				'key'           => 'field_dm_products_tab',
				'label'         => __( 'Featured Products', 'dentomart' ),
				'name'          => '',
				'type'          => 'tab',
				'placement'     => 'top',
			),
			array(
				'key'           => 'field_dm_products_eyebrow',
				'label'         => __( 'Eyebrow text', 'dentomart' ),
				'name'          => 'home_products_eyebrow',
				'type'          => 'text',
				'default_value' => __( 'Freshly stocked', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_products_title',
				'label'         => __( 'Title', 'dentomart' ),
				'name'          => 'home_products_title',
				'type'          => 'text',
				'default_value' => __( 'New Arrivals', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_products_subtitle',
				'label'         => __( 'Subtitle', 'dentomart' ),
				'name'          => 'home_products_subtitle',
				'type'          => 'textarea',
				'rows'          => 2,
				'default_value' => __( 'The latest instruments, consumables and equipment we have added to the range.', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_products_count',
				'label'         => __( 'Number of products', 'dentomart' ),
				'name'          => 'home_products_count',
				'type'          => 'number',
				'default_value' => 8,
				'min'           => 4,
				'max'           => 12,
				'step'          => 4,
			),
			array(
				'key'           => 'field_dm_products_category',
				'label'         => __( 'Limit to category (optional)', 'dentomart' ),
				'name'          => 'home_products_category',
				'type'          => 'select',
				'allow_null'    => 1,
				'ui'            => 1,
				'choices'       => array(),
				'instructions'  => __( 'Leave empty to show the newest products from the whole store.', 'dentomart' ),
			),

			// ------------------------------------------------------------ Brands.
			array(
				'key'           => 'field_dm_brands_tab',
				'label'         => __( 'Top Brands', 'dentomart' ),
				'name'          => '',
				'type'          => 'tab',
				'placement'     => 'top',
			),
			array(
				'key'           => 'field_dm_brands_eyebrow',
				'label'         => __( 'Eyebrow text', 'dentomart' ),
				'name'          => 'home_brands_eyebrow',
				'type'          => 'text',
				'default_value' => __( '400+ brands in stock', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_brands_title',
				'label'         => __( 'Title', 'dentomart' ),
				'name'          => 'home_brands_title',
				'type'          => 'text',
				'default_value' => __( 'Top Brands', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_brands_subtitle',
				'label'         => __( 'Subtitle', 'dentomart' ),
				'name'          => 'home_brands_subtitle',
				'type'          => 'textarea',
				'rows'          => 2,
				'default_value' => __( 'The names you know and trust — all genuine, all in stock.', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_brands_list',
				'label'         => __( 'Featured brands', 'dentomart' ),
				'name'          => 'home_brands_list',
				'type'          => 'select',
				'multiple'      => 1,
				'ui'            => 1,
				'allow_null'    => 0,
				'choices'       => array(),
			),

			// ------------------------------------------------------------ Promos.
			array(
				'key'           => 'field_dm_promos_tab',
				'label'         => __( 'Promotional Banners', 'dentomart' ),
				'name'          => '',
				'type'          => 'tab',
				'placement'     => 'top',
			),
			array(
				'key'           => 'field_dm_promo_a_image',
				'label'         => __( 'Banner A — image', 'dentomart' ),
				'name'          => 'home_promo_a_image',
				'type'          => 'image',
				'return_format' => 'id',
			),
			array(
				'key'           => 'field_dm_promo_a_title',
				'label'         => __( 'Banner A — title', 'dentomart' ),
				'name'          => 'home_promo_a_title',
				'type'          => 'text',
				'default_value' => __( 'Clearance Sale', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_promo_a_text',
				'label'         => __( 'Banner A — text', 'dentomart' ),
				'name'          => 'home_promo_a_text',
				'type'          => 'textarea',
				'rows'          => 2,
				'default_value' => __( 'Great prices on a wide range of dental products. While stocks last.', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_promo_a_cta',
				'label'         => __( 'Banner A — CTA label', 'dentomart' ),
				'name'          => 'home_promo_a_cta',
				'type'          => 'text',
				'default_value' => __( 'Shop Clearance', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_promo_a_url',
				'label'         => __( 'Banner A — CTA URL', 'dentomart' ),
				'name'          => 'home_promo_a_url',
				'type'          => 'url',
				'default_value' => '',
			),
			array(
				'key'           => 'field_dm_promo_b_image',
				'label'         => __( 'Banner B — image', 'dentomart' ),
				'name'          => 'home_promo_b_image',
				'type'          => 'image',
				'return_format' => 'id',
			),
			array(
				'key'           => 'field_dm_promo_b_title',
				'label'         => __( 'Banner B — title', 'dentomart' ),
				'name'          => 'home_promo_b_title',
				'type'          => 'text',
				'default_value' => __( 'New Arrivals', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_promo_b_text',
				'label'         => __( 'Banner B — text', 'dentomart' ),
				'name'          => 'home_promo_b_text',
				'type'          => 'textarea',
				'rows'          => 2,
				'default_value' => __( 'Fresh stock added every week — instruments, materials and equipment.', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_promo_b_cta',
				'label'         => __( 'Banner B — CTA label', 'dentomart' ),
				'name'          => 'home_promo_b_cta',
				'type'          => 'text',
				'default_value' => __( 'Explore New Arrivals', 'dentomart' ),
			),
			array(
				'key'           => 'field_dm_promo_b_url',
				'label'         => __( 'Banner B — CTA URL', 'dentomart' ),
				'name'          => 'home_promo_b_url',
				'type'          => 'url',
				'default_value' => '',
			),

			// ------------------------------------------------------------- Trust.
			array(
				'key'           => 'field_dm_trust_tab',
				'label'         => __( 'Trust / Benefits', 'dentomart' ),
				'name'          => '',
				'type'          => 'tab',
				'placement'     => 'top',
			),
			array(
				'key'           => 'field_dm_trust_title',
				'label'         => __( 'Trust strip title', 'dentomart' ),
				'name'          => 'home_trust_title',
				'type'          => 'text',
				'default_value' => __( 'Why 50,000+ dental professionals choose DentoMart', 'dentomart' ),
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'front_page',
				),
			),
		),
		'menu_order'        => 0,
		'position'          => 'normal',
		'style'             => 'default',
		'label_placement'   => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'    => array( 'the_content' ),
	) );
}
add_action( 'acf/init', 'dentomart_home_acf_group' );

/**
 * Convenience: trusted category term IDs for defaults.
 */
function dentomart_category_term_by_slug( $slug ) {
	$term = get_term_by( 'slug', $slug, 'product_cat' );
	return $term ? $term->term_id : 0;
}

/**
 * Default featured categories (used when ACF has no saved selection).
 *
 * @return int[]
 */
function dentomart_default_categories() {
	$slugs = array( 'orthodontics', 'endodontics', 'restorative', 'oral-surgery', 'prosthodontics', 'implantology', 'instruments', 'equipments', 'laboratory', 'general-dentistry' );
	$ids   = array();
	foreach ( $slugs as $slug ) {
		$id = dentomart_category_term_by_slug( $slug );
		if ( $id ) {
			$ids[] = $id;
		}
	}
	return $ids;
}

/**
 * Default featured brands (used when ACF has no saved selection).
 *
 * @return int[]
 */
function dentomart_default_brands() {
	$names = array( 'GDC', 'Mani', 'Dentaltech', 'Dentsply', 'Waldent Instruments', '3M ESPE', 'Oracraft', 'Woodpecker', 'API', 'Xcem', 'Labodent', 'Coltene Whaledent' );
	$ids   = array();
	foreach ( $names as $name ) {
		$term = get_term_by( 'name', $name, 'pa_brand' );
		if ( $term ) {
			$ids[] = $term->term_id;
		}
	}
	return $ids;
}
