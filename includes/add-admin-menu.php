<?php
/**
 * Register a custom menu page
 */
if ( ! class_exists( 'WooCommerce' ) ) {
	add_action( 'admin_menu', 'techiepress_register_woo_admin_menu' );
}

function techiepress_register_woo_admin_menu() {
	add_submenu_page(
		'woocommerce', 
		__( 'SMS Plugin Settings', 'textdomain' ),
		'Bulk SMS Settings', 
		'manage_options', 
		'wc-settings&tab=cashleo_sms',
		'sukuma_admin_stuff'
	); 
}

function sukuma_admin_stuff() {

	$sms_balance = 0;

	if ( function_exists( 'get_account_balance' ) ) {
		get_account_balance();
		$sms_balance = get_option( 'tpress_account_balance' );
	}
	
	echo '<h3>SMS Balance: UGX <span style="background-color: green; padding: 0 8px; color: white;">' . $sms_balance . '</span><h3>';
	echo '<hr>';
}

/**
 * This function introduces the theme options into the 'Appearance' menu and into a top-level 
 * 'Sandbox Theme' menu.
 */
function wbsm_general_admin_menu() {

	add_menu_page(
		'Bulk SMS',			 // The value used to populate the browser's title bar when the menu page is active
		'Bulk SMS',		     // The text of the menu in the administrator's sidebar
		'administrator',	 // What roles are able to access the menu
		'wbsm_theme_menu',	 // The ID used to bind submenu items to this menu 
		'wbsm_theme_display' // The callback function used to render this menu
	);

} 

// end wbsm_general_admin_menu
add_action( 'admin_menu', 'wbsm_general_admin_menu' );

/**
 * Renders a simple page to display for the theme menu defined above.
 */
function wbsm_theme_display() {
?>
	<!-- Create a header in the default WordPress 'wrap' container -->
	<div class="wrap">
	
		<div id="icon-themes" class="icon32"></div>
		<h2><?php _e( 'Bulk & WooCommerce SMS Notifications.', 'wbsm-sms-manager' ); ?></h2>
		
		<?php settings_errors(); ?>
		
		<form method="post" action="options.php">
			<?php

				if ( function_exists( 'sukuma_admin_stuff' ) ) {
					sukuma_admin_stuff();
				}
				echo '<p>To Purchase more SMS credit, send mm to 0782886702 with your account name to top up your credit.</p>';
				
				echo '<p>This section lets you enter your SMS Account Credentials. Use your login credentials for your <a href="https://sms.sukumasms.com" target="_blank">SMS account here.</a> If you don\'t an account visit <a href="https://sms.sukumasms.com/register.php"  target="_blank">Sukuma SMS to register.</a></p>';

				echo '<hr>';		

				settings_fields( 'wbsm_notifications_settings' );
				do_settings_sections( 'wbsm_notifications_settings' );
				submit_button();
			?>
		</form>
		
	</div><!-- /.wrap -->
<?php
} // end wbsm_theme_display

/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */ 
/**
 * Provides default values for the Input Options.
 */
function wbsm_theme_default_input_options() {
	
	$defaults = array(
		'wbsm_user_name'        => '',
		'wbsm_user_password'    => '',
		'wbsm_admin_phone'	    => '',
		'wbsm_sender_id'        => '',
		'bulk_sms_usage'	    => '',
		'woo_order_status_sms'	=> '',
		'woo_order_notes_sms'	=> '',
	);
	
	return apply_filters( 'wbsm_theme_default_input_options', $defaults );
	
} // end wbsm_theme_default_input_options

/**
 * Initializes the theme's display options page by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */ 
function wbsm_theme_initialize_inputs() {

	if( false == get_option( 'wbsm_notifications_settings' ) ) {	
		add_option( 'wbsm_notifications_settings', apply_filters( 'wbsm_theme_default_input_options', wbsm_theme_default_input_options() ) );
	} // end if

	add_settings_section(
		'wbsm_account_settings',
		__( 'Account Settings', 'wbsm-sms-manager' ),
		'wbsm_account_settings_callback',
		'wbsm_notifications_settings'
	);
	
	add_settings_field(	
		'Account Username',						
		__( 'Account Username', 'wbsm-sms-manager' ),							
		'wbsm_user_name_callback',	
		'wbsm_notifications_settings',	
		'wbsm_account_settings'			
	);

	add_settings_field(	
		'Account Password',						
		__( 'Account Password', 'wbsm-sms-manager' ),							
		'wbsm_user_password_callback',	
		'wbsm_notifications_settings',	
		'wbsm_account_settings'			
	);

	add_settings_field(	
		'Admin Phone Number',						
		__( 'Admin Phone Number', 'wbsm-sms-manager' ),							
		'wbsm_admin_phone_callback',	
		'wbsm_notifications_settings',	
		'wbsm_account_settings'			
	);

	add_settings_field(	
		'Sender ID',						
		__( 'Sender ID', 'wbsm-sms-manager' ),							
		'wbsm_sender_id_callback',	
		'wbsm_notifications_settings',	
		'wbsm_account_settings'			
	);
	
	add_settings_field(
		'Bulk SMS',
		__( 'Bulk SMS', 'wbsm-sms-manager' ),
		'wbsm_bulk_sms_usage_callback',
		'wbsm_notifications_settings',
		'wbsm_account_settings'
	);

	add_settings_field(
		'WooCommerce Order Status',
		__( 'WooCommerce Order Status', 'wbsm-sms-manager' ),
		'wbsm_woo_order_status_sms_callback',
		'wbsm_notifications_settings',
		'wbsm_account_settings'
	);

	add_settings_field(
		'WooCommerce Order Notes',
		__( 'WooCommerce Order Notes', 'wbsm-sms-manager' ),
		'wbsm_woo_order_notes_sms_callback',
		'wbsm_notifications_settings',
		'wbsm_account_settings'
	);
	
	register_setting(
		'wbsm_notifications_settings',
		'wbsm_notifications_settings',
		'wbsm_theme_validate_inputs'
	);

} // end wbsm_theme_initialize_inputs
add_action( 'admin_init', 'wbsm_theme_initialize_inputs' );

