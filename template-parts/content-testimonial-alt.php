<?php
/**
 * Template part for displaying products on template-homepage.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

?>

<article <?php post_class('zuul-testimonial-alt column-2'); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="zuul-testimonial-alt--image"><?php the_post_thumbnail( 'medium' ); ?></div>
	<?php endif; ?>

	<section class="zuul-testimonial-alt-content">

		<div class="zuul-testimonial-alt-content--copy">
			<?php the_content(); ?>
		</div><!-- .zuul-testimonial-alt-content-copy -->

		<footer class="zuul-testimonial-alt-content--footer">
			<div class="zuul-author-meta zuul-author-meta-testimonial">
				<div class="zuul-author-info">
					<?php if ( class_exists('acf') && ( get_field('author_link') || get_field('ara_title') ) ) : ?>
						<a href="<?php the_field('author_link') ?>" class="zuul-author-name" target="_blank"><?php the_title(); ?></a>
						<span class="zuul-author-title"><?php the_field( 'ara_title' ); ?></span>
					<?php else : ?>
						<span class="zuul-author-name"><?php the_title(); ?></span>
						<span class="zuul-author-title">&nbsp;</span>
					<?php endif; ?>
				</div>
			</div><!-- .zuul-author-meta -->
		</footer>

	</section>

</article><!-- #post-<?php the_ID(); ?> -->
