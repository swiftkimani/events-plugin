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

}
