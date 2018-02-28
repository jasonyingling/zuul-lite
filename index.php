<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

 get_header(); ?>

 <?php $posts_page = get_option( 'page_for_posts' ); ?>

 <?php
     if ( is_active_sidebar( 'sidebar-1' ) ) {
         $sidebar = true;
     } else {
         $sidebar = false;
     }
 ?>

 	<div id="primary" class="content-area">
 		<main id="main" class="site-main">
            <div class="container">

    			<div id="zuul-infinite" class="zuul-post-loop <?php  if ( $sidebar ) { echo 'content-with-sidebar column-1'; } ?>">

    				<div class="zuul-loop <?php  if ( $sidebar ) { echo 'sidebar-content'; } else { echo 'column-1'; } ?>">
    		 			<?php
                        $post_count = 0;

    		 			while ( have_posts() ) : the_post();
    		 			?>

    		 				<?php
                                get_template_part( 'template-parts/content', 'loop' );
    		 				?>

    		 			<?php

    		 				// If comments are open or we have at least one comment, load up the comment template.
    		 				if ( comments_open() || get_comments_number() ) :
    		 					comments_template();
    		 				endif;

    		 			endwhile; // End of the loop.
    		 			?>

                        <?php zuul_pagination(); ?>

    				</div><!-- .zuul-loop -->

                    <?php get_sidebar(); ?>

    			</div><!-- .zuul-content -->

            </div><!-- .container -->

 		</main><!-- #main -->
 	</div><!-- #primary -->

 <?php
 get_footer();
