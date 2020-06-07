<?php
/**
 * Plugin name: Woocommerce & Bulk SMS Manager
 * Plugin URI: https://omukiguy.com
 * Description: Send Bulk SMS or Add SMS Notifications to your WooCommerce E-Shop.
 * Author: Laurence Bahiirwa
 * Author URI: https://omukiguy.com
 * Version: 0.1.0
 * License: GPL2 or Later.
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: wbsm-sms-manager
 */

// If this file is access directly, abort!!!
defined( 'ABSPATH' ) or die( 'Unauthorized Access' );

if ( ! defined( 'WBSM_SMS_FILE' ) ) {
	define( 'WBSM_SMS_FILE', __FILE__ );
}
if ( ! defined( 'WBSM_SMS_DIR' ) ) {
	define( 'WBSM_SMS_DIR', dirname( __FILE__ ) );
}
if ( ! defined( 'WBSM_SMS_URL' ) ) {
	define( 'WBSM_SMS_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'WBSM_SMS_BASENAME' ) ) {
	define( 'WBSM_SMS_BASENAME', plugin_basename( __FILE__ ) );
}

// Register your Credentials.
require_once WBSM_SMS_DIR . '/includes/plugin-links.php';
require_once WBSM_SMS_DIR . '/includes/add-admin-menu.php';

$options              = get_option( 'wbsm_notifications_settings' );

$user_name            = ( isset( $options['wbsm_user_name'] ) ) ? $options['wbsm_user_name'] : '';
$user_password        = ( isset( $options['wbsm_user_password'] ) ) ? $options['wbsm_user_password'] : '0';
$woo_order_status_sms = ( isset( $options['woo_order_status_sms'] ) ) ? $options['woo_order_status_sms'] : '0';
$woo_order_notes_sms  = ( isset( $options['woo_order_notes_sms'] ) ) ? $options['woo_order_notes_sms'] : '0';

// No credentials exist, Don't run these files below.
if ( '' === $user_name || '' === $user_password || empty( $user_name ) || empty( $user_password ) ) {
	return;
}

define( 'WBSM_USERNAME', $user_name );
define( 'WBSM_PASSWORD', $user_password );
define( 'WBSM_WOO_NOTIFICATIONS', $woo_order_status_sms );
define( 'WBSM_WOO_NOTES_SMS', $woo_order_status_sms );

// require_once WBSM_SMS_DIR . '/includes/register-add-roles.php';
require_once WBSM_SMS_DIR . '/includes/helper-functions.php';
require_once WBSM_SMS_DIR . '/includes/register-custom-sms-post.php';
require_once WBSM_SMS_DIR . '/includes/sms-database-manager.php';

/**
 * Run all the needed functions at the plugin activation
 * 
 * @function one: Create database for storing the account balance
 * @function two: Create SMS manager role.
 */
function activation_initial_functions() {
	create_sms_database();
	// sms_manager_add_user_role();
	insert_initial_data();
	get_account_balance();
}

register_activation_hook( __FILE__ , 'activation_initial_functions' );
register_deactivation_hook( __FILE__ , 'sms_manager_deregister_role' );

if ( '1' === WBSM_WOO_NOTIFICATIONS || '1' === WBSM_WOO_NOTES_SMS ) {
	// When Plugins loaded.
	add_action( 'plugins_loaded', 'woo_wbsm_init', 0 );
}

function woo_wbsm_init() {

	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {

		function woocommerce_not_installed_notice() {
			$message = sprintf(
				/* translators: URL of WooCommerce plugin */
				__( 'WBSM SMS Notifications for WooCommerce plugin requires <a href="%s">WooCommerce</a> 3.0 or greater to be installed and active.', 'WBSM-sms-notifications-for-woo' ),
				'https://wordpress.org/plugins/woocommerce/'
			);
		
			printf( '<div class="error notice notice-error is-dismissible"><p>%s</p></div>', $message ); 
		}

		// Check if WooCommerce is active
		add_action( 'admin_notices', 'woocommerce_not_installed_notice' );
		return;
	}

	require_once WBSM_SMS_DIR . '/includes/woocommerce/sms-admin.php';
	require_once WBSM_SMS_DIR . '/includes/woocommerce/send-message.php';
}