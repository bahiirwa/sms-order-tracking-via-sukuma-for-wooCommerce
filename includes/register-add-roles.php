<?php
/**
 * Add new user role for SMS Manager.
 *
 * @package SMSOrderTrackingViaSukumaforWoocommerce
 */

/**
 * Set the user roles permissions.
 *
 * @return void
 */
function sms_manager_add_user_role() {
	add_role(
		'sms_manager',
		'SMS Manager',
		array(
			'read'          => true,
			'publish_posts' => true,
			'edit_posts'    => true,
			'delete_posts'  => true,
		)
	);
}

/**
 * Remove new user role for SMS Manager
 */
function sms_manager_deregister_role() {
	remove_role( 'sms_manager' );
}
