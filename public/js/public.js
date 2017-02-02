var WCATCN = {};

/**
 * The default settings
 * @property {Object} defaults The default settings values.
 */
WCATCN.defaults = {

	'autoClose'                   : true,
	'deactivationTimeout'         : 5000,
	'deactivationTimeoutExtended' : 1000,
	'activationEvent'             : 'added_to_cart',
	'container'                   : '.wcatcn-wrapper',
	'closeButton'                 : '.wcatcn-dismiss',
	'debug'                       : false,

}

WCATCN.init = function() {

	/**
	 * Internet Explorer (at least up to version 10) does not support the console
	 * group and groupEnd functions, so we point them to the usual log function.
	 */
	if ( typeof console.group === 'undefined' || typeof console.groupEnd === 'undefined' ) {

		console.group    = console.log;
		console.groupEnd = console.log;

	}


	// Extend the defaults with the options passed from WordPress
	this.options = jQuery.extend( {}, this.defaults, wcatcnOptions );


	// Cache elements
	this.$body         = jQuery( document.body );
	this.$notification = jQuery( this.options.container );
	this.$closeButton  = jQuery( this.options.closeButton );
	
	this.log( 'Initialisation finished.', 'Options', this.options );

}

WCATCN.run = function() {

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
	if ( this.options.autoClose ) {

		jQuery( (function() {

			this.$notification.hover(
				this.cancelDeactivation.bind( this ),
				this.scheduleDeactivationDelayed.bind( this ) );

		}).bind( this ) );

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

	// Schedule deactivation on the main timeout
	if ( this.options.autoClose ) {
		this.scheduleDeactivation();
	}

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

WCATCN.log = function() {

	if ( this.options.debug ) {

		console.group( 'WCATCN' );
		
		for ( var k in arguments ) {

			console.log( arguments[k] );

		}

		console.groupEnd();

	}
}

// Initialise WCATCN on DOMContentLoaded

jQuery( function() {

	WCATCN.init();

	WCATCN.run();

});