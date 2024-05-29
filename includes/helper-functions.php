<?php
/**
 * Functions used in general.
 */

/**
 * Send SMS to API.
 */
function sukuma_send_sms_data( string $send_to_sms_number = 'NULL', string $send_message = 'NULL', string $sender_id = 'NULL' ) {

	if ( 'NULL' === $send_to_sms_number || 'NULL' === $send_message ) {
		return;
	}

	$msgdata = array(
		'method' => 'SendSms',
		'userdata' => array(
			'username' => SOTVSW_USERNAME,
			'password' => SOTVSW_PASSWORD,
		),
		'msgdata' => array(
			array(
				'number' => $send_to_sms_number,
				'message' => $send_message,
				'senderid' => $sender_id,
			),
		)
	);
	
	$url = 'http://sms.sukumasms.com/api/v1/json/';

	$arguments = array(
		'method' => 'POST',
		'body'   => json_encode( $msgdata ),
	);

	$response = wp_remote_post( $url, $arguments );
	
	if ( is_wp_error( $response ) ) {
		$error_message = $response->get_error_message();
		return "Something went wrong: $error_message";
	}
	
	$status   = wp_remote_retrieve_response_code( $response );
	$response =  wp_remote_retrieve_body( $response );
	
	update_option( 'sms_result', $response );

	store_inside_sms_cpt( $msgdata, $response, $status );

	get_account_balance();

}

function store_inside_sms_cpt( $data_to_send_api, $response, $status ) {
	
	$results = json_decode( $response );

	$cost = $results->Cost;

	if ( ( NULL !== $cost ) || ! empty ( $cost ) ) {
		 $results->Cost;
	} else {
		$cost = 0;
	}
	
	// Create post object
	$sms_response_post = array(
		'post_title'    => wp_strip_all_tags( $data_to_send_api['msgdata'][0]['senderid'] . ' - ' . $data_to_send_api['msgdata'][0]['number'] ),
		'post_type'     => 'sms',
		'post_status'   => 'publish',
		'meta_input'    => array(
			'sender_id_field_meta_key'      => $data_to_send_api['msgdata'][0]['senderid'],
			'sender_numbers_field_meta_key' => $data_to_send_api['msgdata'][0]['number'],
			'sender_msg_field_meta_key'     => $data_to_send_api['msgdata'][0]['message'],
			'sms_sent_status_meta_key'      => $results->Status . (isset($results->Message) ?  ' - ' . $results->Message : ''),
			'sms_cost_meta_key'             => $cost,
		),
	);
   
  // Insert the post into the database
  wp_insert_post( $sms_response_post );
}

/**
 * Get Account Balance from API.
 */
function get_account_balance() {

	// $sms_balance = 0;
	$tpress_account_balance = get_option( 'tpress_account_balance' );

	$args = array(
		'method' => 'Balance',
		'userdata' => array(
			'username' => SOTVSW_USERNAME,
			'password' => SOTVSW_PASSWORD,
		)
	);

	$url = 'http://sms.sukumasms.com/api/v1/json/';
	
	$arguments = array(
		'method' => 'POST',
		'body' => json_encode($args),
	);

	$response = wp_remote_post( $url, $arguments );

	if ( is_wp_error( $response ) ) {
		$error_message = $response->get_error_message();
		echo '<p>' . 'Something went wrong:' .  $error_message;
		echo '<p>Balance: ' . $tpress_account_balance . '<p>';
		return;
	}
	
	// only 200 to pass and status = OK
	if ( ! 200 === wp_remote_retrieve_response_code( $response ) ) {
		return;
	}
	
	$balance = json_decode( wp_remote_retrieve_body($response) );

	if ( 'Failed' === $balance->Status && $balance->Message ) {
		echo '<p>Message: ' . $balance->Message . ' Kindly check your account settings.<p>';
		update_option( 'tpress_account_balance' , '0' );
		return;
	}

	if ( 'OK' === $balance->Status ) {
		$sms_balance = number_format( $balance->Balance, 0 );
	}
	
	if( $sms_balance === $tpress_account_balance ) {
		return;
	}
	
	update_option( 'tpress_account_balance' , $sms_balance );
	
	update_the_database_balance();
}

function qualify_phone_number( $phone ) {
	// Confirm not empty.
	if( ! empty( $phone ) ) {
		// Remove all chars but numbers
		$phone = preg_replace( '/[^0-9]/', '', $phone );

		// Check that is is 12 characters 
		// if more return
		// if less 
		// Check for first 256 
		// existing return
		// else add missing return $phone
		return $phone;
	}
}