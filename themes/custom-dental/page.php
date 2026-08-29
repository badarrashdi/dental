<?php
/**
 * Default page template.
 *
 * @package DentoMart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<div class="dm-container dm-page">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'dm-page__content' ); ?>>
			<header class="dm-page__header">
				<h1 class="dm-page__title"><?php the_title(); ?></h1>
			</header>
			<div class="dm-page__body entry-content">
				<?php the_content(); ?>
			</div>
		</article>
	<?php endwhile; ?>
</div>
<?php
get_footer();
