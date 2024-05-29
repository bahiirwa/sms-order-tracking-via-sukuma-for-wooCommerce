<?php
/**
 * Add plugin action links.
 *
 * @package SMSOrderTrackingViaSukumaforWoocommerce
 */

/**
 * Add plugin action links.
 *
 * Add a link to the settings page on the plugins.php page.
 *
 * @since 0.1.0
 *
 * @param  array $links List of existing plugin action links.
 * @return array         List of modified plugin action links.
 */
function sotvsw_plugin_action_links( $links ) {

	$links = array_merge(
		array(
			'<a href="' . esc_url( admin_url( 'admin.php?page=SOTVSW_theme_menu' ) ) . '">' . __( 'Settings', 'sms-order-tracking-via-sukuma-for-woocommerce' ) . '</a>',
			'<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=cashleo_sms' ) ) . '">' . __( 'WooCommerce Settings', 'sms-order-tracking-via-sukuma-for-woocommerce' ) . '</a>',
		),
		$links
	);

	return $links;

}

add_action( 'plugin_action_links_' . SOTVSW_SMS_BASENAME, 'sotvsw_plugin_action_links' );
