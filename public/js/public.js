jQuery( function ( $ ) {

	var deactivationTimeout;

	function wcatcnActivate() {

		clearTimeout( deactivationTimeout );

		$( '.wcatcn-wrapper' ).addClass( 'active' );

		deactivationTimeout = setTimeout( wcatcnDeactivate, 2000 );

	}

	function wcatcnDeactivate() {

		$( '.wcatcn-wrapper' ).removeClass( 'active' );

		// Clear timeout in case deactivation was triggered by user.
		clearTimeout( deactivationTimeout );
	}

	$( document.body ).on( 'added_to_cart', wcatcnActivate );

	$( '.wcatcn-wrapper .button-close' ).click( wcatcnDeactivate );

});