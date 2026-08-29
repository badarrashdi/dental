<?php
/**
 * Slim top utility bar.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="dm-topbar">
	<div class="dm-container dm-topbar__inner">
		<p class="dm-topbar__message">
			<?php esc_html_e( '17,000+ genuine dental products · 400+ trusted brands', 'dentomart' ); ?>
		</p>
		<div class="dm-topbar__links">
			<a class="dm-topbar__link" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Shop All', 'dentomart' ); ?></a>
			<span class="dm-topbar__sep" aria-hidden="true">·</span>
			<a class="dm-topbar__link" href="mailto:support@dentomart.in"><?php esc_html_e( 'support@dentomart.in', 'dentomart' ); ?></a>
		</div>
	</div>
</div>
