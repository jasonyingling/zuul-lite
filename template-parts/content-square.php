<?php
/**
 * Template part for displaying products on template-homepage.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

?>

<article <?php post_class('zuul-post zuul-item column-3'); ?>>

	<div class="zuul-post-wrap">
		<div class="blog-info">
			<header class="zuul-loop-heading">
				<?php the_title( '<h3><a class="zuul-alt" href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
			</header>
		</div>

		<footer class="zuul-post-footer">
			<a href="<?php echo esc_url( get_permalink() ); ?>" class="zuul-button zuul-button-smaller"><?php echo __( 'See More', 'zuul-lite' ); ?></a>
		</footer>
	</div>

	<?php if ( has_post_thumbnail() ) : ?>
		<?php $thumb_url = get_the_post_thumbnail_url($post->ID, 'medium'); ?>
		<div class="zuul-image-background" style="background-image: url('<?php echo esc_url($thumb_url); ?>');"></div>
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
