jQuery( function ( $ ) {

	// The deactivation timeout handler
	var deactivationTimeout;

	/**
	 * Activates (shows) the notification, and renews the deactivation
	 * timeout.
	 */
	function wcatcnActivate() {

		// Prevent deactivation, if scheduled
		clearTimeout( deactivationTimeout );

		// Enable the notification
		$( '.wcatcn-wrapper' ).addClass( 'active' );

		// Set a deactivation timeout anew.
		deactivationTimeout = setTimeout( wcatcnDeactivate, 5000 );

	}

	/**
	 * Deactivates (hides) the notification, and clears the deactivation
	 * timeout.
	 *
	 * Clearing the deactivation timeout is necessary since the notification
	 * can be dismissed on-demand by the user, thus leaving a stray
	 * deactivation scheduled.
	 */
	function wcatcnDeactivate() {

		$( '.wcatcn-wrapper' ).removeClass( 'active' );

		clearTimeout( deactivationTimeout );
	}

	// Activate the notification on the added_to_cart event
	$( document.body ).on( 'added_to_cart', wcatcnActivate );

	// Dismiss the notification when the "Hide" option is clicked
	$( '.wcatcn-dismiss' ).click( function(e) {

		wcatcnDeactivate();
		
		return false;

	} );

});