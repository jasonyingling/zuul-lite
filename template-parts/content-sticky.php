<?php
/**
 * Template part for displaying products on template-homepage.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

?>

<article <?php post_class('zuul-post zuul-sticky column-1'); ?>>

	<div class="zuul-sticky-wrap column-2">
		<div class="blog-info">
			<header class="zuul-loop-heading">
				<?php the_title( '<h3 class="zuul-title-border zuul-title-white"><a class="zuul-alt" href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
			</header>

			<section class="zuul-post-content">
				<?php the_excerpt(); ?>
			</section>
		</div>

		<footer class="zuul-post-footer">
			<?php if ( class_exists( 'WooCommerce' ) && is_shop() ) : ?>
				<a href="<?php echo esc_url( get_permalink() ); ?>" class="zuul-button zuul-button-small"><?php echo __( 'Buy Now', 'zuul-lite' ); ?></a>
			<?php else : ?>
				<a href="<?php echo esc_url( get_permalink() ); ?>" class="zuul-button zuul-button-small"><?php echo __( 'Read More', 'zuul-lite' ); ?></a>
			<?php endif; ?>
		</footer>
	</div>
	<?php if ( class_exists( 'WooCommerce' ) && is_shop() ) : ?>
		<figure class="column-2">
			<a href="<?php echo esc_url( get_permalink() ); ?>">
				<?php the_post_thumbnail(); ?>
			</a>
		</figure>
	<?php elseif ( class_exists('acf') ) : ?>
		<figure class="column-2">
			<a href="<?php echo esc_url( get_permalink() ); ?>">
				<?php $vertical_image = get_field( 'vertical_image' ); ?>
				<?php echo wp_get_attachment_image( $vertical_image['id'], 'zuul-vertical' ); ?>
			</a>
		</figure>
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
