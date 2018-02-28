<?php
/**
 * Template part for displaying an end cap after loops
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

?>

<article class="zuul-end-grid column-3">

	<div class="zuul-end-wrap">
		<?php if ( get_theme_mod('loop_callout_section_title') ) : ?>
			<h3><?php echo get_theme_mod('loop_callout_section_title'); ?></h3>
		<?php endif; ?>

		<?php if ( get_theme_mod('loop_callout_section_text') ) : ?>
			<section class="zuul-end-content">
				<p><?php echo get_theme_mod('loop_callout_section_text'); ?></p>
			</section>
		<?php endif; ?>

		<?php if ( get_theme_mod('loop_callout_button_url') && get_theme_mod('loop_callout_button_text') ) : ?>
			<a href="<?php echo get_theme_mod('loop_callout_button_url'); ?>" class="zuul-button zuul-button-smaller"><?php echo get_theme_mod('loop_callout_button_text'); ?></a>
		<?php endif; ?>

	</div>

</article><!-- #post-<?php the_ID(); ?> -->
