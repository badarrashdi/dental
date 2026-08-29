<?php
/**
 * WooCommerce integration for the theme.
 *
 * Keeps product rendering inside the theme but leaves the full shop
 * templates (archive/single/cart/checkout) for the next phase.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove legacy storefront references if any remain after theme switch.
 */
function dentomart_woocommerce_cleanup() {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
}
add_action( 'init', 'dentomart_woocommerce_cleanup' );

/**
 * Keep the header cart count live across AJAX add-to-cart.
 */
function dentomart_add_to_cart_fragments( $fragments ) {
	$count = dentomart_cart_count();
	$fragments['span.dm-cart-count'] = '<span class="dm-cart-count"' . ( $count > 0 ? '' : ' data-empty="1"' ) . '>' . esc_html( $count ) . '</span>';
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'dentomart_add_to_cart_fragments' );

/**
 * Fetch a term's representative product image.
 *
 * Returns the first published product thumbnail for a taxonomy term.
 *
 * @param int $term_id Term ID.
 * @return int Attachment ID or 0.
 */
function dentomart_term_image_id( $term_id ) {
	$products = get_posts( array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 8,
		'fields'         => 'ids',
		'no_found_rows'  => true,
		'tax_query'      => array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $term_id,
			),
		),
	) );

	foreach ( $products as $product_id ) {
		$thumb = get_post_thumbnail_id( $product_id );
		if ( $thumb ) {
			return (int) $thumb;
		}
	}

	return 0;
}

/**
 * Representative image for many terms in a single query.
 *
 * Returns array of term_id => attachment_id.
 *
 * @param int[] $term_ids Category term IDs.
 * @return array
 */
function dentomart_terms_images( $term_ids ) {
	$term_ids = array_values( array_filter( array_map( 'intval', (array) $term_ids ) ) );
	if ( empty( $term_ids ) ) {
		return array();
	}

	$products = get_posts( array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'posts_per_page'      => 60,
		'orderby'             => 'date',
		'order'               => 'DESC',
		'no_found_rows'       => true,
		'fields'              => 'ids',
		'tax_query'           => array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $term_ids,
				'operator' => 'IN',
			),
		),
	) );

	$used = array();
	$map  = array();
	foreach ( $products as $product_id ) {
		$terms = wp_get_object_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );
		foreach ( $terms as $tid ) {
			if ( in_array( $tid, $term_ids, true ) && empty( $map[ $tid ] ) && empty( $used[ $tid ] ) ) {
				$thumb = get_post_thumbnail_id( $product_id );
				if ( $thumb ) {
					$map[ $tid ] = (int) $thumb;
					$used[ $tid ] = true;
				}
			}
		}
		if ( count( $map ) >= count( $term_ids ) ) {
			break;
		}
	}

	// Fall back to any remaining term with its first product's image.
	foreach ( $term_ids as $tid ) {
		if ( empty( $map[ $tid ] ) ) {
			$img = dentomart_term_image_id( $tid );
			if ( $img ) {
				$map[ $tid ] = $img;
			}
		}
	}

	return $map;
}

/**
 * Render a WooCommerce product card.
 *
 * @param int|\WC_Product $product Product ID or object.
 */
