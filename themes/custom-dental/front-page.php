<?php
/**
 * Homepage template — DentalKart 1:1 match layout.
 *
 * Flow:
 *   1. Hero Multi-Banner Slider (Auto-sliding promotional carousel)
 *   2. Trust Strip (20,000+ Products, 400+ Brands, 100% Genuine, Best Price)
 *   3. Top Categories Slider (Circle carousel of top dental departments)
 *   4. Flash Deals & Limited-Time Offers Slider (with countdown timer)
 *   5. Best Sellers Product Slider (Top popular clinical consumables)
 *   6. Featured Category Promo Banners (Curated split promotional cards)
 *   7. Endodontics & Rotary Essentials Product Slider
 *   8. Dental Instruments & Handpieces Product Slider
 *   9. Equipment & Clinical Machinery Product Slider
 *   10. New Arrivals & Wholesale Deals Product Slider
 *   11. Top Brands Slider (Horizontal brand carousel)
 *   12. Why Choose Us / Authority Content
 *   13. Bulk Clinic Inquiry & Custom Quote CTA
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<?php get_template_part( 'template-parts/homepage/hero' ); ?>

<?php get_template_part( 'template-parts/homepage/trust' ); ?>

<?php get_template_part( 'template-parts/homepage/categories' ); ?>

<?php get_template_part( 'template-parts/homepage/deals-slider' ); ?>

<!-- Best Sellers Product Slider -->
<?php
get_template_part( 'template-parts/homepage/product-slider', null, array(
	'id'                 => 'bestsellers-slider',
	'title'              => __( 'Best Sellers in Dental Practice', 'dentomart' ),
	'eyebrow'            => __( 'MOST ORDERED BY DOCTORS', 'dentomart' ),
	'subtitle'           => __( 'Proven clinical performance and high-demand daily practice consumables.', 'dentomart' ),
	'orderby'            => 'popularity',
	'count'              => 10,
	'placement_meta_key' => '_dm_show_on_bestsellers',
	'badge_icon'         => 'star',
	'view_all'           => wc_get_page_permalink( 'shop' ) . '?orderby=popularity',
) );
?>

<?php get_template_part( 'template-parts/homepage/featured-categories' ); ?>

<!-- Endodontics & Rotary Essentials Slider -->
<?php
get_template_part( 'template-parts/homepage/product-slider', null, array(
	'id'                 => 'endodontics-slider',
	'title'              => __( 'Endodontics & Rotary Essentials', 'dentomart' ),
	'eyebrow'            => __( 'ROOT CANAL SPECIALTIES', 'dentomart' ),
	'subtitle'           => __( 'Precision files, obturation systems, apex locators and sealers.', 'dentomart' ),
	'category'           => 'endodontics',
	'orderby'            => 'popularity',
	'count'              => 10,
	'placement_meta_key' => '_dm_show_on_endodontics',
	'theme_class'        => 'dm-slider--teal',
	'badge_icon'         => 'zap',
) );
?>

<!-- Dental Instruments & Handpieces Slider -->
<?php
get_template_part( 'template-parts/homepage/product-slider', null, array(
	'id'                 => 'instruments-slider',
	'title'              => __( 'Dental Instruments & Handpieces', 'dentomart' ),
	'eyebrow'            => __( 'SURGICAL & CLINICAL PRECISION', 'dentomart' ),
	'subtitle'           => __( 'High-speed airotors, micromotors, extraction forceps and diagnostic sets.', 'dentomart' ),
	'category'           => 'instruments',
	'orderby'            => 'date',
	'count'              => 10,
	'placement_meta_key' => '_dm_show_on_instruments',
	'theme_class'        => 'dm-slider--blue',
	'badge_icon'         => 'box',
) );
?>

<!-- Equipment & Machinery Slider -->
<?php
get_template_part( 'template-parts/homepage/product-slider', null, array(
	'id'                 => 'equipment-slider',
	'title'              => __( 'Equipment & Small Machinery', 'dentomart' ),
	'eyebrow'            => __( 'CLINICAL INFRASTRUCTURE', 'dentomart' ),
	'subtitle'           => __( 'Autoclaves, light cures, scaler units and compressor equipment with pan-India support.', 'dentomart' ),
	'category'           => 'equipment',
	'orderby'            => 'popularity',
	'count'              => 10,
	'placement_meta_key' => '_dm_show_on_equipment',
	'theme_class'        => 'dm-slider--navy',
	'badge_icon'         => 'shield',
) );
?>

<!-- New Arrivals & Wholesale Deals Slider -->
<?php
get_template_part( 'template-parts/homepage/product-slider', null, array(
	'id'                 => 'new-arrivals-slider',
	'title'              => __( 'New Arrivals & Wholesale Deals', 'dentomart' ),
	'eyebrow'            => __( 'FRESH INVENTORY', 'dentomart' ),
	'subtitle'           => __( 'Latest dental supply arrivals with automatic tiered volume discounts for bulk clinic orders.', 'dentomart' ),
	'orderby'            => 'date',
	'count'              => 10,
	'placement_meta_key' => '_dm_show_on_new_arrivals',
	'theme_class'        => 'dm-slider--orange',
	'badge_icon'         => 'tag',
	'view_all'           => wc_get_page_permalink( 'shop' ) . '?orderby=date',
) );
?>

<?php get_template_part( 'template-parts/homepage/brands' ); ?>

<?php get_template_part( 'template-parts/homepage/content' ); ?>

<?php get_template_part( 'template-parts/homepage/cta' ); ?>

<?php
get_footer();
