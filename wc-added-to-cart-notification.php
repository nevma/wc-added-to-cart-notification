<?php

/**
 *
 * @link              https://www.nevma.gr
 * @since             1.0.0
 * @package           Wc_Added_To_Cart_Notification
 *
 * @wordpress-plugin
 * Plugin Name:       Added to cart notification for WooCommerce
 * Plugin URI:        https://github.com/nevma/wc-added-to-cart-notification
 * Description:       Displays a brief, dismissable, responsive notification on the front-end, whenever a product is added to the cart.
 * Version:           1.0.0
 * Author:            Nevma
 * Author URI:        https://www.nevma.gr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wcatcn
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wcatcn-activator.php
 */
function activate_wc_added_to_cart_notification() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wcatcn-activator.php';
	WCATCN_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wcatcn-deactivator.php
 */
function deactivate_wc_added_to_cart_notification() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wcatcn-deactivator.php';
	WCATCN_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wc_added_to_cart_notification' );
register_deactivation_hook( __FILE__, 'deactivate_wc_added_to_cart_notification' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wc-added-to-cart-notification.php';

/**
 * Begins execution of the plugin and returns the plugin's main instance.
 *
 * @return WC_Added_To_Cart_Notification
 * @since    1.0.0
 */
function wcatcn() {

	return WC_Added_To_Cart_Notification::instance();

}

wcatcn()->run();
