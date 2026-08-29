<?php
/**
 * Fallback template.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<div class="dm-container dm-archive">
	<?php if ( have_posts() ) : ?>
		<header class="dm-archive__header">
			<h1 class="dm-archive__title"><?php echo esc_html( get_the_archive_title() ); ?></h1>
			<?php the_archive_description( '<div class="dm-archive__desc">', '</div>' ); ?>
		</header>
		<div class="dm-archive__grid">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'dm-entry' ); ?>>
					<h2 class="dm-entry__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<div class="dm-entry__excerpt"><?php the_excerpt(); ?></div>
				</article>
			<?php endwhile; ?>
		</div>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Nothing found.', 'dentomart' ); ?></p>
	<?php endif; ?>
</div>
<?php
get_footer();
