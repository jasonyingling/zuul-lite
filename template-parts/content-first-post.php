<?php
/**
 * Template part for displaying first post
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

?>

<article <?php post_class('zuul-first-post column-1'); ?>>

	<div class="zuul-post-wrap">
		<div class="blog-info">
			<header class="zuul-loop-heading">
				<?php the_title( '<h3><a class="zuul-alt" href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
			</header>

			<section class="zuul-post-content">
				<?php the_excerpt(); ?>
			</section>
		</div>

		<footer class="zuul-post-footer">
			<?php zuul_author_meta(); ?>
			<a href="<?php echo esc_url( get_permalink() ); ?>" class="zuul-button zuul-button-small"><?php echo __( 'Read More', 'zuul-lite' ); ?></a>
		</footer>
	</div>

	<?php if ( has_post_thumbnail() ) : ?>
		<?php $thumb_url = get_the_post_thumbnail_url($post->ID, 'large'); ?>
		<div class="zuul-image-background" style="background-image: url('<?php echo esc_url( $thumb_url ); ?>');"></div>
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
