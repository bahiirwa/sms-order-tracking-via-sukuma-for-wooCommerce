<?php
/**
 * Send SMS on change of Order Status.
 */

// var_dump(get_option( 'wc_cashleo_order_pending'));

add_action( 'woocommerce_order_status_changed', 'send_sms_onchange_order', 10, 4 );

function send_sms_onchange_order( $order_id, $old_status, $new_status, $order ) {

	// Get $order object from order ID
	$order = wc_get_order( $order_id );
	
	if ( $order ) {

		$first_name = $order->get_billing_first_name();
		$phone      = qualify_phone_number( $order->get_billing_phone() );
        $shop_name  = get_option( 'woocommerce_email_from_name');
        
		$message    = 'Thank you ' . $first_name . '. Your order' . ' #' . $order_id . ' '  . 'is' . ' ' . $new_status . '. ' . $shop_name;

		// Arguments:: number to send to, message, optional sender ID
		sukuma_send_sms_data( $phone, $message, $shop_name );

	}

}
add_action( 'woocommerce_new_customer_note_notification', 'send_sms_on_new_order_note', 10);

function send_sms_on_new_order_note($email_args){
	$order = wc_get_order( $email_args['order_id'] );
	$customer_note = $email_args['customer_note'];
	
	if ( $order ) {

		$first_name = $order->get_billing_first_name();
		$phone      = qualify_phone_number( $order->get_billing_phone() );
		$shop_name  = get_option( 'woocommerce_email_from_name');
		
		$message    = $email_args['customer_note'];

		// Arguments:: number to send to, message, optional sender ID
		sukuma_send_sms_data( $phone, $message, $shop_name );

	}
}
