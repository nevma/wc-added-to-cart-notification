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

	public function update_cart_fragments( $fragments ) {

		$fragments['div.wcatcn-mini-cart-container'] = $this->get_mini_cart();

		return $fragments;
		
	}

	private function setup_notification_components() {

		add_action( 'wcatcn_display_components', array( $this, 'mini_cart' ) );
	}

}
