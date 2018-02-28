<?php
/**
 * Template part for displaying products on template-homepage.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

?>

<article <?php post_class('zuul-feature column-3'); ?>>
	<?php // Use thumbnail size. Max-width 75px ?>
	<?php if ( has_post_thumbnail() ) : ?>
		<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'zuul-feature-icon' ) ); ?>
	<?php endif; ?>

	<div class="zuul-feature-copy">
		<?php the_title( '<h3 class="zuul-feature-title">', '</h3>' ); ?>
		<?php the_content(); ?>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
