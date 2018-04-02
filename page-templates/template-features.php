<?php
/**
 * Template Name: Features
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
					$post_id = get_the_ID();

			        if ( get_query_var( 'paged' ) ) :
			            $paged = get_query_var( 'paged' );
			        elseif ( get_query_var( 'page' ) ) :
			            $paged = get_query_var( 'page' );
			        else :
			            $paged = 1;
			        endif;

			        $posts_per_page = get_option( 'posts_per_page' );

					$args = array(
				        'post_type'     => 'page',
				        'paged'         => $paged,
				        'post_parent'   => $post_id,
				        'orderby'       => 'menu_order',
				        'order'         => 'ASC',
				        'posts_per_page' => '-1'
				    );

			        $features_query = new WP_Query ( $args );

			        if ( $features_query -> have_posts() ) :

			            while ( $features_query -> have_posts() ) : $features_query -> the_post(); ?>

							<?php get_template_part( 'template-parts/content', 'feature' ); ?>

						<?php endwhile; wp_reset_postdata(); ?>

						<?php zuul_pagination( $features_query ); ?>

					<?php endif; wp_reset_postdata(); ?>

			</div>

		</div>

	</section>

<?php
get_footer();
