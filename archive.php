<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<div id="zuul-infinite" class="zuul-post-loop">
				<div class="container">

					<?php
					if ( have_posts() ) : ?>

						<div class="zuul-loop column-1">
							<?php zuul_section_intro( $post->ID ); ?>
							<?php
							/* Start the Loop */
							while ( have_posts() ) : the_post();

								/*
								 * Inlcude Post-Type-specific template for conetnt for Products and Jetpack Portfolios and Testimonials.
								 * Include the Post-Format-specific template for the content.
								 * If you want to override this in a child theme, then include a file
								 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
								 */
								if ( is_post_type_archive( 'jetpack-portfolio' ) || is_post_type_archive( 'product' ) || is_tax('jetpack-portfolio-type') || is_tax('jetpack-portfolio-tag') ) {
									get_template_part( 'template-parts/content', 'product' );
								} elseif ( is_post_type_archive( 'download' ) ) {
									get_template_part( 'template-parts/content', 'downloads' );
								} elseif ( is_post_type_archive( 'jetpack-testimonial') ) {
									get_template_part( 'template-parts/content', 'testimonial' );
								} else {
									get_template_part( 'template-parts/content', 'loop-archive' );
								}

							endwhile; ?>

							<?php zuul_pagination(); ?>

						</div><!-- .zuul-loop -->

					<?php else :

						get_template_part( 'template-parts/content', 'none' );

					endif; ?>

				</div>
			</div><!-- zuul-content -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