function dentomart_product_card( $product, $extra_class = '' ) {
	if ( ! $product instanceof WC_Product ) {
		$product = wc_get_product( $product );
	}
	if ( ! $product ) {
		return;
	}

	$id         = $product->get_id();
	$permalink  = get_permalink( $id );
	$image      = $product->get_image( 'dentomart-card', array( 'loading' => 'lazy', 'class' => 'dm-product-card__img' ) );
	$title      = get_the_title( $id );
	$brand      = dentomart_product_brand( $id );
	$is_new     = dentomart_is_new_product( $product );
	$has_sale   = $product->is_on_sale();
	$discount   = function_exists( 'dentomart_get_discount_percentage' ) ? dentomart_get_discount_percentage( $product ) : 0;
	$rating_data = function_exists( 'dentomart_get_rating_data' ) ? dentomart_get_rating_data( $product ) : array( 'rating' => '4.8', 'count' => 15 );
	
	$cat_terms  = get_the_terms( $id, 'product_cat' );
	$cat_name   = ( $cat_terms && ! is_wp_error( $cat_terms ) ) ? $cat_terms[0]->name : '';
	$badge_text = $brand ? $brand : $cat_name;
	?>
	<div class="dm-product-card <?php echo esc_attr( $extra_class ); ?>" data-product-id="<?php echo esc_attr( $id ); ?>">
		<div class="dm-product-card__top">
			<?php if ( $discount > 0 ) : ?>
				<span class="dm-product-card__badge dm-product-card__badge--discount"><?php echo esc_html( $discount ); ?>% OFF</span>
			<?php elseif ( $is_new ) : ?>
				<span class="dm-product-card__badge dm-product-card__badge--new"><?php esc_html_e( 'NEW', 'dentomart' ); ?></span>
			<?php elseif ( $has_sale ) : ?>
				<span class="dm-product-card__badge dm-product-card__badge--sale"><?php esc_html_e( 'DEAL', 'dentomart' ); ?></span>
			<?php endif; ?>
		</div>

		<a class="dm-product-card__media" href="<?php echo esc_url( $permalink ); ?>" aria-label="<?php echo esc_attr( $title ); ?>">
			<div class="dm-product-card__img-wrap">
				<?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput -- WooCommerce image. ?>
			</div>
		</a>

		<div class="dm-product-card__body">
			<?php if ( $badge_text ) : ?>
				<div class="dm-product-card__meta">
					<span class="dm-product-card__brand"><?php echo esc_html( $badge_text ); ?></span>
				</div>
			<?php endif; ?>

			<h3 class="dm-product-card__title">
				<a href="<?php echo esc_url( $permalink ); ?>" title="<?php echo esc_attr( $title ); ?>">
					<?php echo esc_html( $title ); ?>
				</a>
			</h3>

			<div class="dm-product-card__rating">
				<span class="dm-rating-pill">
					<span class="dm-rating-pill__star">&#9733;</span>
					<span class="dm-rating-pill__val"><?php echo esc_html( $rating_data['rating'] ); ?></span>
				</span>
				<span class="dm-rating-count">(<?php echo esc_html( $rating_data['count'] ); ?>)</span>
			</div>

			<div class="dm-product-card__price-row">
				<div class="dm-product-card__price">
					<?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput -- WC price HTML. ?>
				</div>
			</div>

			<div class="dm-product-card__actions">
				<?php
				if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) {
					woocommerce_template_loop_add_to_cart( array(
						'class'       => 'dm-btn dm-btn--accent dm-btn--sm dm-add-to-cart dm-product-card__btn',
						'aria-label'  => sprintf( __( 'Add %s to cart', 'dentomart' ), $product->get_title() ),
						'text'        => __( 'Add to Cart', 'dentomart' ),
					) );
				} else {
					echo '<a class="dm-btn dm-btn--outline dm-btn--sm dm-product-card__btn" href="' . esc_url( $permalink ) . '">' . esc_html__( 'Select Options', 'dentomart' ) . '</a>';
				}
				?>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Render a standardized horizontal product slider section.
 *
 * @param array $args {
 *     @type string   $id          Section HTML ID.
 *     @type string   $title       Section title.
 *     @type string   $eyebrow     Optional eyebrow/badge.
 *     @type string   $subtitle    Optional subtitle.
 *     @type string   $view_all    URL for "View All" link.
 *     @type WP_Query $query       WooCommerce WP_Query of products.
 *     @type string   $theme_class Optional extra class (e.g. dm-slider--deals, dm-slider--blue).
 *     @type string   $badge_icon  Optional icon for header badge.
 * }
 */
