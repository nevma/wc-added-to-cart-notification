jQuery( function ( $ ) {

	// The deactivation timeout handler
	var deactivationTimeoutHandler;

	var defaults = {
		'deactivationTimeout'         : 5000,
		'deactivationTimeoutExtended' : 1000,
		'activationEvent'             : 'added_to_cart',
		'element'                     : '.wcatcn-wrapper',
		'dismiss'                     : '.wcatcn-dismiss',
	}

	var options = defaults;

	var $notification = $( options.element );

	if ( $notification.length < 1 ) {

		return;

	}

	/**
	 * Activates (shows) the notification, and renews the deactivation
	 * timeout.
	 */
	function wcatcnActivate() {

		// Prevent deactivation, if scheduled
		clearTimeout( deactivationTimeoutHandler );

		// Enable the notification
		$notification.addClass( 'active' );

		// Set a deactivation timeout anew.
		deactivationTimeoutHandler = setTimeout( wcatcnDeactivate, options.deactivationTimeout );

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

		$notification.removeClass( 'active' );

		clearTimeout( deactivationTimeoutHandler );
	}

	// Activate the notification on the added_to_cart event
	$( document.body ).on( options.activationEvent, wcatcnActivate );

	// Dismiss the notification when the "Hide" option is clicked
	$( options.dismiss ).click( function(e) {

		wcatcnDeactivate();
		
		return false;

	} );

});