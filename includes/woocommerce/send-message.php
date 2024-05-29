<?php
/**
 * Send SMS on change of Order Status.
 *
 * @package  woosmsordertracking
 */

// When order sending turned on.
if ( '1' === SOTVSW_WOO_NOTIFICATIONS ) {
	// When Plugins loaded.
	add_action( 'woocommerce_order_status_changed', 'send_sms_onchange_order', 10, 4 );
}

// When Notes sending turned on.
if ( '1' === SOTVSW_WOO_NOTES_SMS ) {
	add_action( 'woocommerce_new_customer_note_notification', 'send_sms_on_new_order_note', 10 );
}


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

		// Get order status.
		$status       = $order->get_status();
		$order_status = ucfirst( $status );

		/**
		 * If the order status is activated, then send the SMS.
		 * sukuma_send_sms_data() Arguments:: number to send to, message, optional sender ID.
		 */
		if ( 'yes' === get_option( 'wc_wsmsmot_order_' . $status ) ) {

			$first_name = $order->get_billing_first_name();
			$phone      = qualify_phone_number( $order->get_billing_phone() );
			$shop_name  = get_option( 'woocommerce_email_from_name' );

			// Default Message.
			$default_message = get_option( 'wc_wsmsmot_default_sms_' . $status );

			// Replacements variables in the default messages.
			$replacements = array(
				'%first_name%'     => $first_name,
				'%last_name%'      => $order->get_billing_last_name(),
				'%phone_number%'   => $phone,
				'%shop_url%'       => get_home_url(),
				'%shop_name%'      => $shop_name,
				'%order_id%'       => $order_id,
				'%order_amount%'   => number_format( $order->get_total() ),
				'%order_status%'   => $order_status,
				'%store_currency%' => $order->get_data()['currency'],
			);

			$message = str_replace( array_keys( $replacements ), $replacements, $default_message );
			
			if ( ! isset( $message ) || empty( $message ) || '' === $message ) {
				$message = "Thank you {$first_name} . Your order # {$order_id} is {$new_status}. {$shop_name}";
			}

			sukuma_send_sms_data( $phone, $message, $shop_name );
		}
	}
}

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
