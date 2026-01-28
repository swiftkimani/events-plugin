<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Swifty_Events_Event_Countdown_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'swifty_event_countdown';
	}

	public function get_title() {
		return __( 'Event Countdown', 'swifty-events' );
	}

	public function get_icon() {
		return 'eicon-countdown';
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
			'event_id',
			array(
				'label' => __( 'Select Event', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'options' => $this->get_events(),
				'default' => '',
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
			'timer_color',
			array(
				'label' => __( 'Timer Color', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swifty-countdown-timer' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

	}
	
	protected function get_events() {
		$events = get_posts( array(
			'post_type' => 'event',
			'posts_per_page' => -1,
		) );
		
		$options = array();
		if ( ! empty( $events ) ) {
			foreach ( $events as $event ) {
				$options[ $event->ID ] = $event->post_title;
			}
		}
		
		return $options;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$event_id = $settings['event_id'];
		
		if ( ! $event_id ) {
			echo __( 'Please select an event.', 'swifty-events' );
			return;
		}
		
		$event_date = get_post_meta( $event_id, '_swifty_event_date', true );
		
		if ( ! $event_date ) {
			echo __( 'Event has no date set.', 'swifty-events' );
			return;
		}

		// Simple JS countdown
		$uniqid = uniqid('countdown_');
		?>
		<div id="<?php echo esc_attr( $uniqid ); ?>" class="swifty-countdown-timer">
			<div class="swifty-countdown-item"><span class="days">00</span> <?php _e('Days', 'swifty-events'); ?></div>
			<div class="swifty-countdown-item"><span class="hours">00</span> <?php _e('Hours', 'swifty-events'); ?></div>
			<div class="swifty-countdown-item"><span class="minutes">00</span> <?php _e('Minutes', 'swifty-events'); ?></div>
			<div class="swifty-countdown-item"><span class="seconds">00</span> <?php _e('Seconds', 'swifty-events'); ?></div>
		</div>
		<script>
		(function() {
			var countDownDate = new Date("<?php echo esc_js( $event_date ); ?>").getTime();
			var x = setInterval(function() {
				var now = new Date().getTime();
				var distance = countDownDate - now;

				if (distance < 0) {
					clearInterval(x);
					document.getElementById("<?php echo esc_js( $uniqid ); ?>").innerHTML = "EXPIRED";
					return;
				}

				var days = Math.floor(distance / (1000 * 60 * 60 * 24));
				var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
				var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
				var seconds = Math.floor((distance % (1000 * 60)) / 1000);

				var container = document.getElementById("<?php echo esc_js( $uniqid ); ?>");
				container.querySelector('.days').innerHTML = days;
				container.querySelector('.hours').innerHTML = hours;
				container.querySelector('.minutes').innerHTML = minutes;
				container.querySelector('.seconds').innerHTML = seconds;
			}, 1000);
		})();
		</script>
		<style>
		.swifty-countdown-timer {
			display: flex;
			gap: 15px;
			font-size: 1.5em;
			font-weight: bold;
			justify-content: center;
		}
		.swifty-countdown-item {
			text-align: center;
			display: flex;
			flex-direction: column;
		}
		.swifty-countdown-item span {
			font-size: 1.5em;
			display: block;
		}
		</style>
		<?php
	}

}
