<?php
/**
 * Jetpack Compatibility File
 *
 * @link https://jetpack.com/
 *
 * @package zuul-lite
 */

/**
 * Jetpack setup function.
 *
 * See: https://jetpack.com/support/infinite-scroll/
 * See: https://jetpack.com/support/responsive-videos/
 * See: https://jetpack.com/support/content-options/
 */
function zuul_jetpack_setup() {
	// Add theme support for Infinite Scroll.
	add_theme_support( 'infinite-scroll', array(
		'container' => 'zuul-infinite',
		'render'    => 'zuul_infinite_scroll_render',
		'footer'    => 'page',
		'wrapper'	=> 'zuul-loop',
		'posts_per_page' => 8,
		'footer_widgets' => array( 'footer_column_1', 'footer_column_2', 'footer_column_3', 'footer_column_4' ),
	) );

	// Add theme support for Responsive Videos.
	add_theme_support( 'jetpack-responsive-videos' );

	// Add theme support for Content Options.
	add_theme_support( 'jetpack-content-options', array(
		'post-details' => array(
			'stylesheet' => 'zuul-style',
			'date'       => '.posted-on',
			'categories' => '.cat-links',
			'tags'       => '.tags-links',
			'author'     => '.zuul-author-meta',
			'comment'    => '.comments-link',
		),
	) );

	add_post_type_support( 'jetpack-portfolio', 'post-formats' );
}
add_action( 'after_setup_theme', 'zuul_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function zuul_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		if ( is_search() ) :
			get_template_part( 'template-parts/content', 'search' );
		else :
			get_template_part( 'template-parts/content', get_post_format() );
		endif;
	}
}
