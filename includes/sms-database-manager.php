<?php
/**
 * This is the database manager file. Involves creating and updating the database table.
 *
 * @package SMSOrderTrackingViaSukumaforWoocommerce
 */

global $wsmot_db_version;
$wsmot_db_version = '1.0';

/**
 * Creating database table for SMS credits history.
 *
 * @return void
 */
function create_sms_database() {
	global $wpdb;
	global $wsmot_db_version;

	$table_name      = $wpdb->prefix . 'tpress_sms_manager';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		balance tinytext NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	add_option( 'wsmot_db_version', $wsmot_db_version );
}

/**
 * Insert initial data in database SMS credits history.
 *
 * @return void
 */
function insert_initial_data() {
	global $wpdb;

	$table_name = $wpdb->prefix . 'tpress_sms_manager';

	$wpdb->insert(
		$table_name,
		array(
			'time'    => current_time( 'mysql' ),
			'balance' => '0',
		)
	);
}

/**
 * Update database table SMS credits history on SMS sending.
 *
 * @return void
 */
function update_the_database_balance() {
	global $wpdb;

	$table_name = $wpdb->prefix . 'tpress_sms_manager';

	// Get the last entry in the database.
	$results = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id DESC LIMIT 1" );

	if ( ! empty( get_option( 'tpress_account_balance' ) ) ) {
		if ( get_option( 'tpress_account_balance' ) === $results[0]->balance ) {
			return;
		}
	}

	$wpdb->insert(
		$table_name,
		array(
			'time'    => current_time( 'mysql' ),
			'balance' => get_option( 'tpress_account_balance' ),
		)
	);
}
