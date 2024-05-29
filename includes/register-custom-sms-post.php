<?php
/**
 * Set up Messages post type for the Feedback.
 *
 * @package SMSOrderTrackingViaSukumaforWoocommerce.
 */

/**
 * Set up feedback post type.
 *
 * @return void
 */
function wsmsot_sms_setup_post_type() {
	$args = array(
		'public'       => true,
		'label'        => __( 'SMS Report', 'textdomain' ),
		'menu_icon'    => 'dashicons-email-alt',
		'supports'     => array( 'title' ),
		'map_meta_cap' => true,
	);
	register_post_type( 'sms', $args );
}

add_action( 'init', 'wsmsot_sms_setup_post_type' );

/**
 * Add metabox.
 *
 * @return void
 */
function wsmsot_add_custom_box() {
	$screens = array( 'sms' );
	foreach ( $screens as $screen ) {
		add_meta_box(
			'wsmsot_sms_box',
			'SMS Information',
			'wsmsot_show_admin_boxes',
			$screen
		);
	}
}
add_action( 'add_meta_boxes', 'wsmsot_add_custom_box' );

/**
 * Show admin side boxes.
 *
 * @param array $post Post object.
 * @return void
 */
function wsmsot_show_admin_boxes( $post ) {
	?>

	<label for="sender_id_field">Sender ID</label><br>
	<input readonly type="text" name="sender_id_field" id="sender_id_field" class="widefat" value="<?php echo esc_attr( get_post_meta( $post->ID, 'sender_id_field_meta_key', true ) ); ?>"><br><br>

	<label for="sms_cost_field">Cost</label><br>
	<input readonly type="text" name="sms_cost_field" id="sms_cost_field" class="widefat" value="<?php echo esc_attr( get_post_meta( $post->ID, 'sms_cost_meta_key', true ) ); ?>"><br><br>

	<label for="sms_sent_status">Message Status</label><br>
	<input readonly type="text" name="sms_sent_status" id="sms_sent_status" class="widefat" value="<?php echo esc_attr( get_post_meta( $post->ID, 'sms_sent_status_meta_key', true ) ); ?>"><br><br>

	<label for="sender_msg_field">Message</label><br>
	<textarea readonly name="sender_msg_field" id="sender_msg_field" class="widefat"><?php echo esc_attr( get_post_meta( $post->ID, 'sender_msg_field_meta_key', true ) ); ?></textarea><br><br>

	<label for="sender_numbers_field">Send Numbers</label><br>
	<textarea readonly name="sender_numbers_field" id="sender_numbers_field" class="widefat"><?php echo esc_attr( get_post_meta( $post->ID, 'sender_numbers_field_meta_key', true ) ); ?></textarea>

	<?php
}

/**
 * Add custom columns Names.
 *
 * @param array $columns  Columns Object.
 * @return array $columns Columns Object.
 */
function wsmsot_custom_columns_list( $columns ) {

	unset( $columns['title'] );
	unset( $columns['author'] );
	unset( $columns['date'] );

	$columns['sender_id']    = 'Sender ID';
	$columns['sent_message'] = 'Message';
	$columns['number_count'] = 'Number Count';
	$columns['sms_status']   = 'Status';
	$columns['sms_cost']     = 'Cost';

	return $columns;
}

add_filter( 'manage_sms_posts_columns', 'wsmsot_custom_columns_list' );
add_filter( 'manage_sms_posts_custom_column', 'wsmsot_add_custom_column_data', 10, 2 );

/**
 * Add Custom Column data for CPT.
 *
 * @param array $column  Column Name.
 * @param int   $post_id Post ID.
 * @return void
 */
function wsmsot_add_custom_column_data( $column, $post_id ) {

	switch ( $column ) {
		case 'sender_id':
			echo esc_attr( get_post_meta( $post_id, 'sender_id_field_meta_key', true ) );
			break;
		case 'sent_message':
			echo esc_attr( get_post_meta( $post_id, 'sender_msg_field_meta_key', true ) );
			break;
		case 'number_count':
			echo esc_attr( get_post_meta( $post_id, 'sender_numbers_field_meta_key', true ) );
			break;
		case 'sms_status':
			echo esc_attr( get_post_meta( $post_id, 'sms_sent_status_meta_key', true ) );
			break;
		case 'sms_cost':
			echo esc_attr( get_post_meta( $post_id, 'sms_cost_meta_key', true ) );
			break;
	}

}
