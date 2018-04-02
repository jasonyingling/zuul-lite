<?php
/**
 * Template part for displaying products on template-homepage.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

?>

<div class="zuul-loop">
<?php

	if ( is_front_page() && is_page_template('page-templates/template-homepage.php') ) {
		$posts_per_page = get_theme_mod( 'zuul_home_portfolio_count', '3' );
	} else {
		$posts_per_page = get_option( 'jetpack_portfolio_posts_per_page', '10' );
	}

	$args = array (
		'post_type' => 'jetpack-portfolio',
		'posts_per_page' => $posts_per_page
	);

	$products = new WP_Query($args);

	while( $products->have_posts() ) : $products->the_post(); ?>
		<?php get_template_part( 'template-parts/content', 'project' ); ?>
	<?php endwhile; wp_reset_query(); ?>

</div>
