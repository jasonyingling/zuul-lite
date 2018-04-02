<?php
/**
 * Template part for displaying projects on template-homepage.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

?>

<article <?php post_class('zuul-product zuul-item zuul-product-overlay column-3' ); ?>>

	<figure>
	<?php if ( has_post_thumbnail() ) : ?>
		<?php the_post_thumbnail('zuul-vertical'); ?>
	<?php endif; ?>

		<header class="zuul-loop-heading-overlay">
			<div class="zuul-overlay-wrap">
				<?php $cta_string = __( 'See More', 'zuul-lite' ); ?>
				<?php the_title( '<h3><a class="zuul-alt" href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
				<?php if ( has_excerpt()) {
					echo '<p>' . get_the_excerpt() . '</p>';
				} ?>
			</div>
			<a href="<?php echo esc_url( get_permalink() ); ?>" class="zuul-button zuul-button-small"><?php echo apply_filters( 'zuul_button_string', $cta_string ); ?></a>
		</header>

	</figure>

</article><!-- #post-<?php the_ID(); ?> -->
