<?php
/**
 * gibby functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package zuul-lite
 */

if ( ! function_exists( 'zuul_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function zuul_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on _s, use a find and replace
		 * to change 'zuul-lite' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'zuul-lite', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in three locations.
	    register_nav_menus( array(
			// Menus moved to site-header file
	        'footer-menu' => esc_html__( 'Footer', 'zuul-lite' ),
	    ) );
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'zuul_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add excerpt support to pages
		add_post_type_support( 'page', 'excerpt' );
		add_post_type_support( 'jetpack-portfolio', 'excerpt' );

	}
endif;
add_action( 'after_setup_theme', 'zuul_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function zuul_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'zuul_content_width', 1440 );
}
add_action( 'after_setup_theme', 'zuul_content_width', 0 );

/**
 * Add Image Sizes
 */
add_image_size( 'zuul-featured', 1440, 756 );
add_image_size( 'zuul-1160', 1160, 560 );
add_image_size( 'zuul-vertical', 735, 1100, true );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function zuul_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'zuul-lite' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'zuul-lite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Column 1', 'zuul-lite' ),
		'id'            => 'footer_column_1',
		'description'   => esc_html__( 'Add widgets here.', 'zuul-lite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Column 2', 'zuul-lite' ),
		'id'            => 'footer_column_2',
		'description'   => esc_html__( 'Add widgets here.', 'zuul-lite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Column 3', 'zuul-lite' ),
		'id'            => 'footer_column_3',
		'description'   => esc_html__( 'Add widgets here.', 'zuul-lite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Column 4', 'zuul-lite' ),
		'id'            => 'footer_column_4',
		'description'   => esc_html__( 'Add widgets here.', 'zuul-lite' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'zuul_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function zuul_scripts() {
	wp_enqueue_style( 'zuul-style', get_stylesheet_uri(), '', '20180224' );

	wp_enqueue_style( 'zuul-fonts', '//fonts.googleapis.com/css?family=Montserrat:300,500|Didact+Gothic:400' );

	wp_enqueue_script( 'zuul-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20180224', true );

	wp_enqueue_script( 'zuul-scripts', get_template_directory_uri() . '/js/min/zuul-main-min.js', array( 'isotope' ), '20180224', true );

	wp_enqueue_script( 'zuul-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20180224', true );

	wp_enqueue_script( 'isotope', get_template_directory_uri() . '/js/min/isotope.pkgd.min.js', array( 'jquery' ), '20180224', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'zuul_scripts' );

/**
 * Registers an editor stylesheet for the theme.
 */
function zuul_theme_add_editor_styles() {
    add_editor_style( 'editor-style.css' );
}
add_action( 'admin_init', 'zuul_theme_add_editor_styles' );

/**
 * Load theme updater functions.
 * Action is used so that child themes can easily disable.
 */
function zuul_theme_updater() {
	define('CNKT_INSTALLER_PATH', get_template_directory_uri() .'/inc/admin/connekt-plugin-installer/');
	include_once( get_template_directory() . '/inc/admin/connekt-plugin-installer/class-connekt-plugin-installer.php');
	require( get_template_directory() . '/inc/admin/information/theme-information.php' );
}
add_action( 'after_setup_theme', 'zuul_theme_updater' );

/**
 * Integrate Easy Navigation
 */
require get_template_directory() . '/easy-navigation/site-header-functions.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Gets the gallery shortcode data from post content.
 */
function zuul_gallery_data() {
	global $post;
	$pattern = get_shortcode_regex();
	if (   preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
		&& array_key_exists( 2, $matches )
		&& in_array( 'gallery', $matches[2] ) )
	{
		return $matches;
	}
}


if ( ! function_exists( 'zuul_filtered_content') ) :
/**
 * If the post is a gallery or video format remove the first gallery or video and return the content
 *
 * @since zuul 1.0
 */
function zuul_filtered_content() {

	global $post, $wp_embed;

	$content = get_the_content( esc_html__( 'Read More', 'zuul-lite' ) );

	if ( has_post_format( 'gallery' ) ) {

		$gallery_data = zuul_gallery_data();

		// Remove the first gallery from the post since we're using it in place of the featured image
		if ( $gallery_data && is_array( $gallery_data ) ) {
			$content = str_replace( $gallery_data[0][0], '', $content );
		}
	}

	if ( has_post_format( 'video' ) ) {

		// Remove the first video embed from the post since we're using it in place of the featured image
		if ( ! empty( $wp_embed->last_url ) ) {
			$content = str_replace( $wp_embed->last_url, '', $content );
		} else {
			$video = get_media_embedded_in_content( $content );
			$content = str_replace( $video, '', $content );
		}
	}

	echo apply_filters( 'the_content', $content );
}
endif;

//Wrap embed/iframe code in video-container wrapper
function zuul_wrap_embed_with_div($html, $url, $attr) {

	$oembedSource = parse_url($url, PHP_URL_HOST);
	$domain = str_replace('.', '-', $oembedSource);

	if ($oembedSource === 'youtube.com'  || $oembedSource === 'vimeo.com' || $oembedSource === 'animoto.com' || $oembedSource === 'blip.tv' || $oembedSource === 'collegehumor.com' || $oembedSource === 'funnyordie.com' || $oembedSource === 'hulu.com' || $oembedSource === 'ted.com' || $oembedSource === 'wordpress.tv'
		|| $oembedSource === 'www.youtube.com'  || $oembedSource === 'www.vimeo.com' || $oembedSource === 'www.animoto.com' || $oembedSource === 'www.blip.tv' || $oembedSource === 'www.collegehumor.com' || $oembedSource === 'www.funnyordie.com' || $oembedSource === 'www.hulu.com' || $oembedSource === 'www.ted.com' || $oembedSource === 'www.wordpress.tv'
	) {
		return '<div class="video"><div class="video-wrapper oembed-'.$domain.'">' . $html . '</div></div>';
	} else {
		return '<div class="oembed oembed-'.$domain.'">' . $html . '</div>';
	}

}
add_filter('embed_oembed_html', 'zuul_wrap_embed_with_div', 10, 3);
add_filter('oembed_result', 'zuul_wrap_embed_with_div', 10, 3);

function zuul_excerpt_length( $length ) {
	if ( is_admin() ) {
		return $length;
	}

	return 20;
}
add_filter( 'excerpt_length', 'zuul_excerpt_length', 999 );

function zuul_modify_read_more_link() {
    return '';
}
add_filter( 'the_content_more_link', 'zuul_modify_read_more_link' );
