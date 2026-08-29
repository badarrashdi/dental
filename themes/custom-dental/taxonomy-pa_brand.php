<?php
/**
 * Brand taxonomy template — used for individual brand term pages.
 *
 * Example: /brand/mani/, /brand/gdc/ — these each show that brand's hero,
 * description, product count and a paginated grid of all products.
 *
 * The directory of all brands lives at /brands (page-brands.php).
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$brand_id    = (int) get_queried_object()->term_id;
$brand_name  = get_queried_object()->name;
$brand_desc  = term_description( get_queried_object() );
$brand_image = dentomart_brand_image_id( $brand_id );

$paged = max( 1, (int) get_query_var( 'paged' ) );
$query = new WP_Query( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 24,
	'paged'          => $paged,
	'tax_query'      => array(
		array(
			'taxonomy' => 'pa_brand',
			'field'    => 'term_id',
			'terms'    => $brand_id,
		),
	),
) );
?>
<section class="dm-brand-single" aria-labelledby="dm-brand-single-title">
	<div class="dm-container">
		<nav class="dm-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'dentomart' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'dentomart' ); ?></a>
			<span aria-hidden="true">›</span>
			<a href="<?php echo esc_url( home_url( '/brands/' ) ); ?>"><?php esc_html_e( 'Brands', 'dentomart' ); ?></a>
			<span aria-hidden="true">›</span>
			<span><?php echo esc_html( $brand_name ); ?></span>
		</nav>

		<header class="dm-brand-single__head">
			<div class="dm-brand-single__media">
				<?php if ( $brand_image ) : ?>
					<?php echo wp_get_attachment_image( $brand_image, 'dentomart-brand', false, array( 'class' => 'dm-brand-single__img', 'loading' => 'eager' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php else : ?>
					<span class="dm-brand-single__placeholder" aria-hidden="true"><?php echo esc_html( mb_substr( $brand_name, 0, 1 ) ); ?></span>
				<?php endif; ?>
			</div>
			<div class="dm-brand-single__copy">
				<p class="dm-section__eyebrow"><?php esc_html_e( 'Brand', 'dentomart' ); ?></p>
				<h1 class="dm-brand-single__title" id="dm-brand-single-title"><?php echo esc_html( $brand_name ); ?></h1>
				<?php if ( $brand_desc ) : ?>
					<div class="dm-brand-single__desc"><?php echo wp_kses_post( $brand_desc ); ?></div>
				<?php endif; ?>
				<ul class="dm-brand-single__stats">
					<li><strong><?php echo esc_html( number_format_i18n( get_queried_object()->count ) ); ?></strong><span><?php esc_html_e( 'Products', 'dentomart' ); ?></span></li>
					<li><strong>100%</strong><span><?php esc_html_e( 'Genuine', 'dentomart' ); ?></span></li>
					<li><strong>Fast</strong><span><?php esc_html_e( 'Dispatch', 'dentomart' ); ?></span></li>
				</ul>
			</div>
		</header>

		<?php if ( $query->have_posts() ) : ?>
			<div class="dm-brand-single__grid">
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
				$base = trailingslashit( get_term_link( get_queried_object() ) ) . 'page/%#%/';
				?>
				<nav class="dm-pagination" aria-label="<?php esc_attr_e( 'Products pagination', 'dentomart' ); ?>">
					<?php
					echo paginate_links( array(
						'base'      => $base,
						'format'    => '',
						'current'   => $paged,
						'total'     => $total_pages,
						'prev_text' => '&larr; ' . esc_html__( 'Previous', 'dentomart' ),
						'next_text' => esc_html__( 'Next', 'dentomart' ) . ' &rarr;',
						'type'      => 'list',
					) );
					?>
				</nav>
			<?php endif; ?>
		<?php else : ?>
			<div class="dm-brand-single__empty">
				<p><?php esc_html_e( 'No products from this brand are currently in stock. Please check back soon.', 'dentomart' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();