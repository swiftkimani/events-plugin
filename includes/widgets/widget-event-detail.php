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
		$event_id = get_the_ID();

		// Fetch Meta
		$event_date     = get_post_meta( $event_id, '_swifty_event_date', true );
		$event_location = get_post_meta( $event_id, '_swifty_event_location', true );
		$event_organizer = get_post_meta( $event_id, '_swifty_event_organizer', true );
		$map_address     = get_post_meta( $event_id, '_swifty_map_address', true );
		$options         = get_option( 'swifty_events_settings' );
		$api_key         = isset( $options['google_maps_api_key'] ) ? $options['google_maps_api_key'] : '';

		// URL for Back Button (Go to archive or home?)
		// Ideally this should be dynamic, but for now linking to events archive or home.
		$back_url = get_post_type_archive_link( 'event' );
		if ( ! $back_url ) { $back_url = home_url( '/' ); }

		echo '<div class="swifty-single-event-wrapper">'; // Grid Container

		// --- LEFT COLUMN: Main Content ---
		echo '<div class="swifty-single-main">';
		
		// Top Back Button
		echo '<a href="' . esc_url( $back_url ) . '" class="swifty-back-btn swifty-back-top"><i class="eicon-arrow-left"></i> ' . __( 'Back to Events', 'swifty-events' ) . '</a>';

		// Featured Image (Hero)
		if ( has_post_thumbnail() ) {
			echo '<div class="swifty-single-hero">';
			the_post_thumbnail( 'full' );
			echo '</div>';
		}

		// Title
		echo '<h1 class="swifty-single-title">' . get_the_title() . '</h1>';

		// Meta Row (Icon + Text)
		echo '<div class="swifty-single-meta-row">';
		if ( 'yes' === $settings['show_date'] && $event_date ) {
			echo '<div class="swifty-meta-item"><i class="eicon-calendar"></i> ' . date_i18n( get_option( 'date_format' ), strtotime( $event_date ) ) . '</div>';
		}
		if ( 'yes' === $settings['show_location'] && $event_location ) {
			echo '<div class="swifty-meta-item"><i class="eicon-map-pin"></i> ' . esc_html( $event_location ) . '</div>';
		}
		echo '</div>'; // End Meta Row

		// Description
		echo '<div class="swifty-single-content swifty-content-typography">';
		echo apply_filters( 'the_content', get_the_content() );
		echo '</div>';

		// RSVP Form (Inline here or Sidebar? User didn't specify, sticking to main content flow or bottom)
		if ( 'yes' === $settings['show_rsvp'] ) {
			echo '<div class="swifty-rsvp-section">';
			if ( isset( $_GET['rsvp_sent'] ) && 'true' == $_GET['rsvp_sent'] ) {
				echo '<div class="swifty-success-message" style="color: green; font-weight: bold;">' . __( 'RSVP Sent Successfully!', 'swifty-events' ) . '</div>';
			} else {
				?>
				<h3><?php _e( 'Join this Event', 'swifty-events' ); ?></h3>
				<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" class="swifty-rsvp-form-styled">
					<input type="hidden" name="action" value="swifty_submit_rsvp">
					<input type="hidden" name="event_id" value="<?php echo get_the_ID(); ?>">
					<?php wp_nonce_field( 'swifty_rsvp_action', 'swifty_rsvp_nonce' ); ?>
					
					<div class="swifty-form-row">
						<input type="text" name="rsvp_name" placeholder="<?php _e( 'Your Name', 'swifty-events' ); ?>" required>
					</div>
					<div class="swifty-form-row">
						<input type="email" name="rsvp_email" placeholder="<?php _e( 'Your Email', 'swifty-events' ); ?>" required>
					</div>
					<button type="submit" class="swifty-btn swifty-btn-rsvp"><?php _e( 'Confirm RSVP', 'swifty-events' ); ?></button>
				</form>
				<?php
			}
			echo '</div>';
		}

		// Bottom Back Button
		echo '<a href="' . esc_url( $back_url ) . '" class="swifty-back-btn swifty-back-bottom"><i class="eicon-arrow-left"></i> ' . __( 'Back to Events', 'swifty-events' ) . '</a>';

		echo '</div>'; // End Main Content


		// --- RIGHT COLUMN: Sticky Sidebar ---
		echo '<aside class="swifty-single-sidebar">';
		
		echo '<div class="swifty-sidebar-inner">'; // Inner wrapper for sticky

		// 1. Info Card
		echo '<div class="swifty-info-card">';
		echo '<h3>' . __( 'Event Details', 'swifty-events' ) . '</h3>';
		echo '<ul class="swifty-info-list">';
		if ( $event_date ) {
			echo '<li><strong>' . __( 'Date:', 'swifty-events' ) . '</strong> ' . date_i18n( get_option( 'date_format' ), strtotime( $event_date ) ) . '</li>';
		}
		if ( $event_organizer && 'yes' === $settings['show_organizer'] ) {
			echo '<li><strong>' . __( 'Organizer:', 'swifty-events' ) . '</strong> ' . esc_html( $event_organizer ) . '</li>';
		}
		echo '</ul>';
		
		// Map (Inside Sidebar now)
		if ( 'yes' === $settings['show_map'] && $map_address && $api_key ) {
			echo '<div class="swifty-mini-map">';
			echo '<iframe width="100%" height="200" style="border:0; border-radius: 12px;" loading="lazy" src="https://www.google.com/maps/embed/v1/place?key=' . esc_attr( $api_key ) . '&q=' . urlencode( $map_address ) . '"></iframe>';
			echo '<a href="https://www.google.com/maps/search/?api=1&query=' . urlencode( $map_address ) . '" target="_blank" class="swifty-btn-map-link">' . __( 'Get Directions', 'swifty-events' ) . '</a>';
			echo '</div>';
		}
		echo '</div>'; // End Info Card

		// 2. Related Events
		$terms = get_the_terms( $event_id, 'event_category' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$related_term_ids = wp_list_pluck( $terms, 'term_id' );
			$related_args = array(
				'post_type' => 'event',
				'posts_per_page' => 3,
				'post__not_in' => array( $event_id ),
				'tax_query' => array(
					array(
						'taxonomy' => 'event_category',
						'field' => 'term_id',
						'terms' => $related_term_ids,
					),
				),
			);
			$related_query = new \WP_Query( $related_args );

			if ( $related_query->have_posts() ) {
				echo '<div class="swifty-related-events">';
				echo '<h3>' . __( 'Related Events', 'swifty-events' ) . '</h3>';
				while ( $related_query->have_posts() ) {
					$related_query->the_post();
					echo '<div class="swifty-related-item">';
					echo '<a href="' . get_the_permalink() . '">';
					if ( has_post_thumbnail() ) {
						echo '<div class="swifty-related-thumb">' . get_the_post_thumbnail( get_the_ID(), 'thumbnail' ) . '</div>';
					}
					echo '<div class="swifty-related-info">';
					echo '<h4 class="swifty-related-title">' . get_the_title() . '</h4>';
					$rel_date = get_post_meta( get_the_ID(), '_swifty_event_date', true );
					if($rel_date) echo '<span class="swifty-related-date">' . date_i18n( 'M j, Y', strtotime( $rel_date ) ) . '</span>';
					echo '</div>';
					echo '</a>';
					echo '</div>';
				}
				echo '</div>';
				wp_reset_postdata();
			}
		}

		echo '</div>'; // End Sticky Inner
		echo '</aside>'; // End Right Sidebar

		echo '</div>'; // End Grid Wrapper
	}

}