function dentomart_render_product_slider_section( $args ) {
	$id          = ! empty( $args['id'] ) ? $args['id'] : 'slider-' . wp_rand( 100, 999 );
	$title       = ! empty( $args['title'] ) ? $args['title'] : '';
	$eyebrow     = ! empty( $args['eyebrow'] ) ? $args['eyebrow'] : '';
	$subtitle    = ! empty( $args['subtitle'] ) ? $args['subtitle'] : '';
	$view_all    = ! empty( $args['view_all'] ) ? $args['view_all'] : '';
	$query       = ! empty( $args['query'] ) ? $args['query'] : null;
	$theme_class = ! empty( $args['theme_class'] ) ? $args['theme_class'] : '';
	$badge_icon  = ! empty( $args['badge_icon'] ) ? $args['badge_icon'] : '';

	if ( ! $query || ! $query->have_posts() ) {
		return;
	}

	$track_id = 'track-' . $id;
	?>
	<section class="dm-section dm-product-slider-section <?php echo esc_attr( $theme_class ); ?>" id="<?php echo esc_attr( $id ); ?>">
		<div class="dm-container">
			<div class="dm-slider-header">
				<div class="dm-slider-header__left">
					<?php if ( $eyebrow ) : ?>
						<span class="dm-slider-eyebrow">
							<?php if ( $badge_icon ) : ?>
								<?php echo dentomart_icon( $badge_icon, 14 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<?php endif; ?>
							<?php echo esc_html( $eyebrow ); ?>
						</span>
					<?php endif; ?>
					<h2 class="dm-slider-title"><?php echo esc_html( $title ); ?></h2>
					<?php if ( $subtitle ) : ?>
						<p class="dm-slider-subtitle"><?php echo esc_html( $subtitle ); ?></p>
					<?php endif; ?>
				</div>

				<div class="dm-slider-header__right">
					<?php if ( $view_all ) : ?>
						<a class="dm-slider-more-link" href="<?php echo esc_url( $view_all ); ?>">
							<span><?php esc_html_e( 'View All', 'dentomart' ); ?></span>
							<?php echo dentomart_icon( 'chevron-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</a>
					<?php endif; ?>

					<div class="dm-slider-nav" data-dm-scroll-controls="<?php echo esc_attr( $track_id ); ?>">
						<button type="button" class="dm-slider-nav__btn dm-slider-nav__btn--prev" data-dm-scroll="prev" aria-label="<?php esc_attr_e( 'Previous products', 'dentomart' ); ?>" disabled>
							<?php echo dentomart_icon( 'chevron-left', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</button>
						<button type="button" class="dm-slider-nav__btn dm-slider-nav__btn--next" data-dm-scroll="next" aria-label="<?php esc_attr_e( 'Next products', 'dentomart' ); ?>">
							<?php echo dentomart_icon( 'chevron-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</button>
					</div>
				</div>
			</div>

			<div class="dm-slider-wrapper">
				<div class="dm-slider-track" id="<?php echo esc_attr( $track_id ); ?>" tabindex="0" aria-label="<?php echo esc_attr( $title ); ?>">
					<?php
					while ( $query->have_posts() ) :
						$query->the_post();
						?>
						<div class="dm-slider-item">
							<?php dentomart_product_card( $query->post ); ?>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Product brand label (pa_brand).
 *
 * @param int $product_id Product ID.
 * @return string
 */
function dentomart_product_brand( $product_id ) {
	$terms = get_the_terms( $product_id, 'pa_brand' );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return '';
	}
	return $terms[0]->name;
}

/**
 * Representative image for many brand terms in a single query.
 *
 * Returns array of term_id => attachment_id by looking at the first product
 * in each brand. Falls back to a curated lookup for brands without products.
 *
 * @param int[] $term_ids Brand term IDs.
 * @return array
 */
function dentomart_brand_term_images( $term_ids ) {
	$term_ids = array_values( array_filter( array_map( 'intval', (array) $term_ids ) ) );
	if ( empty( $term_ids ) ) {
		return array();
	}

	$products = get_posts( array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'posts_per_page'      => 120,
		'orderby'             => 'date',
		'order'               => 'DESC',
		'no_found_rows'       => true,
		'fields'              => 'ids',
		'tax_query'           => array(
			array(
				'taxonomy' => 'pa_brand',
				'field'    => 'term_id',
				'terms'    => $term_ids,
				'operator' => 'IN',
			),
		),
	) );

	$map = array();
	foreach ( $products as $product_id ) {
		$brands = wp_get_object_terms( $product_id, 'pa_brand', array( 'fields' => 'ids' ) );
		foreach ( $brands as $bid ) {
			if ( in_array( $bid, $term_ids, true ) && empty( $map[ $bid ] ) ) {
				$thumb = get_post_thumbnail_id( $product_id );
				if ( $thumb ) {
					$map[ $bid ] = (int) $thumb;
				}
			}
		}
		if ( count( $map ) >= count( $term_ids ) ) {
			break;
		}
	}

	// Fill missing entries with the term's own uploaded thumbnail (if any).
	foreach ( $term_ids as $tid ) {
		if ( empty( $map[ $tid ] ) ) {
			$thumb_id = (int) get_term_meta( $tid, 'thumbnail_id', true );
			if ( $thumb_id ) {
				$map[ $tid ] = $thumb_id;
			}
		}
	}

	return $map;
}

/**
 * Get the best available image for a single brand term.
 *
 * Priority: uploaded term thumbnail → first product thumbnail → 0.
 *
 * @param int $term_id Brand term ID.
 * @return int Attachment ID or 0.
 */
function dentomart_brand_image_id( $term_id ) {
	$term_id = (int) $term_id;
	if ( ! $term_id ) {
		return 0;
	}

	$thumb_id = (int) get_term_meta( $term_id, 'thumbnail_id', true );
	if ( $thumb_id ) {
		return $thumb_id;
	}

	$map = dentomart_brand_term_images( array( $term_id ) );
	return isset( $map[ $term_id ] ) ? (int) $map[ $term_id ] : 0;
}

/**
 * Whether a product counts as "new" (recently published).
 *
 * @param \WC_Product $product Product object.
 * @return bool
 */
function dentomart_is_new_product( $product ) {
	$days  = 60;
	$cut   = strtotime( "-{$days} days" );
	$date  = $product->get_date_created();
	if ( ! $date ) {
		return false;
	}
	return $date->getTimestamp() >= $cut;
}

/**
 * Global tracker for rendered product IDs to prevent duplicates across sliders.
 */
function dentomart_get_rendered_product_ids() {
	if ( ! isset( $GLOBALS['dentomart_rendered_products'] ) || ! is_array( $GLOBALS['dentomart_rendered_products'] ) ) {
		$GLOBALS['dentomart_rendered_products'] = array();
	}
	return $GLOBALS['dentomart_rendered_products'];
}

function dentomart_register_rendered_product_id( $id ) {
	if ( ! isset( $GLOBALS['dentomart_rendered_products'] ) || ! is_array( $GLOBALS['dentomart_rendered_products'] ) ) {
		$GLOBALS['dentomart_rendered_products'] = array();
	}
	$GLOBALS['dentomart_rendered_products'][] = (int) $id;
}

/**
 * Lightweight product query used by the homepage carousels.
 *
 * @param array $args WP_Query args.
 * @return WP_Query
 */
function dentomart_product_query( $args = array() ) {
	$defaults = array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'posts_per_page'      => 8,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'meta_key'            => '_price', // products all carry price meta
		'orderby'             => 'date',
		'order'               => 'DESC',
	);

	return new WP_Query( wp_parse_args( $args, $defaults ) );
}

/**
 * Query products with brand diversity and distinct exclusion across sections.
 * Ensures 1 product per brand when available and completely unique products per slider.
 *
 * @param array $args
 * @return WP_Query
 */
function dentomart_query_diverse_products( $args = array() ) {
	$count              = ! empty( $args['posts_per_page'] ) ? (int) $args['posts_per_page'] : 10;
	$category           = ! empty( $args['category'] ) ? $args['category'] : '';
	$orderby            = ! empty( $args['orderby'] ) ? $args['orderby'] : 'popularity';
	$order              = ! empty( $args['order'] ) ? $args['order'] : 'DESC';
	$manual_ids         = ! empty( $args['post__in'] ) ? (array) $args['post__in'] : array();
	$placement_meta_key = ! empty( $args['placement_meta_key'] ) ? sanitize_key( $args['placement_meta_key'] ) : '';
	$one_per_brand      = isset( $args['one_per_brand'] ) ? (bool) $args['one_per_brand'] : true;
	$exclude_rendered   = isset( $args['exclude_rendered'] ) ? (bool) $args['exclude_rendered'] : true;

	// If explicit manual IDs are supplied, query those directly
	if ( ! empty( $manual_ids ) ) {
		$manual_query = new WP_Query( array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'post__in'            => array_map( 'intval', $manual_ids ),
			'posts_per_page'      => $count,
			'orderby'             => 'post__in',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		) );
		if ( $manual_query->have_posts() ) {
			foreach ( $manual_query->posts as $p ) {
				dentomart_register_rendered_product_id( $p->ID );
			}
			return $manual_query;
		}
	}

	$rendered_ids = $exclude_rendered ? dentomart_get_rendered_product_ids() : array();
	$chosen_posts = array();
	$seen_brands  = array();

	// Check if admin has checked specific products for this placement
	if ( $placement_meta_key ) {
		$placement_args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => $count,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'meta_query'          => array(
				array(
					'key'     => $placement_meta_key,
					'value'   => 'yes',
					'compare' => '=',
				),
			),
		);
		if ( ! empty( $rendered_ids ) ) {
			$placement_args['post__not_in'] = $rendered_ids;
		}
		$placement_query = new WP_Query( $placement_args );
		if ( $placement_query->have_posts() ) {
			foreach ( $placement_query->posts as $p_obj ) {
				$brand = dentomart_product_brand( $p_obj->ID );
				if ( $brand ) {
					$seen_brands[ $brand ] = true;
				}
				$chosen_posts[] = $p_obj;
				dentomart_register_rendered_product_id( $p_obj->ID );
				if ( count( $chosen_posts ) >= $count ) {
					break;
				}
			}
		}
	}

	// If placement query satisfied count, return it
	if ( count( $chosen_posts ) >= $count ) {
		$chosen_ids = wp_list_pluck( $chosen_posts, 'ID' );
		return new WP_Query( array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'post__in'            => $chosen_ids,
			'posts_per_page'      => count( $chosen_ids ),
			'orderby'             => 'post__in',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		) );
	}

	// Update rendered IDs after placement query
	$rendered_ids = $exclude_rendered ? dentomart_get_rendered_product_ids() : array();

	// Resolve category term if passed
	$tax_query = array();
	if ( $category ) {
		if ( is_numeric( $category ) ) {
			$tax_query[] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => (int) $category,
			);
		} else {
			$term = get_term_by( 'slug', $category, 'product_cat' );
			if ( ! $term ) {
				$term = get_term_by( 'name', $category, 'product_cat' );
			}
			if ( $term && ! is_wp_error( $term ) ) {
				$tax_query[] = array(
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $term->term_id,
				);
			}
		}
	}

	// Query candidate pool
	$query_args = array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'posts_per_page'      => 80,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'meta_key'            => '_price',
		'orderby'             => $orderby,
		'order'               => $order,
	);

	if ( ! empty( $tax_query ) ) {
		$query_args['tax_query'] = $tax_query;
	}

	if ( ! empty( $rendered_ids ) ) {
		$query_args['post__not_in'] = $rendered_ids;
	}

	$candidate_query = new WP_Query( $query_args );

	// If candidate pool is too small, pull from general products excluding rendered
	if ( count( $candidate_query->posts ) < $count && ! empty( $rendered_ids ) ) {
		$fill_args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => 80,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'post__not_in'        => $rendered_ids,
			'orderby'             => $orderby,
			'order'               => $order,
		);
		$fill_query = new WP_Query( $fill_args );
		$existing_ids = wp_list_pluck( $candidate_query->posts, 'ID' );
		foreach ( $fill_query->posts as $fp ) {
			if ( ! in_array( $fp->ID, $existing_ids, true ) ) {
				$candidate_query->posts[] = $fp;
			}
		}
	}

	// Filter for Brand Diversity (1 product per brand)
	$chosen_posts  = array();
	$seen_brands   = array();
	$fallback_pool = array();

	foreach ( $candidate_query->posts as $post_obj ) {
		$brand = dentomart_product_brand( $post_obj->ID );
		if ( $one_per_brand && $brand ) {
			if ( empty( $seen_brands[ $brand ] ) ) {
				$chosen_posts[] = $post_obj;
				$seen_brands[ $brand ] = true;
			} else {
				$fallback_pool[] = $post_obj;
			}
		} else {
			$chosen_posts[] = $post_obj;
		}

		if ( count( $chosen_posts ) >= $count ) {
			break;
		}
	}

	// Fill remaining from fallback pool
	if ( count( $chosen_posts ) < $count && ! empty( $fallback_pool ) ) {
		foreach ( $fallback_pool as $fallback_post ) {
			$chosen_posts[] = $fallback_post;
			if ( count( $chosen_posts ) >= $count ) {
				break;
			}
		}
	}

	// Absolute fallback if database has few products
	if ( empty( $chosen_posts ) ) {
		$any_query = new WP_Query( array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => $count,
		) );
		$chosen_posts = $any_query->posts;
	}

	$chosen_ids = wp_list_pluck( $chosen_posts, 'ID' );

	if ( empty( $chosen_ids ) ) {
		return new WP_Query( array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 0,
		) );
	}

	return new WP_Query( array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'post__in'            => $chosen_ids,
		'posts_per_page'      => count( $chosen_ids ),
		'orderby'             => 'post__in',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	) );
}

