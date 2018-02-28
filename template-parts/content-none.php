<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

?>

<section class="no-results not-found column-1 content-with-sidebar">

	<div class="sidebar-content">
		
		<header class="page-header">
			<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'zuul-lite' ); ?></h1>
		</header><!-- .page-header -->

		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php
				printf(
					wp_kses(
						/* translators: 1: link to WP admin new post page. */
						__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'zuul-lite' ),
						array(
							'a' => array(
								'href' => array(),
							),
						)
					),
					esc_url( admin_url( 'post-new.php' ) )
				);
			?></p>

		<?php elseif ( is_search() ) : ?>

			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'zuul-lite' ); ?></p>
			<?php
				get_search_form();

		else : ?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'zuul-lite' ); ?></p>
			<?php
				get_search_form();

		endif; ?>
	</div><!-- .page-content -->

	<?php get_sidebar(); ?>

</section><!-- .no-results -->