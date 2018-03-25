<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-pro
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('column-2 zuul-related'); ?>>

	<?php if ( has_post_thumbnail() ) { ?>

		<figure class="zuul-featured-hp"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'zuul-1160' ); ?></a></figure>

	<?php } else { ?>
		<div class="zuul-featured-related"></div>
	<?php } ?>

	<div class="zuul-section-title">

		<div class="zuul-intro">
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php if ( ! is_singular( 'post' ) ) : ?>
				<p><?php the_excerpt(); ?></p>
			<?php endif; ?>
		</div>

	</div><!-- .zuul-section-title -->

</article><!-- #post-<?php the_ID(); ?> -->
