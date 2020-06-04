<?php
/**
 * Send SMS on change of Order Status.
 */
add_action( 'woocommerce_order_status_changed', 'send_sms_onchange_order', 20, 4 );

function send_sms_onchange_order( $order_id, $old_status, $new_status, $order ) {

	// Get $order object from order ID
	$order = wc_get_order( $order_id );
	
	if ( $order ) {

		$first_name = $order->get_billing_first_name();
		$phone      = $order->get_billing_phone();
        $shop_name  = get_option( 'woocommerce_email_from_name');
        
		$message    = 'Thank you ' . $first_name . '. Your order' . ' #' . $order_id . ' '  . 'is' . ' ' . $new_status . '. ' . $shop_name;

		// Arguments:: number to send to, message, optional sender ID
		sukuma_send_sms_data( $phone, $message, $shop_name );

	}

}
