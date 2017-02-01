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
	'debug'                       : false,

}

WCATCN.init = function() {

	this.options = this.defaults;

	this.options.debug = true;

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

		this.$notification.on( 'mouseover', this.onMouseOver.bind( this ) );

	}

}

/**
 * Activates (shows) the notification, and renews the deactivation
 * timeout.
 */
WCATCN.activate = function() {

	this.log( 'Activating' );

	// Enable the notification
	this.$notification.addClass( 'active' );

	// Schedule dismissal on the main timeout
	this.scheduleDismissal( this.options.deactivationTimeout );
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

	this.log( 'Deactivating' );

	// Cancel any already scheduled dismissal just in case.
	clearTimeout( this.timeoutHandler );

	// Deactivate
	this.$notification.removeClass( 'active' );

	return false;
}

WCATCN.scheduleDismissal = function( timeout ) {

	this.log( 'Scheduling dismissal in ' + timeout + ' ms' );

	// Cancel any already scheduled dismissals
	clearTimeout( this.timeoutHandler );

	// Schedule anew
	this.timeoutHandler = setTimeout( this.deactivate.bind( this ), timeout );
}

WCATCN.onMouseOver = function() {

	// Schedule dismissal on the extended timeout
	this.scheduleDismissal( this.options.deactivationTimeoutExtended );

}

WCATCN.log = function( message ) {

	if ( this.options.debug ) {

		console.log( 'WCATCN: ' + message );

	}
}


jQuery( function ( $ ) {

	WCATCN.init();

});