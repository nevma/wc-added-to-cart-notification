<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.nevma.gr
 * @since      1.0.0
 *
 * @package    Wc_Added_To_Cart_Notification
 * @subpackage Wc_Added_To_Cart_Notification/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wc_Added_To_Cart_Notification
 * @subpackage Wc_Added_To_Cart_Notification/includes
 * @author     Nevma <info@nevma.gr>
 */
class WC_Added_To_Cart_Notification {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The single instance of the class.
	 *
	 * @since    1.0.0
	 * @var      WC_Added_To_Cart_Notification The main instance of this plugin.
	 */
	protected static $_instance;

	/**
	 * The plugin's options.
	 *
	 * @since    1.1.0
	 * @var      array $options The plugin's options
	 */
	protected $options;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	protected function __construct() {

		$this->plugin_name = 'wcatcn';
		$this->version = '1.0.0';

		// Disabled for now since options can only be modified via the theme, with a hook, and thus their loading needs to be delayed until the `after_setup_theme` hook.
		// $this->load_options();
		
		$this->load_dependencies();
		$this->set_locale();
		$this->load_admin_side();

		// Delay the loading of options until the theme has been setup, to allow it to handle theme via a filter.
		add_action( 'after_setup_theme', array( $this, 'load_options' ) );

		// Delay the public-facing side initialization until `template_redirect`, so that all necessary information (user capabilities, conditional tags) is available
		add_action( 'template_redirect', array( $this, 'load_public_side' ) );

	}

	/**
	 * Returns the main plugin's instance.
	 *
	 * Ensures that no more than one instances of the plugin will be created.
	 *
	 * @since 1.0.0
	 * @static
	 * @return The main instance of the plugin.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {

			self::$_instance = new self();

		}

		return self::$_instance;
	}

	/**
	 * Forbids instance cloning.
	 * @since 1.0.0
	 */
	private function __clone() {
	}

	/**
	 * Forbid isntance unserializing.
	 * @since 1.0.0
	 */
	private function __wakeup() {
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WCATCN_Template_Loader. Handles templates for the public-facing side.
	 * - WCATCN_i18n. Defines internationalization functionality.
	 * - WCATCN_Admin. Defines all hooks for the admin area.
	 * - WCATCN_Public. Defines all hooks for the public side of the site.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for handling templates for the public-facing side.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wcatcn-template-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wcatcn-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wcatcn-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wcatcn-public.php';

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WCATCN_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WCATCN_i18n();

		add_action( 'plugins_loaded', array( $plugin_i18n, 'load_plugin_textdomain' ) );

	}

	/**
	 * Load the plugin's options
	 *
	 * @since  1.1.0
	 * @access public
	 */
	public function load_options() {

		$this->options = get_option( 'wcatcn_options', $this->get_default_options() );

		// For now, expose a filter for the plugin's options, as a quick & dirty solution until a proper settings page is impemented.
		$this->options = apply_filters( 'wcatcn_options', $this->options );

	}

	/**
	 * Update the plugin's options
	 *
	 * @since  1.1.0
	 * @param array The new options
	 * @access private
	 */
	private function update_options( $options ) {

		return update_option( 'wcatcn_options', $options );

	}

	/**
	 * Get the plugin's default options
	 *
	 * @since  1.1.0
	 * @return array The default options
	 * @access private
	 */
	private function get_default_options() {

		$defaults = array(
			'enabled'                     => false,
			'preview'                     => false,
			'autoClose'                   => true,
			'deactivationTimeout'         => 5000,
			'deactivationTimeoutExtended' => 1000,
			'debug'                       => false,
		);

		return $defaults;

	}

	/**
	 * Load the admin-facing functionality of the plugin.
	 *
	 * @since    1.1.0
	 * @access   private
	 */
	private function load_admin_side() {

		$plugin_admin = new WCATCN_Admin( $this->get_plugin_name(), $this->get_version() );

		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_scripts' ) );

	}

	/**
	 * Load the public-facing functionality of the plugin.
	 *
	 * @since    1.1.0
	 * @access   public
	 */
	public function load_public_side() {

		$is_enabled = $this->options['enabled'];
		$preview_mode = $this->options['preview'];

		// Bail if plugin is disabled
		if ( ! $is_enabled ) {

			return;

		}

		// Bail if preview mode is enabled but current user lacks privileges
		if ( $preview_mode ) {

			$current_user = wp_get_current_user();

			if ( ! in_array( 'administrator', $current_user->roles )
				&& ! in_array( 'shop_manager', $current_user->roles ) ) {

				return;

			}

		}

		$plugin_public = new WCATCN_Public( $this->get_plugin_name(), $this->get_version(), $this->options );

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
