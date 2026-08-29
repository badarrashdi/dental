<?php
/**
 * Admin Delivery Pincode Settings Page.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Admin Submenu under WooCommerce for Pincodes.
 */
function dentomart_register_pincode_admin_menu() {
	add_submenu_page(
		'woocommerce',
		__( 'Delivery Pincodes', 'dentomart' ),
		__( 'Delivery Pincodes', 'dentomart' ),
		'manage_options',
		'dentomart-pincodes',
		'dentomart_render_pincode_admin_page'
	);
}
add_action( 'admin_menu', 'dentomart_register_pincode_admin_menu', 60 );

/**
 * Get saved pincode settings with defaults.
 *
 * @return array
 */
function dentomart_get_pincode_settings() {
	$defaults = array(
		'mode'              => get_option( 'dentomart_pincode_mode', 'all' ),
		'express_pincodes'  => get_option( 'dentomart_express_pincodes', '110001, 110002, 400001, 400002, 560001, 600001, 700001, 500001' ),
		'standard_pincodes' => get_option( 'dentomart_standard_pincodes', '' ),
		'express_msg'       => get_option( 'dentomart_express_msg', __( '✓ Express Clinic Delivery available (Dispatch in 24–48 hrs).', 'dentomart' ) ),
		'standard_msg'      => get_option( 'dentomart_standard_msg', __( '✓ Standard Clinic Delivery available (Dispatch in 3–5 business days).', 'dentomart' ) ),
		'unserviceable_msg' => get_option( 'dentomart_unserviceable_msg', __( '✕ Delivery is currently unavailable for this PIN code. Contact our desk.', 'dentomart' ) ),
	);

	return $defaults;
}

/**
 * Render WP Admin Pincode Settings Page.
 */
function dentomart_render_pincode_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$updated = false;
	if ( isset( $_POST['dentomart_pincode_nonce'] ) && wp_verify_nonce( $_POST['dentomart_pincode_nonce'], 'dentomart_save_pincode_settings' ) ) {
		$mode              = sanitize_text_field( $_POST['dentomart_pincode_mode'] ?? 'all' );
		$express_pincodes  = sanitize_textarea_field( $_POST['dentomart_express_pincodes'] ?? '' );
		$standard_pincodes = sanitize_textarea_field( $_POST['dentomart_standard_pincodes'] ?? '' );
		$express_msg       = sanitize_text_field( $_POST['dentomart_express_msg'] ?? '' );
		$standard_msg      = sanitize_text_field( $_POST['dentomart_standard_msg'] ?? '' );
		$unserviceable_msg = sanitize_text_field( $_POST['dentomart_unserviceable_msg'] ?? '' );

		update_option( 'dentomart_pincode_mode', $mode );
		update_option( 'dentomart_express_pincodes', $express_pincodes );
		update_option( 'dentomart_standard_pincodes', $standard_pincodes );
		update_option( 'dentomart_express_msg', $express_msg );
		update_option( 'dentomart_standard_msg', $standard_msg );
		update_option( 'dentomart_unserviceable_msg', $unserviceable_msg );

		$updated = true;
	}

	$settings = dentomart_get_pincode_settings();
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Delivery Pincode & Dispatch Settings', 'dentomart' ); ?></h1>
		<p class="description">
			<?php esc_html_e( 'Manage serviceable clinic PIN codes, express dispatch coverage, and customer delivery estimate messages.', 'dentomart' ); ?>
		</p>

		<?php if ( $updated ) : ?>
			<div class="notice notice-success is-dismissible">
				<p><strong><?php esc_html_e( 'Pincode delivery settings saved successfully!', 'dentomart' ); ?></strong></p>
			</div>
		<?php endif; ?>

		<form method="post" action="" style="max-width: 800px; margin-top: 20px; background: #ffffff; border: 1px solid #ccd0d4; padding: 20px 24px; border-radius: 8px;">
			<?php wp_nonce_field( 'dentomart_save_pincode_settings', 'dentomart_pincode_nonce' ); ?>

			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><label for="dentomart_pincode_mode"><?php esc_html_e( 'Serviceability Mode', 'dentomart' ); ?></label></th>
						<td>
							<select name="dentomart_pincode_mode" id="dentomart_pincode_mode" class="regular-text">
								<option value="all" <?php selected( $settings['mode'], 'all' ); ?>><?php esc_html_e( 'All 6-digit PIN codes serviceable (Express list highlighted)', 'dentomart' ); ?></option>
								<option value="specific" <?php selected( $settings['mode'], 'specific' ); ?>><?php esc_html_e( 'Restricted: Only listed PIN codes serviceable', 'dentomart' ); ?></option>
							</select>
							<p class="description"><?php esc_html_e( 'Choose whether all valid PIN codes are accepted or only explicit listed codes.', 'dentomart' ); ?></p>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="dentomart_express_pincodes"><?php esc_html_e( 'Express 24-48 Hr PIN Codes', 'dentomart' ); ?></label></th>
						<td>
							<textarea name="dentomart_express_pincodes" id="dentomart_express_pincodes" rows="4" class="large-text code"><?php echo esc_textarea( $settings['express_pincodes'] ); ?></textarea>
							<p class="description"><?php esc_html_e( 'Enter comma or line separated PIN codes that receive priority Express Clinic Delivery.', 'dentomart' ); ?></p>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="dentomart_standard_pincodes"><?php esc_html_e( 'Standard 3-5 Day PIN Codes', 'dentomart' ); ?></label></th>
						<td>
							<textarea name="dentomart_standard_pincodes" id="dentomart_standard_pincodes" rows="3" class="large-text code"><?php echo esc_textarea( $settings['standard_pincodes'] ); ?></textarea>
							<p class="description"><?php esc_html_e( 'Explicit PIN codes for standard delivery (used if Restricted mode is active). Leave empty to treat all non-express codes as standard in All mode.', 'dentomart' ); ?></p>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="dentomart_express_msg"><?php esc_html_e( 'Express Delivery Message', 'dentomart' ); ?></label></th>
						<td>
							<input type="text" name="dentomart_express_msg" id="dentomart_express_msg" value="<?php echo esc_attr( $settings['express_msg'] ); ?>" class="large-text" />
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="dentomart_standard_msg"><?php esc_html_e( 'Standard Delivery Message', 'dentomart' ); ?></label></th>
						<td>
							<input type="text" name="dentomart_standard_msg" id="dentomart_standard_msg" value="<?php echo esc_attr( $settings['standard_msg'] ); ?>" class="large-text" />
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="dentomart_unserviceable_msg"><?php esc_html_e( 'Unserviceable Notice', 'dentomart' ); ?></label></th>
						<td>
							<input type="text" name="dentomart_unserviceable_msg" id="dentomart_unserviceable_msg" value="<?php echo esc_attr( $settings['unserviceable_msg'] ); ?>" class="large-text" />
						</td>
					</tr>
				</tbody>
			</table>

			<?php submit_button( __( 'Save Pincode Settings', 'dentomart' ) ); ?>
		</form>
	</div>
	<?php
}
