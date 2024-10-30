/**
 * Feedier plugin Saving process
 */
jQuery( document ).ready( function () {

    jQuery( document ).on( 'submit', '#lcs_add_siteid', function ( e ) {

        e.preventDefault();

        jQuery(this).append('<input type="hidden" name="action" value="store_admin_data" />');
        jQuery(this).append('<input type="hidden" name="_nonce" value="'+ livechatsuite_exchanger._nonce +'" />');


        // We make our call
        jQuery.ajax( {
            url: livechatsuite_exchanger.ajax_url,
            type: 'post',
            data: jQuery(this).serialize(),
            success: function (response) {
				//alert(response);// change this to a better modal box with images//
            }
        } );

    } );

} );