/**
 * Get wholesale bulk pricing tiers for a product.
 * Returns array of tiers: [['qty' => 5, 'price' => X, 'save' => 2, 'label' => 'Extra 2% off'], ...]
 *
 * @param int|WC_Product $product Product ID or instance.
 * @return array
 */
function dentomart_get_product_bulk_tiers( $product ) {
	if ( ! $product instanceof WC_Product ) {
		$product = wc_get_product( $product );
	}
	if ( ! $product ) {
		return array();
	}

	$custom_tiers = get_post_meta( $product->get_id(), '_bulk_price_tiers', true );
	if ( ! empty( $custom_tiers ) && is_array( $custom_tiers ) ) {
		$parsed = array();
		$reg    = (float) ( $product->get_regular_price() ? $product->get_regular_price() : $product->get_price() );
		foreach ( $custom_tiers as $t ) {
			if ( empty( $t['qty'] ) || empty( $t['price'] ) ) {
				continue;
			}
			$qty   = (int) $t['qty'];
			$price = (float) $t['price'];
			$save  = $reg > 0 ? round( max( 0, ( ( $reg - $price ) / $reg ) * 100 ), 1 ) : 0;
			$parsed[] = array(
				'qty'   => $qty,
				'price' => $price,
				'save'  => $save,
				'label' => $save > 0 ? sprintf( __( 'Extra %s%% off', 'dentomart' ), $save ) : '',
			);
		}
		if ( ! empty( $parsed ) ) {
			return $parsed;
		}
	}

	// Default wholesale discount tiers for all products:
	// 5+ items -> 2% off
	// 10+ items -> 4% off
	$price = (float) $product->get_price();
	if ( $price <= 0 ) {
		return array();
	}

	$tier5_price  = round( $price * 0.98, 2 );
	$tier10_price = round( $price * 0.96, 2 );

	return array(
		array(
			'qty'   => 5,
			'price' => $tier5_price,
			'save'  => 2,
			'label' => __( 'Extra 2% off', 'dentomart' ),
		),
		array(
			'qty'   => 10,
			'price' => $tier10_price,
			'save'  => 4,
			'label' => __( 'Extra 4% off', 'dentomart' ),
		),
	);
}

