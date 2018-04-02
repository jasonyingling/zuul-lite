<?php
/**
 * Theme updater admin page and functions.
 *
 * @package EDD Sample Theme
 */

 /**
  * Add an admin notice on theme activate
  */
 function zuul_theme_admin_notices() {
    global $pagenow;

  	if ( ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) || ( false === get_option( 'zuul_admin_notice_shown' ) ) ) { ?>

        <?php update_option( 'zuul_admin_notice_shown', true ); ?>
        <div class="notice notice-success is-dismissible">
            <p><?php printf( __( 'Thanks for using %1$s. To get the most out of your theme check the <a href="%2$s">Theme Help</a> page. For even more features and WooCommerce support get <a href="%3$s"><strong>Zuul Pro</strong></a>.', 'zuul-lite' ), __( 'Zuul Lite', 'zuul-lite' ), admin_url( "themes.php?page=zuul-lite-start" ), esc_url('https://themes.pizza/downloads/zuul-pro') ); ?></p>
            <div class="zuul-admin-notice">
                <a class="zuul-admin-button" href="https://www.themes.pizza/downloads/zuul-pro/"><?php echo __('Get Zuul Pro', 'zuul-lite'); ?></a>
            </div>
        </div>

  	<?php }
 }
 add_action( 'admin_notices', 'zuul_theme_admin_notices' );

 /**
  * Load Getting Started styles in the admin
  *
  * since 1.0.0
  */
 function zuul_start_load_admin_scripts() {

 	// Load styles only on our page
 	global $pagenow;
 	if( 'themes.php' != $pagenow )
 		return;

 	/**
 	 * Getting Started scripts and styles
 	 *
 	 * @since 1.0
 	 */

 	// Getting Started javascript
 	wp_enqueue_script( 'zuul-getting-started', get_template_directory_uri() . '/inc/admin/getting-started/getting-started.js', array( 'jquery' ), '1.0.0', true );

 	// Getting Started styles
 	wp_register_style( 'zuul-getting-started', get_template_directory_uri() . '/inc/admin/getting-started/getting-started.css', false, '1.0.0' );
 	wp_enqueue_style( 'zuul-getting-started' );


 	// Thickbox
 	add_thickbox();
 }
 add_action( 'admin_enqueue_scripts', 'zuul_start_load_admin_scripts' );

class Zuul_Theme_Information {

	/**
	 * Variables required for the theme updater
	 *
	 * @since 1.0.0
	 * @type string
	 */
	 protected $remote_api_url = null;
	 protected $theme_slug = null;
	 protected $version = null;
	 protected $author = null;
	 protected $strings = null;

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	function __construct( $config = array(), $strings = array() ) {

		$config = wp_parse_args( $config, array(
			'theme_slug' => get_template(),
			'version' => '',
			'author' => '',
		) );

		// Set config arguments
		$this->theme_slug = sanitize_key( $config['theme_slug'] );
		$this->version = $config['version'];
		$this->author = $config['author'];

		// Populate version fallback
		if ( '' == $config['version'] ) {
			$theme = wp_get_theme( $this->theme_slug );
			$this->version = $theme->get( 'Version' );
		}

		// Strings passed in from the information config
		$this->strings = $strings;

		add_action( 'admin_menu', array( $this, 'theme_info_menu' ) );

	}

	/**
	 * Adds a menu item for the theme information under the appearance menu.
	 *
	 * since 1.0.0
	 */
	function theme_info_menu() {

		$strings = $this->strings;

		add_theme_page(
			$strings['theme-information'],
			$strings['theme-information'],
			'manage_options',
			$this->theme_slug . '-start',
			array( $this, 'theme_info_page' )
		);
	}

