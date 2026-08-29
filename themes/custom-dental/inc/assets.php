<?php
/**
 * Enqueue styles and scripts.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue front-end assets.
 */
function dentomart_enqueue_assets() {
	// Local font library stylesheet (Montserrat).
	$font_sheet = dentomart_font_library_sheet();
	if ( $font_sheet ) {
		wp_enqueue_style( 'dentomart-fonts', $font_sheet, array(), DENTOMART_VERSION );
	}

	wp_enqueue_style( 'dentomart-style', DENTOMART_URI . '/style.css', array(), DENTOMART_VERSION );
	wp_enqueue_style( 'dentomart-main', DENTOMART_URI . '/assets/css/main.css', array( 'dentomart-style' ), DENTOMART_VERSION );

	// WooCommerce styles are layered on top so product widgets inherit the theme.
	wp_enqueue_style( 'dentomart-woocommerce', DENTOMART_URI . '/assets/css/woocommerce.css', array( 'dentomart-main' ), DENTOMART_VERSION );

	wp_enqueue_script( 'dentomart-main', DENTOMART_URI . '/assets/js/main.js', array(), DENTOMART_VERSION, true );

	$pincode_settings = function_exists( 'dentomart_get_pincode_settings' ) ? dentomart_get_pincode_settings() : array();

	// Data for the JS (cart count endpoint, header states, pincodes).
	wp_localize_script( 'dentomart-main', 'dentomartData', array(
		'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
		'cartUrl'      => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '',
		'cartCount'    => dentomart_cart_count(),
		'nonce'        => wp_create_nonce( 'dentomart' ),
		'pincodes'     => $pincode_settings,
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'dentomart_enqueue_assets' );

/**
 * Resolve the font-library stylesheet URL, falling back to nothing (system fonts).
 *
 * WordPress stores downloaded fonts under wp-content/fonts with a hashed
 * stylesheet. We pick it up so Montserrat stays local and fast.
 */
function dentomart_font_library_sheet() {
	$fonts_dir = WP_CONTENT_DIR . '/fonts';
	$fonts_url = content_url( 'fonts' );

	if ( ! is_dir( $fonts_dir ) ) {
		return '';
	}

	$files = glob( $fonts_dir . '/*.css' );
	if ( empty( $files ) ) {
		return '';
	}

	return $fonts_url . '/' . basename( $files[0] );
}

/**
 * Add a <body> class for JS styling hooks.
 */
function dentomart_body_class( $classes ) {
	$classes[] = 'dentomart';

	if ( function_exists( 'is_woocommerce' ) ) {
		if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
			$classes[] = 'woocommerce-page';
		}
	}

	if ( is_front_page() ) {
		$classes[] = 'is-front-page';
	}

	return $classes;
}
add_filter( 'body_class', 'dentomart_body_class' );