/**
 * Apply Wholesale Tiered Discounts dynamically in cart:
 * - 5 to 9 units: 2% discount
 * - 10+ units: 4% discount
 *
 * @param \WC_Cart $cart Cart instance.
 */
function dentomart_apply_wholesale_cart_discount( $cart ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}
	if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
		return;
	}

	foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
		$quantity = $cart_item['quantity'];
		$product  = $cart_item['data'];
		if ( ! $product instanceof WC_Product ) {
			continue;
		}

		// Retrieve base selling price (before dynamic modification)
		$product_id = $product->get_id();
		$original_product = wc_get_product( $product_id );
		if ( ! $original_product ) {
			continue;
		}
		$base_price = (float) $original_product->get_price();
		if ( $base_price <= 0 ) {
			continue;
		}

		$custom_tiers = get_post_meta( $product_id, '_bulk_price_tiers', true );
		$discounted_price = null;

		if ( ! empty( $custom_tiers ) && is_array( $custom_tiers ) ) {
			usort( $custom_tiers, function ( $a, $b ) {
				return (int) $b['qty'] - (int) $a['qty'];
			} );
			foreach ( $custom_tiers as $tier ) {
				if ( ! empty( $tier['qty'] ) && ! empty( $tier['price'] ) && $quantity >= (int) $tier['qty'] ) {
					$discounted_price = (float) $tier['price'];
					break;
				}
			}
		}

		if ( null === $discounted_price ) {
			if ( $quantity >= 10 ) {
				$discounted_price = round( $base_price * 0.96, 2 );
			} elseif ( $quantity >= 5 ) {
				$discounted_price = round( $base_price * 0.98, 2 );
			}
		}

		if ( null !== $discounted_price && $discounted_price < $base_price ) {
			$cart_item['data']->set_price( $discounted_price );
		}
	}
}
add_action( 'woocommerce_before_calculate_totals', 'dentomart_apply_wholesale_cart_discount', 20, 1 );

