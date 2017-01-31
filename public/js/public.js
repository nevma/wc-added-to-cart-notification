var WCATCN = {};

/**
 * The default settings
 * @property {Object} defaults The default settings values.
 */
WCATCN.defaults = {

	'deactivationTimeout'         : 5000,
	'deactivationTimeoutExtended' : 1000,
	'activationEvent'             : 'added_to_cart',
	'element'                     : '.wcatcn-wrapper',
	'dismiss'                     : '.wcatcn-dismiss',

}

WCATCN.init = function() {

	this.options = this.defaults;

	// Cache elements
	this.$body = jQuery( document.body );
	this.$notification = jQuery( this.options.element );
	this.$dismiss = jQuery( this.options.dismiss );

	// Bail if notification element doesn't exist
	if ( ! this.$notification.length ) {

		return;

	}

	/*
	 * Register the event handlers
	 */

	// Activate the notification on the added_to_cart event
	this.$body.on( this.options.activationEvent, this.activate.bind( this ) );

	if ( this.$dismiss.length > 0 ) {

		// Dismiss the notification when the "Hide" option is clicked
		this.$dismiss.on( 'click', this.deactivate.bind( this ) );

	}

}

/**
 * Activates (shows) the notification, and renews the deactivation
 * timeout.
 */
WCATCN.activate = function() {

	// Prevent deactivation, if scheduled
	clearTimeout( this.timeoutHandler );
	
	// Enable the notification
	this.$notification.addClass( 'active' );

	// Set a deactivation timeout anew.
	this.timeoutHandler = setTimeout( this.deactivate, this.options.deactivationTimeout );
}

/**
 * Deactivates (hides) the notification, and clears the deactivation
 * timeout.
 *
 * Clearing the deactivation timeout is necessary since the notification
 * can be dismissed on-demand by the user, thus leaving a stray
 * deactivation scheduled.
 */
WCATCN.deactivate = function() {
	
	this.$notification.removeClass( 'active' );

	clearTimeout( this.timeoutHandler );

	return false;
}


jQuery( function ( $ ) {

	WCATCN.init();

});