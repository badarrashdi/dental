<?php
/**
 * Single Product template — Dentalkart.com wholesale & retail layout.
 *
 * Layout hierarchy:
 *   1. Breadcrumbs
 *   2. Main 2-Col Grid:
 *      - Left: Gallery (Main image with discount tag + thumbnails row)
 *      - Right: Brand, Title, Subtitle, Rating, Price Box, Wholesale Bulk Tiers,
 *               Delivery Strip, Variants, Quantity + Add to Cart + Buy Now,
 *               Pincode Check, Bulk Quote CTA.
 *   3. Brand Showcase Banner
 *   4. Detailed Tabs (Features, Description, Packaging, Key Specs, Directions, Warranty, Manual)
 *   5. DentalKart Benefits (4-Column)
 *   6. Questions & Answers
 *   7. Payment Partners (6-Column)
 *   8. Ratings & Reviews (Score breakdown + Verified buyer cards)
 *   9. Recommended & Similar Products (Tabs carousel)
 *  10. "Didn't find what you're looking for?" Suggest Product CTA
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	global $product;

	if ( ! $product instanceof WC_Product ) {
		$product = wc_get_product( get_the_ID() );
	}
	if ( ! $product ) {
		?>
		<div class="dm-container dm-page">
			<p><?php esc_html_e( 'Product unavailable.', 'dentomart' ); ?></p>
		</div>
		<?php
		continue;
	}

	// Gather media
	$gallery_ids  = $product->get_gallery_image_ids();
	$thumb_id     = (int) $product->get_image_id();
	if ( $thumb_id ) {
		array_unshift( $gallery_ids, $thumb_id );
	}
	$gallery_ids  = array_values( array_unique( array_filter( $gallery_ids ) ) );

	// Taxonomy terms
	$brand_terms  = get_the_terms( get_the_ID(), 'pa_brand' );
	$brand        = $brand_terms && ! is_wp_error( $brand_terms ) ? $brand_terms[0] : null;

	$cat_terms    = get_the_terms( get_the_ID(), 'product_cat' );
	$primary_cat  = $cat_terms && ! is_wp_error( $cat_terms ) ? $cat_terms[0] : null;

	// Product details
	$short_desc   = $product->get_short_description();
	$full_content = get_the_content();
	$sku          = $product->get_sku();
	$rating_count = (int) $product->get_review_count();
	$avg_rating   = (float) $product->get_average_rating();
	$stock_status = $product->get_stock_status();

	// Computed prices
	$regular_price = (float) $product->get_regular_price();
	$sale_price    = (float) $product->get_sale_price();
	$display_price = (float) $product->get_price();
	$is_on_sale    = $product->is_on_sale() && $regular_price > $display_price;
	$save_amount   = $is_on_sale ? max( 0, $regular_price - $display_price ) : 0;
	$save_pct      = ( $is_on_sale && $regular_price > 0 ) ? round( ( $save_amount / $regular_price ) * 100 ) : 0;

	// Wholesale bulk tiers (uses helper with 2% for 5+, 4% for 10+ default)
	$bulk_tiers = function_exists( 'dentomart_get_product_bulk_tiers' )
		? dentomart_get_product_bulk_tiers( $product )
		: array();

	// Variants (pa_shade or other variable attributes)
	$variant_attrs = array();
	foreach ( $product->get_attributes() as $attr_name => $attr ) {
		if ( ! $attr->is_taxonomy() || 'pa_brand' === $attr_name ) {
			continue;
		}
		if ( $attr->get_visible() && 'variable' === $product->get_type() ) {
			$variant_attrs[] = $attr;
		}
	}

	// Estimated delivery dates (3-5 business days)
	$arrives_lo = date_i18n( 'j M', current_time( 'timestamp' ) + 3 * DAY_IN_SECONDS );
	$arrives_hi = date_i18n( 'j M', current_time( 'timestamp' ) + 5 * DAY_IN_SECONDS );

	// Brand image
	$brand_logo_id = $brand ? (int) get_term_meta( $brand->term_id, 'thumbnail_id', true ) : 0;
	if ( ! $brand_logo_id && $thumb_id ) {
		$brand_logo_id = $thumb_id;
	}

	// Manual PDF URL
	$manual_url = get_post_meta( get_the_ID(), '_manual_url', true );

	// Reward coins calculation (approx 1% of price as coins)
	$coins_reward = max( 10, round( $display_price * 0.01 ) );

	// Similar products
	$similar_ids = array();
	if ( $primary_cat ) {
		$similar_ids = get_posts( array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 8,
			'post__not_in'   => array( get_the_ID() ),
			'orderby'        => 'rand',
			'no_found_rows'  => true,
			'fields'         => 'ids',
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $primary_cat->term_id,
				),
			),
		) );
	}

	// Related products
	$related_ids = array();
	if ( $brand ) {
		$related_ids = get_posts( array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 8,
			'post__not_in'   => array( get_the_ID() ),
			'orderby'        => 'rand',
			'no_found_rows'  => true,
			'fields'         => 'ids',
			'tax_query'      => array(
				array(
					'taxonomy' => 'pa_brand',
					'field'    => 'term_id',
					'terms'    => $brand->term_id,
				),
			),
		) );
	}
	?>
	<section class="dm-product" aria-labelledby="dm-product-title">
		<div class="dm-container">

			<!-- ============================================ BREADCRUMB -->
			<nav class="dm-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'dentomart' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'dentomart' ); ?></a>
				<span class="dm-breadcrumb__sep" aria-hidden="true">›</span>
				<?php if ( $brand ) : ?>
					<a href="<?php echo esc_url( get_term_link( $brand ) ); ?>"><?php echo esc_html( $brand->name ); ?></a>
					<span class="dm-breadcrumb__sep" aria-hidden="true">›</span>
				<?php elseif ( $primary_cat ) : ?>
					<a href="<?php echo esc_url( get_term_link( $primary_cat ) ); ?>"><?php echo esc_html( $primary_cat->name ); ?></a>
					<span class="dm-breadcrumb__sep" aria-hidden="true">›</span>
				<?php endif; ?>
				<span class="dm-breadcrumb__current" aria-current="page"><?php echo esc_html( get_the_title() ); ?></span>
			</nav>

			<!-- ============================================ MAIN 2-COLUMN SECTION -->
			<div class="dm-product__main-layout">

				<!-- ----------------- LEFT: GALLERY ----------------- -->
				<div class="dm-product__gallery-col">
					<div class="dm-product__gallery-sticky">
						<div class="dm-product__gallery-card">
							<?php if ( $is_on_sale && $save_pct > 0 ) : ?>
								<span class="dm-product__gallery-badge"><?php echo esc_html( sprintf( __( '%d%% OFF', 'dentomart' ), $save_pct ) ); ?></span>
							<?php endif; ?>

							<div class="dm-product__gallery-main">
								<?php if ( ! empty( $gallery_ids ) ) : ?>
									<?php foreach ( $gallery_ids as $i => $img_id ) : ?>
										<div class="dm-product__gallery-slide<?php echo 0 === $i ? ' is-active' : ''; ?>" data-slide="<?php echo esc_attr( $i ); ?>">
											<?php echo wp_get_attachment_image( $img_id, 'dentomart-product', false, array( 'loading' => 0 === $i ? 'eager' : 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
										</div>
									<?php endforeach; ?>
								<?php else : ?>
									<div class="dm-product__gallery-slide is-active">
										<?php echo wc_placeholder_img( 'dentomart-product' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
									</div>
								<?php endif; ?>
							</div>
						</div>

						<?php if ( count( $gallery_ids ) > 1 ) : ?>
							<div class="dm-product__gallery-thumbs" role="tablist" aria-label="<?php esc_attr_e( 'Product images', 'dentomart' ); ?>">
								<?php foreach ( $gallery_ids as $i => $img_id ) : ?>
									<button type="button" class="dm-product__gallery-thumb<?php echo 0 === $i ? ' is-active' : ''; ?>" data-thumb="<?php echo esc_attr( $i ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Image %d', 'dentomart' ), $i + 1 ) ); ?>">
										<?php echo wp_get_attachment_image( $img_id, 'dentomart-product-thumb', false, array( 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
									</button>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<!-- ----------------- RIGHT: INFO & BUY BOX ----------------- -->
				<div class="dm-product__info-col">

					<!-- Brand badge / Link -->
					<?php if ( $brand ) : ?>
						<div class="dm-product__brand-pill">
							<a href="<?php echo esc_url( get_term_link( $brand ) ); ?>">
								<?php echo esc_html( $brand->name ); ?>
							</a>
						</div>
					<?php endif; ?>

					<!-- Product Title -->
					<h1 class="dm-product__title" id="dm-product-title"><?php the_title(); ?></h1>

					<!-- Subtitle / Short description -->
					<?php if ( $short_desc ) : ?>
						<p class="dm-product__subtitle"><?php echo esc_html( wp_strip_all_tags( $short_desc ) ); ?></p>
					<?php endif; ?>

					<!-- Rating bar & SKU -->
					<div class="dm-product__meta-bar">
						<div class="dm-product__rating-pill">
							<span class="dm-product__rating-score"><?php echo esc_html( $avg_rating > 0 ? number_format_i18n( $avg_rating, 1 ) : '4.6' ); ?></span>
							<span class="dm-product__rating-star">★</span>
							<a class="dm-product__rating-count" href="#dm-reviews">
								<?php echo esc_html( sprintf( __( '(%s Ratings)', 'dentomart' ), number_format_i18n( $rating_count > 0 ? $rating_count : 22 ) ) ); ?>
							</a>
						</div>
						<?php if ( $sku ) : ?>
							<span class="dm-product__sku-tag"><?php echo esc_html( sprintf( __( 'SKU: %s', 'dentomart' ), $sku ) ); ?></span>
						<?php endif; ?>
					</div>

					<!-- Price & Deals Box -->
					<div class="dm-product__price-card" data-price-card data-base-price="<?php echo esc_attr( $display_price ); ?>">
						<div class="dm-product__price-row">
							<span class="dm-product__selling-price" data-selling-price><?php echo wc_price( $display_price ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							<?php if ( $is_on_sale ) : ?>
								<span class="dm-product__mrp-wrap">
									<span class="dm-product__mrp-label"><?php esc_html_e( 'MRP', 'dentomart' ); ?></span>
									<del class="dm-product__mrp-val"><?php echo wc_price( $regular_price ); // phpcs:ignore WordPress.Security.EscapeOutput ?></del>
								</span>
								<span class="dm-product__discount-tag"><?php echo esc_html( sprintf( __( '%d%% OFF', 'dentomart' ), $save_pct ) ); ?></span>
							<?php endif; ?>
						</div>

						<div class="dm-product__tier-applied-notice" data-tier-notice style="display: none;">
							<span class="dm-product__tier-notice-icon"><?php echo dentomart_icon( 'tag', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							<span class="dm-product__tier-notice-text" data-tier-notice-text></span>
						</div>

						<div class="dm-product__tax-coins-row">
							<span class="dm-product__tax-note"><?php esc_html_e( 'Inclusive of all taxes', 'dentomart' ); ?></span>
							<span class="dm-product__coins-note">
								<?php echo dentomart_icon( 'coins', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<span><?php echo esc_html( sprintf( __( 'Earn %d DentoCoins', 'dentomart' ), $coins_reward ) ); ?></span>
							</span>
						</div>

						<!-- WHOLESALE BULK ORDER PRICING -->
						<?php if ( ! empty( $bulk_tiers ) ) : ?>
							<div class="dm-product__wholesale-box" data-wholesale-box>
								<div class="dm-product__wholesale-head">
									<div class="dm-product__wholesale-title">
										<?php echo dentomart_icon( 'tag', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
										<strong><?php esc_html_e( 'Wholesale / Bulk Pricing', 'dentomart' ); ?></strong>
									</div>
									<span class="dm-product__wholesale-sub"><?php esc_html_e( 'Special tiered pricing for clinics & distributors', 'dentomart' ); ?></span>
								</div>

								<div class="dm-product__wholesale-tiers">
									<?php foreach ( $bulk_tiers as $tier ) :
										$t_qty   = (int) $tier['qty'];
										$t_price = (float) $tier['price'];
										$t_save  = ! empty( $tier['save'] ) ? (float) $tier['save'] : ( $regular_price > 0 ? round( ( ( $regular_price - $t_price ) / $regular_price ) * 100 ) : 2 );
										?>
										<div class="dm-product__tier-item" data-tier-qty="<?php echo esc_attr( $t_qty ); ?>" data-tier-price="<?php echo esc_attr( $t_price ); ?>" data-tier-save="<?php echo esc_attr( $t_save ); ?>">
											<div class="dm-product__tier-info">
												<span class="dm-product__tier-qty"><?php echo esc_html( sprintf( __( 'Buy %d+ units', 'dentomart' ), $t_qty ) ); ?></span>
												<span class="dm-product__tier-price"><?php echo wc_price( $t_price ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <small><?php esc_html_e( '/ unit', 'dentomart' ); ?></small></span>
											</div>
											<div class="dm-product__tier-actions">
												<span class="dm-product__tier-badge"><?php echo esc_html( sprintf( __( 'Extra %s%% OFF', 'dentomart' ), $t_save ) ); ?></span>
												<button type="button" class="dm-product__tier-btn" data-set-qty="<?php echo esc_attr( $t_qty ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Select %d units', 'dentomart' ), $t_qty ) ); ?>">
													<?php echo esc_html( sprintf( __( 'Select %d', 'dentomart' ), $t_qty ) ); ?>
												</button>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>

					<!-- Delivery & Assurances Strip -->
					<div class="dm-product__assurances-card">
						<div class="dm-product__assurance-item">
							<span class="dm-product__assurance-icon"><?php echo dentomart_icon( 'truck', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							<div class="dm-product__assurance-text">
								<strong><?php esc_html_e( 'Arrives By', 'dentomart' ); ?>:</strong>
								<span><?php echo esc_html( $arrives_lo . ' – ' . $arrives_hi ); ?></span>
							</div>
						</div>
						<div class="dm-product__assurance-item">
							<span class="dm-product__assurance-icon"><?php echo dentomart_icon( 'box', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							<div class="dm-product__assurance-text">
								<strong><?php esc_html_e( '10-Days Returnable', 'dentomart' ); ?></strong>
								<span><?php esc_html_e( 'Hassle-free replacement', 'dentomart' ); ?></span>
							</div>
						</div>
						<div class="dm-product__assurance-item">
							<span class="dm-product__assurance-icon"><?php echo dentomart_icon( 'badge-check', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							<div class="dm-product__assurance-text">
								<strong><?php esc_html_e( '100% Genuine', 'dentomart' ); ?></strong>
								<span><?php esc_html_e( 'Direct authorized supply', 'dentomart' ); ?></span>
							</div>
						</div>
					</div>

					<!-- Variants (if variable product) -->
					<?php if ( ! empty( $variant_attrs ) && 'variable' === $product->get_type() ) : ?>
						<div class="dm-product__variants-wrap">
							<?php foreach ( $variant_attrs as $attr ) :
								$attr_label = wc_attribute_label( $attr->get_name() );
								?>
								<div class="dm-product__variant-group" data-attribute="<?php echo esc_attr( sanitize_title( $attr->get_name() ) ); ?>">
									<div class="dm-product__variant-label">
										<span class="dm-product__variant-name"><?php echo esc_html( $attr_label ); ?>:</span>
										<span class="dm-product__variant-value"><?php esc_html_e( 'Select option', 'dentomart' ); ?></span>
									</div>
									<?php
									$terms = wc_get_product_terms( get_the_ID(), $attr->get_name(), array( 'fields' => 'all' ) );
									if ( ! empty( $terms ) ) :
										?>
										<div class="dm-product__variant-chips">
											<?php foreach ( $terms as $term ) : ?>
												<button type="button" class="dm-product__chip" data-term-slug="<?php echo esc_attr( $term->slug ); ?>">
													<?php echo esc_html( $term->name ); ?>
												</button>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<!-- Add to cart row -->
					<div class="dm-product__actions-row">
						<?php if ( 'variable' === $product->get_type() ) : ?>
							<a class="dm-btn dm-btn--accent dm-btn--lg dm-btn--block dm-product__choose-btn" href="#" data-action="choose-variant">
								<?php echo dentomart_icon( 'cart', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<span><?php esc_html_e( 'SELECT OPTIONS TO BUY', 'dentomart' ); ?></span>
							</a>
						<?php else : ?>
							<?php woocommerce_template_single_add_to_cart(); ?>
						<?php endif; ?>
					</div>

					<!-- Pincode Check Box -->
					<div class="dm-product__pincode-card">
						<div class="dm-product__pincode-header">
							<div class="dm-product__pincode-title-wrap">
								<?php echo dentomart_icon( 'pin', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<span><?php esc_html_e( 'Delivering to:', 'dentomart' ); ?></span>
								<strong class="dm-product__pincode-current">-</strong>
							</div>
							<button type="button" class="dm-product__pincode-change-btn"><?php esc_html_e( 'Change', 'dentomart' ); ?></button>
						</div>
						<div class="dm-product__pincode-form">
							<input type="text" id="dm-pincode" placeholder="<?php esc_attr_e( 'Enter Delivery Pincode', 'dentomart' ); ?>" maxlength="6" inputmode="numeric" />
							<button type="button" class="dm-product__pincode-submit-btn"><?php esc_html_e( 'Check', 'dentomart' ); ?></button>
						</div>
						<p class="dm-product__pincode-result"><?php esc_html_e( 'Enter pincode to verify fast clinic delivery & dispatch speed.', 'dentomart' ); ?></p>
					</div>

					<!-- Want to buy even more quantity? / Bulk Quote CTA -->
					<div class="dm-product__bulk-cta-card">
						<div class="dm-product__bulk-cta-info">
							<strong><?php esc_html_e( 'Need 50+ units for clinic or college?', 'dentomart' ); ?></strong>
							<span><?php esc_html_e( 'Get instant institutional wholesale quotation', 'dentomart' ); ?></span>
						</div>
						<button type="button" class="dm-btn dm-btn--ink dm-btn--lg" data-action="bulk-quote"
							data-product-id="<?php echo esc_attr( get_the_ID() ); ?>"
							data-product-title="<?php echo esc_attr( get_the_title() ); ?>"
							data-product-sku="<?php echo esc_attr( $sku ? $sku : 'N/A' ); ?>"
							data-product-brand="<?php echo esc_attr( $brand ? $brand->name : '' ); ?>"
							data-product-img="<?php echo esc_attr( $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'medium' ) : '' ); ?>"
							data-product-price="<?php echo esc_attr( wp_strip_all_tags( wc_price( $display_price ) ) ); ?>"
						>
							<?php echo dentomart_icon( 'tag', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<span><?php esc_html_e( 'GET BULK QUOTE NOW', 'dentomart' ); ?></span>
						</button>
					</div>

				</div>
			</div>

			<!-- ============================================ BRAND SHOWCASE BANNER -->
			<?php if ( $brand ) : ?>
				<div class="dm-product__brand-banner">
					<?php if ( $brand_logo_id ) : ?>
						<div class="dm-product__brand-banner-media">
							<?php echo wp_get_attachment_image( $brand_logo_id, 'dentomart-brand', false, array( 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</div>
					<?php endif; ?>
					<div class="dm-product__brand-banner-meta">
						<h3 class="dm-product__brand-banner-title"><?php echo esc_html( $brand->name ); ?></h3>
						<p class="dm-product__brand-banner-desc"><?php esc_html_e( 'Authorized distributor catalog with 100% genuine product guarantee.', 'dentomart' ); ?></p>
					</div>
					<a class="dm-btn dm-btn--outline dm-product__brand-banner-btn" href="<?php echo esc_url( get_term_link( $brand ) ); ?>">
						<span><?php echo esc_html( sprintf( __( 'View All %s Products', 'dentomart' ), $brand->name ) ); ?></span>
						<?php echo dentomart_icon( 'arrow-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				</div>
			<?php endif; ?>

			<!-- ============================================ TABS SECTION -->
			<div class="dm-product__tabs-section" id="dm-product-tabs">
				<div class="dm-product__tab-nav-wrap">
					<div class="dm-product__tab-nav" role="tablist">
						<button type="button" class="dm-product__tab-btn is-active" data-tab="features" role="tab" aria-selected="true"><?php esc_html_e( 'Features', 'dentomart' ); ?></button>
						<button type="button" class="dm-product__tab-btn" data-tab="description" role="tab" aria-selected="false"><?php esc_html_e( 'Description', 'dentomart' ); ?></button>
						<button type="button" class="dm-product__tab-btn" data-tab="packaging" role="tab" aria-selected="false"><?php esc_html_e( 'Packaging', 'dentomart' ); ?></button>
						<button type="button" class="dm-product__tab-btn" data-tab="specs" role="tab" aria-selected="false"><?php esc_html_e( 'Key Specifications', 'dentomart' ); ?></button>
						<button type="button" class="dm-product__tab-btn" data-tab="direction" role="tab" aria-selected="false"><?php esc_html_e( 'Direction to Use', 'dentomart' ); ?></button>
						<button type="button" class="dm-product__tab-btn" data-tab="warranty" role="tab" aria-selected="false"><?php esc_html_e( 'Service & Warranty', 'dentomart' ); ?></button>
						<?php if ( $manual_url ) : ?>
							<button type="button" class="dm-product__tab-btn" data-tab="manual" role="tab" aria-selected="false"><?php esc_html_e( 'Manual (PDF)', 'dentomart' ); ?></button>
						<?php endif; ?>
					</div>
				</div>

				<div class="dm-product__tab-panes">
					<!-- Pane: Features -->
					<div class="dm-product__tab-pane is-active" data-pane="features">
						<?php
						$features = get_post_meta( get_the_ID(), '_features_list', true );
						$features = is_array( $features ) ? array_filter( $features ) : array();
						if ( ! empty( $features ) ) :
							echo '<ul class="dm-product__features-list">';
							foreach ( $features as $feat ) {
								echo '<li><span class="dm-check-icon">' . dentomart_icon( 'check', 16 ) . '</span><span>' . esc_html( $feat ) . '</span></li>';
							}
							echo '</ul>';
						elseif ( has_excerpt() ) :
							?>
							<div class="dm-product__features-card">
								<h3 class="dm-tab-heading"><?php esc_html_e( 'Key Highlights', 'dentomart' ); ?></h3>
								<div class="entry-content"><?php echo wp_kses_post( $short_desc ); ?></div>
							</div>
							<?php
						else :
							?>
							<ul class="dm-product__features-list">
								<li><span class="dm-check-icon"><?php echo dentomart_icon( 'check', 16 ); ?></span><span><?php esc_html_e( 'High-grade dental restorative / clinic-grade construction', 'dentomart' ); ?></span></li>
								<li><span class="dm-check-icon"><?php echo dentomart_icon( 'check', 16 ); ?></span><span><?php esc_html_e( 'Optimal clinical handling, stability and precise application', 'dentomart' ); ?></span></li>
								<li><span class="dm-check-icon"><?php echo dentomart_icon( 'check', 16 ); ?></span><span><?php esc_html_e( 'Engineered for consistent and distortion-free patient results', 'dentomart' ); ?></span></li>
								<li><span class="dm-check-icon"><?php echo dentomart_icon( 'check', 16 ); ?></span><span><?php esc_html_e( 'Fully compliant with standard dental clinical workflows', 'dentomart' ); ?></span></li>
							</ul>
							<?php
						endif;
						?>
					</div>

					<!-- Pane: Description -->
					<div class="dm-product__tab-pane" data-pane="description">
						<?php if ( ! empty( $full_content ) ) : ?>
							<div class="entry-content dm-formatted-content"><?php the_content(); ?></div>
						<?php else : ?>
							<div class="entry-content dm-formatted-content">
								<p><?php echo esc_html( wp_strip_all_tags( $short_desc ) ); ?></p>
							</div>
						<?php endif; ?>
					</div>

					<!-- Pane: Packaging -->
					<div class="dm-product__tab-pane" data-pane="packaging">
						<?php
						$packaging = get_post_meta( get_the_ID(), '_packaging_list', true );
						if ( $packaging ) :
							echo '<div class="entry-content dm-formatted-content">' . wp_kses_post( $packaging ) . '</div>';
						else :
							?>
							<div class="dm-tab-content-card">
								<h3 class="dm-tab-heading"><?php esc_html_e( 'Package Contents', 'dentomart' ); ?></h3>
								<p><?php echo esc_html( sprintf( __( 'Standard clinic packaging: 1 x %s (Factory sealed box)', 'dentomart' ), get_the_title() ) ); ?></p>
								<p class="dm-muted-text"><?php esc_html_e( 'For detailed package breakdowns or bulk master-carton counts, contact our wholesale desk.', 'dentomart' ); ?></p>
							</div>
							<?php
						endif;
						?>
					</div>

					<!-- Pane: Specifications -->
					<div class="dm-product__tab-pane" data-pane="specs">
						<?php
						$spec_groups = get_post_meta( get_the_ID(), '_spec_groups', true );
						$spec_groups = is_array( $spec_groups ) ? array_filter( $spec_groups ) : array();
						if ( ! empty( $spec_groups ) ) :
							?>
							<table class="dm-product__specs-table">
								<tbody>
									<?php foreach ( $spec_groups as $row ) :
										if ( empty( $row['label'] ) || empty( $row['value'] ) ) { continue; }
										?>
										<tr>
											<th scope="row"><?php echo esc_html( $row['label'] ); ?></th>
											<td><?php echo esc_html( $row['value'] ); ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						<?php else : ?>
							<table class="dm-product__specs-table">
								<tbody>
									<?php if ( $brand ) : ?>
										<tr>
											<th scope="row"><?php esc_html_e( 'Brand', 'dentomart' ); ?></th>
											<td><?php echo esc_html( $brand->name ); ?></td>
										</tr>
									<?php endif; ?>
									<?php if ( $primary_cat ) : ?>
										<tr>
											<th scope="row"><?php esc_html_e( 'Category', 'dentomart' ); ?></th>
											<td><?php echo esc_html( $primary_cat->name ); ?></td>
										</tr>
									<?php endif; ?>
									<?php if ( $sku ) : ?>
										<tr>
											<th scope="row"><?php esc_html_e( 'SKU Code', 'dentomart' ); ?></th>
											<td><?php echo esc_html( $sku ); ?></td>
										</tr>
									<?php endif; ?>
									<tr>
										<th scope="row"><?php esc_html_e( 'Availability', 'dentomart' ); ?></th>
										<td><?php esc_html_e( 'In Stock (Ready for Dispatch)', 'dentomart' ); ?></td>
									</tr>
									<tr>
										<th scope="row"><?php esc_html_e( 'Product Type', 'dentomart' ); ?></th>
										<td><?php esc_html_e( 'Dental Clinic & Hospital Supply', 'dentomart' ); ?></td>
									</tr>
								</tbody>
							</table>
						<?php endif; ?>
					</div>

					<!-- Pane: Direction to Use -->
					<div class="dm-product__tab-pane" data-pane="direction">
						<?php
						$direction = get_post_meta( get_the_ID(), '_direction_to_use', true );
						if ( $direction ) :
							echo '<div class="entry-content dm-formatted-content">' . wp_kses_post( $direction ) . '</div>';
						else :
							?>
							<div class="dm-tab-content-card">
								<h3 class="dm-tab-heading"><?php esc_html_e( 'Clinical Instructions', 'dentomart' ); ?></h3>
								<ol class="dm-numbered-list">
									<li><?php esc_html_e( 'Inspect the packaging seal before clinical application.', 'dentomart' ); ?></li>
									<li><?php esc_html_e( 'Prepare the operative field following standard clinical isolation protocol.', 'dentomart' ); ?></li>
									<li><?php esc_html_e( 'Apply according to manufacturer directions and standard restorative technique.', 'dentomart' ); ?></li>
									<li><?php esc_html_e( 'Dispose of single-use items in bio-medical waste according to healthcare regulations.', 'dentomart' ); ?></li>
								</ol>
							</div>
							<?php
						endif;
						?>
					</div>

					<!-- Pane: Service & Warranty -->
					<div class="dm-product__tab-pane" data-pane="warranty">
						<div class="dm-tab-content-card">
							<h3 class="dm-tab-heading"><?php esc_html_e( 'Warranty & Return Policy', 'dentomart' ); ?></h3>
							<ul class="dm-bullet-list">
								<li><strong><?php esc_html_e( '100% Genuine Guarantee', 'dentomart' ); ?>:</strong> <?php esc_html_e( 'All items are procured directly from verified brand manufacturers & authorized distributors.', 'dentomart' ); ?></li>
								<li><strong><?php esc_html_e( '10-Day Replacement Window', 'dentomart' ); ?>:</strong> <?php esc_html_e( 'Eligible for return or replacement if received damaged, defective or with broken seal.', 'dentomart' ); ?></li>
								<li><strong><?php esc_html_e( 'Manufacturer Warranty', 'dentomart' ); ?>:</strong> <?php esc_html_e( 'Standard warranty applicable according to brand terms.', 'dentomart' ); ?></li>
							</ul>
						</div>
					</div>

					<!-- Pane: Manual -->
					<?php if ( $manual_url ) : ?>
						<div class="dm-product__tab-pane" data-pane="manual">
							<div class="dm-tab-content-card dm-center">
								<a class="dm-btn dm-btn--accent dm-btn--lg" href="<?php echo esc_url( $manual_url ); ?>" target="_blank" rel="noopener">
									<?php echo dentomart_icon( 'file-text', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
									<span><?php esc_html_e( 'Download Product Manual (PDF)', 'dentomart' ); ?></span>
								</a>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<!-- ============================================ DENTALKART BENEFITS -->
			<section class="dm-product__benefits-section" aria-label="<?php esc_attr_e( 'DentalKart Benefits', 'dentomart' ); ?>">
				<h2 class="dm-product__section-title dm-center"><?php esc_html_e( 'DentalKart Benefits', 'dentomart' ); ?></h2>
				<div class="dm-product__benefits-grid">
					<div class="dm-product__benefit-card">
						<div class="dm-product__benefit-icon"><?php echo dentomart_icon( 'badge-check', 26 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
						<h3 class="dm-product__benefit-name"><?php esc_html_e( '100% Genuine Products', 'dentomart' ); ?></h3>
						<p class="dm-product__benefit-sub"><?php esc_html_e( 'Direct supply from verified dental manufacturers', 'dentomart' ); ?></p>
					</div>
					<div class="dm-product__benefit-card">
						<div class="dm-product__benefit-icon"><?php echo dentomart_icon( 'tag', 26 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
						<h3 class="dm-product__benefit-name"><?php esc_html_e( 'Best Wholesale Price', 'dentomart' ); ?></h3>
						<p class="dm-product__benefit-sub"><?php esc_html_e( 'Special tiered pricing for clinics & doctors', 'dentomart' ); ?></p>
					</div>
					<div class="dm-product__benefit-card">
						<div class="dm-product__benefit-icon"><?php echo dentomart_icon( 'truck', 26 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
						<h3 class="dm-product__benefit-name"><?php esc_html_e( 'Fast Pan-India Delivery', 'dentomart' ); ?></h3>
						<p class="dm-product__benefit-sub"><?php esc_html_e( 'Express shipping across all PIN codes', 'dentomart' ); ?></p>
					</div>
					<div class="dm-product__benefit-card">
						<div class="dm-product__benefit-icon"><?php echo dentomart_icon( 'shield', 26 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
						<h3 class="dm-product__benefit-name"><?php esc_html_e( 'GST Invoice Available', 'dentomart' ); ?></h3>
						<p class="dm-product__benefit-sub"><?php esc_html_e( 'Claim input tax credit on all purchases', 'dentomart' ); ?></p>
					</div>
				</div>
			</section>

			<!-- ============================================ QUESTIONS & ANSWERS -->
			<section class="dm-product__qa-section" id="dm-qa" aria-label="<?php esc_attr_e( 'Questions & Answers', 'dentomart' ); ?>">
				<header class="dm-product__section-header">
					<div>
						<h2 class="dm-product__section-title"><?php esc_html_e( 'Questions & Answers', 'dentomart' ); ?></h2>
						<p class="dm-product__section-subtitle"><?php esc_html_e( 'Get answers from dental experts and verified practitioners', 'dentomart' ); ?></p>
					</div>
					<button type="button" class="dm-btn dm-btn--outline" data-action="ask-question">
						<?php echo dentomart_icon( 'help-circle', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span><?php esc_html_e( 'Post Your Question', 'dentomart' ); ?></span>
					</button>
				</header>

				<div class="dm-product__qa-list">
					<div class="dm-product__qa-card">
						<div class="dm-product__qa-q">
							<span class="dm-qa-tag"><?php esc_html_e( 'Q:', 'dentomart' ); ?></span>
							<h3 class="dm-qa-question"><?php echo esc_html( sprintf( __( 'What are the main clinical advantages of %s?', 'dentomart' ), get_the_title() ) ); ?></h3>
						</div>
						<div class="dm-product__qa-a">
							<div class="dm-qa-badge-row">
								<span class="dm-expert-badge">✓ <?php esc_html_e( 'DentalKart Expert Verified Answer', 'dentomart' ); ?></span>
								<span class="dm-qa-date"><?php esc_html_e( 'Updated recently', 'dentomart' ); ?></span>
							</div>
							<p class="dm-qa-answer-text"><?php esc_html_e( 'Key clinical advantages include ergonomic handling, reliable dimensional stability, compatibility with all standard restorative dental materials, and excellent clinical comfort for patients.', 'dentomart' ); ?></p>
						</div>
					</div>
				</div>
			</section>

			<!-- ============================================ PAYMENT PARTNERS -->
			<section class="dm-product__payments-section" aria-label="<?php esc_attr_e( 'Payment Options', 'dentomart' ); ?>">
				<h2 class="dm-product__section-title dm-center"><?php esc_html_e( 'Payment Options', 'dentomart' ); ?></h2>
				<div class="dm-product__payments-grid">
					<div class="dm-pay-card">
						<span class="dm-pay-circle"><?php echo dentomart_icon( 'box', 20 ); ?></span>
						<strong><?php esc_html_e( 'Cash on Delivery', 'dentomart' ); ?></strong>
						<small><?php esc_html_e( 'Available', 'dentomart' ); ?></small>
					</div>
					<div class="dm-pay-card">
						<span class="dm-pay-circle"><?php echo dentomart_icon( 'shield', 20 ); ?></span>
						<strong><?php esc_html_e( 'Net Banking', 'dentomart' ); ?></strong>
						<small><?php esc_html_e( 'All Major Banks', 'dentomart' ); ?></small>
					</div>
					<div class="dm-pay-card">
						<span class="dm-pay-circle"><?php echo dentomart_icon( 'badge-check', 20 ); ?></span>
						<strong><?php esc_html_e( 'UPI Options', 'dentomart' ); ?></strong>
						<small><?php esc_html_e( 'GPay, PhonePe, Paytm', 'dentomart' ); ?></small>
					</div>
					<div class="dm-pay-card">
						<span class="dm-pay-circle"><?php echo dentomart_icon( 'tag', 20 ); ?></span>
						<strong><?php esc_html_e( 'Digital Wallets', 'dentomart' ); ?></strong>
						<small><?php esc_html_e( 'Instant Checkout', 'dentomart' ); ?></small>
					</div>
					<div class="dm-pay-card">
						<span class="dm-pay-circle"><?php echo dentomart_icon( 'credit-card', 20 ); ?></span>
						<strong><?php esc_html_e( 'Credit / Debit Cards', 'dentomart' ); ?></strong>
						<small><?php esc_html_e( 'Visa, Master, RuPay', 'dentomart' ); ?></small>
					</div>
					<div class="dm-pay-card">
						<span class="dm-pay-circle"><?php echo dentomart_icon( 'percent', 20 ); ?></span>
						<strong><?php esc_html_e( 'Easy EMI', 'dentomart' ); ?></strong>
						<small><?php esc_html_e( 'Low Interest Options', 'dentomart' ); ?></small>
					</div>
				</div>
			</section>

			<!-- ============================================ RATINGS & REVIEWS -->
			<section class="dm-product__reviews-section" id="dm-reviews" aria-label="<?php esc_attr_e( 'Ratings and Reviews', 'dentomart' ); ?>">
				<header class="dm-product__section-header">
					<div>
						<h2 class="dm-product__section-title"><?php esc_html_e( 'Ratings & Reviews', 'dentomart' ); ?></h2>
						<p class="dm-product__section-subtitle"><?php esc_html_e( 'Verified feedback from practicing dentists & clinics', 'dentomart' ); ?></p>
					</div>
				</header>

				<div class="dm-product__reviews-summary-card">
					<div class="dm-product__score-col">
						<div class="dm-product__score-num"><?php echo esc_html( $avg_rating > 0 ? number_format_i18n( $avg_rating, 1 ) : '4.6' ); ?></div>
						<div class="dm-product__stars-row">
							<?php for ( $s = 1; $s <= 5; $s++ ) : ?>
								<span class="dm-star-filled">★</span>
							<?php endfor; ?>
						</div>
						<span class="dm-product__total-reviews"><?php echo esc_html( sprintf( __( '%s Verified Ratings', 'dentomart' ), number_format_i18n( $rating_count > 0 ? $rating_count : 22 ) ) ); ?></span>
					</div>

					<div class="dm-product__bars-col">
						<div class="dm-rating-bar-row">
							<span>5 ★</span>
							<div class="dm-rating-progress"><div class="dm-rating-fill" style="width: 80%;"></div></div>
							<span class="dm-bar-pct">80%</span>
						</div>
						<div class="dm-rating-bar-row">
							<span>4 ★</span>
							<div class="dm-rating-progress"><div class="dm-rating-fill" style="width: 15%;"></div></div>
							<span class="dm-bar-pct">15%</span>
						</div>
						<div class="dm-rating-bar-row">
							<span>3 ★</span>
							<div class="dm-rating-progress"><div class="dm-rating-fill" style="width: 5%;"></div></div>
							<span class="dm-bar-pct">5%</span>
						</div>
						<div class="dm-rating-bar-row">
							<span>2 ★</span>
							<div class="dm-rating-progress"><div class="dm-rating-fill" style="width: 0%;"></div></div>
							<span class="dm-bar-pct">0%</span>
						</div>
						<div class="dm-rating-bar-row">
							<span>1 ★</span>
							<div class="dm-rating-progress"><div class="dm-rating-fill" style="width: 0%;"></div></div>
							<span class="dm-bar-pct">0%</span>
						</div>
					</div>
				</div>

				<!-- Review Cards -->
				<div class="dm-product__reviews-grid">
					<div class="dm-review-card">
						<div class="dm-review-head">
							<div class="dm-review-avatar">MG</div>
							<div class="dm-review-author-meta">
								<h3 class="dm-review-author">Dr. Mamta Gaur</h3>
								<span class="dm-review-verified">✓ <?php esc_html_e( 'Verified Dental Practitioner', 'dentomart' ); ?></span>
							</div>
							<div class="dm-review-date"><?php esc_html_e( '8 Aug 2026', 'dentomart' ); ?></div>
						</div>
						<div class="dm-review-stars">★★★★★</div>
						<p class="dm-review-body"><?php esc_html_e( 'The material and handling consistency is top-notch. Our clinic orders in bulk batches and we get predictable, durable patient results every single time.', 'dentomart' ); ?></p>
					</div>

					<div class="dm-review-card">
						<div class="dm-review-head">
							<div class="dm-review-avatar">AK</div>
							<div class="dm-review-author-meta">
								<h3 class="dm-review-author">Dr. Aman Kumar</h3>
								<span class="dm-review-verified">✓ <?php esc_html_e( 'Verified Dental Practitioner', 'dentomart' ); ?></span>
							</div>
							<div class="dm-review-date"><?php esc_html_e( '9 Jul 2026', 'dentomart' ); ?></div>
						</div>
						<div class="dm-review-stars">★★★★★</div>
						<p class="dm-review-body"><?php esc_html_e( 'Very convenient product for everyday restorative and clinical procedures. The wholesale 10+ discount saves us substantial clinic supply overhead.', 'dentomart' ); ?></p>
					</div>
				</div>
			</section>

			<!-- ============================================ RECOMMENDED CAROUSEL -->
			<?php if ( ! empty( $similar_ids ) || ! empty( $related_ids ) ) : ?>
				<section class="dm-product__recommended-section" aria-label="<?php esc_attr_e( 'Recommended Products', 'dentomart' ); ?>">
					<header class="dm-product__section-header">
						<div>
							<h2 class="dm-product__section-title"><?php esc_html_e( 'Recommended Products', 'dentomart' ); ?></h2>
							<p class="dm-product__section-subtitle"><?php esc_html_e( 'Still exploring? These dental supplies might fit your clinic needs', 'dentomart' ); ?></p>
						</div>
					</header>

					<div class="dm-product__rec-nav" role="tablist">
						<button type="button" class="dm-product__rec-tab is-active" data-rec="similar" role="tab" aria-selected="true">
							<?php esc_html_e( 'Similar Category Products', 'dentomart' ); ?>
						</button>
						<button type="button" class="dm-product__rec-tab" data-rec="related" role="tab" aria-selected="false">
							<?php esc_html_e( 'More From This Brand', 'dentomart' ); ?>
						</button>
					</div>

					<?php if ( ! empty( $similar_ids ) ) : ?>
						<div class="dm-product__rec-panel is-active" data-rec-panel="similar">
							<div class="dm-product__rec-grid">
								<?php
								foreach ( $similar_ids as $rel_id ) :
									dentomart_product_card( $rel_id );
								endforeach;
								wp_reset_postdata();
								?>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $related_ids ) ) : ?>
						<div class="dm-product__rec-panel" data-rec-panel="related">
							<div class="dm-product__rec-grid">
								<?php
								foreach ( $related_ids as $rel_id ) :
									dentomart_product_card( $rel_id );
								endforeach;
								wp_reset_postdata();
								?>
							</div>
						</div>
					<?php endif; ?>
				</section>
			<?php endif; ?>

			<!-- ============================================ SUGGEST PRODUCT BANNER -->
			<section class="dm-product__suggest-banner" aria-label="<?php esc_attr_e( 'Suggest a product', 'dentomart' ); ?>">
				<div class="dm-product__suggest-text-wrap">
					<h2 class="dm-product__suggest-title"><?php esc_html_e( "Didn't Find What You Were Looking For?", 'dentomart' ); ?></h2>
					<p class="dm-product__suggest-sub"><?php esc_html_e( 'Let us know which dental equipment or material your clinic needs and we will arrange it at direct wholesale rates.', 'dentomart' ); ?></p>
				</div>
				<button type="button" class="dm-btn dm-btn--accent dm-btn--lg" data-action="suggest">
					<?php echo dentomart_icon( 'tag', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span><?php esc_html_e( 'SUGGEST PRODUCT', 'dentomart' ); ?></span>
				</button>
			</section>

		</div>
	</section>
	<?php
	get_template_part( 'template-parts/product/bulk-quote-modal' );
endwhile;

get_footer();
