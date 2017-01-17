<?php

/**
 * Mini-cart
 *
 * This file is used to display the mini-cart section
 *
 * @link       https://www.nevma.gr
 * @since      1.0.0
 *
 * @package    Wc_Added_To_Cart_Notification
 * @subpackage Wc_Added_To_Cart_Notification/public/templates
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

?>

	<div class = "wcatcn-mini-cart-container">
		<?php woocommerce_mini_cart(); ?>
	</div>