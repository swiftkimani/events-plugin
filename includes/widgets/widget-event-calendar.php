<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Swifty_Events_Event_Calendar_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'swifty_event_calendar';
	}

	public function get_title() {
		return __( 'Event Calendar', 'swifty-events' );
	}

	public function get_icon() {
		return 'eicon-calendar';
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
		// Basic Calendar Implementation
		$current_month = isset( $_GET['swifty_month'] ) ? intval( $_GET['swifty_month'] ) : date( 'n' );
		$current_year  = isset( $_GET['swifty_year'] ) ? intval( $_GET['swifty_year'] ) : date( 'Y' );
		
		// Navigation
		$prev_month = $current_month - 1;
		$prev_year  = $current_year;
		if ( $prev_month < 1 ) {
			$prev_month = 12;
			$prev_year--;
		}
		
		$next_month = $current_month + 1;
		$next_year  = $current_year;
		if ( $next_month > 12 ) {
			$next_month = 1;
			$next_year++;
		}
		
		$calendar_html = '<div class="swifty-calendar-controls">';
		$calendar_html .= '<a href="?swifty_month=' . $prev_month . '&swifty_year=' . $prev_year . '">&laquo; ' . __( 'Prev', 'swifty-events' ) . '</a>';
		$calendar_html .= '<span>' . date( 'F Y', mktime( 0, 0, 0, $current_month, 1, $current_year ) ) . '</span>';
		$calendar_html .= '<a href="?swifty_month=' . $next_month . '&swifty_year=' . $next_year . '">' . __( 'Next', 'swifty-events' ) . ' &raquo;</a>';
		$calendar_html .= '</div>';
		
		$calendar_html .= '<table class="swifty-calendar">';
		$calendar_html .= '<thead><tr>';
		$days = array( 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' );
		foreach ( $days as $day ) {
			$calendar_html .= '<th>' . $day . '</th>';
		}
		$calendar_html .= '</tr></thead><tbody><tr>';
		
		$first_day_of_month = mktime( 0, 0, 0, $current_month, 1, $current_year );
		$number_days = date( 't', $first_day_of_month );
		$date_components = getdate( $first_day_of_month );
		$month_name = $date_components['month'];
		$day_of_week = $date_components['wday'];
		
		if ( $day_of_week > 0 ) {
			$calendar_html .= str_repeat( '<td></td>', $day_of_week );
		}
		
		$current_day = 1;
		
		while ( $current_day <= $number_days ) {
			if ( $day_of_week == 7 ) {
				$day_of_week = 0;
				$calendar_html .= '</tr><tr>';
			}
			
			$current_date_str = sprintf( '%04d-%02d-%02d', $current_year, $current_month, $current_day );
			
			// Query Events for this day
			$events = get_posts( array(
				'post_type' => 'event',
				'meta_query' => array(
					array(
						'key' => '_swifty_event_date',
						'value' => $current_date_str,
						'compare' => '=',
					)
				)
			) );
			
			$calendar_html .= '<td>';
			$calendar_html .= '<span class="day-number">' . $current_day . '</span>';
			
			if ( ! empty( $events ) ) {
				foreach ( $events as $event ) {
					$calendar_html .= '<a href="' . get_permalink( $event->ID ) . '" class="calendar-event-link">' . get_the_title( $event->ID ) . '</a>';
				}
			}
			
			$calendar_html .= '</td>';
			
			$current_day++;
			$day_of_week++;
		}
		
		if ( $day_of_week != 7 ) {
			$remaining_days = 7 - $day_of_week;
			$calendar_html .= str_repeat( '<td></td>', $remaining_days );
		}
		
		$calendar_html .= '</tr></tbody></table>';
		
		echo $calendar_html;
	}

}
