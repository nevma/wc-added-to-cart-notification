var WCATCN = {};

/**
 * The default settings
 * @property {Object} defaults The default settings values.
 */
WCATCN.defaults = {

	'deactivationTimeout'         : 5000,
	'deactivationTimeoutExtended' : 1000,
	'activationEvent'             : 'added_to_cart',
	'container'                   : '.wcatcn-wrapper',
	'closeButton'                 : '.wcatcn-dismiss',
	'debug'                       : false,

}

WCATCN.init = function() {

	this.options = this.defaults;

	// Cache elements
	this.$body         = jQuery( document.body );
	this.$notification = jQuery( this.options.container );
	this.$closeButton  = jQuery( this.options.closeButton );

	// Bail if notification element doesn't exist
	if ( ! this.$notification.length ) {

		return;

	}

	/*
	 * Register the event handlers
	 */

	// Activate the notification on the added_to_cart event
	this.$body.on( this.options.activationEvent, this.activate.bind( this ) );

	// Dismiss the notification on clicking the close button
	if ( this.$closeButton.length > 0 ) {

		this.$closeButton.on( 'click', this.deactivate.bind( this ) );

	}

	// Postpone the auto deactivation on hover
	jQuery( (function() {

		this.$notification.hover(
			this.cancelDeactivation.bind( this ),
			this.scheduleDeactivationDelayed.bind( this ) );

	}).bind( this ) );

}

/**
 * Activates (shows) the notification, and renews the deactivation
 * timeout.
 */
WCATCN.activate = function() {

	this.log( 'Activating' );

	// Enable the notification
	this.$notification.addClass( 'active' );

	// Schedule deactivation on the main timeout
	this.scheduleDeactivation();
}

/**
 * Deactivates (hides) the notification.
 */
WCATCN.deactivate = function() {

	this.log( 'Deactivating' );

	/*
	 * Cancel a possibly scheduled future deactivation, in case this one was
	 * triggered by clicking on the Hide button.
	 */
	this.cancelDeactivation();

	// Deactivate
	this.$notification.removeClass( 'active' );

	return false;
}

WCATCN.cancelDeactivation = function() {

	clearTimeout( this.timeoutHandler );
}

WCATCN.scheduleDeactivation = function() {

	this.log( 'Scheduling deactivation' );

	// Schedule the deactivation anew
	this.cancelDeactivation();

	this.timeoutHandler = setTimeout( this.deactivate.bind( this ), this.options.deactivationTimeout );
}

WCATCN.scheduleDeactivationDelayed = function() {

	this.log( 'Scheduling delayed deactivation' );

	// Schedule the deactivation anew
	this.cancelDeactivation();

	this.timeoutHandler = setTimeout( this.deactivate.bind( this ), this.options.deactivationTimeoutExtended );
}

WCATCN.log = function( message ) {

	if ( this.options.debug ) {

		console.log( 'WCATCN: ' + message );

	}
}

// Initialise WCATCN on DOMContentLoaded

jQuery( function() {

	WCATCN.init();

});