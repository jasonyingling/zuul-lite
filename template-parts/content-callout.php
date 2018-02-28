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

	<article class="zuul-callout">

		<div class="container">

			<div class="column-1">
				<?php if ( get_theme_mod('callout_section_text') ) : ?>
					<h4><?php echo esc_html( get_theme_mod('callout_section_text') ); ?></h4>
				<?php endif; ?>
				<?php if ( get_theme_mod('callout_button_url') && get_theme_mod('callout_button_text') ) : ?>
					<?php $callout_link_markup = '<a href="' . esc_url( get_theme_mod('callout_button_url') ) . '" class="zuul-button">' . esc_html( get_theme_mod('callout_button_text') ) . '</a>' ?>

					<?php echo apply_filters( 'zuul_callout_url', $callout_link_markup ); ?>
				<?php endif; ?>
			</div>

		</div>

	</article><!-- #post-<?php the_ID(); ?> -->

</section>
