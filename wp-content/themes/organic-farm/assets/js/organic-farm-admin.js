( function ( $ ) {
	'use strict';

	// Handle notice dismiss button click
	$( document ).on( 'click', '.notice-info .notice-dismiss', function () {
		var type = $( this ).closest( '.notice-info' ).data( 'notice' );
		if ( ! type ) {
			return;
		}
		$.ajax( {
			type: 'POST',
			url: organic_farm_localize.ajax_url,
			data: {
				action: 'organic_farm_dismissed_notice_handler',
				type: type,
				wpnonce: organic_farm_localize.dismiss_nonce
			}
		} );
	} );

} )( jQuery );