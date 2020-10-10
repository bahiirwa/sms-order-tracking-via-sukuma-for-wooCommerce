<?php
/**
 * Plugin name: SMS Order Tracking via Sukuma for Woocommerce
 * Plugin URI: https://github.com/bahiirwa/sms-order-tracking-via-sukuma-for-woocommerce/
 * Description: Send SMS Notifications to your customers when order status changes or when you make a new order note in your WooCommerce E-Shop.
 * Author: Laurence Bahiirwa
 * Author URI: https://omukiguy.com
 * Version: 1.0.0
 * License: GPL2 or Later.
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text-domain: sms-order-tracking-via-sukuma-for-woocommerce
 *
 * WC requires at least: 3.0
 * WC tested up to: 4.6.1
 *
 * @package SMSOrderTrackingViaSukumaforWoocommerce
 */

// If this file is access directly, abort!!!
defined( 'ABSPATH' ) || die( 'Unauthorized Access' );

if ( ! defined( 'SOTVSW_SMS_FILE' ) ) {
	define( 'SOTVSW_SMS_FILE', __FILE__ );
}
if ( ! defined( 'SOTVSW_SMS_DIR' ) ) {
	define( 'SOTVSW_SMS_DIR', dirname( __FILE__ ) );
}
if ( ! defined( 'SOTVSW_SMS_URL' ) ) {
	define( 'SOTVSW_SMS_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'SOTVSW_SMS_BASENAME' ) ) {
	define( 'SOTVSW_SMS_BASENAME', plugin_basename( __FILE__ ) );
}

// Register your Credentials.
require_once SOTVSW_SMS_DIR . '/includes/plugin-links.php';
require_once SOTVSW_SMS_DIR . '/includes/add-admin-menu.php';

$options              = get_option( 'SOTVSW_notifications_settings' );
$user_name            = ( isset( $options['SOTVSW_user_name'] ) ) ? $options['SOTVSW_user_name'] : '';
$user_password        = ( isset( $options['SOTVSW_user_password'] ) ) ? $options['SOTVSW_user_password'] : '0';
$admin_phone          = ( isset( $options['SOTVSW_admin_phone'] ) ) ? $options['SOTVSW_admin_phone'] : '';
$sender_id            = ( isset( $options['SOTVSW_sender_id'] ) ) ? $options['SOTVSW_sender_id'] : get_bloginfo( 'name' );
$woo_order_status_sms = ( isset( $options['woo_order_status_sms'] ) ) ? $options['woo_order_status_sms'] : '0';
$woo_order_notes_sms  = ( isset( $options['woo_order_notes_sms'] ) ) ? $options['woo_order_notes_sms'] : '0';

// No credentials exist, Don't run these files below.
if ( '' === $user_name || '' === $user_password || empty( $user_name ) || empty( $user_password ) ) {
	return;
}

define( 'SOTVSW_USERNAME', $user_name );
define( 'SOTVSW_PASSWORD', $user_password );
define( 'SOTVSW_ADMIN_PHONE', $admin_phone );
define( 'SOTVSW_SENDER_ID', $sender_id );
define( 'SOTVSW_WOO_NOTIFICATIONS', $woo_order_status_sms );
define( 'SOTVSW_WOO_NOTES_SMS', $woo_order_status_sms );

// require_once SOTVSW_SMS_DIR . '/includes/register-add-roles.php';.
require_once SOTVSW_SMS_DIR . '/includes/helper-functions.php';
require_once SOTVSW_SMS_DIR . '/includes/register-custom-sms-post.php';
require_once SOTVSW_SMS_DIR . '/includes/sms-database-manager.php';

/**
 * Run all the needed functions at the plugin activation.
 *
 * @function one: Create database for storing the account balance.
 * @function two: Create SMS manager role.
 *
 * @return void
 */
function activation_initial_functions() {
	create_sms_database();
	// Add the custom user roles function here - sms_manager_add_user_role();.
	insert_initial_data();
	get_account_balance();
}

register_activation_hook( __FILE__, 'activation_initial_functions' );
register_deactivation_hook( __FILE__, 'sms_manager_deregister_role' );

if ( '1' === SOTVSW_WOO_NOTIFICATIONS || '1' === SOTVSW_WOO_NOTES_SMS ) {
	// When Plugins loaded.
	add_action( 'plugins_loaded', 'woo_sotvsw_init', 0 );
}

/**
 * Return Notice for missing WooCommerce Plugin Install.
 *
 * @return void
 */
function woo_sotvsw_init() {

	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {

		// Check if WooCommerce is active.
		add_action( 'admin_notices', 'sotvsw_woocommerce_not_installed_notice' );
		return;
	}

	require_once SOTVSW_SMS_DIR . '/includes/woocommerce/sms-admin.php';
	require_once SOTVSW_SMS_DIR . '/includes/woocommerce/send-message.php';
}

/**
 * Return a notice for WooCommerce missing.
 *
 * @return void
 */
function sotvsw_woocommerce_not_installed_notice() {
	$message = sprintf(
		/* translators: URL of WooCommerce plugin */
		__( 'WBSM SMS Notifications for WooCommerce plugin requires <a href="%s">WooCommerce</a> 3.0 or greater to be installed and active.', 'sms-order-tracking-via-sukuma-for-woocommerce' ),
		'https://wordpress.org/plugins/woocommerce/'
	);

	printf( '<div class="error notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $message ) );
}
