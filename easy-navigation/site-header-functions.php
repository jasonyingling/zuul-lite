<?php
function zuul_site_header_setup() {

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus( array(
        'menu-1' => esc_html__( 'Primary', 'zuul-lite' ),
        'mobile-menu-1' => esc_html__( 'Mobile', 'zuul-lite' ),
    ) );

    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support( 'custom-logo', array(
        'width'       => 300,
        'flex-width'  => true,
        'flex-height' => true,
    ) );

}
add_action( 'after_setup_theme', 'zuul_site_header_setup' );

/**
 * Enqueue scripts and styles.
 */
function zuul_site_header_scripts() {

    wp_enqueue_script( 'mmenu', get_template_directory_uri() . '/easy-navigation/js/vendor/jquery.mmenu.all.js', array('jquery'), '20180307', true );
    
	wp_enqueue_script( 'zuul-site-header', get_template_directory_uri() . '/easy-navigation/js/site-header.js', array('mmenu'), '20180307', true );

}
add_action( 'wp_enqueue_scripts', 'zuul_site_header_scripts' );
