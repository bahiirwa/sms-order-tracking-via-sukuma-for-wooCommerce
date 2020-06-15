<?php
/**
 * Send SMS on change of Order Status.
 *
 * @category Plugin
 * @package  woosmsordertracking
 * @author   Laurence Bahiirwa
 * @license  GPL2 or Later
 */

add_action( 'woocommerce_order_status_changed', 'send_sms_onchange_order', 10, 4 );

/**
 * Send SMS on change of order status.
 *
 * @param int    $order_id    Order ID value by WC.
 * @param string $old_status  Previous Order status.
 * @param string $new_status  New Order status.
 * @param array  $order       Order Object.
 * @return void
 */
function send_sms_onchange_order( $order_id, $old_status, $new_status, $order ) {
	// Get $order object from order ID.
	$order = wc_get_order( $order_id );

	if ( $order ) {
		$first_name = $order->get_billing_first_name();
		$phone      = qualify_phone_number( $order->get_billing_phone() );
		$shop_name  = get_option( 'woocommerce_email_from_name' );
		$message    = "Thank you {$first_name} . Your order # {$order_id} is {$new_status}. {$shop_name}";

		// Arguments:: number to send to, message, optional sender ID.
		sukuma_send_sms_data( $phone, $message, $shop_name );
	}
}

add_action( 'woocommerce_new_customer_note_notification', 'send_sms_on_new_order_note', 10 );

/**
 * Send SMS on new order note to customer.
 *
 * @param array $email_args Email arguments.
 * @return void
 */
function send_sms_on_new_order_note( $email_args ) {
	$order         = wc_get_order( $email_args['order_id'] );
	$customer_note = $email_args['customer_note'];

	if ( $order ) {
		$first_name = $order->get_billing_first_name();
		$phone      = qualify_phone_number( $order->get_billing_phone() );
		$shop_name  = get_option( 'woocommerce_email_from_name' );
		$message    = $email_args['customer_note'];

		// Arguments:: number to send to, message, optional sender ID.
		sukuma_send_sms_data( $phone, $message, $shop_name );
	}
}
