<?php
/**
 * Site header.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'dentomart' ); ?></a>

<header class="site-header">
	<?php get_template_part( 'template-parts/header/topbar' ); ?>
	<div class="dm-header-sticky" id="dmHeaderSticky">
		<?php get_template_part( 'template-parts/header/masthead' ); ?>
		<?php get_template_part( 'template-parts/header/nav' ); ?>
	</div>
</header>

<?php get_template_part( 'template-parts/header/mobile' ); ?>
<?php get_template_part( 'template-parts/header/pincode-modal' ); ?>

<main id="primary" class="site-main">
