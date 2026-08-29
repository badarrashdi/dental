<?php
/**
 * Template Name: Contact Us Page
 * Template for the Contact Us page — DentalKart style with interactive form, address, and creative photo.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$contact_img_url = get_template_directory_uri() . '/assets/images/contact-hub.jpg';
?>

<div class="dm-contact-page">

	<!-- Hero Header -->
	<section class="dm-contact-hero">
		<div class="dm-container">
			<div class="dm-contact-hero__inner">
				<span class="dm-slider-eyebrow" style="color: #ffffff;"><?php esc_html_e( 'WE ARE HERE TO HELP', 'dentomart' ); ?></span>
				<h1 class="dm-contact-hero__title"><?php esc_html_e( 'Get in Touch with Our Dental Specialists', 'dentomart' ); ?></h1>
				<p class="dm-contact-hero__subtitle"><?php esc_html_e( 'Have a question about a product, bulk clinic order, or clinical equipment installation? Our dedicated dental procurement specialists are available 6 days a week.', 'dentomart' ); ?></p>
			</div>
		</div>
	</section>

	<!-- Contact Cards Strip -->
	<section class="dm-contact-cards-section">
		<div class="dm-container">
			<div class="dm-contact-cards-grid">
				<div class="dm-contact-card">
					<div class="dm-contact-card__icon dm-contact-card__icon--blue">
						<?php echo dentomart_icon( 'phone', 26 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
					<h3><?php esc_html_e( 'Call or WhatsApp', 'dentomart' ); ?></h3>
					<p class="dm-contact-card__detail"><strong>+91 98765 43210</strong></p>
					<p class="dm-contact-card__sub"><?php esc_html_e( 'Toll-Free: 1800-123-3368', 'dentomart' ); ?><br><?php esc_html_e( 'Mon - Sat: 9:00 AM – 8:00 PM IST', 'dentomart' ); ?></p>
				</div>

				<div class="dm-contact-card">
					<div class="dm-contact-card__icon dm-contact-card__icon--teal">
						<?php echo dentomart_icon( 'mail', 26 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
					<h3><?php esc_html_e( 'Email Support', 'dentomart' ); ?></h3>
					<p class="dm-contact-card__detail"><strong>support@dentomart.com</strong></p>
					<p class="dm-contact-card__sub"><?php esc_html_e( 'Wholesale: bulk@dentomart.com', 'dentomart' ); ?><br><?php esc_html_e( 'Response within 2-4 business hours', 'dentomart' ); ?></p>
				</div>

				<div class="dm-contact-card">
					<div class="dm-contact-card__icon dm-contact-card__icon--orange">
						<?php echo dentomart_icon( 'pin', 26 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
					<h3><?php esc_html_e( 'Central Hub & Warehouse', 'dentomart' ); ?></h3>
					<p class="dm-contact-card__detail"><strong>Plot 42, Dental Logistics Park</strong></p>
					<p class="dm-contact-card__sub"><?php esc_html_e( 'Phase 2, Okhla Industrial Area', 'dentomart' ); ?><br><?php esc_html_e( 'New Delhi, Delhi - 110020, India', 'dentomart' ); ?></p>
				</div>
			</div>
		</div>
	</section>

	<!-- Main Form & Visual Section -->
	<section class="dm-contact-form-section">
		<div class="dm-container">
			<div class="dm-contact-layout">

				<!-- Form Column -->
				<div class="dm-contact-form-card">
					<div class="dm-contact-form-header">
						<span class="dm-slider-eyebrow"><?php esc_html_e( 'SEND US A MESSAGE', 'dentomart' ); ?></span>
						<h2 class="dm-contact-form-title"><?php esc_html_e( 'Clinic Inquiry & Support Form', 'dentomart' ); ?></h2>
						<p class="dm-contact-form-desc"><?php esc_html_e( 'Fill out the details below and our clinical account manager will connect with you promptly.', 'dentomart' ); ?></p>
					</div>

					<form class="dm-custom-contact-form" id="dmContactForm" method="post" action="#">
						<div class="dm-form-row dm-form-row--2col">
							<div class="dm-form-group">
								<label for="dm_contact_name"><?php esc_html_e( 'Full Name / Doctor Name', 'dentomart' ); ?> <span class="required">*</span></label>
								<input type="text" id="dm_contact_name" name="contact_name" class="dm-form-input" placeholder="<?php esc_attr_e( 'e.g. Dr. Rajesh Sharma', 'dentomart' ); ?>" required />
							</div>
							<div class="dm-form-group">
								<label for="dm_contact_clinic"><?php esc_html_e( 'Dental Clinic / Hospital Name', 'dentomart' ); ?></label>
								<input type="text" id="dm_contact_clinic" name="contact_clinic" class="dm-form-input" placeholder="<?php esc_attr_e( 'e.g. Advanced Dental Care', 'dentomart' ); ?>" />
							</div>
						</div>

						<div class="dm-form-row dm-form-row--2col">
							<div class="dm-form-group">
								<label for="dm_contact_phone"><?php esc_html_e( 'Mobile / WhatsApp Number', 'dentomart' ); ?> <span class="required">*</span></label>
								<input type="tel" id="dm_contact_phone" name="contact_phone" class="dm-form-input" placeholder="<?php esc_attr_e( 'e.g. 9876543210', 'dentomart' ); ?>" required />
							</div>
							<div class="dm-form-group">
								<label for="dm_contact_email"><?php esc_html_e( 'Email Address', 'dentomart' ); ?> <span class="required">*</span></label>
								<input type="email" id="dm_contact_email" name="contact_email" class="dm-form-input" placeholder="<?php esc_attr_e( 'e.g. doctor@clinic.com', 'dentomart' ); ?>" required />
							</div>
						</div>

						<div class="dm-form-group">
							<label for="dm_contact_subject"><?php esc_html_e( 'Inquiry Type', 'dentomart' ); ?></label>
							<select id="dm_contact_subject" name="contact_subject" class="dm-form-input">
								<option value="bulk"><?php esc_html_e( 'Wholesale Bulk Clinic Order', 'dentomart' ); ?></option>
								<option value="equipment"><?php esc_html_e( 'Equipment Demo & Installation (Chairs / Autoclaves)', 'dentomart' ); ?></option>
								<option value="product"><?php esc_html_e( 'Product Technical Consultation', 'dentomart' ); ?></option>
								<option value="order_status"><?php esc_html_e( 'Existing Order & Dispatch Tracking', 'dentomart' ); ?></option>
								<option value="suggest"><?php esc_html_e( 'Request / Suggest a New Product', 'dentomart' ); ?></option>
								<option value="other"><?php esc_html_e( 'Other Inquiry', 'dentomart' ); ?></option>
							</select>
						</div>

						<div class="dm-form-group">
							<label for="dm_contact_message"><?php esc_html_e( 'Your Message or Product List', 'dentomart' ); ?> <span class="required">*</span></label>
							<textarea id="dm_contact_message" name="contact_message" rows="5" class="dm-form-input" placeholder="<?php esc_attr_e( 'Please mention item quantities, brand preferences, or your clinical requirements...', 'dentomart' ); ?>" required></textarea>
						</div>

						<button type="submit" class="dm-btn dm-btn--accent dm-btn--lg dm-contact-submit-btn">
							<span><?php esc_html_e( 'SEND MESSAGE', 'dentomart' ); ?></span>
							<?php echo dentomart_icon( 'arrow-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</button>

						<div class="dm-form-status-msg" id="dmFormStatus" aria-live="polite"></div>
					</form>
				</div>

				<!-- Visual / Support Hub Column -->
				<div class="dm-contact-sidebar">
					<div class="dm-contact-media-card">
						<img src="<?php echo esc_url( $contact_img_url ); ?>" alt="<?php esc_attr_e( 'DentoMart Clinical Support & Sales Hub', 'dentomart' ); ?>" class="dm-contact-hub-img" loading="lazy" />
						<div class="dm-contact-media-info">
							<h4><?php esc_html_e( 'Central Clinical Support Hub', 'dentomart' ); ?></h4>
							<p><?php esc_html_e( 'Our in-house clinical experts review every bulk inquiry and equipment requirement to provide customized wholesale quotes within 2 hours.', 'dentomart' ); ?></p>
						</div>
					</div>

					<div class="dm-contact-hours-card">
						<div class="dm-contact-hours-card__header">
							<?php echo dentomart_icon( 'clock', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<strong><?php esc_html_e( 'Operating Hours & Dispatch Times', 'dentomart' ); ?></strong>
						</div>
						<ul class="dm-contact-hours-list">
							<li><span><?php esc_html_e( 'Monday – Friday:', 'dentomart' ); ?></span> <strong>9:00 AM – 8:00 PM IST</strong></li>
							<li><span><?php esc_html_e( 'Saturday:', 'dentomart' ); ?></span> <strong>9:00 AM – 6:00 PM IST</strong></li>
							<li><span><?php esc_html_e( 'Sunday:', 'dentomart' ); ?></span> <em><?php esc_html_e( 'Emergency Dispatch Only', 'dentomart' ); ?></em></li>
						</ul>
					</div>
				</div>

			</div>
		</div>
	</section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var form = document.getElementById('dmContactForm');
	var status = document.getElementById('dmFormStatus');
	if (form && status) {
		form.addEventListener('submit', function(e) {
			e.preventDefault();
			var name = document.getElementById('dm_contact_name').value;
			status.innerHTML = '<div style="background:#ECFDF5; border:1px solid #6EE7B7; color:#065F46; padding:14px 18px; border-radius:10px; margin-top:16px; font-weight:700;">✓ Thank you, Dr. ' + (name ? name.replace(/[<>&]/g, '') : '') + '! Your message has been sent to our Clinical Procurement Desk. We will reach out within 2 hours.</div>';
			form.reset();
		});
	}
});
</script>

<?php
get_footer();
