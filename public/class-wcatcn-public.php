<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.nevma.gr
 * @since      1.0.0
 *
 * @package    Wc_Added_To_Cart_Notification
 * @subpackage Wc_Added_To_Cart_Notification/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wc_Added_To_Cart_Notification
 * @subpackage Wc_Added_To_Cart_Notification/public
 * @author     Nevma <info@nevma.gr>
 */
class WCATCN_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $is_enabled;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->setup_notification_components();

	}

	/**
	 * Register the stylesheets for the front-end
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the front-end
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/public.js', array( 'jquery' ), $this->version, true );

	}

	public function display() {

		// Display only on WooCommerce pages.
		if ( ! apply_filters( 'wcatcn_display', is_woocommerce() ) ) {

			return;

		}

		WCATCN_Loader::get_template( 'wrapper', 'start' );

		do_action( 'wcatcn_display_components' );

		WCATCN_Loader::get_template( 'wrapper', 'end' );

	}

	public function mini_cart() {

		WCATCN_Loader::get_template( 'mini-cart' );

	}

	public function get_mini_cart() {

		ob_start();

		$this->mini_cart();

		return ob_get_clean();
	}

	public function cross_sells() {

		/**
		 * Total cross-sells items, as well as columns, will be filtered
		 * from within WooCommerce's native template, regardless whatever
		 * values this plugin may set.
		 *
		 * To overcome this, the plugin exposes its own filters for these
		 * values (so that the user doesn't have to deal with priorities and
		 * stuff.
		 *
		 * It then attempts to enforce those filtered values by hooking on
		 * WooCommerce's native filters at an insanely late priority, produce
		 * its output, and then unhook again so that cross-sells displayed at
		 * other areas are not affected by the plugin's options.
		 */
		add_filter( 'woocommerce_cross_sells_total', array( $this, 'filter_cross_sells_total' ), 999 );
		add_filter( 'woocommerce_cross_sells_columns', array( $this, 'filter_cross_sells_columns' ), 999 );

		WCATCN_Loader::get_template( 'cross-sells' );

		remove_filter( 'woocommerce_cross_sells_total', array( $this, 'filter_cross_sells_total' ), 999 );
		remove_filter( 'woocommerce_cross_sells_columns', array( $this, 'filter_cross_sells_columns' ), 999 );

	}

	public function get_cross_sells() {

		ob_start();

		$this->cross_sells();

		return ob_get_clean();
		
	}

	public function filter_cross_sells_total() {

		return apply_filters( 'wcatcn_cross_sells_total', 4 );

	}

	public function filter_cross_sells_columns() {

		return apply_filters( 'wcatcn_cross_sells_columns', 4 );

	}

	public function update_cart_fragments( $fragments ) {

		$fragments['div.wcatcn-mini-cart-container'] = $this->get_mini_cart();

		return $fragments;
		
	}

	private function setup_notification_components() {

		add_action( 'wcatcn_display_components', array( $this, 'mini_cart' ) );
		add_action( 'wcatcn_display_components', array( $this, 'cross_sells' ) );

	}

}
