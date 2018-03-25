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
            <p><?php printf( __( 'Thanks for using %1$s. To get the most out of your theme check the <a href="%2$s">Getting Started</a> page. For even more features and WooCommerce support go <a href="%3$s">Pro</a>.', 'zuul-lite' ), __( 'Zuul Lite', 'zuul-lite' ), admin_url( "themes.php?page=zuul-lite-start" ), esc_url('https://themes.pizza/downloads/zuul-pro') ); ?></p>
            <p><?php printf( __( 'Sign up to get important product updates and information from <a href="%s">Themes.Pizza</a>.', 'zuul-lite'), esc_url('https://themes.pizza') ); ?></p>

            <!-- Begin MailChimp Signup Form -->
            <style type="text/css">
                #mc_embed_signup{background:#ffffff; clear:left; font:14px Helvetica,Arial,sans-serif; padding: 0; max-width: 300px; }
                #mc_embed_signup form { padding: 0; }
                #mc_embed_signup .mc-field-group { padding-bottom: 10px; }
                #mc_embed_signup div#mce-responses { margin: 0; padding: 0; }
                #mc_embed_signup div.response { margin-top: 0; padding-top: 0; }
                /* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
                   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
            </style>
            <div id="mc_embed_signup">
            <form action="https://pizza.us17.list-manage.com/subscribe/post?u=70c8e0d050385a46da35a77ef&amp;id=8f30673aa7" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                <div id="mc_embed_signup_scroll">

            <div class="mc-field-group">
                <label for="mce-EMAIL" style="visibility: hidden; height: 4px;">Email Address </label>
                <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="Email Address">
            </div>
            <div class="mc-field-group input-group" style="display: none;">
                <strong>Freemium Themes </strong>
                <ul><li><input type="checkbox" value="1" name="group[3225][1]" id="mce-group[3225]-3225-0" checked><label for="mce-group[3225]-3225-0">Zuul Lite</label></li>
            </ul>
            </div>
                <div id="mce-responses" class="clear">
                    <div class="response" id="mce-error-response" style="display:none"></div>
                    <div class="response" id="mce-success-response" style="display:none"></div>
                </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_70c8e0d050385a46da35a77ef_8f30673aa7" tabindex="-1" value=""></div>
                <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
                </div>
            </form>
            </div>
            <!--End mc_embed_signup-->
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

    //wp_enqueue_script( 'zuul-form', get_template_directory_uri() . '/inc/admin/getting-started/zuul-form.js', array( 'jquery' ), '1.0.0', true );

    //wp_add_inline_script( 'zuul-form', '(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]="EMAIL";ftypes[0]="email";fnames[1]="FNAME";ftypes[1]="text";fnames[2]="LNAME";ftypes[2]="text";fnames[3]="BIRTHDAY";ftypes[3]="birthday";}(jQuery));var $mcj = jQuery.noConflict(true);');

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
	 protected $download_id = null;
	 protected $renew_url = null;
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

		// Strings passed in from the updater config
		$this->strings = $strings;

		add_action( 'admin_menu', array( $this, 'theme_info_menu' ) );

	}

	/**
	 * Adds a menu item for the theme license under the appearance menu.
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
	 * Outputs the markup used on the theme license page.
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
                        <a href="https://www.themes.pizza/downloads/zuul-pro/">Get Zuul Pro</a>
                    </p>
				</div>
			</div>

			<div class="panels">
				<ul class="inline-list">
					<li class="current"><a id="pro" href="#"><?php esc_html_e( 'Go Pro', 'zuul-lite' ); ?></a></li>
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
                            <a class="zuul-admin-button" href="https://www.themes.pizza/downloads/zuul-pro/">Get Zuul Pro</a>
                        </div>

                        <!-- Grab feed of help file -->
						<?php
							include_once( ABSPATH . WPINC . '/feed.php' );

							$upgrade = fetch_feed( 'https://themes.pizza/downloads/zuul-pro/feed/?withoutcomments=1' );

							if ( ! is_wp_error( $upgrade ) ) :
								$maxitems = $upgrade->get_item_quantity( 1 );
								$upgrade_items = $upgrade->get_items( 0, $maxitems );
							endif;

							$upgrade_items_check = array_filter( $upgrade_items );
						?>

						<!-- Output the feed -->
						<?php if ( is_wp_error( $upgrade ) || empty( $upgrade_items_check ) ) : ?>
							<p><?php esc_html_e( 'More information coming soon!', 'zuul-lite' ); ?> <a href="http://themes.pizza/downloads/zuul-pro/" title="View help file"><?php esc_html_e( 'Upgrade to Zuul Pro &rarr;', 'zuul-lite' ); ?></a></p>
						<?php else : ?>
							<?php foreach ( $upgrade_items as $item ) : ?>
								<?php echo $item->get_content(); ?>
							<?php endforeach; ?>
						<?php endif; ?>

                        <div class="zuul-admin-div">
                            <a class="zuul-admin-button" href="https://www.themes.pizza/downloads/zuul-pro/">Get Zuul Pro</a>
                        </div>

					</div>

					<!-- Help file panel -->
					<div id="help-panel" class="panel-left">

						<!-- Grab feed of help file -->
						<?php
							$rss = fetch_feed( 'https://themes.pizza/help/zuul/feed/?withoutcomments=1' );

							if ( ! is_wp_error( $rss ) ) :
								$maxitems = $rss->get_item_quantity( 1 );
								$rss_items = $rss->get_items( 0, $maxitems );
							endif;

							$rss_items_check = array_filter( $rss_items );
						?>

						<!-- Output the feed -->
						<?php if ( is_wp_error( $rss ) || empty( $rss_items_check ) ) : ?>
							<p><?php esc_html_e( 'This help file feed seems to be temporarily down. You can always view the help file online in the meantime.', 'zuul-lite' ); ?> <a href="https://themes.pizza/help/zuul/" title="View help file"><?php echo $theme['Name']; ?> <?php esc_html_e( 'Help File &rarr;', 'zuul-lite' ); ?></a></p>
						<?php else : ?>
							<?php foreach ( $rss_items as $item ) : ?>
								<?php echo $item->get_content(); ?>
							<?php endforeach; ?>
						<?php endif; ?>
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

                        <a href="https://www.themes.pizza/downloads/zuul-pro/"><img style="max-width: 100%;" src="https://www.themes.pizza/wp-content/uploads/edd/2018/01/featured-1000x750.jpg" alt="Zuul Pro WordPress Theme"></a>
                        <div class="panel-aside">
                            <h4>Get 20% off Zuul Pro</h4>
                            <p>Sign up now for product updates and a 20% off code for Zuul Pro!</p>

                            <!-- Begin MailChimp Signup Form -->
                            <style type="text/css">
                                #mc_embed_signup{background:#f4f4f4; clear:left; font:14px Helvetica,Arial,sans-serif; padding: 0; }
                                #mc_embed_signup form { padding: 0; }
                                #mc_embed_signup div#mce-responses { margin: 0; padding: 0; }
                                #mc_embed_signup div.response { margin-top: 0; padding-top: 0; }
                            	/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
                            	   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
                            </style>
                            <div id="mc_embed_signup">
                            <form action="https://pizza.us17.list-manage.com/subscribe/post?u=70c8e0d050385a46da35a77ef&amp;id=8f30673aa7" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                                <div id="mc_embed_signup_scroll">

                            <div class="mc-field-group">
                                <label for="mce-EMAIL" style="visibility: hidden; height: 4px;">Email Address </label>
                            	<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="Email Address">
                            </div>
                            <div class="mc-field-group input-group" style="display: none;">
                                <strong>Freemium Themes </strong>
                                <ul><li><input type="checkbox" value="1" name="group[3225][1]" id="mce-group[3225]-3225-0" checked><label for="mce-group[3225]-3225-0">Zuul Lite</label></li>
                            </ul>
                            </div>
                            	<div id="mce-responses" class="clear">
                            		<div class="response" id="mce-error-response" style="display:none"></div>
                            		<div class="response" id="mce-success-response" style="display:none"></div>
                            	</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_70c8e0d050385a46da35a77ef_8f30673aa7" tabindex="-1" value=""></div>
                                <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
                                </div>
                            </form>
                            </div>
                            <!--End mc_embed_signup-->
                            <div class="zuul-admin-div">
                                <a class="zuul-admin-button" href="https://www.themes.pizza/downloads/zuul-pro/">Get Zuul Pro</a>
                            </div>
                        </div>

					</div><!-- .panel-right -->
				</div><!-- .panel -->
			</div><!-- .panels -->
		</div><!-- .getting-started -->

		<?php
	}

}

/**
 * This is a means of catching errors from the activation method above and displyaing it to the customer
 */
function zuul_sample_theme_admin_notices() {
	if ( isset( $_GET['sl_theme_activation'] ) && ! empty( $_GET['message'] ) ) {

		switch( $_GET['sl_theme_activation'] ) {

			case 'false':
				$message = urldecode( $_GET['message'] );
				?>
				<div class="error">
					<p><?php echo $message; ?></p>
				</div>
				<?php
				break;

			case 'true':
			default:

				break;

		}
	}
}
add_action( 'admin_notices', 'zuul_sample_theme_admin_notices' );
