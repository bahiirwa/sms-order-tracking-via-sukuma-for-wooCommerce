<?php
/**
 * Functions used in general.
 */

/**
 * Get Account Balance from API.
 */
function get_account_balance() {

	$sms_balance = 0;
	$tpress_account_balance = get_option( 'tpress_account_balance' );

    $args = array(
        'method' => 'Balance',
        'userdata' => array(
            'username' => SMS_ACCOUNT_USERNAME,
            'password' => SMS_ACCOUNT_PASSWORD,
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

	if ( 'OK' !== $balance->Status && $balance->Message ) {
		echo '<p>Message: ' . $balance->Message . '<p>';
		echo '<p>Balance: ' . $tpress_account_balance . '<p>';
	}

	if ( 'OK' === $balance->Status ) {
		$sms_balance = number_format( $balance->Balance, 0 );
	}
	
	if( $balance->Balance === $tpress_account_balance ) {
		return;
	}
	
	update_option( 'tpress_account_balance' , $sms_balance );
	
	update_the_database_balance();
}