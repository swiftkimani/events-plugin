<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Swifty_Events_CMS {

	public function init() {
		// Handle RSVP Form Submission
		add_action( 'admin_post_swifty_submit_rsvp', array( $this, 'handle_rsvp_submission' ) );
		add_action( 'admin_post_nopriv_swifty_submit_rsvp', array( $this, 'handle_rsvp_submission' ) );

		// Handle Event Submission Form
		add_action( 'admin_post_swifty_submit_event', array( $this, 'handle_event_submission' ) );
		add_action( 'admin_post_nopriv_swifty_submit_event', array( $this, 'handle_event_submission' ) );
		
		// Admin Columns
		$this->add_admin_columns();
	}

	public function handle_rsvp_submission() {
		if ( ! isset( $_POST['swifty_rsvp_nonce'] ) || ! wp_verify_nonce( $_POST['swifty_rsvp_nonce'], 'swifty_rsvp_action' ) ) {
			wp_die( 'Security check failed' );
		}

		$event_id = intval( $_POST['event_id'] );
		$name     = sanitize_text_field( $_POST['rsvp_name'] );
		$email    = sanitize_email( $_POST['rsvp_email'] );

		if ( ! $event_id || ! $name || ! $email ) {
			wp_die( 'Please fill all required fields.' );
		}

		$rsvp_data = array(
			'post_title'  => 'RSVP: ' . $name . ' - ' . get_the_title( $event_id ),
			'post_type'   => 'swifty_rsvp',
			'post_status' => 'publish',
		);

		$rsvp_id = wp_insert_post( $rsvp_data );

		if ( $rsvp_id ) {
			update_post_meta( $rsvp_id, '_swifty_rsvp_event_id', $event_id );
			update_post_meta( $rsvp_id, '_swifty_rsvp_name', $name );
			update_post_meta( $rsvp_id, '_swifty_rsvp_email', $email );

			// --- SEND EMAILS ---
			$event_title = get_the_title( $event_id );
			$admin_email = get_option( 'admin_email' );
			$headers = array( 'Content-Type: text/html; charset=UTF-8' );

			// 1. User Confirmation
			$subject = sprintf( __( 'RSVP Confirmation: %s', 'swifty-events' ), $event_title );
			$message = '<h3>' . __( 'You are confirmed!', 'swifty-events' ) . '</h3>';
			$message .= '<p>' . sprintf( __( 'Hi %s,', 'swifty-events' ), $name ) . '</p>';
			$message .= '<p>' . sprintf( __( 'Thanks for RSVPing to <strong>%s</strong>.', 'swifty-events' ), $event_title ) . '</p>';
			$message .= '<p>' . __( 'We look forward to seeing you there.', 'swifty-events' ) . '</p>';
			
			wp_mail( $email, $subject, $message, $headers );

			// 2. Admin Notification
			$admin_subject = __( 'New Event RSVP', 'swifty-events' );
			$admin_message = '<p>' . sprintf( __( 'New RSVP for <strong>%s</strong>', 'swifty-events' ), $event_title ) . '</p>';
			$admin_message .= '<p>' . sprintf( __( 'Name: %s', 'swifty-events' ), $name ) . '</p>';
			$admin_message .= '<p>' . sprintf( __( 'Email: %s', 'swifty-events' ), $email ) . '</p>';

			wp_mail( $admin_email, $admin_subject, $admin_message, $headers );

			// Redirect back with success message
			wp_redirect( add_query_arg( 'rsvp_sent', 'true', get_permalink( $event_id ) ) );
			exit;
		}
	}

	public function handle_event_submission() {
		if ( ! isset( $_POST['swifty_event_submission_nonce'] ) || ! wp_verify_nonce( $_POST['swifty_event_submission_nonce'], 'swifty_event_submission_action' ) ) {
			wp_die( 'Security check failed' );
		}

		$title = sanitize_text_field( $_POST['event_title'] );
		$desc  = sanitize_textarea_field( $_POST['event_description'] );
		$date  = sanitize_text_field( $_POST['event_date'] );

		$event_data = array(
			'post_title'   => $title,
			'post_content' => $desc,
			'post_type'    => 'event',
			'post_status'  => 'pending', // Pending review
		);

		$event_id = wp_insert_post( $event_data );

		if ( $event_id ) {
			update_post_meta( $event_id, '_swifty_event_date', $date );
			
			// Redirect
			$redirect = isset( $_POST['_wp_http_referer'] ) ? $_POST['_wp_http_referer'] : home_url();
			wp_redirect( add_query_arg( 'event_submitted', 'true', $redirect ) );
			exit;
		}
	}
	
	// --- ADMIN COLUMNS FOR RSVP ---
	public function add_admin_columns() {
		add_filter( 'manage_swifty_rsvp_posts_columns', array( $this, 'set_rsvp_columns' ) );
		add_action( 'manage_swifty_rsvp_posts_custom_column', array( $this, 'custom_rsvp_column' ), 10, 2 );
	}
	
	public function set_rsvp_columns( $columns ) {
		$new_columns = array();
		$new_columns['cb'] = $columns['cb'];
		$new_columns['title'] = __( 'RSVP Info', 'swifty-events' );
		$new_columns['rsvp_event'] = __( 'Event', 'swifty-events' ); // New Column
		$new_columns['date'] = $columns['date'];
		return $new_columns;
	}
	
	public function custom_rsvp_column( $column, $post_id ) {
		if ( 'rsvp_event' === $column ) {
			$event_id = get_post_meta( $post_id, '_swifty_rsvp_event_id', true );
			if ( $event_id ) {
				echo '<a href="' . get_edit_post_link( $event_id ) . '"><strong>' . get_the_title( $event_id ) . '</strong></a>';
			} else {
				echo __( 'Unknown Event', 'swifty-events' );
			}
		}
	}

}
