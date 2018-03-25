<?php
/**
 * Template Name: Full Width
 * Template Post Type: post, page, jetpack-testimonial, jetpack-portfolio
 *
 * This is the template that displays blog posts.
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
                    <?php get_template_part( 'template-parts/content', get_post_type() ); ?>
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
