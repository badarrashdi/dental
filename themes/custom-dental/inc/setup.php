<?php
/**
 * Theme setup: support, menus, image sizes.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme setup on 'after_setup_theme'.
 */
function dentomart_setup() {
	load_theme_textdomain( 'dentomart', DENTOMART_DIR . '/languages' );

	// WordPress core features.
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array(
		'height'      => 56,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'automatic-feed-links' );

	// WooCommerce support.
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Navigation menus.
	register_nav_menus( array(
		'primary'        => __( 'Primary Menu', 'dentomart' ),
		'footer_shop'    => __( 'Footer: Shop Links', 'dentomart' ),
		'footer_support' => __( 'Footer: Support Links', 'dentomart' ),
	) );

	// Editor styles (optional small refinement).
	add_editor_style( 'assets/css/editor.css' );
}
add_action( 'after_setup_theme', 'dentomart_setup' );

/**
 * Content width.
 */
function dentomart_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'dentomart_content_width', 1200 );
}
add_action( 'after_setup_theme', 'dentomart_content_width', 0 );

/**
 * Register widget areas.
 */
function dentomart_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Shop Sidebar', 'dentomart' ),
		'id'            => 'shop-sidebar',
		'description'   => __( 'Widgets for WooCommerce archive pages (future phase).', 'dentomart' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer: About Column', 'dentomart' ),
		'id'            => 'footer-about',
		'description'   => __( 'Footer brand/about column. Recommended widget: Text.', 'dentomart' ),
		'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="footer-widget-title">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'dentomart_widgets_init' );

/**
 * Enable thumbnail uploads on the pa_brand taxonomy.
 *
 * Exposes the built-in WP term thumbnail UI so brand logos / hero images can
 * be uploaded from Products → Attributes → Brand → Edit term.
 */
function dentomart_brand_term_thumbnails() {
	if ( ! taxonomy_exists( 'pa_brand' ) ) {
		return;
	}
	add_term_meta( '_thumbnail_id', '', '', false );
	add_action( 'pa_brand_add_form_fields', 'dentomart_brand_term_thumbnail_field', 10, 2 );
	add_action( 'pa_brand_edit_form_fields', 'dentomart_brand_term_thumbnail_field', 10, 2 );
	add_action( 'created_pa_brand', 'dentomart_brand_term_thumbnail_save', 10, 2 );
	add_action( 'edited_pa_brand', 'dentomart_brand_term_thumbnail_save', 10, 2 );
}
add_action( 'init', 'dentomart_brand_term_thumbnails', 20 );

/**
 * Render thumbnail picker on pa_brand term add/edit screens.
 */
function dentomart_brand_term_thumbnail_field( $term ) {
	$term_id   = is_object( $term ) ? (int) $term->term_id : 0;
	$image_id  = $term_id ? (int) get_term_meta( $term_id, 'thumbnail_id', true ) : 0;
	wp_enqueue_media();
	?>
	<div class="form-field term-thumbnail-wrap">
		<?php if ( $term_id ) : ?>
			<th scope="row" valign="top"><label for="brand-thumbnail"><?php esc_html_e( 'Brand Logo / Image', 'dentomart' ); ?></label></th>
			<td>
		<?php else : ?>
			<label for="brand-thumbnail"><?php esc_html_e( 'Brand Logo / Image', 'dentomart' ); ?></label>
		<?php endif; ?>
			<div class="dentomart-brand-thumb" style="margin: 8px 0;">
				<?php if ( $image_id ) : ?>
					<?php echo wp_get_attachment_image( $image_id, 'dentomart-brand', false, array( 'style' => 'max-width:200px;height:auto;border:1px solid #e2e8f0;border-radius:8px;' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php endif; ?>
			</div>
			<input type="hidden" name="brand_thumbnail_id" id="brand-thumbnail" value="<?php echo esc_attr( $image_id ); ?>" />
			<button type="button" class="button dentomart-brand-thumb-upload"><?php esc_html_e( 'Upload / Choose image', 'dentomart' ); ?></button>
			<button type="button" class="button dentomart-brand-thumb-remove"><?php esc_html_e( 'Remove', 'dentomart' ); ?></button>
			<p class="description"><?php esc_html_e( 'Upload the brand logo or a representative product image. Recommended size: 600×360.', 'dentomart' ); ?></p>
		<?php if ( $term_id ) : ?>
			</td>
		<?php endif; ?>
	</div>
	<script>
	(function($){
		var frame;
		$(document).on('click', '.dentomart-brand-thumb-upload', function(e){
			e.preventDefault();
			var $btn = $(this);
			if (frame) { frame.open(); return; }
			frame = wp.media({ title: '<?php echo esc_js( __( 'Choose brand image', 'dentomart' ) ); ?>', button: { text: '<?php echo esc_js( __( 'Use image', 'dentomart' ) ); ?>', }, library: { type: 'image' }, multiple: false });
			frame.on('select', function(){
				var attachment = frame.state().get('selection').first().toJSON();
				$('#brand-thumbnail').val(attachment.id);
				$('.dentomart-brand-thumb').html('<img src="' + (attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url) + '" style="max-width:200px;height:auto;border:1px solid #e2e8f0;border-radius:8px;" />');
			});
			frame.open();
		});
		$(document).on('click', '.dentomart-brand-thumb-remove', function(e){
			e.preventDefault();
			$('#brand-thumbnail').val('');
			$('.dentomart-brand-thumb').html('');
		});
	})(jQuery);
	</script>
	<?php
}

/**
 * Save the brand thumbnail on term create/edit.
 */
function dentomart_brand_term_thumbnail_save( $term_id ) {
	if ( ! isset( $_POST['brand_thumbnail_id'] ) ) {
		return;
	}
	$image_id = (int) $_POST['brand_thumbnail_id'];
	if ( $image_id ) {
		update_term_meta( $term_id, 'thumbnail_id', $image_id );
	} else {
		delete_term_meta( $term_id, 'thumbnail_id' );
	}
}

// Brand archive is exposed at /brands via a dedicated page template
// (page-brands.php) instead of a rewrite — see template file in theme root.

/**
 * Register additional image sizes.
 */
function dentomart_image_sizes() {
	// Product card / carousel image (crop to square like WooCommerce thumbnails).
	add_image_size( 'dentomart-card', 400, 400, true );
	// Category card image (wide-ish square).
	add_image_size( 'dentomart-category', 560, 560, true );
	// Hero image.
	add_image_size( 'dentomart-hero', 900, 900, false );
	// Promo banner.
	add_image_size( 'dentomart-banner', 900, 600, true );
	// Brand card image (wide-ish, for top-brands strip).
	add_image_size( 'dentomart-brand', 600, 360, true );
	// Single product gallery image (large).
	add_image_size( 'dentomart-product', 900, 900, true );
	// Single product thumbnail strip.
	add_image_size( 'dentomart-product-thumb', 160, 160, true );
}
add_action( 'after_setup_theme', 'dentomart_image_sizes' );

/**
 * Register Homepage Slider Placement Meta Box on WooCommerce Products.
 */
function dentomart_add_product_placement_metabox() {
	add_meta_box(
		'dentomart_product_placement',
		__( 'Homepage Slider Placement', 'dentomart' ),
		'dentomart_render_product_placement_metabox',
		'product',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'dentomart_add_product_placement_metabox' );

/**
 * Render Homepage Slider Placement Checkboxes.
 */
function dentomart_render_product_placement_metabox( $post ) {
	wp_nonce_field( 'dentomart_save_product_placement', 'dentomart_product_placement_nonce' );

	$placements = array(
		'_dm_show_on_flash_deals'   => __( '🔥 Flash Deals & Today\'s Offers', 'dentomart' ),
		'_dm_show_on_bestsellers'   => __( '⭐ Best Sellers in Practice', 'dentomart' ),
		'_dm_show_on_endodontics'   => __( '⚡ Endodontics & Rotary Files', 'dentomart' ),
		'_dm_show_on_instruments'   => __( '🩺 Instruments & Handpieces', 'dentomart' ),
		'_dm_show_on_equipment'     => __( '⚙️ Equipment & Machinery', 'dentomart' ),
		'_dm_show_on_new_arrivals'  => __( '🆕 New Arrivals & Wholesale', 'dentomart' ),
	);
	?>
	<div class="dentomart-placement-wrap" style="padding: 6px 0;">
		<p style="font-size: 12px; color: #64748B; margin-top: 0; line-height: 1.4;">
			<?php esc_html_e( 'Select which homepage slider(s) this product should appear in:', 'dentomart' ); ?>
		</p>
		<div style="display: flex; flex-direction: column; gap: 8px;">
			<?php foreach ( $placements as $meta_key => $label ) : ?>
				<?php $val = get_post_meta( $post->ID, $meta_key, true ); ?>
				<label style="display: flex; align-items: center; gap: 8px; font-weight: 500; cursor: pointer;">
					<input type="checkbox" name="<?php echo esc_attr( $meta_key ); ?>" value="yes" <?php checked( $val, 'yes' ); ?> />
					<span><?php echo esc_html( $label ); ?></span>
				</label>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}

/**
 * Save Homepage Slider Placement Meta on Product Save.
 */
function dentomart_save_product_placement_meta( $post_id ) {
	if ( ! isset( $_POST['dentomart_product_placement_nonce'] ) || ! wp_verify_nonce( $_POST['dentomart_product_placement_nonce'], 'dentomart_save_product_placement' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$keys = array(
		'_dm_show_on_flash_deals',
		'_dm_show_on_bestsellers',
		'_dm_show_on_endodontics',
		'_dm_show_on_instruments',
		'_dm_show_on_equipment',
		'_dm_show_on_new_arrivals',
	);

	foreach ( $keys as $meta_key ) {
		if ( ! empty( $_POST[ $meta_key ] ) && 'yes' === $_POST[ $meta_key ] ) {
			update_post_meta( $post_id, $meta_key, 'yes' );
		} else {
			delete_post_meta( $post_id, $meta_key );
		}
	}
}
add_action( 'save_post_product', 'dentomart_save_product_placement_meta' );

