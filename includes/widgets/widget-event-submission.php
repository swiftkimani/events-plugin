<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Swifty_Events_Event_Submission_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'swifty_event_submission';
	}

	public function get_title() {
		return __( 'Event Submission Form', 'swifty-events' );
	}

	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	public function get_categories() {
		return array( 'swifty-events' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'swifty-events' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		if ( isset( $_GET['event_submitted'] ) && 'true' == $_GET['event_submitted'] ) {
			echo '<div class="swifty-success-message">' . __( 'Event submitted successfully! It is pending review.', 'swifty-events' ) . '</div>';
		}
		?>
		<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" class="swifty-event-submission-form">
			<input type="hidden" name="action" value="swifty_submit_event">
			<?php wp_nonce_field( 'swifty_event_submission_action', 'swifty_event_submission_nonce' ); ?>
			
			<p>
				<label for="event_title"><?php _e( 'Event Title', 'swifty-events' ); ?></label>
				<input type="text" name="event_title" id="event_title" required>
			</p>
			
			<p>
				<label for="event_description"><?php _e( 'Description', 'swifty-events' ); ?></label>
				<textarea name="event_description" id="event_description" rows="5" required></textarea>
			</p>
			
			<p>
				<label for="event_date"><?php _e( 'Event Date', 'swifty-events' ); ?></label>
				<input type="date" name="event_date" id="event_date" required>
			</p>
			
			<p>
				<button type="submit"><?php _e( 'Submit Event', 'swifty-events' ); ?></button>
			</p>
		</form>
		<style>
		.swifty-event-submission-form input, .swifty-event-submission-form textarea {
			width: 100%;
			padding: 8px;
			margin-bottom: 15px;
		}
		.swifty-event-submission-form button {
			padding: 10px 20px;
			background: #333;
			color: #fff;
			border: none;
			cursor: pointer;
		}
		</style>
		<?php
	}

}