/**
 * Display Wholesale Extra OFF Badge on Cart Item Name.
 *
 * @param string $name Item name HTML.
 * @param array $cart_item Cart item array.
 * @param string $cart_item_key Cart item key.
 * @return string
 */
function dentomart_cart_item_wholesale_badge( $name, $cart_item, $cart_item_key ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return $name;
	}
	$quantity = isset( $cart_item['quantity'] ) ? (int) $cart_item['quantity'] : 1;
	$product  = isset( $cart_item['data'] ) ? $cart_item['data'] : null;

	if ( $product instanceof WC_Product && $quantity >= 5 ) {
		$pct_off = ( $quantity >= 10 ) ? 4 : 2;
		$custom_tiers = get_post_meta( $product->get_id(), '_bulk_price_tiers', true );
		if ( ! empty( $custom_tiers ) && is_array( $custom_tiers ) ) {
			$reg = (float) ( $product->get_regular_price() ? $product->get_regular_price() : $product->get_price() );
			foreach ( $custom_tiers as $tier ) {
				if ( ! empty( $tier['qty'] ) && $quantity >= (int) $tier['qty'] && ! empty( $tier['price'] ) ) {
					$t_price = (float) $tier['price'];
					$pct_off = $reg > 0 ? round( ( ( $reg - $t_price ) / $reg ) * 100, 1 ) : $pct_off;
					break;
				}
			}
		}
		$badge = sprintf(
			' <span class="dm-cart-wholesale-badge">%s</span>',
			sprintf( esc_html__( 'Extra %s%% OFF Applied', 'dentomart' ), $pct_off )
		);
		$name .= $badge;
	}

	return $name;
}
add_filter( 'woocommerce_cart_item_name', 'dentomart_cart_item_wholesale_badge', 20, 3 );


