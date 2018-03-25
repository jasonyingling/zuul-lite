<?php
/**
 * Template Name: Sidebar
 * Template Post Type: post, page, jetpack-testimonial, jetpack-portfolio
 *
 * This is the template for use with a sidebar
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

get_header(); ?>

 	<div id="primary" class="content-area">
 		<main id="main" class="site-main">

            <?php
 			while ( have_posts() ) : the_post(); ?>
                <div class="container">
                    <?php get_template_part( 'template-parts/content', 'sidebar' ); ?>
                </div>
            <?php

 				// If comments are open or we have at least one comment, load up the comment template.
 				if ( comments_open() || get_comments_number() ) :
 					comments_template();
 				endif;

 			endwhile; // End of the loop.
 			?>

 		</main><!-- #main -->

 	</div><!-- #primary -->

<?php
get_footer();
