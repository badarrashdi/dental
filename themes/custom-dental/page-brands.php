<?php
/**
 * Template Name: Brands Directory
 *
 * Lists every pa_brand term as an image card grid, with A–Z filtering.
 * Used by the /brands page so visitors can browse all brands in one place.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$brand_alpha = isset( $_GET['alpha'] ) ? sanitize_text_field( wp_unslash( $_GET['alpha'] ) ) : '';

$args = array(
	'taxonomy'   => 'pa_brand',
	'hide_empty' => true,
	'orderby'    => 'name',
	'order'      => 'ASC',
);
if ( $brand_alpha && '#' !== $brand_alpha && 1 === strlen( $brand_alpha ) ) {
	$args['name__like'] = strtoupper( $brand_alpha );
}

$terms = get_terms( $args );

$all_terms = get_terms( array( 'taxonomy' => 'pa_brand', 'hide_empty' => true ) );
$letters   = array();
foreach ( (array) $all_terms as $t ) {
	if ( ! is_wp_error( $t ) && $t->name ) {
		$letter = strtoupper( mb_substr( $t->name, 0, 1 ) );
		if ( ! isset( $letters[ $letter ] ) ) {
			$letters[ $letter ] = true;
		}
	}
}
ksort( $letters );

$images = array();
if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
	$images = dentomart_brand_term_images( wp_list_pluck( $terms, 'term_id' ) );
}
?>
<section class="dm-brand-archive" aria-labelledby="dm-brand-archive-title">
	<div class="dm-container">
		<header class="dm-brand-archive__head">
			<p class="dm-section__eyebrow"><?php esc_html_e( 'Browse by maker', 'dentomart' ); ?></p>
			<h1 class="dm-brand-archive__title" id="dm-brand-archive-title"><?php esc_html_e( 'All Brands', 'dentomart' ); ?></h1>
			<p class="dm-brand-archive__subtitle">
				<?php
				$total = is_array( $all_terms ) ? count( $all_terms ) : 0;
				echo esc_html( sprintf( _n( '%s brand in stock', '%s brands in stock', $total, 'dentomart' ), number_format_i18n( $total ) ) );
				?>
			</p>
		</header>

		<nav class="dm-brand-alpha" aria-label="<?php esc_attr_e( 'Filter brands by letter', 'dentomart' ); ?>">
			<a class="dm-brand-alpha__btn<?php echo '' === $brand_alpha ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e( 'All', 'dentomart' ); ?></a>
			<?php foreach ( array_keys( $letters ) as $letter ) : ?>
				<a class="dm-brand-alpha__btn<?php echo strtoupper( $brand_alpha ) === $letter ? ' is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'alpha', $letter, get_permalink() ) ); ?>"><?php echo esc_html( $letter ); ?></a>
			<?php endforeach; ?>
		</nav>

		<?php if ( is_wp_error( $terms ) || empty( $terms ) ) : ?>
			<div class="dm-brand-archive__empty">
				<p><?php esc_html_e( 'No brands found for this filter.', 'dentomart' ); ?></p>
			</div>
		<?php else : ?>
			<div class="dm-brand-archive__grid">
				<?php foreach ( $terms as $term ) : ?>
					<?php
					$image_id = isset( $images[ $term->term_id ] ) ? $images[ $term->term_id ] : 0;
					$initial  = mb_substr( $term->name, 0, 1 );
					?>
					<a class="dm-brand-archive-card" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
						<span class="dm-brand-archive-card__media">
							<?php if ( $image_id ) : ?>
								<?php echo wp_get_attachment_image( $image_id, 'dentomart-brand', false, array( 'class' => 'dm-brand-archive-card__img', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<?php else : ?>
								<span class="dm-brand-archive-card__placeholder" aria-hidden="true"><?php echo esc_html( $initial ); ?></span>
							<?php endif; ?>
						</span>
						<span class="dm-brand-archive-card__body">
							<span class="dm-brand-archive-card__name"><?php echo esc_html( $term->name ); ?></span>
							<span class="dm-brand-archive-card__meta"><?php echo esc_html( sprintf( _n( '%s product', '%s products', $term->count, 'dentomart' ), number_format_i18n( $term->count ) ) ); ?></span>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();