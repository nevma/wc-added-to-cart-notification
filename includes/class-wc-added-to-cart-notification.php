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
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WCATCN_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

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
	 * @since    1.0.1
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

		$this->plugin_name = 'wc-added-to-cart-notification';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->load_options();
		$this->define_admin_hooks();
		$this->define_public_hooks();

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
	 * - WCATCN_Loader. Orchestrates the hooks of the plugin.
	 * - WCATCN_i18n. Defines internationalization functionality.
	 * - WCATCN_Admin. Defines all hooks for the admin area.
	 * - WCATCN_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wcatcn-loader.php';

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

		$this->loader = new WCATCN_Loader();

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

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Load the plugin's options
	 *
	 * @since  1.0.1
	 * @access private
	 */
	private function load_options() {

		$this->options = get_option( 'wcatcn_options', $this->get_default_options() );

	}

	/**
	 * Update the plugin's options
	 *
	 * @since  1.0.1
	 * @param array The new options
	 * @access private
	 */
	private function update_options( $options ) {

		return update_option( 'wcatcn_options', $options );

	}

	/**
	 * Get the plugin's default options
	 *
	 * @since  1.0.1
	 * @return array The default options
	 * @access private
	 */
	private function get_default_options() {

		$defaults = array(
			'notification' => array(
				'autoClose'                   => true,
				'deactivationTimeout'         => 5000,
				'deactivationTimeoutExtended' => 1000,
				'debug'                       => false,	
			),
		);

		return $defaults;

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new WCATCN_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new WCATCN_Public( $this->get_plugin_name(), $this->get_version(), $this->options );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_footer', $plugin_public, 'display' );
		$this->loader->add_action( 'woocommerce_add_to_cart_fragments', $plugin_public, 'filter_cart_fragments' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
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
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WCATCN_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
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
