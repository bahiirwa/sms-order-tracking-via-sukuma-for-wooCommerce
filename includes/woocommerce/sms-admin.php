<?php
/**
 * Set up the needed variables and logins on admin side.
 */

/**
 * Class WSMSOT_WC_SMS_Admin
 * 
 * @package SMSOrderTrackingViaSukumaforWoocommerce
 */
class WSMSOT_WC_SMS_Admin {

	/**
	 * WSMSOT_WC_SMS_Admin constructor.
	 */
	public function __construct() {
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 100 );
		add_action( 'woocommerce_settings_tabs_wsmsmot_sms', array( $this, 'wsmsmot_sms_settings_tab' ) );
		add_action( 'woocommerce_update_options_wsmsmot_sms', array( $this, 'update_wsmsmot_sms_settings' ) );
	}

	/**
	 * Add WSMSMOT settings tab.
	 *
	 * @param array $settings_tabs Woo Setting Tabs.
	 *
	 * @return array $wsmsmot_settings_tab
	 */
	public function add_settings_tab( $settings_tabs ) {

		$wsmsmot_settings_tab = array();
		foreach ( $settings_tabs as $tab_id => $tab_title ) {
			$wsmsmot_settings_tab[ $tab_id ] = $tab_title;
			if ( 'email' === $tab_id ) {
				$wsmsmot_settings_tab['wsmsmot_sms'] = 'Order SMS Settings';
			}
		}

		return $wsmsmot_settings_tab;
	}

	/**
	 * wsmsmot settings tab custom script.
	 */
	public function wsmsmot_sms_settings_tab() {
		woocommerce_admin_fields( $this->get_wsmsmot_sms_settings() );
	}

	/**
	 * Save wsmsmot settings.
	 */
	public function update_wsmsmot_sms_settings() {
		woocommerce_update_options( $this->get_wsmsmot_sms_settings() );
	}

	/**
	 * WSMSMOT WooCommerce settings.
	 *
	 * @return mixed|void
	 */
	public function get_wsmsmot_sms_settings() {

		$settings = array(
			array(
				'title' => 'SMS Account Balance',
				'type'  => 'title',
				'desc'  => 'Balance: <span style="background-color: green; padding: 0 8px; color: white;">UGX ' . get_option( 'tpress_account_balance' ) . '</span> To Purchase more SMS credit, send mm to 0782886702 with your account name to top up your credit.</a>',
			),
			
			array( 'type' => 'sectionend' ),
			
			array(
				'title' => 'Customer SMS Notifications',
				'type'  => 'title',
				'desc'  => 'This section lets you select the order status changes that will send a SMS notification to customers',
			),

			array(
				'name'          => 'Send SMS Notifications for these order statuses:',
				'id'            => 'wc_wsmsmot_order_pending',
				'desc'          => 'Pending',
				'default'       => 'no',
				'type'          => 'checkbox',
				'checkboxgroup' => 'start',
			),

			array(
				'id'            => 'wc_wsmsmot_order_on_hold',
				'desc'          => 'On-Hold',
				'default'       => 'no',
				'type'          => 'checkbox',
				'checkboxgroup' => '',
			),

			array(
				'id'            => 'wc_wsmsmot_order_processing',
				'desc'          => 'Processing',
				'default'       => 'no',
				'type'          => 'checkbox',
				'checkboxgroup' => '',
			),

			array(
				'id'            => 'wc_wsmsmot_order_completed',
				'desc'          => 'Completed',
				'default'       => 'no',
				'type'          => 'checkbox',
				'checkboxgroup' => '',
			),

			array(
				'id'            => 'wc_wsmsmot_order_cancelled',
				'desc'          => 'Cancelled',
				'default'       => 'no',
				'type'          => 'checkbox',
				'checkboxgroup' => '',
			),

			array(
				'id'            => 'wc_wsmsmot_order_refunded',
				'desc'          => 'Refunded',
				'std'           => 'yes',
				'default'       => 'no',
				'type'          => 'checkbox',
				'checkboxgroup' => '',
			),

			array(
				'id'            => 'wc_wsmsmot_order_failed',
				'desc'          => 'Failed',
				'default'       => 'no',
				'type'          => 'checkbox',
				'checkboxgroup' => 'end',
			),

			array( 'type' => 'sectionend' ),
			
			array( 
				'type' => 'title',
				'name' => 'Default Customer SMS Message',
				'desc' => '<hr>'
			),

			array( 'type' => 'sectionend' ),

			array(
				'id'   => 'wc_wsmsmot_admin_codes-description',
				'name' => 'Use the tags below to customize the Customer Message below:',
				'desc' => '<p>
								<code>%first_name%</code>: Customer\'s First Name
								<code>%last_name%</code>: Customer\'s Last Name
								<code>%phone_number%</code>: Customer\'s Phone Number
								<code>%shop_name%</code>: Shop Name (' . get_bloginfo( 'name' ) . ')
								<code>%shop_url%</code>: Shop URL (' . get_home_url() . ')
								<code>%order_id%</code>: The Order Number
								<code>%order_amount%</code>: The Order Amount
								<code>%store_currency%</code>: The default currency of the store
								<code>%order_status%</code>: The Order Status</p>',
				'type' => 'title',
			),

			array(
				'id'       => 'wc_wsmsmot_default_sms_pending',
				'name'     => 'Pending SMS Message',
				'desc_tip' => 'Enter a custom SMS message to be sent when an order status is changed to pending or leave blank to use the default message above.',
				'css'      => 'min-width:500px;min-height:80px;',
				'type'     => 'textarea',
			),

			array(
				'id'       => 'wc_wsmsmot_default_sms_on_hold',
				'name'     => 'On-Hold SMS Message',
				'desc_tip' => 'Enter a custom SMS message to be sent when an order status is changed to on-hold or leave blank to use the default message above.',
				'css'      => 'min-width:500px;min-height:80px;',
				'type'     => 'textarea',
			),

			array(
				'id'       => 'wc_wsmsmot_default_sms_processing',
				'name'     => 'Processing SMS Message',
				'desc_tip' => 'Enter a custom SMS message to be sent when an order status is changed to processing or leave blank to use the default message above.',
				'css'      => 'min-width:500px;min-height:80px;',
				'type'     => 'textarea',
			),

			array(
				'id'       => 'wc_wsmsmot_default_sms_completed',
				'name'     => 'Completed SMS Message',
				'desc_tip' => 'Enter a custom SMS message to be sent for completed orders or leave blank to use the default message above.',
				'css'      => 'min-width:500px;min-height:80px;',
				'type'     => 'textarea',
			),

			array(
				'id'       => 'wc_wsmsmot_default_sms_cancelled',
				'name'     => 'Cancelled SMS Message',
				'desc_tip' => 'Enter a custom SMS message to be sent when an order status is changed to cancelled or leave blank to use the default message above.',
				'css'      => 'min-width:500px;min-height:80px;',
				'type'     => 'textarea',
			),

			array(
				'id'       => 'wc_wsmsmot_default_sms_refunded',
				'name'     => 'Refunded SMS Message',
				'desc_tip' => 'Enter a custom SMS message to be sent when an order status is changed to refunded or leave blank to use the default message above.',
				'css'      => 'min-width:500px;min-height:80px;',
				'type'     => 'textarea',
			),

			array(
				'id'       => 'wc_wsmsmot_default_sms_failed',
				'name'     => 'Failed SMS Message',
				'desc_tip' => 'Enter a custom SMS message to be sent when an order status is changed to failed or leave blank to use the default message above.',
				'css'      => 'min-width:500px;min-height:80px;',
				'type'     => 'textarea',
			),

			array( 'type' => 'sectionend' ),

		);

		return apply_filters( 'wsmsot_api_details_settings', $settings );
	}

}

new WSMSOT_WC_SMS_Admin();
