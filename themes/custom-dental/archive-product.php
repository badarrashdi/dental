<?php
/**
 * Main shop archive.
 *
 * Same layout as product category archives but lists all products.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$brand_ids = get_terms( array( 'taxonomy' => 'pa_brand', 'fields' => 'ids', 'hide_empty' => true ) );
$filter_brand  = isset( $_GET['brand'] )  ? sanitize_text_field( wp_unslash( $_GET['brand'] ) )  : '';
$filter_min    = isset( $_GET['min'] )    ? (float) $_GET['min'] : 0;
$filter_max    = isset( $_GET['max'] )    ? (float) $_GET['max'] : 0;
$filter_stock  = ! empty( $_GET['in_stock'] );
$filter_sort   = isset( $_GET['orderby'] ) ? sanitize_key( wp_unslash( $_GET['orderby'] ) ) : 'menu_order';
$base_url = wc_get_page_permalink( 'shop' );

$args = array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 24,
	'paged'          => max( 1, get_query_var( 'paged' ) ),
);

if ( $filter_brand ) {
	$args['tax_query'] = array(
		array(
			'taxonomy' => 'pa_brand',
			'field'    => 'slug',
			'terms'    => $filter_brand,
		),
	);
}

if ( $filter_min > 0 || $filter_max > 0 ) {
	$args['meta_query'] = array( 'relation' => 'AND' );
	if ( $filter_min > 0 ) {
		$args['meta_query'][] = array( 'key' => '_price', 'value' => $filter_min, 'compare' => '>=', 'type' => 'NUMERIC' );
	}
	if ( $filter_max > 0 ) {
		$args['meta_query'][] = array( 'key' => '_price', 'value' => $filter_max, 'compare' => '<=', 'type' => 'NUMERIC' );
	}
}

if ( $filter_stock ) {
	$args['meta_query']   = $args['meta_query'] ?? array( 'relation' => 'AND' );
	$args['meta_query'][] = array( 'key' => '_stock_status', 'value' => 'instock' );
}

switch ( $filter_sort ) {
	case 'price-asc':
		$args['orderby'] = 'meta_value_num'; $args['meta_key'] = '_price'; $args['order'] = 'ASC'; break;
	case 'price-desc':
		$args['orderby'] = 'meta_value_num'; $args['meta_key'] = '_price'; $args['order'] = 'DESC'; break;
	case 'newest':
		$args['orderby'] = 'date'; $args['order'] = 'DESC'; break;
	case 'popularity':
		$args['orderby'] = 'meta_value_num'; $args['meta_key'] = 'total_sales'; $args['order'] = 'DESC'; break;
}

$query = new WP_Query( $args );
$total_count = (int) $query->found_posts;

// All top-level categories for the sidebar.
$top_cats = get_terms( array( 'taxonomy' => 'product_cat', 'parent' => 0, 'hide_empty' => true ) );

// All brands.
$all_brands = ! empty( $brand_ids ) ? get_terms( array( 'taxonomy' => 'pa_brand', 'hide_empty' => true, 'orderby' => 'name' ) ) : array();
?>
<section class="dm-shop" aria-labelledby="dm-shop-title">
	<div class="dm-container">

		<nav class="dm-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'dentomart' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'dentomart' ); ?></a>
			<span aria-hidden="true">›</span>
			<span><?php esc_html_e( 'Shop', 'dentomart' ); ?></span>
		</nav>

		<header class="dm-shop__head">
			<div>
				<p class="dm-section__eyebrow"><?php esc_html_e( 'Store', 'dentomart' ); ?></p>
				<h1 class="dm-shop__title" id="dm-shop-title"><?php esc_html_e( 'All Products', 'dentomart' ); ?></h1>
				<p class="dm-shop__desc"><?php esc_html_e( 'Browse our complete catalogue of genuine dental products — instruments, materials, equipment and consumables.', 'dentomart' ); ?></p>
			</div>
			<p class="dm-shop__count"><?php echo esc_html( sprintf( _n( '%s product', '%s products', $total_count, 'dentomart' ), number_format_i18n( $total_count ) ) ); ?></p>
		</header>

		<div class="dm-shop__layout">

			<aside class="dm-shop__sidebar" aria-label="<?php esc_attr_e( 'Filters', 'dentomart' ); ?>">
				<form class="dm-shop__filters" method="get" action="<?php echo esc_url( $base_url ); ?>">
					<?php if ( ! empty( $top_cats ) && ! is_wp_error( $top_cats ) ) : ?>
						<div class="dm-shop__filter">
							<h3 class="dm-shop__filter-title"><?php esc_html_e( 'Categories', 'dentomart' ); ?></h3>
							<ul class="dm-shop__filter-list">
								<?php foreach ( $top_cats as $cat ) : ?>
									<li>
										<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>">
											<?php echo esc_html( $cat->name ); ?>
											<span class="dm-shop__filter-count">(<?php echo esc_html( number_format_i18n( $cat->count ) ); ?>)</span>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $all_brands ) && ! is_wp_error( $all_brands ) ) : ?>
						<div class="dm-shop__filter">
							<h3 class="dm-shop__filter-title"><?php esc_html_e( 'Brand', 'dentomart' ); ?></h3>
							<ul class="dm-shop__filter-list">
								<li>
									<label>
										<input type="radio" name="brand" value="" <?php checked( '', $filter_brand ); ?> />
										<span><?php esc_html_e( 'All brands', 'dentomart' ); ?></span>
									</label>
								</li>
								<?php foreach ( $all_brands as $b ) : ?>
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

					<div class="dm-shop__filter">
						<h3 class="dm-shop__filter-title"><?php esc_html_e( 'Price', 'dentomart' ); ?></h3>
						<div class="dm-shop__price">
							<input type="number" name="min" value="<?php echo esc_attr( $filter_min ); ?>" placeholder="<?php esc_attr_e( 'Min', 'dentomart' ); ?>" min="0" step="100" inputmode="numeric" />
							<span aria-hidden="true">—</span>
							<input type="number" name="max" value="<?php echo esc_attr( $filter_max ); ?>" placeholder="<?php esc_attr_e( 'Max', 'dentomart' ); ?>" min="0" step="100" inputmode="numeric" />
						</div>
					</div>

					<div class="dm-shop__filter">
						<label class="dm-shop__stock-toggle">
							<input type="checkbox" name="in_stock" value="1" <?php checked( $filter_stock ); ?> />
							<span><?php esc_html_e( 'In stock only', 'dentomart' ); ?></span>
						</label>
					</div>

					<button type="submit" class="dm-btn dm-btn--accent dm-shop__apply"><?php esc_html_e( 'Apply filters', 'dentomart' ); ?></button>
					<a class="dm-shop__reset" href="<?php echo esc_url( $base_url ); ?>"><?php esc_html_e( 'Reset', 'dentomart' ); ?></a>
				</form>
			</aside>

			<div class="dm-shop__main">
				<div class="dm-shop__toolbar">
					<p class="dm-shop__showing"><?php echo esc_html( sprintf( __( 'Showing %1$d of %2$d', 'dentomart' ), (int) $query->post_count, $total_count ) ); ?></p>
					<form class="dm-shop__sort" method="get" action="<?php echo esc_url( $base_url ); ?>">
						<?php foreach ( array( 'brand', 'min', 'max', 'in_stock' ) as $key ) :
							$val = isset( $_GET[ $key ] ) ? sanitize_text_field( wp_unslash( $_GET[ $key ] ) ) : '';
							if ( '' !== $val ) : ?>
								<input type="hidden" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $val ); ?>" />
							<?php endif;
						endforeach; ?>
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