	/**
	 * Outputs the markup used on the theme information page.
	 *
	 * since 1.0.0
	 */
	function theme_info_page() {

        $strings = $this->strings;

		// Theme info
		$theme = wp_get_theme( 'zuul-lite' );

		// Lowercase theme name for resources links
		$theme_name_lower = get_template();

		?>

		<div class="wrap getting-started">
			<h2 class="notices"></h2>
			<div class="intro-wrap">
				<div class="intro">
					<h3><?php printf( esc_html__( 'Getting started with %1$s', 'zuul-lite' ), $theme['Name'] ); ?> <span>v.<?php echo $theme['Version'] ?></span></h3>

					<h4><?php printf( esc_html__( 'Zuul Lite is just the beginning. Get even more out of your WordPress with Zuul Pro featuring WooCommerce support.', 'zuul-lite' ), $theme['Name'] ); ?></h4>
                    <p>
                        <a href="https://www.themes.pizza/downloads/zuul-pro/"><?php echo __('Get Zuul Pro', 'zuul-lite'); ?></a>
                    </p>
				</div>
			</div>

			<div class="panels">
				<ul class="inline-list">
					<li class="current"><a id="pro" href="#"><?php esc_html_e( 'Get Zuul Pro', 'zuul-lite' ); ?></a></li>
                    <li><a id="help" href="#"><?php esc_html_e( 'Help File', 'zuul-lite' ); ?></a></li>
					<li><a id="plugins" href="#"><?php esc_html_e( 'Plugins', 'zuul-lite' ); ?></a></li>
				</ul>

				<div id="panel" class="panel">

                    <!-- Go Pro panel -->
					<div id="pro-panel" class="panel-left visible">

						<!-- Output pro upsell options -->
                        <h4><?php esc_html_e( 'Unlock the gateway to more features', 'zuul-lite' ); ?></h4>

						<p><?php esc_html_e( 'Get even more out of Zuul by going Pro. Add WooCommerce support to start selling products today. And make Zuul even more customizable with Advanced Custom Fields support. Plus get access to premium support.', 'zuul-lite' ); ?></p>

                        <div class="zuul-admin-div">
                            <a class="zuul-admin-button" href="https://www.themes.pizza/downloads/zuul-pro/"><?php echo __('Get Zuul Pro', 'zuul-lite'); ?></a>
                        </div>

                        <?php get_template_part( '/inc/admin/information/zuul-pro' ); ?>

                        <div class="zuul-admin-div">
                            <a class="zuul-admin-button" href="https://www.themes.pizza/downloads/zuul-pro/"><?php echo __('Get Zuul Pro', 'zuul-lite'); ?></a>
                        </div>

					</div>

					<!-- Help file panel -->
					<div id="help-panel" class="panel-left">

                        <?php get_template_part( '/inc/admin/information/zuul-help' ); ?>

					</div>

					<!-- Updates panel -->
					<div id="plugins-panel" class="panel-left">
						<h4><?php esc_html_e( 'Recommended Plugins', 'zuul-lite' ); ?></h4>

						<p><?php esc_html_e( 'Below is a list of recommended plugins to help you get the most out of the Zuul Theme. Each plugin is optional, but may be required to fully recreate the demo version of the site.', 'zuul-lite' ); ?></p>

						<hr/>

					<?php
						$plugin_array = array(
							array(
								'slug' => 'jetpack',
							),
							array(
								'slug' => 'shortcodes-ultimate'
							),
							array(
								'slug' => 'wpforms-lite'
							),
						);

						if(class_exists('Connekt_Plugin_Installer')){
							Connekt_Plugin_Installer::init($plugin_array);
						}
					?>
					</div><!-- .panel-left -->


					<div class="panel-right">

                        <a href="https://www.themes.pizza/downloads/zuul-pro/"><img style="max-width: 100%;" src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/inc/admin/information/assets/featured-1000x750.jpg" alt="<?php echo esc_attr( 'Zuul Pro WordPress Theme', 'zuul-lite' ); ?>"></a>
                        <div class="panel-aside">
                            <h4><?php echo __('Get Zuul Pro', 'zuul-lite'); ?></h4>
                            <p><?php echo __('Get even more out of your site by getting Zuul Pro.', 'zuul-lite'); ?></p>
                            <ul>
                                <li><?php echo __('WooCommere support', 'zuul-lite'); ?></li>
                                <li><?php echo __('Customize more with Advanced Custom Fields', 'zuul-lite'); ?></li>
                                <li><?php echo __('Video and Gallery Post Formats', 'zuul-lite'); ?></li>
                                <li><?php echo __('Featured Products', 'zuul-lite'); ?></li>
                                <li><?php echo __('More hooks and features', 'zuul-lite'); ?></li>
                                <li><?php echo __('Priority support', 'zuul-lite'); ?></li>
                            </ul>

                            <div class="zuul-admin-div">
                                <a class="zuul-admin-button" href="https://www.themes.pizza/downloads/zuul-pro/"><?php echo __('Get Zuul Pro', 'zuul-lite'); ?></a>
                            </div>
                        </div>

					</div><!-- .panel-right -->
				</div><!-- .panel -->
			</div><!-- .panels -->
		</div><!-- .getting-started -->

		<?php
	}

}
