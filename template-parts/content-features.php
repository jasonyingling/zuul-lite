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
	$posts_per_page = get_theme_mod( 'zuul_home_features_count', '6' );
} else {
	$posts_per_page = get_option( 'posts_per_page' );
}

$args = array(
	'post_type'     => 'page',
	'post_parent'   => $post->ID,
	'orderby'       => 'menu_order',
	'order'         => 'ASC',
	'posts_per_page' => $posts_per_page
);

$features_query = new WP_Query ( $args );

if ( $features_query -> have_posts() ) :

	while ( $features_query -> have_posts() ) : $features_query -> the_post(); ?>

		<?php get_template_part( 'template-parts/content', 'feature' ); ?>

	<?php endwhile; wp_reset_postdata(); ?>

<?php endif; wp_reset_query(); ?>
</div>
