<?php
/**
 * Register a custom menu page
 */
add_action( 'admin_menu', 'techiepress_register_woo_admin_menu' );

function techiepress_register_woo_admin_menu() {
	add_submenu_page(
		'woocommerce', 
		__( 'SMS Plugin Settings', 'textdomain' ),
		'Order SMS Tracking Settings', 
		'manage_options', 
		'wc-settings&tab=wsmsmot_sms',
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
function SOTVSW_general_admin_menu() {

	add_menu_page(
		'Woo Order SMS Tracking',
		'Order SMS',
		'manage_options',
		'SOTVSW_theme_menu',
		'SOTVSW_theme_display',
		'dashicons-location-alt',
		26
	);

} 

// end SOTVSW_general_admin_menu
add_action( 'admin_menu', 'SOTVSW_general_admin_menu' );

/**
 * Renders a simple page to display for the theme menu defined above.
 */
function SOTVSW_theme_display() {
?>
	<!-- Create a header in the default WordPress 'wrap' container -->
	<div class="wrap">
	
		<div id="icon-themes" class="icon32"></div>
		<h2><?php _e( 'Bulk & WooCommerce SMS Notifications.', 'sms-order-tracking-via-sukuma-for-woocommerce' ); ?></h2>
		
		<?php settings_errors(); ?>
		
		<form method="post" action="options.php">
			<?php

				if ( function_exists( 'sukuma_admin_stuff' ) ) {
					sukuma_admin_stuff();
				}
				echo '<p>To Purchase more SMS credit, send mm to 0782886702 with your account name to top up your credit.</p>';
				
				echo '<p>This section lets you enter your SMS Account Credentials. Use your login credentials for your <a href="https://sms.sukumasms.com" target="_blank">SMS account here.</a> If you don\'t an account visit <a href="https://sms.sukumasms.com/register.php"  target="_blank">Sukuma SMS to register.</a></p>';

				echo '<hr>';		

				settings_fields( 'SOTVSW_notifications_settings' );
				do_settings_sections( 'SOTVSW_notifications_settings' );
				submit_button();
			?>
		</form>
		
	</div><!-- /.wrap -->
<?php
} // end SOTVSW_theme_display

/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */ 
/**
 * Provides default values for the Input Options.
 */
function SOTVSW_theme_default_input_options() {
	
	$defaults = array(
		'SOTVSW_user_name'        => '',
		'SOTVSW_user_password'    => '',
		'SOTVSW_admin_phone'	    => '',
		'SOTVSW_sender_id'        => '',
		'bulk_sms_usage'	    => '',
		'woo_order_status_sms'	=> '',
		'woo_order_notes_sms'	=> '',
	);
	
	return apply_filters( 'SOTVSW_theme_default_input_options', $defaults );
	
} // end SOTVSW_theme_default_input_options

/**
 * Initializes the theme's display options page by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */ 
function SOTVSW_theme_initialize_inputs() {

	if( false == get_option( 'SOTVSW_notifications_settings' ) ) {	
		add_option( 'SOTVSW_notifications_settings', apply_filters( 'SOTVSW_theme_default_input_options', SOTVSW_theme_default_input_options() ) );
	} // end if

	add_settings_section(
		'SOTVSW_account_settings',
		__( 'Account Settings', 'sms-order-tracking-via-sukuma-for-woocommerce' ),
		'SOTVSW_account_settings_callback',
		'SOTVSW_notifications_settings'
	);
	
	add_settings_field(	
		'Account Username',						
		__( 'Account Username', 'sms-order-tracking-via-sukuma-for-woocommerce' ),							
		'SOTVSW_user_name_callback',	
		'SOTVSW_notifications_settings',	
		'SOTVSW_account_settings'			
	);

	add_settings_field(	
		'Account Password',						
		__( 'Account Password', 'sms-order-tracking-via-sukuma-for-woocommerce' ),							
		'SOTVSW_user_password_callback',	
		'SOTVSW_notifications_settings',	
		'SOTVSW_account_settings'			
	);

	add_settings_field(	
		'Admin Phone Number',						
		__( 'Admin Phone Number', 'sms-order-tracking-via-sukuma-for-woocommerce' ),							
		'SOTVSW_admin_phone_callback',	
		'SOTVSW_notifications_settings',	
		'SOTVSW_account_settings'			
	);

	add_settings_field(	
		'Sender ID',						
		__( 'Sender ID', 'sms-order-tracking-via-sukuma-for-woocommerce' ),							
		'SOTVSW_sender_id_callback',	
		'SOTVSW_notifications_settings',	
		'SOTVSW_account_settings'			
	);
	
	add_settings_field(
		'Bulk SMS',
		__( 'Bulk SMS', 'sms-order-tracking-via-sukuma-for-woocommerce' ),
		'SOTVSW_bulk_sms_usage_callback',
		'SOTVSW_notifications_settings',
		'SOTVSW_account_settings'
	);

	add_settings_field(
		'SMS Order Status',
		__( 'SMS Order Status', 'sms-order-tracking-via-sukuma-for-woocommerce' ),
		'SOTVSW_woo_order_status_sms_callback',
		'SOTVSW_notifications_settings',
		'SOTVSW_account_settings'
	);

	add_settings_field(
		'SMS Order Notes',
		__( 'SMS Order Notes', 'sms-order-tracking-via-sukuma-for-woocommerce' ),
		'SOTVSW_woo_order_notes_sms_callback',
		'SOTVSW_notifications_settings',
		'SOTVSW_account_settings'
	);
	
	register_setting(
		'SOTVSW_notifications_settings',
		'SOTVSW_notifications_settings',
		'SOTVSW_theme_validate_inputs'
	);

} // end SOTVSW_theme_initialize_inputs
add_action( 'admin_init', 'SOTVSW_theme_initialize_inputs' );

/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * 
 * This function provides a simple description for the Input Examples page.
 *
 * It's called from the 'SOTVSW_theme_initialize_inputs_options' function by being passed as a parameter
 * in the add_settings_section function.
 */
function SOTVSW_account_settings_callback() {
	echo '<p>' . __( 'All fields should be filled correctly to enable your SMS to work.', 'sms-order-tracking-via-sukuma-for-woocommerce' ) . '</p>';
} // end SOTVSW_general_options_callback

/* ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------ */ 

function SOTVSW_user_name_callback() {
	
	$options = get_option( 'SOTVSW_notifications_settings' );
	
	$SOTVSW_user_name = ! empty( $options['SOTVSW_user_name'] ) ? $options['SOTVSW_user_name'] : '';
	echo '<input type="text" id="SOTVSW_user_name" name="SOTVSW_notifications_settings[SOTVSW_user_name]" value="' . $SOTVSW_user_name . '" /><span class="description" style="margin-left:10px; color: #5a5a5a">Add your SukumaSMS Account Username.</span>';
	
} // end SOTVSW_user_name_callback

function SOTVSW_user_password_callback() {
	
	$options = get_option( 'SOTVSW_notifications_settings' );

	$SOTVSW_user_password = ! empty( $options['SOTVSW_user_password'] ) ? $options['SOTVSW_user_password'] : '';
	echo '<input type="password" id="SOTVSW_user_password" name="SOTVSW_notifications_settings[SOTVSW_user_password]" value="' . $SOTVSW_user_password . '" /><span class="description" style="margin-left:10px; color: #5a5a5a">Add your SukumaSMS Account Password.</span>
	<p style="margin-top:10px;"><input type="checkbox" onclick="SOTVSW_password_toggle()">Show Password</p>';
	
} // end SOTVSW_user_password_callback

function SOTVSW_admin_phone_callback() {
	
	$options = get_option( 'SOTVSW_notifications_settings' );
	
	$phone = ! empty( $options['SOTVSW_admin_phone'] ) ? $options['SOTVSW_admin_phone'] : '';
	echo '<input type="text" id="SOTVSW_admin_phone" name="SOTVSW_notifications_settings[SOTVSW_admin_phone]" value="' . $phone . '" /><span class="description" style="margin-left:10px; color: #5a5a5a">Add your Administrative SMS Number.</span>';
	
} // end SOTVSW_admin_phone_callback

function SOTVSW_sender_id_callback() {
	
	$options = get_option( 'SOTVSW_notifications_settings' );
	$blogname = get_option( 'blogname' );
	
	$SOTVSW_sender_id = ! empty( $options['SOTVSW_sender_id'] ) ? $options['SOTVSW_sender_id'] : $blogname;
	echo '<input type="text" id="SOTVSW_sender_id" name="SOTVSW_notifications_settings[SOTVSW_sender_id]" value="' . $SOTVSW_sender_id . '" /><span class="description" style="margin-left:10px; color: #5a5a5a">Add your sender ID. This defaults to your Website Name if not changed.</span>';
	
} // end SOTVSW_sender_id_callback

function SOTVSW_bulk_sms_usage_callback() {

	$options = get_option( 'SOTVSW_notifications_settings' );

	$bulk_sms_usage = ! empty( $options['bulk_sms_usage'] ) ? $options['bulk_sms_usage'] : '';
	$html = '<input type="checkbox" id="bulk_sms_usage" name="SOTVSW_notifications_settings[bulk_sms_usage]" value="1"' . checked( 1, $bulk_sms_usage, false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="bulk_sms_usage">Turn on Default Bulk SMS Usage</label>';
	
	echo $html;

} // end SOTVSW_bulk_sms_usage_callback

function SOTVSW_woo_order_status_sms_callback() {

	$options = get_option( 'SOTVSW_notifications_settings' );

	$woo_order_status_sms = ! empty( $options['woo_order_status_sms'] ) ? $options['woo_order_status_sms'] : '';
	$html = '<input type="checkbox" id="woo_order_status_sms" name="SOTVSW_notifications_settings[woo_order_status_sms]" value="1"' . checked( 1, $woo_order_status_sms, false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="woo_order_status_sms">Send SMS on WooCommerce Order Status Change</label>';
	
	echo $html;

} // end SOTVSW_woo_order_notes_sms_callback

function SOTVSW_woo_order_notes_sms_callback() {

	$options = get_option( 'SOTVSW_notifications_settings' );

	$woo_order_notes_sms = ! empty( $options['woo_order_notes_sms'] ) ? $options['woo_order_notes_sms'] : '';
	$html = '<input type="checkbox" id="woo_order_notes_sms" name="SOTVSW_notifications_settings[woo_order_notes_sms]" value="1"' . checked( 1, $woo_order_notes_sms, false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="woo_order_notes_sms">Send SMS on WooCommerce Order Notes to Customer</label>';
	
	echo $html;

} // end SOTVSW_woo_order_notes_sms_callback

/* ------------------------------------------------------------------------ *
 * Setting Callbacks
 * ------------------------------------------------------------------------ */ 
function SOTVSW_theme_validate_inputs( $input ) {

	// Create our array for storing the validated options
	$output = array();
	
	// Loop through each of the incoming options
	foreach( $input as $key => $value ) {
		
		// Check to see if the current option has a value. If so, process it.
		if( isset( $input[$key] ) ) {
			// Strip all HTML and PHP tags and properly handle quoted strings
			$output[$key] = wp_strip_all_tags( stripslashes( $input[ $key ] ) );
		}
		
	} // end foreach
	
	// Return the array processing any additional functions filtered by this action
	return apply_filters( 'SOTVSW_theme_validate_inputs', $output, $input );

} // end SOTVSW_theme_validate_inputs


add_action( 'admin_footer', 'SOTVSW_toggle_password' );

function SOTVSW_toggle_password() {
	?>
	<script>
		function SOTVSW_password_toggle() {
			var password_box = document.getElementById("SOTVSW_user_password");
			if (password_box.type === "password") {
				password_box.type = "text";
			} else {
				password_box.type = "password";
			}
		}
	</script>
	<?php
}