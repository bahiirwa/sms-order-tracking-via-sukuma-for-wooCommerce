<?php
/**
 * Send SMS on change of Order Status.
 *
 * @category Plugin
 * @package  woosmsordertracking
 * @author   Laurence Bahiirwa
 * @license  GPL2 or Later
 */

// When order sending turned on.
if ( '1' === WBSM_WOO_NOTIFICATIONS ) {
	// When Plugins loaded.
	add_action( 'woocommerce_order_status_changed', 'send_sms_onchange_order', 10, 4 );
}

// When Notes sending turned on.
if ( '1' === WBSM_WOO_NOTES_SMS ) {
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
		$first_name = $order->get_billing_first_name();
		$phone      = qualify_phone_number( $order->get_billing_phone() );
		$shop_name  = get_option( 'woocommerce_email_from_name' );
		$message    = "Thank you {$first_name} . Your order # {$order_id} is {$new_status}. {$shop_name}";
		$status     = $order->get_status();

		$send = false;

		// If the order status is activated, then send the SMS.
		if ( 'yes' === get_option( 'wc_cashleo_order_' . $status ) ) {
			
			// Arguments:: number to send to, message, optional sender ID.
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

function wporg_add_custom_box() {
    $screens = ['shop_order' ];
    foreach ($screens as $screen) {
        add_meta_box(
            'wporg_box_id',           // Unique ID
            'Custom Meta Box Title',  // Box title
            'wporg_custom_box_html',  // Content callback, must be of type callable
            $screen                   // Post type
        );
	}
	
}
add_action('add_meta_boxes', 'wporg_add_custom_box');

function wporg_custom_box_html()
{	
	global $post;
	echo '<pre>';
	var_dump( $post );
	echo '</pre>';

	$value = get_post_meta($post->ID );
}