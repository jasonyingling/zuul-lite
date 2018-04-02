<?php
/**
 * gibby Theme Customizer.
 *
 * @package zuul-lite
 */

 /**
  * Return whether we're previewing the front page and it's a static page.
  */
 function zuul_is_static_front_page() {
 	return ( is_page_template( 'page-templates/template-homepage.php' ) );
 }

 /**
  * Return whether we're previewing a testimonial or post index
  */
 function zuul_is_callout_visible() {
     return true;
 }

 /**
  * Jetpack callback
  */
 function zuul_jetpack_callback( $control ) {
 	if ( class_exists( 'Jetpack' ) ) {
 		return true;
 	} else {
 		return false;
 	}
 }

 /**
 * Sanitize range slider
 */
function zuul_sanitize_range( $input ) {
	filter_var( $input, FILTER_FLAG_ALLOW_FRACTION );
	return ( $input );
}

/**
 * Sanitize checkbox
 */
function zuul_sanitize_checkbox( $input ) {
	return ( 1 == $input ) ? 1 : '';
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function zuul_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'zuul_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'zuul_customize_partial_blogdescription',
		) );
	}

    /**
     * Header Background Opacity Range
     */
    $wp_customize->add_setting( 'zuul_bg_opacity', array(
        'default'           => '1',
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'zuul_sanitize_range',
    ) );

    $wp_customize->add_control( 'zuul_bg_opacity', array(
        'type'        => 'range',
        'priority'    => 40,
        'section'     => 'header_image',
        'label'       => __( 'Header Image Opacity', 'zuul-lite' ),
        'description' => __( 'Change the opacity of your header image.', 'zuul-lite' ),
        'input_attrs' => array(
            'min'   => 0,
            'max'   => 1,
            'step'  => .05,
            'style' => 'width: 100%',
        ),
    ) );

    /**
     * Zuul Logo Size
     */
    $wp_customize->add_setting( 'zuul_logo_size', array(
        'default'           => '120',
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'zuul_sanitize_range',
    ) );

    $wp_customize->add_control( 'zuul_logo_size', array(
        'type'        => 'range',
        'priority'    => 10,
        'section'     => 'title_tagline',
        'label'       => __( 'Logo Size', 'zuul-lite' ),
        'description' => __( 'Adjust the size of your site logo', 'zuul-lite' ),
        'input_attrs' => array(
            'min'   => 25,
            'max'   => 1000,
            'step'  => 1,
            'style' => 'width: 100%',
        ),
    ) );

    /**
     * Colors
     */
    $wp_customize->add_setting( 'zuul_gradient', array(
        'default'           => '',
        'sanitize_callback'  => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( 'zuul_gradient', array(
        'label'         => __( 'Gradient', 'zuul-lite' ),
        'description'   => __( 'Include gradient CSS. Try grabbing CSS from <a href="https://www.grabient.com/" target="_blank">Grabient</a>. For more information see docs.', 'zuul-lite' ),
        'section'       => 'colors',
        'type'          => 'textarea',
        'priority'      => 2,
    ) );

    $wp_customize->add_setting( 'zuul_primary_color', array(
        'default'           => '#9599e2',
        'sanitize_callback'  => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control (
        $wp_customize,
        'zuul_primary_color',
        array(
            'label'         => __( 'Primary Color', 'zuul-lite' ),
            'description'   => __( 'Set the primary site color. Used for buttons, links, and rollovers.', 'zuul-lite' ),
            'section'       => 'colors',
            'settings'      => 'zuul_primary_color',
            'priority'      => 3,
        ) )
    );

    $wp_customize->add_setting( 'zuul_footer_background', array(
        'default'           => '#333333',
        'sanitize_callback'  => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control (
        $wp_customize,
        'zuul_footer_background',
        array(
            'label'         => __( 'Footer Background Color', 'zuul-lite' ),
            'description'   => __( 'Set the footer background color.', 'zuul-lite' ),
            'section'       => 'colors',
            'settings'      => 'zuul_footer_background',
            'priority'      => 4,
        ) )
    );

	/**
	 * Theme Options Panel
	 */
	$wp_customize->add_panel( 'zuul_theme_options_panel', array(
		'priority'   => 5,
		'capability' => 'edit_theme_options',
		'title'      => esc_html__( 'Theme Options', 'zuul-lite' ),
        'description' => __( 'To take advantage of the custom front page options you will need to use a static front page using the Homepage template. You can set this in the Homepage settings panel in the customizer or Settings > Reading from the dashboard.', 'zuul-lite' ),
	) );

    /**
     * Front Page Hero
     */

    $wp_customize->add_section( 'zuul_front_page_hero', array(
 		'priority'   => 2,
 		'capability' => 'edit_theme_options',
 		'title'      => esc_html__( 'Front Page Hero', 'zuul-lite' ),
 	    'panel'      => 'zuul_theme_options_panel',
 	) );

    $wp_customize->add_setting( 'zuul_hero_title', array(
 	    'default'           => get_bloginfo('name'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
 	) );

    $wp_customize->add_control( 'zuul_hero_title', array(
        'label'           => __( 'Front Page Title', 'zuul-lite' ),
        'description'     => __( 'Enter text for the front page title.', 'zuul-lite' ),
        'section'         => 'zuul_front_page_hero',
        'type'            => 'text',
        'active_callback' => 'zuul_is_static_front_page',
        'priority'        => 1,
    ) );

    $wp_customize->selective_refresh->add_partial( 'zuul_hero_title', array(
        'selector' => '.zuul-hero-title',
        'container_inclusive' => false,
        'render_callback' => 'zuul_hero_title_partial',
    ) );

    $wp_customize->add_setting( 'zuul_hero_desc', array(
 	    'default'           => get_bloginfo('description'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
 	) );

    $wp_customize->add_control( 'zuul_hero_desc', array(
        'label'           => __( 'Front Page Description', 'zuul-lite' ),
        'description'     => __( 'Enter text for the front page description.', 'zuul-lite' ),
        'section'         => 'zuul_front_page_hero',
        'type'            => 'text',
        'active_callback' => 'zuul_is_static_front_page',
        'priority'        => 2,
    ) );

    $wp_customize->selective_refresh->add_partial( 'zuul_hero_desc', array(
        'selector' => '.zuul-hero-desc',
        'container_inclusive' => false,
        'render_callback' => 'zuul_hero_desc_partial',
    ) );


    $wp_customize->add_setting( 'zuul_hero_link', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'zuul_hero_link', array(
		'label'           => __( 'Button URL', 'zuul-lite' ),
		'description'     => __( 'Enter a URL for the button.', 'zuul-lite' ),
		'section'         => 'zuul_front_page_hero',
		'type'            => 'url',
		'active_callback' => 'zuul_is_static_front_page',
		'priority'        => 4,
	) );

	$wp_customize->add_setting( 'zuul_hero_cta', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'zuul_hero_cta', array(
		'label'           => __( 'Button Text', 'zuul-lite' ),
		'description'     => __( 'Enter text for the button.', 'zuul-lite' ),
		'section'         => 'zuul_front_page_hero',
		'type'            => 'text',
		'active_callback' => 'zuul_is_static_front_page',
		'priority'        => 3,
	) );

    $wp_customize->selective_refresh->add_partial( 'zuul_hero_cta', array(
        'selector' => '.zuul-hero-copy .zuul-button',
        'container_inclusive' => false,
        'render_callback' => 'zuul_hero_cta_partial',
    ) );

	/**
	 * Front Page Panel
	 */
	$wp_customize->add_section( 'zuul_front_page', array(
		'priority'   => 5,
		'capability' => 'edit_theme_options',
		'title'      => esc_html__( 'Front Page Sections', 'zuul-lite' ),
		'panel'      => 'zuul_theme_options_panel',
        'description' => __( 'In order to show the front page sections you will need to use a static front page using the Homepage template. This can be set in the Homepage Settings panel in the customizer or in the Settings > Reading menu of your dashboard.', 'zuul-lite' )
	) );

	/**
	 * Filter number of front page sections
	 */
	$num_sections = apply_filters( 'zuul_front_page_sections', 7 );

	// Create a setting and control for each of the sections available in the theme.
	for ( $i = 1; $i < ( 1 + $num_sections ); $i++ ) {
		$wp_customize->add_setting( 'panel_' . $i, array(
			'default'           => false,
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		) );

		$wp_customize->add_control( 'panel_' . $i, array(
			/* translators: %d is the front page section number */
			'label'           => sprintf( __( 'Front Page Section %d Content', 'zuul-lite' ), $i ),
			'description'     => ( 1 !== $i ? '' : __( 'Select pages to feature in each area from the dropdowns. Empty sections will not be displayed.', 'zuul-lite' ) ),
			'section'         => 'zuul_front_page',
			'type'            => 'dropdown-pages',
			'allow_addition'  => true,
			'active_callback' => 'zuul_is_static_front_page',
			'priority'        => 10,
		) );

		$wp_customize->selective_refresh->add_partial( 'panel_' . $i, array(
			'selector'            => '#panel' . $i,
			'render_callback'     => 'zuul_front_page_section',
			'container_inclusive' => true,
		) );
	}

	/**
	 * Homepage Portfolio Count
	 */
	$wp_customize->add_setting( 'zuul_home_portfolio_count', array(
		'default'           => '2',
		'capability'        => 'edit_theme_options',
		'type'              => 'theme_mod',
		'sanitize_callback' => 'absint',
	));

	$wp_customize->add_control( 'zuul_home_portfolio_count_select', array(
		'settings'        => 'zuul_home_portfolio_count',
		'label'           => esc_html__( 'Number of portfolio items to show:', 'zuul-lite' ),
		'section'         => 'zuul_front_page',
		'type'            => 'select',
		'priority'        => 20,
		'active_callback' => 'zuul_jetpack_callback',
		'choices'         => array(
			'1'  => '1',
			'2'  => '2',
			'3'  => '3',
			'4'  => '4',
			'5'  => '5',
			'6'  => '6',
			'7'  => '7',
			'8'  => '8',
			'9'  => '9',
			'10' => '10',
		),
	));


	/**
	 * Homepage Testimonial Count
	 */
	$wp_customize->add_setting( 'zuul_home_testimonial_count', array(
		'default'           => '2',
		'capability'        => 'edit_theme_options',
		'type'              => 'theme_mod',
		'sanitize_callback' => 'absint',
	));

	$wp_customize->add_control( 'zuul_home_testimonial_count_select', array(
		'settings'        => 'zuul_home_testimonial_count',
		'label'           => esc_html__( 'Number of testimonials to show:', 'zuul-lite' ),
		'section'         => 'zuul_front_page',
		'type'            => 'select',
		'priority'        => 30,
		'active_callback' => 'zuul_jetpack_callback',
		'choices'         => array(
			'1'  => '1',
			'2'  => '2',
			'3'  => '3',
			'4'  => '4',
			'5'  => '5',
			'6'  => '6',
			'7'  => '7',
			'8'  => '8',
			'9'  => '9',
			'10' => '10',
		),
	));


	/**
	 * Homepage Blog Count
	 */
	$wp_customize->add_setting( 'zuul_home_blog_count', array(
		'default'           => '4',
		'capability'        => 'edit_theme_options',
		'type'              => 'theme_mod',
		'sanitize_callback' => 'absint',
	));

	$wp_customize->add_control( 'zuul_home_blog_count_select', array(
		'settings'        => 'zuul_home_blog_count',
		'label'           => esc_html__( 'Number of blog posts to show:', 'zuul-lite' ),
		'section'         => 'zuul_front_page',
		'type'            => 'select',
        'active_callback' => 'zuul_is_static_front_page',
		'priority'        => 40,
		'choices'         => array(
			'1'  => '1',
			'2'  => '2',
			'3'  => '3',
			'4'  => '4',
			'5'  => '5',
			'6'  => '6',
			'7'  => '7',
			'8'  => '8',
			'9'  => '9',
			'10' => '10',
		),
	));

	/**
	 * Homepage Features Count
	 */
	$wp_customize->add_setting( 'zuul_home_features_count', array(
		'default'           => '6',
		'capability'        => 'edit_theme_options',
		'type'              => 'theme_mod',
		'sanitize_callback' => 'absint',
	));

	$wp_customize->add_control( 'zuul_home_features_count_select', array(
		'settings'        => 'zuul_home_features_count',
		'label'           => esc_html__( 'Number of features to show:', 'zuul-lite' ),
		'section'         => 'zuul_front_page',
		'type'            => 'select',
        'active_callback' => 'zuul_is_static_front_page',
		'priority'        => 50,
		'choices'         => array(
			'99'  => esc_html__( 'Show All', 'zuul-lite' ),
			'1'  => '1',
			'2'  => '2',
			'3'  => '3',
			'4'  => '4',
			'5'  => '5',
			'6'  => '6',
			'7'  => '7',
			'8'  => '8',
			'9'  => '9',
			'10' => '10',
		),
	));

    // Add Navigation width setting
	$wp_customize->add_setting( 'zuul_show_mobile_nav', array(
		'default' => '960',
		'sanitize_callback' => 'absint',
		'transport' => 'refresh',
	) );

    // Add Navigation Width Control
	$wp_customize->add_control( 'zuul_show_mobile_nav', array(
		'type'			=> 'number',
		'priority'		=> 50,
		'section'		=> 'title_tagline',
		'label'			=> __( 'Mobile Navigation Control', 'zuul-lite' ),
		'description'	=> __( 'Set the width at which point the mobile menu button shows and the main navigation disappears.', 'zuul-lite' ),
		'input_attrs' => array(
            'min'   => 320,
        ),
	) );

}
add_action( 'customize_register', 'zuul_customize_register' );


/**
 * Adjust header height based on theme option
 */
function zuul_css_output() {
	// Theme Options
    $gradient = esc_html( get_theme_mod( 'zuul_gradient' ) );
    $primary = esc_html( get_theme_mod( 'zuul_primary_color', '#9599e2' ) );
    $footer = esc_html( get_theme_mod( 'zuul_footer_background', '#333333' ) );
    $logo_size = esc_html( get_theme_mod( 'zuul_logo_size', 120 ) ) . 'px' ;
    $mobile_width = esc_html( get_theme_mod( 'zuul_show_mobile_nav', 960 ) ) . 'px';

	// Check for styles before outputting
	if ( $gradient || $primary || $footer || $logo_size ) {

	wp_enqueue_style( 'zuul-style', get_stylesheet_uri() );

	$zuul_custom_css = "

    @media screen and (min-width: $mobile_width) {
		.hamburger-button {
		    display: none;
		}
		.nv-main-navigation {
			display: block;
		}
	}

    .nv-site-branding img {
        max-width: $logo_size;
    }

	.nv-site-header,
    blockquote:after,
    .entry-footer,
    .zuul-callout,
    .zuul-sticky,
    .zuul-item figure, .zuul-product figure,
    .zuul-first-post,
    .zuul-post,
    .zuul-product-overlay:hover figure,
    .zuul-testimonial,
    .zuul-end-grid,
    .zuul-product-overlay.zull-overlay-always-on,
    .zuul-product.product:hover,
    .zuul-single-author-meta .zuul-single-author-bio {
		$gradient
	}

    h1.zuul-title-alt, h2.zuul-title-alt, h3.zuul-title-alt, h4.zuul-title-alt, h5.zuul-title-alt, h6.zuul-title-alt,
    h6,
    .zuul-button,
    .zuul-button:visited,
    .zuul-button.zuul-button-alt:hover, .zuul-button.zuul-button-alt:focus,
    .zuul-button.zuul-button-outline-white:hover, .zuul-button.zuul-button-outline-white:focus,
    .zuul-button.zuul-button-outline-primary,
    .zuul-button.zuul-button-outline-primary:visited,
    .zuul-more:hover, .zuul-more:focus,
    .nav-next a:hover,
    .nav-next a:focus,
    .nav-previous a:hover, .nav-previous a:focus,
    .zuul-more,
    .nav-previous a,
    .nav-next a,
    .zuul-more:visited,
    .nav-previous a:visited,
    .nav-next a:visited,
    #zuul-infinite #infinite-handle span button:hover, #zuul-infinite #infinite-handle span button:focus,
    button:hover, button:focus, button:active,
    input[type=\"button\"]:hover,
    input[type=\"button\"]:focus,
    input[type=\"button\"]:active,
    input[type=\"reset\"]:hover,
    input[type=\"reset\"]:focus,
    input[type=\"reset\"]:active,
    input[type=\"submit\"]:hover,
    input[type=\"submit\"]:focus,
    input[type=\"submit\"]:active,
    a, a.zuul-alt:hover, a.zuul-alt:focus, a.zuul-alt:active,
    .nv-main-navigation .nv-callout:hover a, .nv-main-navigation .nv-callout:focus a, .nv-main-navigation .nv-callout.focus a,
    .widget_calendar table a,
    .site-footer a:hover, .site-footer a:focus,
    .entry-content:not(.wc-tab) a:not(.button):not(.share-icon):hover, .entry-content:not(.wc-tab) a:not(.button):not(.share-icon):focus,
    .entry-content:not(.wc-tab) a:not(.button):not(.share-icon):hover, .entry-content:not(.wc-tab) a:not(.button):not(.share-icon):focus,
    .zuul-author-meta .zuul-author-name:hover, .zuul-author-meta .zuul-author-name:focus,
    .comment-list a,
    .comment-list a:hover, .comment-list a:focus, .comment-list a:visited,
    .comment-list .zuul-author-meta a:hover, .comment-list .zuul-author-meta a:focus,
    .comment-list .comment .reply a:hover, .comment-list .comment .reply a:focus,
    .comment-list .pingback .reply a:hover,
    .comment-list .pingback .reply a:focus,
    .comment-list .comment-meta a:hover, .comment-list .comment-meta a:focus,
    .comment-respond #cancel-comment-reply-link:hover, .comment-respond #cancel-comment-reply-link:focus,
    .search-content .entry-title a:hover, .search-content .entry-title a:focus,
    div.wpforms-container-full .wpforms-form input[type=submit]:hover, div.wpforms-container-full .wpforms-form input[type=submit]:focus, div.wpforms-container-full .wpforms-form input[type=submit]:active, div.wpforms-container-full .wpforms-form button[type=submit]:hover, div.wpforms-container-full .wpforms-form button[type=submit]:focus, div.wpforms-container-full .wpforms-form button[type=submit]:active, div.wpforms-container-full .wpforms-form .wpforms-page-button:hover, div.wpforms-container-full .wpforms-form .wpforms-page-button:focus, div.wpforms-container-full .wpforms-form .wpforms-page-button:active,
    .entry-content div.wpforms-container-full .wpforms-form input[type=submit]:hover,
    .entry-content div.wpforms-container-full .wpforms-form input[type=submit]:focus,
    .entry-content div.wpforms-container-full .wpforms-form input[type=submit]:active,
    .entry-content div.wpforms-container-full .wpforms-form button[type=submit]:hover,
    .entry-content div.wpforms-container-full .wpforms-form button[type=submit]:focus,
    .entry-content div.wpforms-container-full .wpforms-form button[type=submit]:active,
    .entry-content div.wpforms-container-full .wpforms-form .wpforms-page-button:hover,
    .entry-content div.wpforms-container-full .wpforms-form .wpforms-page-button:focus,
    .entry-content div.wpforms-container-full .wpforms-form .wpforms-page-button:active,
    .widget div.wpforms-container-full .wpforms-form input[type=submit]:hover,
    .widget div.wpforms-container-full .wpforms-form input[type=submit]:focus,
    .widget div.wpforms-container-full .wpforms-form input[type=submit]:active,
    .widget div.wpforms-container-full .wpforms-form button[type=submit]:hover,
    .widget div.wpforms-container-full .wpforms-form button[type=submit]:focus,
    .widget div.wpforms-container-full .wpforms-form button[type=submit]:active,
    .widget div.wpforms-container-full .wpforms-form .wpforms-page-button:hover,
    .widget div.wpforms-container-full .wpforms-form .wpforms-page-button:focus,
    .widget div.wpforms-container-full .wpforms-form .wpforms-page-button:active,
    .woocommerce-message a.button:hover,
    .woocommerce-message a.button:focus
    .woocommerce-error a.button:hover,
    .woocommerce-error a.button:focus,
    .woocommerce-info a.button:hover,
    .woocommerce-info a.button:focus,
    .zuul-loop-heading a:hover, .zuul-loop-heading a:focus, .zuul-loop-heading a:active,
    .zuul-single-author-meta a:hover, .zuul-single-author-meta a:focus,
    .entry-footer .cat-links i, .entry-footer .tags-links i {
        color: $primary;
    }

    .zuul-button.zuul-button-alt,
    .zuul-button.zuul-button-outline-primary:hover, .zuul-button.zuul-button-outline-primary:focus,
    .zuul-more:before,
    .nav-next a:before,
    .nav-previous a:after,
    #zuul-infinite #infinite-handle span button,
    button,
    input[type=\"button\"],
    input[type=\"reset\"],
    input[type=\"submit\"],
    .zuul-pagination .page-numbers.current,
    .woocommerce-pagination ul.page-numbers .page-numbers.current,
    .widget a:before,
    .comment-list a:before,
    .search-content .entry-title a:after,
    .entry-content:not(.wc-tab) a:not(.button):not(.share-icon):before,
    div.wpforms-container-full .wpforms-form input[type=submit], div.wpforms-container-full .wpforms-form button[type=submit], div.wpforms-container-full .wpforms-form .wpforms-page-button,
    .entry-content div.wpforms-container-full .wpforms-form input[type=submit], .entry-content div.wpforms-container-full .wpforms-form button[type=submit], .entry-content div.wpforms-container-full .wpforms-form .wpforms-page-button,
    .widget div.wpforms-container-full .wpforms-form input[type=submit], .widget div.wpforms-container-full .wpforms-form button[type=submit], .widgett div.wpforms-container-full .wpforms-form .wpforms-page-button,
    body #rcp_subscription_levels .rcp_subscription_level input[type=radio]:checked + label,
    .zuul-testimonial-alt .zuul-testimonial-alt-content--footer .zuul-author-meta-testimonial:before  {
        background: $primary;
    }

    h1.zuul-title-border:before, h2.zuul-title-border:before, h3.zuul-title-border:before, h4.zuul-title-border:before, h5.zuul-title-border:before, h6.zuul-title-border:before,
    .zuul-button.zuul-button-alt,
    .zuul-button.zuul-button-outline-primary,
    #zuul-infinite #infinite-handle span button,
    button,
    input[type=\"button\"],
    input[type=\"reset\"],
    input[type=\"submit\"],
    div.wpforms-container-full .wpforms-form input[type=submit], div.wpforms-container-full .wpforms-form button[type=submit], div.wpforms-container-full .wpforms-form .wpforms-page-button,
    div.wpforms-container-full .wpforms-form input[type=submit]:hover, div.wpforms-container-full .wpforms-form input[type=submit]:focus, div.wpforms-container-full .wpforms-form input[type=submit]:active, div.wpforms-container-full .wpforms-form button[type=submit]:hover, div.wpforms-container-full .wpforms-form button[type=submit]:focus, div.wpforms-container-full .wpforms-form button[type=submit]:active, div.wpforms-container-full .wpforms-form .wpforms-page-button:hover, div.wpforms-container-full .wpforms-form .wpforms-page-button:focus, div.wpforms-container-full .wpforms-form .wpforms-page-button:active,
    .entry-content div.wpforms-container-full .wpforms-form input[type=submit], .entry-content div.wpforms-container-full .wpforms-form button[type=submit], .entry-content div.wpforms-container-full .wpforms-form .wpforms-page-button,
    .widget div.wpforms-container-full .wpforms-form input[type=submit], .widget div.wpforms-container-full .wpforms-form button[type=submit], .widgett div.wpforms-container-full .wpforms-form .wpforms-page-button,
    .entry-content div.wpforms-container-full .wpforms-form input[type=submit]:hover,
    .entry-content div.wpforms-container-full .wpforms-form input[type=submit]:focus,
    .entry-content div.wpforms-container-full .wpforms-form input[type=submit]:active,
    .entry-content div.wpforms-container-full .wpforms-form button[type=submit]:hover,
    .entry-content div.wpforms-container-full .wpforms-form button[type=submit]:focus,
    .entry-content div.wpforms-container-full .wpforms-form button[type=submit]:active,
    .entry-content div.wpforms-container-full .wpforms-form .wpforms-page-button:hover,
    .entry-content div.wpforms-container-full .wpforms-form .wpforms-page-button:focus,
    .entry-content div.wpforms-container-full .wpforms-form .wpforms-page-button:active,
    .widget div.wpforms-container-full .wpforms-form input[type=submit]:hover,
    .widget div.wpforms-container-full .wpforms-form input[type=submit]:focus,
    .widget div.wpforms-container-full .wpforms-form input[type=submit]:active,
    .widget div.wpforms-container-full .wpforms-form button[type=submit]:hover,
    .widget div.wpforms-container-full .wpforms-form button[type=submit]:focus,
    .widget div.wpforms-container-full .wpforms-form button[type=submit]:active,
    .widget div.wpforms-container-full .wpforms-form .wpforms-page-button:hover,
    .widget div.wpforms-container-full .wpforms-form .wpforms-page-button:focus,
    .widget div.wpforms-container-full .wpforms-form .wpforms-page-button:active {
        border-color: $primary;
    }

    .content-with-sidebar .widget:hover::before {
        border-top-color: $primary;
        border-right-color: $primary;
    }

    .content-with-sidebar .widget:hover::after {
        border-bottom-color: $primary;
        border-left-color: $primary;
    }

    .site-footer {
        background: $footer;
    }

	";
	wp_add_inline_style( 'zuul-style', $zuul_custom_css );
} }
add_action( 'wp_enqueue_scripts', 'zuul_css_output' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function zuul_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function zuul_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Render the hero title for the selective refresh partial.
 *
 * @return void
 */
function zuul_hero_title_partial() {
    return get_theme_mod( 'zuul_hero_title' );
}

/**
 * Render the hero description for the selective refresh partial.
 *
 * @return void
 */
function zuul_hero_desc_partial() {
    return get_theme_mod( 'zuul_hero_desc' );
}

/**
 * Render the hero cta for the selective refresh partial.
 *
 * @return void
 */
function zuul_hero_cta_partial() {
    return get_theme_mod( 'zuul_hero_cta' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function zuul_customize_preview_js() {
	wp_enqueue_script( 'zuul_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20171101', true );
}
add_action( 'customize_preview_init', 'zuul_customize_preview_js' );
