<?php
/**
 * Template Name: Builder
 * Template Post Type: post, page, jetpack-testimonial, jetpack-portfolio
 *
 * This is the template that can be used with page builders
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

get_header(); ?>

 	<div id="primary" class="content-area">
 		<main id="main" class="site-main">

 			<?php
 			while ( have_posts() ) : the_post();

 				the_content();

 			endwhile; // End of the loop.
 			?>

 		</main><!-- #main -->
 	</div><!-- #primary -->

<?php
get_footer();
