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
            <p><?php printf( __( 'Thanks for using %s. To get the most out of your theme check the <a href="%s">Getting Started</a> page. For even more features and WooCommerce support go <a href="%s">Pro</a>.', 'zuul-lite' ), __( 'Zuul Lite', 'zuul-lite' ), admin_url( "themes.php?page=zuul-lite-start" ), esc_url('https://themes.pizza/downloads/zuul-pro') ); ?></p>
            <p><?php printf( __( 'Sign up to get important product updates and information from <a href="%s">Themes.Pizza</a>.', 'zuul-lite'), esc_url('https://themes.pizza') ); ?></p>

            <!-- Begin MailChimp Signup Form -->
            <link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
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
            <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='BIRTHDAY';ftypes[3]='birthday';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
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

 	// Getting Started styles
 	wp_register_style( 'zuul-getting-started', get_template_directory_uri() . '/inc/admin/getting-started/getting-started.css', false, '1.0.0' );
 	wp_enqueue_style( 'zuul-getting-started' );

 	// Thickbox
 	add_thickbox();
 }
 add_action( 'admin_enqueue_scripts', 'zuul_start_load_admin_scripts' );

class Zuul_Theme_Updater_Admin {

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
			'remote_api_url' => 'https://themes.pizza',
			'theme_slug' => get_template(),
			'item_name' => '',
			'license' => '',
			'version' => '',
			'author' => '',
			'download_id' => '',
			'renew_url' => '',
			'beta' => false,
		) );

		// Set config arguments
		$this->remote_api_url = $config['remote_api_url'];
		$this->item_name = $config['item_name'];
		$this->theme_slug = sanitize_key( $config['theme_slug'] );
		$this->version = $config['version'];
		$this->author = $config['author'];
		$this->download_id = $config['download_id'];
		$this->renew_url = $config['renew_url'];
		$this->beta = $config['beta'];

		// Populate version fallback
		if ( '' == $config['version'] ) {
			$theme = wp_get_theme( $this->theme_slug );
			$this->version = $theme->get( 'Version' );
		}

		// Strings passed in from the updater config
		$this->strings = $strings;

		add_action( 'init', array( $this, 'updater' ) );
		add_action( 'admin_init', array( $this, 'register_option' ) );
		add_action( 'admin_init', array( $this, 'license_action' ) );
		add_action( 'admin_menu', array( $this, 'license_menu' ) );
		add_action( 'update_option_' . $this->theme_slug . '_license_key', array( $this, 'activate_license' ), 10, 2 );
		add_filter( 'http_request_args', array( $this, 'disable_wporg_request' ), 5, 2 );

	}

	/**
	 * Creates the updater class.
	 *
	 * since 1.0.0
	 */
	function updater() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		/* If there is no valid license key status, don't allow updates. */
		if ( get_option( $this->theme_slug . '_license_key_status', false) != 'valid' ) {
			return;
		}

		if ( !class_exists( 'Zuul_Theme_Updater' ) ) {
			// Load our custom theme updater
			include( dirname( __FILE__ ) . '/theme-updater-class.php' );
		}

		new Zuul_Theme_Updater(
			array(
				'remote_api_url' 	=> $this->remote_api_url,
				'version' 			=> $this->version,
				'license' 			=> trim( get_option( $this->theme_slug . '_license_key' ) ),
				'item_name' 		=> $this->item_name,
				'author'			=> $this->author,
				'beta'              => $this->beta
			),
			$this->strings
		);
	}

	/**
	 * Adds a menu item for the theme license under the appearance menu.
	 *
	 * since 1.0.0
	 */
	function license_menu() {

		$strings = $this->strings;

		add_theme_page(
			$strings['theme-license'],
			$strings['theme-license'],
			'manage_options',
			$this->theme_slug . '-start',
			array( $this, 'license_page' )
		);
	}

	/**
	 * Outputs the markup used on the theme license page.
	 *
	 * since 1.0.0
	 */
	function license_page() {

        $strings = $this->strings;

		$license = trim( get_option( $this->theme_slug . '_license_key' ) );
		$status = get_option( $this->theme_slug . '_license_key_status', false );

		// Checks license status to display under license key
		if ( ! $license ) {
			$message    = $strings['enter-key'];
		} else {
			// delete_transient( $this->theme_slug . '_license_message' );
			if ( ! get_transient( $this->theme_slug . '_license_message', false ) ) {
				set_transient( $this->theme_slug . '_license_message', $this->check_license(), ( 60 * 60 * 24 ) );
			}
			$message = get_transient( $this->theme_slug . '_license_message' );
		}

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
                            <link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
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
                            <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='BIRTHDAY';ftypes[3]='birthday';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
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

	/**
	 * Registers the option used to store the license key in the options table.
	 *
	 * since 1.0.0
	 */
	function register_option() {
		register_setting(
			$this->theme_slug . '-license',
			$this->theme_slug . '_license_key',
			array( $this, 'sanitize_license' )
		);
	}

	/**
	 * Sanitizes the license key.
	 *
	 * since 1.0.0
	 *
	 * @param string $new License key that was submitted.
	 * @return string $new Sanitized license key.
	 */
	function sanitize_license( $new ) {

		$old = get_option( $this->theme_slug . '_license_key' );

		if ( $old && $old != $new ) {
			// New license has been entered, so must reactivate
			delete_option( $this->theme_slug . '_license_key_status' );
			delete_transient( $this->theme_slug . '_license_message' );
		}

		return $new;
	}

	/**
	 * Makes a call to the API.
	 *
	 * @since 1.0.0
	 *
	 * @param array $api_params to be used for wp_remote_get.
	 * @return array $response decoded JSON response.
	 */
	 function get_api_response( $api_params ) {

		// Call the custom API.
		$verify_ssl = (bool) apply_filters( 'edd_sl_api_request_verify_ssl', true );
		$response   = wp_remote_post( $this->remote_api_url, array( 'timeout' => 15, 'sslverify' => $verify_ssl, 'body' => $api_params ) );

		// Make sure the response came back okay.
		if ( is_wp_error( $response ) ) {
			wp_die( $response->get_error_message(), __( 'Error', 'zuul-lite' ) . $response->get_error_code() );
		}

		return $response;
	 }

	/**
	 * Activates the license key.
	 *
	 * @since 1.0.0
	 */
	function activate_license() {

		$license = trim( get_option( $this->theme_slug . '_license_key' ) );

		// Data to send in our API request.
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_name'  => urlencode( $this->item_name ),
			'url'        => home_url()
		);

		$response = $this->get_api_response( $api_params );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'zuul-lite' );
			}

		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success ) {

				switch( $license_data->error ) {

					case 'expired' :

						$message = sprintf(
							__( 'Your license key expired on %s.', 'zuul-lite' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'revoked' :

						$message = __( 'Your license key has been disabled.', 'zuul-lite' );
						break;

					case 'missing' :

						$message = __( 'Invalid license.', 'zuul-lite' );
						break;

					case 'invalid' :
					case 'site_inactive' :

						$message = __( 'Your license is not active for this URL.', 'zuul-lite' );
						break;

					case 'item_name_mismatch' :

						$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'zuul-lite' ), $args['name'] );
						break;

					case 'no_activations_left':

						$message = __( 'Your license key has reached its activation limit.', 'zuul-lite' );
						break;

					default :

						$message = __( 'An error occurred, please try again.', 'zuul-lite' );
						break;
				}

				if ( ! empty( $message ) ) {
					$base_url = admin_url( 'themes.php?page=' . $this->theme_slug . '-license' );
					$redirect = add_query_arg( array( 'sl_theme_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

					wp_redirect( $redirect );
					exit();
				}

			}

		}

		// $response->license will be either "active" or "inactive"
		if ( $license_data && isset( $license_data->license ) ) {
			update_option( $this->theme_slug . '_license_key_status', $license_data->license );
			delete_transient( $this->theme_slug . '_license_message' );
		}

		wp_redirect( admin_url( 'themes.php?page=' . $this->theme_slug . '-license' ) );
		exit();

	}

	/**
	 * Deactivates the license key.
	 *
	 * @since 1.0.0
	 */
	function deactivate_license() {

		// Retrieve the license from the database.
		$license = trim( get_option( $this->theme_slug . '_license_key' ) );

		// Data to send in our API request.
		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license,
			'item_name'  => urlencode( $this->item_name ),
			'url'        => home_url()
		);

		$response = $this->get_api_response( $api_params );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'zuul-lite' );
			}

		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if ( $license_data && ( $license_data->license == 'deactivated' ) ) {
				delete_option( $this->theme_slug . '_license_key_status' );
				delete_transient( $this->theme_slug . '_license_message' );
			}

		}

		if ( ! empty( $message ) ) {
			$base_url = admin_url( 'themes.php?page=' . $this->theme_slug . '-license' );
			$redirect = add_query_arg( array( 'sl_theme_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

			wp_redirect( $redirect );
			exit();
		}

		wp_redirect( admin_url( 'themes.php?page=' . $this->theme_slug . '-license' ) );
		exit();

	}

	/**
	 * Constructs a renewal link
	 *
	 * @since 1.0.0
	 */
	function get_renewal_link() {

		// If a renewal link was passed in the config, use that
		if ( '' != $this->renew_url ) {
			return $this->renew_url;
		}

		// If download_id was passed in the config, a renewal link can be constructed
		$license_key = trim( get_option( $this->theme_slug . '_license_key', false ) );
		if ( '' != $this->download_id && $license_key ) {
			$url = esc_url( $this->remote_api_url );
			$url .= '/checkout/?edd_license_key=' . $license_key . '&download_id=' . $this->download_id;
			return $url;
		}

		// Otherwise return the remote_api_url
		return $this->remote_api_url;

	}



	/**
	 * Checks if a license action was submitted.
	 *
	 * @since 1.0.0
	 */
	function license_action() {

		if ( isset( $_POST[ $this->theme_slug . '_license_activate' ] ) ) {
			if ( check_admin_referer( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' ) ) {
				$this->activate_license();
			}
		}

		if ( isset( $_POST[$this->theme_slug . '_license_deactivate'] ) ) {
			if ( check_admin_referer( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' ) ) {
				$this->deactivate_license();
			}
		}

	}

	/**
	 * Checks if license is valid and gets expire date.
	 *
	 * @since 1.0.0
	 *
	 * @return string $message License status message.
	 */
	function check_license() {

		$license = trim( get_option( $this->theme_slug . '_license_key' ) );
		$strings = $this->strings;

		$api_params = array(
			'edd_action' => 'check_license',
			'license'    => $license,
			'item_name'  => urlencode( $this->item_name ),
			'url'        => home_url()
		);

		$response = $this->get_api_response( $api_params );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = $strings['license-status-unknown'];
			}

		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// If response doesn't include license data, return
			if ( !isset( $license_data->license ) ) {
				$message = $strings['license-status-unknown'];
				return $message;
			}

			// We need to update the license status at the same time the message is updated
			if ( $license_data && isset( $license_data->license ) ) {
				update_option( $this->theme_slug . '_license_key_status', $license_data->license );
			}

			// Get expire date
			$expires = false;
			if ( isset( $license_data->expires ) && 'lifetime' != $license_data->expires ) {
				$expires = date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) );
				$renew_link = '<a href="' . esc_url( $this->get_renewal_link() ) . '" target="_blank">' . $strings['renew'] . '</a>';
			} elseif ( isset( $license_data->expires ) && 'lifetime' == $license_data->expires ) {
				$expires = 'lifetime';
			}

			// Get site counts
			$site_count = $license_data->site_count;
			$license_limit = $license_data->license_limit;

			// If unlimited
			if ( 0 == $license_limit ) {
				$license_limit = $strings['unlimited'];
			}

			if ( $license_data->license == 'valid' ) {
				$message = $strings['license-key-is-active'] . ' ';
				if ( isset( $expires ) && 'lifetime' != $expires ) {
					$message .= sprintf( $strings['expires%s'], $expires ) . ' ';
				}
				if ( isset( $expires ) && 'lifetime' == $expires ) {
					$message .= $strings['expires-never'];
				}
				if ( $site_count && $license_limit ) {
					$message .= sprintf( $strings['%1$s/%2$-sites'], $site_count, $license_limit );
				}
			} else if ( $license_data->license == 'expired' ) {
				if ( $expires ) {
					$message = sprintf( $strings['license-key-expired-%s'], $expires );
				} else {
					$message = $strings['license-key-expired'];
				}
				if ( $renew_link ) {
					$message .= ' ' . $renew_link;
				}
			} else if ( $license_data->license == 'invalid' ) {
				$message = $strings['license-keys-do-not-match'];
			} else if ( $license_data->license == 'inactive' ) {
				$message = $strings['license-is-inactive'];
			} else if ( $license_data->license == 'disabled' ) {
				$message = $strings['license-key-is-disabled'];
			} else if ( $license_data->license == 'site_inactive' ) {
				// Site is inactive
				$message = $strings['site-is-inactive'];
			} else {
				$message = $strings['license-status-unknown'];
			}

		}

		return $message;
	}

	/**
	 * Disable requests to wp.org repository for this theme.
	 *
	 * @since 1.0.0
	 */
	function disable_wporg_request( $r, $url ) {

		// If it's not a theme update request, bail.
		if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) ) {
 			return $r;
 		}

 		// Decode the JSON response
 		$themes = json_decode( $r['body']['themes'] );

 		// Remove the active parent and child themes from the check
 		$parent = get_option( 'template' );
 		$child = get_option( 'stylesheet' );
 		unset( $themes->themes->$parent );
 		unset( $themes->themes->$child );

 		// Encode the updated JSON response
 		$r['body']['themes'] = json_encode( $themes );

 		return $r;
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
