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
 * Description:       Provides feedback to the user when a product is added to the cart, by displaying a closeable notification popup. The notification contains the mini-cart, some basic cart info (totals etc), and can be further customised to include extra information.
 * Version:           1.0.0
 * Author:            Nevma
 * Author URI:        https://www.nevma.gr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-added-to-cart-notification
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
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wc_added_to_cart_notification() {

	$plugin = new WC_Added_To_Cart_Notification();
	$plugin->run();

}
run_wc_added_to_cart_notification();
