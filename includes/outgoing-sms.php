<?php
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
			'username' => SMS_ACCOUNT_USERNAME,
			'password' => SMS_ACCOUNT_PASSWORD,
		),
		'msgdata' => array(
			array(
				'number' => $send_to_sms_number,
				'message' => $send_message,
				'senderid' => $sender_id,
			)
		)
	);
	
	$url = 'http://sms.sukumasms.com/api/v1/json/';

	$arguments = array(
		'method' => 'POST',
		'body' => json_encode( $msgdata ),
	);

	$response = wp_remote_post( $url, $arguments );

	
	if ( is_wp_error( $response ) ) {
		$error_message = $response->get_error_message();
		return "Something went wrong: $error_message";
	}
	
	$status = wp_remote_retrieve_response_code($response);
	$response =  wp_remote_retrieve_body($response);
	
	update_option( 'sms_result', $response );

	store_inside_sms_cpt( $data_to_send_api, $response, $status);

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
	$my_post = array(
	// 'post_title'    => wp_strip_all_tags( $_POST['post_title'] ),
	'post_type'     => 'sms',
	'post_status'   => 'publish',
	'meta_input'    => array(
		'sender_id_field_meta_key'  => $data_to_send_api['msgdata'][0]['senderid'],
		'sender_numbers_field_meta_key'  => $data_to_send_api['msgdata'][0]['number'],
		'sender_msg_field_meta_key'  => $data_to_send_api['msgdata'][0]['message'],
		'sms_sent_status_meta_key'  => $results->Status . ' - ' . $results->Message,
		'sms_cost_meta_key'  => $cost,
	),
  );
   
  // Insert the post into the database
  wp_insert_post( $my_post );
}