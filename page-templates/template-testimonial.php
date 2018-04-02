<?php
/**
 * Template Name: Testimonials
 *
 * This is the template that displays portfolio posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

get_header(); ?>

	<section class="zuul-content">

		<div class="container">

			<?php zuul_section_intro( $post->ID ); ?>

			<div class="zuul-loop">
				<?php
			        if ( get_query_var( 'paged' ) ) :
			            $paged = get_query_var( 'paged' );
			        elseif ( get_query_var( 'page' ) ) :
			            $paged = get_query_var( 'page' );
			        else :
			            $paged = 1;
			        endif;

			        $posts_per_page = get_option( 'jetpack_testimonial_posts_per_page', '12' );

			        $args = array(
			            'post_type'      => 'jetpack-testimonial',
			            'paged'          => $paged,
			            'posts_per_page' => $posts_per_page,
			        );

			        $project_query = new WP_Query ( $args );

			        if ( post_type_exists( 'jetpack-testimonial' ) && $project_query -> have_posts() ) :

			            while ( $project_query -> have_posts() ) : $project_query -> the_post(); ?>

							<?php get_template_part( 'template-parts/content', 'testimonial-alt' ); ?>

						<?php endwhile; ?>

						<?php zuul_pagination( $project_query ); ?>

					<?php endif; wp_reset_postdata(); ?>

			</div>

		</div>

	</section>

<?php
get_footer();
