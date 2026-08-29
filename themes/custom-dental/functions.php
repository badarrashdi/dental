<?php
/**
 * DentoMart theme bootstrap.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DENTOMART_VERSION', '1.0.0' );
define( 'DENTOMART_DIR', get_template_directory() );
define( 'DENTOMART_URI', get_template_directory_uri() );

require DENTOMART_DIR . '/inc/setup.php';
require DENTOMART_DIR . '/inc/assets.php';
require DENTOMART_DIR . '/inc/helpers.php';
require DENTOMART_DIR . '/inc/woocommerce.php';
require DENTOMART_DIR . '/inc/homepage.php';
require DENTOMART_DIR . '/inc/pincode-admin.php';

