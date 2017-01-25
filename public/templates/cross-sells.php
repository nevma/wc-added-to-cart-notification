<?php

/**
 * Cross-sells
 *
 * This file is used to display the cross-sells section
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

	<div class = "wcatcn-cross-sells-container">
		<?php woocommerce_cross_sell_display( 5, 5 ); ?>
	</div>