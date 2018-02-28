<?php
/**
 * Template part for displaying featured content area on template-homepage.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

?>

<section>

	<article <?php post_class('zuul-featured'); ?>>

		<div class="container">

			<div class="column-2 zuul-featured-content extra-padding-right">

				<?php if ( get_theme_mod('featured_section_title') ) : ?>
					<header class="featured-header">
						<h2 class="zuul-title-alt zuul-title-border"><?php echo esc_html( get_theme_mod('featured_section_title') ); ?></h2>
					</header><!-- .entry-header -->
				<?php endif; ?>

				<?php if ( get_theme_mod('featured_section_copy') ) : ?>
					<div class="zuul-bigger-content">
						<p><?php echo esc_html( get_theme_mod('featured_section_copy') ); ?></p>
					</div><!-- .entry-content -->
				<?php endif; ?>

				<?php if ( get_theme_mod('featured_button_text') && get_theme_mod('featured_button_url') ) : ?>

					<footer>
						<?php $featured_link_markup = '<a href="' . esc_url( get_theme_mod('featured_button_url') ) . '" class="zuul-button zuul-button-alt">' . esc_html( get_theme_mod('featured_button_text') ) . '</a>';  ?>

						<?php echo apply_filters( 'zuul_featured_url', $featured_link_markup ); ?>

					</footer><!-- .entry-footer -->
				<?php endif; ?>

			</div><!-- .featured-left -->

			<?php if ( get_theme_mod( 'featured_section_image' ) ) : ?>
				<div class="column-2 zuul-featured-figure">
					<?php $featured_image_markup = '<figure class="zuul-offset-top">' . wp_get_attachment_image( absint (get_theme_mod('featured_section_image') ), 'large' ) . '</figure>'; ?>

					<?php echo apply_filters( 'zuul_featured_image', $featured_image_markup ); ?>
				</div><!-- .featured-right -->
			<?php endif; ?>

		</div>

	</article><!-- #post-<?php the_ID(); ?> -->

</section>
