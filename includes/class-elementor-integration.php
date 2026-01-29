<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Swifty_Events_Elementor_Integration
 */
class Swifty_Events_Elementor_Integration {

	public function init() {
		// Check if Elementor is installed and active
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		// Register Widgets
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );

		// Register Category
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_categories' ) );
	}

	public function register_categories( $elements_manager ) {
		$elements_manager->add_category(
			'swifty-events',
			array(
				'title' => __( 'Swifty Events', 'swifty-events' ),
				'icon'  => 'fa fa-calendar',
			)
		);
	}

	public function register_widgets( $widgets_manager ) {
		require_once SWIFTY_EVENTS_PATH . 'includes/widgets/widget-event-list.php';
		require_once SWIFTY_EVENTS_PATH . 'includes/widgets/widget-event-detail.php';
		require_once SWIFTY_EVENTS_PATH . 'includes/widgets/widget-event-countdown.php';
		require_once SWIFTY_EVENTS_PATH . 'includes/widgets/widget-event-calendar.php';
		require_once SWIFTY_EVENTS_PATH . 'includes/widgets/widget-event-submission.php';
		require_once SWIFTY_EVENTS_PATH . 'includes/widgets/widget-event-latest-category.php';
		require_once SWIFTY_EVENTS_PATH . 'includes/widgets/widget-wigh-event-list.php';

		$widgets_manager->register( new \Swifty_Events_Event_List_Widget() );
		$widgets_manager->register( new \Swifty_Events_Event_Detail_Widget() );
		$widgets_manager->register( new \Swifty_Events_Event_Countdown_Widget() );
		$widgets_manager->register( new \Swifty_Events_Event_Calendar_Widget() );
		$widgets_manager->register( new \Swifty_Events_Event_Submission_Widget() );
		$widgets_manager->register( new \Swifty_Events_Latest_Category_Widget() );
		$widgets_manager->register( new \Swifty_Events_Wigh_List_Widget() );
	}

}
