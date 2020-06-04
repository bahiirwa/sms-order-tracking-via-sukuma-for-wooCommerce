<?php
/**
 * Register a custom menu page
 */
if ( ! class_exists( 'WooCommerce' ) ) {
	add_action( 'admin_menu', 'techiepress_register_woo_admin_menu' );
} else {
	add_action( 'admin_menu', 'techiepress_register_admin_menu' );
}


function techiepress_register_admin_menu() {
	add_menu_page(
		__( 'SMS Plugin Settings', 'textdomain' ),
		'SMS Details',
		'manage_options',
		'sms-plugin.php',
		'sukuma_admin_stuff',
		'dashicons-testimonial',
		85
	);
}

function techiepress_register_woo_admin_menu() {
	add_submenu_page( 
		'woocommerce', 
		__( 'SMS Plugin Settings', 'textdomain' ),
		'SMS Balance', 
		'manage_options', 
		'wc-settings&tab=cashleo_sms',
		'sukuma_admin_stuff',
	); 
}

function sukuma_admin_stuff() {
	
	get_account_balance();
	$sms_balance = number_format( get_option( 'tpress_account_balance' ), 0 );

	echo '<h3>Balance: <span style="background-color: green; padding: 0 8px; color: white;">' . $sms_balance . '</span><h3>';

}