/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * 
 * This function provides a simple description for the Input Examples page.
 *
 * It's called from the 'wbsm_theme_initialize_inputs_options' function by being passed as a parameter
 * in the add_settings_section function.
 */
function wbsm_account_settings_callback() {
	echo '<p>' . __( 'All fields should be filled correctly to enable your SMS to work.', 'wbsm-sms-manager' ) . '</p>';
} // end wbsm_general_options_callback

/* ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------ */ 

function wbsm_user_name_callback() {
	
	$options = get_option( 'wbsm_notifications_settings' );
	
	$wbsm_user_name = ! empty( $options['wbsm_user_name'] ) ? $options['wbsm_user_name'] : '';
	echo '<input type="text" id="wbsm_user_name" name="wbsm_notifications_settings[wbsm_user_name]" value="' . $wbsm_user_name . '" /><span class="description">Add your SukumaSMS Account Username.</span>';
	
} // end wbsm_user_name_callback

function wbsm_user_password_callback() {
	
	$options = get_option( 'wbsm_notifications_settings' );

	$wbsm_user_password = ! empty( $options['wbsm_user_password'] ) ? $options['wbsm_user_password'] : '';
	echo '<input type="text" id="wbsm_user_password" name="wbsm_notifications_settings[wbsm_user_password]" value="' . $wbsm_user_password . '" /><span class="description">Add your SukumaSMS Account Password.</span>';
	
} // end wbsm_user_password_callback

function wbsm_admin_phone_callback() {
	
	$options = get_option( 'wbsm_notifications_settings' );
	
	$phone = ! empty( $options['wbsm_admin_phone'] ) ? $options['wbsm_admin_phone'] : '';
	echo '<input type="text" id="wbsm_admin_phone" name="wbsm_notifications_settings[wbsm_admin_phone]" value="' . $phone . '" /><span class="description">Add your Administrative SMS Number.</span>';
	
} // end wbsm_admin_phone_callback

function wbsm_sender_id_callback() {
	
	$options = get_option( 'wbsm_notifications_settings' );
	$blogname = get_option( 'blogname' );
	
	$wbsm_sender_id = ! empty( $options['wbsm_sender_id'] ) ? $options['wbsm_sender_id'] : $blogname;
	echo '<input type="text" id="wbsm_sender_id" name="wbsm_notifications_settings[wbsm_sender_id]" value="' . $wbsm_sender_id . '" /><span class="description">Add your default sender ID. This can be your Business Name or anything else.</span>';
	
} // end wbsm_sender_id_callback

function wbsm_bulk_sms_usage_callback() {

	$options = get_option( 'wbsm_notifications_settings' );

	$bulk_sms_usage = ! empty( $options['bulk_sms_usage'] ) ? $options['bulk_sms_usage'] : '';
	$html = '<input type="checkbox" id="bulk_sms_usage" name="wbsm_notifications_settings[bulk_sms_usage]" value="1"' . checked( 1, $bulk_sms_usage, false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="bulk_sms_usage">Turn on Default Bulk SMS Usage</label>';
	
	echo $html;

} // end wbsm_bulk_sms_usage_callback

function wbsm_woo_order_status_sms_callback() {

	$options = get_option( 'wbsm_notifications_settings' );

	$woo_order_status_sms = ! empty( $options['woo_order_status_sms'] ) ? $options['woo_order_status_sms'] : '';
	$html = '<input type="checkbox" id="woo_order_status_sms" name="wbsm_notifications_settings[woo_order_status_sms]" value="1"' . checked( 1, $woo_order_status_sms, false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="woo_order_status_sms">Turn on WooCommerce Order Notes Change SMS Usage</label>';
	
	echo $html;

} // end wbsm_woo_order_notes_sms_callback

function wbsm_woo_order_notes_sms_callback() {

	$options = get_option( 'wbsm_notifications_settings' );

	$woo_order_notes_sms = ! empty( $options['woo_order_notes_sms'] ) ? $options['woo_order_notes_sms'] : '';
	$html = '<input type="checkbox" id="woo_order_notes_sms" name="wbsm_notifications_settings[woo_order_notes_sms]" value="1"' . checked( 1, $woo_order_notes_sms, false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="woo_order_notes_sms">Turn on WooCommerce Order Notes Change SMS Usage</label>';
	
	echo $html;

} // end wbsm_woo_order_notes_sms_callback

/* ------------------------------------------------------------------------ *
 * Setting Callbacks
 * ------------------------------------------------------------------------ */ 
function wbsm_theme_validate_inputs( $input ) {

	// Create our array for storing the validated options
	$output = array();
	
	// Loop through each of the incoming options
	foreach( $input as $key => $value ) {
		
		// Check to see if the current option has a value. If so, process it.
		if( isset( $input[$key] ) ) {
		
			// Strip all HTML and PHP tags and properly handle quoted strings
			$output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
			
		} // end if
		
	} // end foreach
	
	// Return the array processing any additional functions filtered by this action
	return apply_filters( 'wbsm_theme_validate_inputs', $output, $input );

} // end wbsm_theme_validate_inputs
