<?php
/**
 * Product category archive.
 *
 * Layout: filter sidebar on the left, product grid on the right. Filters
 * include sub-category list, brand, price range, and in-stock toggle.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$term = get_queried_object();
if ( ! $term ) {
	?>
	<div class="dm-container dm-page">
		<h1><?php esc_html_e( 'Category not found', 'dentomart' ); ?></h1>
	</div>
	<?php
	get_footer();
	return;
}

// Sub-categories of the current category.
$sub_cats = get_terms( array(
	'taxonomy'   => 'product_cat',
	'parent'     => $term->term_id,
	'hide_empty' => true,
	'orderby'    => 'name',
) );

// Available brands within the current category (so the brand filter is
// context-aware instead of showing every brand on the site).
global $wpdb;
$brand_ids = $wpdb->get_col( $wpdb->prepare(
	"SELECT DISTINCT t.term_id FROM {$wpdb->terms} t
	 INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
	 INNER JOIN {$wpdb->term_relationships} tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
	 INNER JOIN {$wpdb->posts} p ON tr.object_id = p.ID
	 WHERE tt.taxonomy = 'pa_brand'
	  AND p.post_type = 'product'
	  AND p.post_status = 'publish'
	  AND p.ID IN (
	    SELECT object_id FROM {$wpdb->term_relationships} tr2
	      INNER JOIN {$wpdb->term_taxonomy} tt2 ON tr2.term_taxonomy_id = tt2.term_taxonomy_id
	      WHERE tt2.taxonomy = 'product_cat' AND tt2.term_id = %d
	  )
	 ORDER BY t.name ASC",
	$term->term_id
) );
$brands = ! empty( $brand_ids ) ? get_terms( array( 'taxonomy' => 'pa_brand', 'include' => $brand_ids, 'hide_empty' => true ) ) : array();

// Get current filter values from the URL.
$filter_brand  = isset( $_GET['brand'] )  ? sanitize_text_field( wp_unslash( $_GET['brand'] ) )  : '';
$filter_min    = isset( $_GET['min'] )    ? (float) $_GET['min'] : 0;
$filter_max    = isset( $_GET['max'] )    ? (float) $_GET['max'] : 0;
$filter_stock  = ! empty( $_GET['in_stock'] );
$filter_sort   = isset( $_GET['orderby'] ) ? sanitize_key( wp_unslash( $_GET['orderby'] ) ) : 'menu_order';

$base_url = get_term_link( $term );

// Query products.
$args = array(
	'post_type'           => 'product',
	'post_status'         => 'publish',
	'posts_per_page'      => 24,
	'paged'               => max( 1, get_query_var( 'paged' ) ),
	'tax_query'           => array(
		array(
			'taxonomy' => 'product_cat',
			'field'    => 'term_id',
			'terms'    => $term->term_id,
		),
	),
);

// Filter by brand.
if ( $filter_brand ) {
	$args['tax_query'][] = array(
		'taxonomy' => 'pa_brand',
		'field'    => 'slug',
		'terms'    => $filter_brand,
	);
}

// Filter by price.
if ( $filter_min > 0 || $filter_max > 0 ) {
	$meta_query = array( 'relation' => 'AND' );
	if ( $filter_min > 0 ) {
		$meta_query[] = array( 'key' => '_price', 'value' => $filter_min, 'compare' => '>=', 'type' => 'NUMERIC' );
	}
	if ( $filter_max > 0 ) {
		$meta_query[] = array( 'key' => '_price', 'value' => $filter_max, 'compare' => '<=', 'type' => 'NUMERIC' );
	}
	$args['meta_query'] = $meta_query;
}

// Filter by stock.
if ( $filter_stock ) {
	$args['meta_query']   = $args['meta_query'] ?? array( 'relation' => 'AND' );
	$args['meta_query'][] = array( 'key' => '_stock_status', 'value' => 'instock' );
}

// Sort.
switch ( $filter_sort ) {
	case 'price-asc':
		$args['orderby']  = 'meta_value_num';
		$args['meta_key'] = '_price';
		$args['order']    = 'ASC';
		break;
	case 'price-desc':
		$args['orderby']  = 'meta_value_num';
		$args['meta_key'] = '_price';
		$args['order']    = 'DESC';
		break;
	case 'newest':
		$args['orderby'] = 'date';
		$args['order']   = 'DESC';
		break;
	case 'popularity':
		$args['orderby'] = 'meta_value_num';
		$args['meta_key'] = 'total_sales';
		$args['order']    = 'DESC';
		break;
	default:
		$args['orderby'] = 'menu_order title';
		$args['order']   = 'ASC';
}

$query = new WP_Query( $args );
$total_count = (int) $query->found_posts;
?>
<section class="dm-shop" aria-labelledby="dm-shop-title">
	<div class="dm-container">

		<nav class="dm-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'dentomart' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'dentomart' ); ?></a>
			<span aria-hidden="true">›</span>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Shop', 'dentomart' ); ?></a>
			<?php
			$ancestors = get_ancestors( $term->term_id, 'product_cat' );
			$ancestors = array_reverse( $ancestors );
			foreach ( $ancestors as $aid ) :
				$a = get_term( $aid, 'product_cat' );
				if ( ! $a || is_wp_error( $a ) ) { continue; }
				?>
				<span aria-hidden="true">›</span>
				<a href="<?php echo esc_url( get_term_link( $a ) ); ?>"><?php echo esc_html( $a->name ); ?></a>
			<?php endforeach; ?>
			<span aria-hidden="true">›</span>
			<span><?php echo esc_html( $term->name ); ?></span>
		</nav>

		<header class="dm-shop__head">
			<div>
				<p class="dm-section__eyebrow"><?php esc_html_e( 'Category', 'dentomart' ); ?></p>
				<h1 class="dm-shop__title" id="dm-shop-title"><?php echo esc_html( $term->name ); ?></h1>
				<?php if ( term_description( $term ) ) : ?>
					<div class="dm-shop__desc"><?php echo wp_kses_post( term_description( $term ) ); ?></div>
				<?php endif; ?>
			</div>
			<p class="dm-shop__count"><?php echo esc_html( sprintf( _n( '%s product', '%s products', $total_count, 'dentomart' ), number_format_i18n( $total_count ) ) ); ?></p>
		</header>

		<div class="dm-shop__layout">

			<!-- ============================================ SIDEBAR FILTERS -->
			<aside class="dm-shop__sidebar" aria-label="<?php esc_attr_e( 'Filters', 'dentomart' ); ?>">
				<form class="dm-shop__filters" method="get" action="<?php echo esc_url( $base_url ); ?>">

					<!-- Sub-categories -->
					<?php if ( ! empty( $sub_cats ) && ! is_wp_error( $sub_cats ) ) : ?>
						<div class="dm-shop__filter">
							<h3 class="dm-shop__filter-title"><?php esc_html_e( 'Sub-categories', 'dentomart' ); ?></h3>
							<ul class="dm-shop__filter-list">
								<?php foreach ( $sub_cats as $sub ) : ?>
									<li>
										<a href="<?php echo esc_url( get_term_link( $sub ) ); ?>">
											<?php echo esc_html( $sub->name ); ?>
											<span class="dm-shop__filter-count">(<?php echo esc_html( number_format_i18n( $sub->count ) ); ?>)</span>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>

					<!-- Brand filter -->
					<?php if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) : ?>
						<div class="dm-shop__filter">
							<h3 class="dm-shop__filter-title"><?php esc_html_e( 'Brand', 'dentomart' ); ?></h3>
							<ul class="dm-shop__filter-list">
								<li>
									<label>
										<input type="radio" name="brand" value="" <?php checked( '', $filter_brand ); ?> />
										<span><?php esc_html_e( 'All brands', 'dentomart' ); ?></span>
									</label>
								</li>
								<?php foreach ( $brands as $b ) : ?>
									<li>
										<label>
											<input type="radio" name="brand" value="<?php echo esc_attr( $b->slug ); ?>" <?php checked( $b->slug, $filter_brand ); ?> />
											<span><?php echo esc_html( $b->name ); ?></span>
											<span class="dm-shop__filter-count">(<?php echo esc_html( number_format_i18n( $b->count ) ); ?>)</span>
										</label>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>

					<!-- Price filter -->
					<div class="dm-shop__filter">
						<h3 class="dm-shop__filter-title"><?php esc_html_e( 'Price', 'dentomart' ); ?></h3>
						<div class="dm-shop__price">
							<input type="number" name="min" value="<?php echo esc_attr( $filter_min ); ?>" placeholder="<?php esc_attr_e( 'Min', 'dentomart' ); ?>" min="0" step="100" inputmode="numeric" />
							<span aria-hidden="true">—</span>
							<input type="number" name="max" value="<?php echo esc_attr( $filter_max ); ?>" placeholder="<?php esc_attr_e( 'Max', 'dentomart' ); ?>" min="0" step="100" inputmode="numeric" />
						</div>
					</div>

					<!-- In stock -->
					<div class="dm-shop__filter">
						<label class="dm-shop__stock-toggle">
							<input type="checkbox" name="in_stock" value="1" <?php checked( $filter_stock ); ?> />
							<span><?php esc_html_e( 'In stock only', 'dentomart' ); ?></span>
						</label>
					</div>

					<!-- Apply -->
					<button type="submit" class="dm-btn dm-btn--accent dm-shop__apply"><?php esc_html_e( 'Apply filters', 'dentomart' ); ?></button>
					<a class="dm-shop__reset" href="<?php echo esc_url( $base_url ); ?>"><?php esc_html_e( 'Reset', 'dentomart' ); ?></a>
				</form>
			</aside>

			<!-- ============================================ PRODUCT GRID -->
			<div class="dm-shop__main">
				<div class="dm-shop__toolbar">
					<p class="dm-shop__showing"><?php echo esc_html( sprintf( __( 'Showing %1$d of %2$d', 'dentomart' ), (int) $query->post_count, $total_count ) ); ?></p>
					<form class="dm-shop__sort" method="get" action="<?php echo esc_url( $base_url ); ?>">
						<?php
						// Preserve current filters in the sort form.
						foreach ( array( 'brand', 'min', 'max', 'in_stock' ) as $key ) :
							$val = isset( $_GET[ $key ] ) ? sanitize_text_field( wp_unslash( $_GET[ $key ] ) ) : '';
							if ( '' !== $val ) :
								?>
								<input type="hidden" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $val ); ?>" />
							<?php endif;
						endforeach;
						?>
						<label for="dm-sort"><?php esc_html_e( 'Sort', 'dentomart' ); ?></label>
						<select id="dm-sort" name="orderby" onchange="this.form.submit()">
							<option value="menu_order" <?php selected( 'menu_order', $filter_sort ); ?>><?php esc_html_e( 'Featured', 'dentomart' ); ?></option>
							<option value="newest" <?php selected( 'newest', $filter_sort ); ?>><?php esc_html_e( 'Newest', 'dentomart' ); ?></option>
							<option value="popularity" <?php selected( 'popularity', $filter_sort ); ?>><?php esc_html_e( 'Best selling', 'dentomart' ); ?></option>
							<option value="price-asc" <?php selected( 'price-asc', $filter_sort ); ?>><?php esc_html_e( 'Price: low to high', 'dentomart' ); ?></option>
							<option value="price-desc" <?php selected( 'price-desc', $filter_sort ); ?>><?php esc_html_e( 'Price: high to low', 'dentomart' ); ?></option>
						</select>
					</form>
				</div>

				<?php if ( $query->have_posts() ) : ?>
					<div class="dm-shop__grid">
						<?php
						while ( $query->have_posts() ) :
							$query->the_post();
							dentomart_product_card( get_the_ID() );
						endwhile;
						wp_reset_postdata();
						?>
					</div>

					<?php
					$total_pages = (int) $query->max_num_pages;
					if ( $total_pages > 1 ) :
						$base_paginate = add_query_arg( array_filter( array(
							'brand'   => $filter_brand,
							'min'     => $filter_min ?: null,
							'max'     => $filter_max ?: null,
							'in_stock' => $filter_stock ? '1' : null,
							'orderby' => 'menu_order' === $filter_sort ? null : $filter_sort,
						) ), $base_url );
						$base_paginate = trailingslashit( $base_paginate ) . ( strpos( $base_paginate, '?' ) === false ? '?' : '&' ) . 'paged=%#%';
						?>
						<nav class="dm-pagination" aria-label="<?php esc_attr_e( 'Products pagination', 'dentomart' ); ?>">
							<?php
							echo paginate_links( array(
								'base'      => $base_paginate,
								'format'    => '',
								'current'   => max( 1, get_query_var( 'paged' ) ),
								'total'     => $total_pages,
								'prev_text' => '&larr; ' . esc_html__( 'Previous', 'dentomart' ),
								'next_text' => esc_html__( 'Next', 'dentomart' ) . ' &rarr;',
								'type'      => 'list',
							) );
							?>
						</nav>
					<?php endif; ?>
				<?php else : ?>
					<div class="dm-shop__empty">
						<p><?php esc_html_e( 'No products match your filters.', 'dentomart' ); ?></p>
						<a class="dm-btn dm-btn--accent" href="<?php echo esc_url( $base_url ); ?>"><?php esc_html_e( 'Clear filters', 'dentomart' ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();