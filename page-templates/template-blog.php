<?php
/**
 * Template Name: Blog
 *
 * This is the template that displays portfolio posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

get_header(); ?>

	<section class="zuul-post-loop">

		<div class="container">

			<div class="zuul-loop column-1">

				<?php zuul_section_intro( $post->ID ); ?>

				<?php
			        if ( get_query_var( 'paged' ) ) :
			            $paged = get_query_var( 'paged' );
			        elseif ( get_query_var( 'page' ) ) :
			            $paged = get_query_var( 'page' );
			        else :
			            $paged = 1;
			        endif;

			        $args = array(
			            'paged'          => $paged,
						'ignore_sticky_posts' => true,
			        );

			        $post_query = new WP_Query ( $args );

			        if ( $post_query -> have_posts() ) :

			            while ( $post_query -> have_posts() ) : $post_query -> the_post(); ?>

							<?php get_template_part( 'template-parts/content' ); ?>

						<?php endwhile; ?>

						<?php zuul_pagination( $post_query ); ?>

					<?php endif; wp_reset_postdata(); ?>

			</div>

		</div>

	</section>

<?php
get_footer();
