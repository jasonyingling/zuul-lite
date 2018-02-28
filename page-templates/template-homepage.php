<?php
/**
 * Template Name: Homepage
 *
 * This is the template that displays sections.
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
		?>

			<?php do_action( 'zuul_frontpage_featured'); ?>

			<div class="zuul-homepage-content">
				<?php
				// Get each of our panels and show the post data.
				if ( 0 !== zuul_panel_count() || is_customize_preview() ) : // If we have pages to show.

					/**
					 * Filter number of front page sections
					 */
					$num_sections = apply_filters( 'zuul_front_page_sections', 7 );
					global $zuul_counter;

					// Create a setting and control for each of the sections available in the theme.
					for ( $i = 1; $i < ( 1 + $num_sections ); $i++ ) {
						$zuul_counter = $i;
						zuul_front_page_section( null, $i );
					}

					else :

						if ( current_user_can( 'customize' ) ) { ?>
						    <section class="container placeholder-container">
						        <h2><?php _e( 'No sections available to display.', 'zuul-lite' ); ?></h2>
						        <p><?php printf(
						            __( '<a class="button" href="%s">Set up your homepage sections &rarr;</a>', 'zuul-lite' ),
						            admin_url( 'customize.php?autofocus[section]=zuul_front_page' )
						        ); ?></p>
						    </section>
						<?php }


				endif; // The if ( 0 !== zuul_panel_count() ) ends here. ?>

			</div><!-- .zuul-homepage-content -->

		<?php
			endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
