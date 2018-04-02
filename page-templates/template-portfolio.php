<?php
/**
 * Template Name: Portfolio
 *
 * This is the template that displays portfolio posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

get_header(); ?>

	<?php $portfolio_types = get_terms( array(
		'taxonomy' => 'jetpack-portfolio-type',
		'hide_empty' => true
	) ); ?>

	<section class="zuul-content">

		<div class="container">

			<?php zuul_section_intro( $post->ID ); ?>

			<ul class="zuul-project-filters">
				<li><a href="#" class="zuul-active-filter" data-filter="*"><?php echo __( 'All', 'zuul-lite' ); ?></a></li>

				<?php foreach ( $portfolio_types as $type ) {
					echo '<li><a href="#" data-filter=".jetpack-portfolio-type-' . $type->slug . '">' . $type->name . '</a></li>';
				} ?>
			</ul>

			<div class="zuul-loop zuul-loop-projects">
				<?php
			        if ( get_query_var( 'paged' ) ) :
			            $paged = get_query_var( 'paged' );
			        elseif ( get_query_var( 'page' ) ) :
			            $paged = get_query_var( 'page' );
			        else :
			            $paged = 1;
			        endif;

			        $posts_per_page = get_option( 'jetpack_portfolio_posts_per_page', '10' );

			        $args = array(
			            'post_type'      => 'jetpack-portfolio',
			            'paged'          => $paged,
			            'posts_per_page' => $posts_per_page,
			        );

			        $project_query = new WP_Query ( $args );

			        if ( post_type_exists( 'jetpack-portfolio' ) && $project_query -> have_posts() ) :

			            while ( $project_query -> have_posts() ) : $project_query -> the_post(); ?>

							<?php get_template_part( 'template-parts/content', 'project' ); ?>

						<?php endwhile; ?>

						<?php zuul_pagination( $project_query ); ?>

					<?php endif; wp_reset_postdata(); ?>

			</div>

		</div>

	</section>

<?php
get_footer();
