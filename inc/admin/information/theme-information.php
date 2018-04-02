<?php
/**
 * Easy Digital Downloads Theme Updater
 *
 * @package EDD Sample Theme
 */

// Includes the files needed for the theme information
if ( !class_exists( 'Zuul_Theme_Information' ) ) {
	include( dirname( __FILE__ ) . '/theme-information-admin.php' );
}

// The theme version to use in the updater
define( 'ZUUL_SL_THEME_VERSION', wp_get_theme( 'zuul-lite' )->get( 'Version' ) );

// Loads the updater classes
$zuul_info = new Zuul_Theme_Information(

	// Config settings
	$config = array(
		'remote_api_url' => 'https://jasonyingling.me', // Site where EDD is hosted
		'item_name'      => __( 'Zuul WordPress Theme', 'zuul-lite' ), // Name of theme
		'theme_slug'     => 'zuul-lite', // Theme slug
		'version'        => ZUUL_SL_THEME_VERSION, // The current version of this theme
		'author'         => __( 'Jason Yingling', 'zuul-lite' ), // The author of this theme
	),

	// Strings
	$strings = array(
		'theme-information'             => __( 'Theme Help', 'zuul-lite' ),
	)

);
