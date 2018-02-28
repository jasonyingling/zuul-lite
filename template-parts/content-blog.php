<?php
/**
 * Template part for displaying products on template-homepage.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

?>

<div class="zuul-loop zuul-hp-loop">
<?php
	if ( is_front_page() && is_page_template('page-templates/template-homepage.php') ) {
		$posts_per_page = get_theme_mod( 'zuul_home_blog_count', '2' );
	} else {
		$posts_per_page = get_option( 'posts_per_page' );
	}

	$args = array (
		'post_type' => 'post',
		'posts_per_page' => $posts_per_page,
		'ignore_sticky_posts' => true,
	);

	$post_count = 0;

	$blog = new WP_Query($args);

	while( $blog->have_posts() ) : $blog->the_post(); ?>
		<?php get_template_part( 'template-parts/content', 'hp-post' ); ?>
	<?php endwhile; wp_reset_query(); ?>
</div>
