<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Swifty_Events_Event_Detail_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'swifty_event_detail';
	}

	public function get_title() {
		return __( 'Event Details', 'swifty-events' );
	}

	public function get_icon() {
		return 'eicon-post-info';
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

		$this->add_control(
			'show_date',
			array(
				'label'        => __( 'Show Date', 'swifty-events' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'swifty-events' ),
				'label_off'    => __( 'Hide', 'swifty-events' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_location',
			array(
				'label'        => __( 'Show Location', 'swifty-events' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'swifty-events' ),
				'label_off'    => __( 'Hide', 'swifty-events' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		
		$this->add_control(
			'show_organizer',
			array(
				'label'        => __( 'Show Organizer', 'swifty-events' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'swifty-events' ),
				'label_off'    => __( 'Hide', 'swifty-events' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);


		$this->add_control(
			'show_map',
			array(
				'label'        => __( 'Show Map', 'swifty-events' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'swifty-events' ),
				'label_off'    => __( 'Hide', 'swifty-events' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		
		$this->add_control(
			'show_rsvp',
			array(
				'label'        => __( 'Show RSVP Form', 'swifty-events' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'swifty-events' ),
				'label_off'    => __( 'Hide', 'swifty-events' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Style', 'swifty-events' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
		$this->add_control(
			'text_color',
			array(
				'label' => __( 'Text Color', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swifty-event-details' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Only show on singular event posts or if explicitly used elsewhere
		// For the purpose of the widget, we assume it's used in a context where get_the_ID() returns the event ID.
		
		$event_date     = get_post_meta( get_the_ID(), '_swifty_event_date', true );
		$event_location = get_post_meta( get_the_ID(), '_swifty_event_location', true );
		$event_organizer = get_post_meta( get_the_ID(), '_swifty_event_organizer', true );
		$map_address     = get_post_meta( get_the_ID(), '_swifty_map_address', true );
		$options         = get_option( 'swifty_events_settings' );
		$api_key         = isset( $options['google_maps_api_key'] ) ? $options['google_maps_api_key'] : '';

		echo '<div class="swifty-event-details">';
		
		if ( 'yes' === $settings['show_date'] && $event_date ) {
			echo '<div class="swifty-event-detail-item swifty-event-date">';
			echo '<span class="swifty-label">' . __( 'Date:', 'swifty-events' ) . ' </span>';
			echo '<span class="swifty-value">' . date_i18n( get_option( 'date_format' ), strtotime( $event_date ) ) . '</span>';
			echo '</div>';
		}

		if ( 'yes' === $settings['show_location'] && $event_location ) {
			echo '<div class="swifty-event-detail-item swifty-event-location">';
			echo '<span class="swifty-label">' . __( 'Location:', 'swifty-events' ) . ' </span>';
			echo '<span class="swifty-value">' . esc_html( $event_location ) . '</span>';
			echo '</div>';
		}
		
		if ( 'yes' === $settings['show_organizer'] && $event_organizer ) {
			echo '<div class="swifty-event-detail-item swifty-event-organizer">';
			echo '<span class="swifty-label">' . __( 'Organizer:', 'swifty-events' ) . ' </span>';
			echo '<span class="swifty-value">' . esc_html( $event_organizer ) . '</span>';
			echo '</div>';
		}
		
		// Map Integration
		if ( 'yes' === $settings['show_map'] && $map_address && $api_key ) {
			echo '<div class="swifty-event-map" style="margin-top: 20px;">';
			echo '<iframe
					width="100%"
					height="300"
					style="border:0"
					loading="lazy"
					allowfullscreen
					src="https://www.google.com/maps/embed/v1/place?key=' . esc_attr( $api_key ) . '&q=' . urlencode( $map_address ) . '">
				  </iframe>';
			echo '</div>';
		} elseif ( 'yes' === $settings['show_map'] && $map_address && ! $api_key ) {
			echo '<p style="color:red;">' . __( 'Please set Google Maps API Key in settings.', 'swifty-events' ) . '</p>';
		}

		// RSVP Form
		if ( 'yes' === $settings['show_rsvp'] ) {
			if ( isset( $_GET['rsvp_sent'] ) && 'true' == $_GET['rsvp_sent'] ) {
				echo '<div class="swifty-success-message" style="margin-top: 20px; color: green;">' . __( 'RSVP Sent Successfully!', 'swifty-events' ) . '</div>';
			} else {
				?>
				<div class="swifty-rsvp-form" style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
					<h3><?php _e( 'RSVP for this Event', 'swifty-events' ); ?></h3>
					<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post">
						<input type="hidden" name="action" value="swifty_submit_rsvp">
						<input type="hidden" name="event_id" value="<?php echo get_the_ID(); ?>">
						<?php wp_nonce_field( 'swifty_rsvp_action', 'swifty_rsvp_nonce' ); ?>
						
						<p>
							<label for="rsvp_name"><?php _e( 'Name', 'swifty-events' ); ?></label>
							<input type="text" name="rsvp_name" required style="width:100%;">
						</p>
						<p>
							<label for="rsvp_email"><?php _e( 'Email', 'swifty-events' ); ?></label>
							<input type="email" name="rsvp_email" required style="width:100%;">
						</p>
						<p>
							<button type="submit" class="button"><?php _e( 'Confirm RSVP', 'swifty-events' ); ?></button>
						</p>
					</form>
				</div>
				<?php
			}
		}

		echo '</div>';
	}

}
