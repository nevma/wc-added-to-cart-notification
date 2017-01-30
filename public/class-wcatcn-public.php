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

		$this->add_notification_components();

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
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/public.js', array( 'jquery' ), $this->version, true );

	}

	/**
	 * Display the whole output
	 *
	 * @since 1.0.0
	 */
	public function display() {

		// Display only on WooCommerce pages.
		if ( ! apply_filters( 'wcatcn_display', is_woocommerce() ) ) {

			return;

		}

		WCATCN_Loader::get_template( 'wrapper', 'start' );

		do_action( 'wcatcn_display_components' );

		WCATCN_Loader::get_template( 'wrapper', 'end' );

	}

	/**
	 * Display the mini-cart section
	 *
	 * @since  1.0.0
	 */
	public function mini_cart() {

		WCATCN_Loader::get_template( 'mini-cart' );

	}

	/**
	 * Return the mini-cart section as a string
	 *
	 * @since  1.0.0
	 * @return string The markup of the mini-cart section.
	 */
	public function get_mini_cart() {

		ob_start();

		$this->mini_cart();

		return ob_get_clean();
	}

	/**
	 * Display the cross-sells section
	 *
	 * @since 1.0.0
	 */
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

	/**
	 * Return the cross-sells section as a string
	 * 
	 * @since 1.0.0
	 * @return string The markup of the cross-sells section.
	 */
	public function get_cross_sells() {

		ob_start();

		$this->cross_sells();

		return ob_get_clean();

	}

	/**
	 * Filter the number of products when displaying cross-sells.
	 *
	 * @since 1.0.0
	 * @return int The maximum total number of cross-sells products to
	 *             display.
	 */
	public function filter_cross_sells_total() {

		return apply_filters( 'wcatcn_cross_sells_total', 4 );

	}

	/**
	 * Filter the number of columsn when displaying cross-sells.
	 *
	 * @since  1.0.0
	 * @return int The number of columns in which to arrange the cross-sells
	 *             products.
	 */
	public function filter_cross_sells_columns() {

		return apply_filters( 'wcatcn_cross_sells_columns', 4 );

	}

	/**
	 * Filter the cart fragments.
	 *
	 * Filters WooCommerce's cart fragments in order to include the mini-cart
	 * section displayed by the plugin, so that it is updated when needed
	 * (e.g. whenever a product is added to the cart).
	 *
	 * @since  1.0.0
	 * @param  array $fragments The unfiltered fragments.
	 * @return array            The filtered fragments.
	 */
	public function filter_cart_fragments( $fragments ) {

		$fragments['div.wcatcn-mini-cart-container'] = $this->get_mini_cart();

		return $fragments;
		
	}

	/**
	 * Add the notification components to the display action.
	 *
	 * The components are added as actions so as to let plugins and themes
	 * change their order, or disable some or all of them alltogether.
	 *
	 * @since  1.0.0
	 */
	private function add_notification_components() {

		add_action( 'wcatcn_display_components', array( $this, 'mini_cart' ) );
		add_action( 'wcatcn_display_components', array( $this, 'cross_sells' ) );

	}

}