/**
 * Render DentalKart-styled 4-Step Checkout Progress Bar.
 *
 * @param int $current_step 1 = Cart, 2 = Address & Details, 3 = Payment, 4 = Confirmed.
 */
function dentomart_render_checkout_step_bar( $current_step = 1 ) {
	$steps = array(
		1 => array( 'label' => __( 'Shopping Cart', 'dentomart' ), 'icon' => 'cart' ),
		2 => array( 'label' => __( 'Delivery & Clinic Info', 'dentomart' ), 'icon' => 'truck' ),
		3 => array( 'label' => __( 'Payment & Review', 'dentomart' ), 'icon' => 'credit-card' ),
		4 => array( 'label' => __( 'Order Confirmed', 'dentomart' ), 'icon' => 'badge-check' ),
	);
	?>
	<div class="dm-checkout-progress" aria-label="<?php esc_attr_e( 'Checkout Progress', 'dentomart' ); ?>">
		<div class="dm-checkout-progress__inner">
			<?php foreach ( $steps as $num => $s ) : ?>
				<?php
				$is_active = ( $num === $current_step );
				$is_done   = ( $num < $current_step );
				$state_cls = $is_done ? 'is-done' : ( $is_active ? 'is-active' : '' );
				?>
				<div class="dm-checkout-step <?php echo esc_attr( $state_cls ); ?>">
					<div class="dm-checkout-step__badge">
						<?php if ( $is_done ) : ?>
							<?php echo dentomart_icon( 'check', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<?php else : ?>
							<span class="dm-checkout-step__num"><?php echo esc_html( $num ); ?></span>
						<?php endif; ?>
					</div>
					<span class="dm-checkout-step__title"><?php echo esc_html( $s['label'] ); ?></span>
				</div>
				<?php if ( $num < count( $steps ) ) : ?>
					<div class="dm-checkout-step__connector <?php echo $is_done ? 'is-done' : ''; ?>"></div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}

/**
 * Step bar on Cart page.
 */
function dentomart_cart_step_bar() {
	if ( function_exists( 'WC' ) && ! WC()->cart->is_empty() ) {
		dentomart_render_checkout_step_bar( 1 );
	}
}
add_action( 'woocommerce_before_cart', 'dentomart_cart_step_bar', 5 );

/**
 * Step bar on Checkout page.
 */
function dentomart_checkout_step_bar() {
	dentomart_render_checkout_step_bar( 2 );
}
add_action( 'woocommerce_before_checkout_form', 'dentomart_checkout_step_bar', 5 );

/**
 * Step bar and Order Confirmed Header on Thank You page.
 */
function dentomart_thankyou_header( $order_id ) {
	if ( ! $order_id ) {
		return;
	}
	dentomart_render_checkout_step_bar( 4 );
	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		return;
	}
	?>
	<div class="dm-thankyou-banner">
		<div class="dm-thankyou-banner__icon">
			<?php echo dentomart_icon( 'badge-check', 44 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
		<div class="dm-thankyou-banner__content">
			<span class="dm-thankyou-banner__eyebrow"><?php esc_html_e( 'ORDER CONFIRMED', 'dentomart' ); ?></span>
			<h2 class="dm-thankyou-banner__title"><?php esc_html_e( 'Thank You, Doctor! Your Order is Placed.', 'dentomart' ); ?></h2>
			<p class="dm-thankyou-banner__desc"><?php echo sprintf( esc_html__( 'We have received your order #%s. A confirmation with tracking details has been sent to %s.', 'dentomart' ), '<strong>' . esc_html( $order->get_order_number() ) . '</strong>', '<strong>' . esc_html( $order->get_billing_email() ) . '</strong>' ); ?></p>
		</div>
	</div>

	<div class="dm-order-tracking-timeline">
		<div class="dm-timeline-step is-done">
			<div class="dm-timeline-step__bullet">✓</div>
			<div class="dm-timeline-step__info">
				<strong><?php esc_html_e( 'Order Confirmed', 'dentomart' ); ?></strong>
				<span><?php echo esc_html( wc_format_datetime( $order->get_date_created(), 'd M, h:i A' ) ); ?></span>
			</div>
		</div>
		<div class="dm-timeline-connector is-done"></div>
		<div class="dm-timeline-step is-active">
			<div class="dm-timeline-step__bullet">2</div>
			<div class="dm-timeline-step__info">
				<strong><?php esc_html_e( 'Packing at Warehouse', 'dentomart' ); ?></strong>
				<span><?php esc_html_e( 'Quality Checked', 'dentomart' ); ?></span>
			</div>
		</div>
		<div class="dm-timeline-connector"></div>
		<div class="dm-timeline-step">
			<div class="dm-timeline-step__bullet">3</div>
			<div class="dm-timeline-step__info">
				<strong><?php esc_html_e( 'Express Dispatch', 'dentomart' ); ?></strong>
				<span><?php esc_html_e( 'Tracking via SMS/Email', 'dentomart' ); ?></span>
			</div>
		</div>
		<div class="dm-timeline-connector"></div>
		<div class="dm-timeline-step">
			<div class="dm-timeline-step__bullet">4</div>
			<div class="dm-timeline-step__info">
				<strong><?php esc_html_e( 'Delivered to Clinic', 'dentomart' ); ?></strong>
				<span><?php esc_html_e( 'Safe Handover', 'dentomart' ); ?></span>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_before_thankyou', 'dentomart_thankyou_header', 5 );

/**
 * Trust & Security Reassurance Box on Cart.
 */
function dentomart_cart_trust_box() {
	?>
	<div class="dm-cart-trust-card">
		<div class="dm-cart-trust-card__header">
			<?php echo dentomart_icon( 'shield', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<strong><?php esc_html_e( 'Clinic Guarantee & Protection', 'dentomart' ); ?></strong>
		</div>
		<ul class="dm-cart-trust-card__list">
			<li><?php echo dentomart_icon( 'check', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <span><?php esc_html_e( '100% Genuine Certified Products direct from manufacturers', 'dentomart' ); ?></span></li>
			<li><?php echo dentomart_icon( 'check', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <span><?php esc_html_e( 'GST Invoice with input tax credit eligibility on every order', 'dentomart' ); ?></span></li>
			<li><?php echo dentomart_icon( 'check', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <span><?php esc_html_e( 'Safe 256-Bit SSL Encrypted Checkout & UPI / Card Protection', 'dentomart' ); ?></span></li>
			<li><?php echo dentomart_icon( 'check', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <span><?php esc_html_e( 'Hassle-free replacement for transit damage & batch returns', 'dentomart' ); ?></span></li>
		</ul>
	</div>
	<?php
}
add_action( 'woocommerce_after_cart_totals', 'dentomart_cart_trust_box', 20 );

/**
 * Add Clinic Name & GSTIN to Checkout Fields.
 */
function dentomart_add_clinic_gstin_checkout_field( $fields ) {
	$fields['billing']['billing_clinic_name'] = array(
		'label'       => __( 'Dental Clinic / Hospital Name (Optional)', 'dentomart' ),
		'placeholder' => __( 'e.g. Smile Care Dental Clinic', 'dentomart' ),
		'required'    => false,
		'class'       => array( 'form-row-wide' ),
		'priority'    => 25,
	);

	$fields['billing']['billing_gstin'] = array(
		'label'       => __( 'Clinic GSTIN (For Tax Invoice Credit - Optional)', 'dentomart' ),
		'placeholder' => __( 'e.g. 29ABCDE1234F1Z5', 'dentomart' ),
		'required'    => false,
		'class'       => array( 'form-row-wide' ),
		'priority'    => 26,
	);

	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'dentomart_add_clinic_gstin_checkout_field' );

/**
 * Save Clinic Name & GSTIN to Order Meta.
 */
function dentomart_save_clinic_gstin_order_meta( $order_id ) {
	if ( ! empty( $_POST['billing_clinic_name'] ) ) {
		update_post_meta( $order_id, '_billing_clinic_name', sanitize_text_field( $_POST['billing_clinic_name'] ) );
	}
	if ( ! empty( $_POST['billing_gstin'] ) ) {
		update_post_meta( $order_id, '_billing_gstin', sanitize_text_field( $_POST['billing_gstin'] ) );
	}
}
add_action( 'woocommerce_checkout_update_order_meta', 'dentomart_save_clinic_gstin_order_meta' );


