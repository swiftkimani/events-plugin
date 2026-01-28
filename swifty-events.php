<?php
/**
 * Plugin Name: Swifty Events
 * Description: A powerful and easy-to-use event management plugin with Elementor integration.
 * Version: 1.0.0
 * Author: Antigravity
 * Text Domain: swifty-events
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'SWIFTY_EVENTS_VERSION', '1.0.0' );
define( 'SWIFTY_EVENTS_PATH', plugin_dir_path( __FILE__ ) );
define( 'SWIFTY_EVENTS_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main Plugin Class
 */
final class Swifty_Events {

	/**
	 * Instance
	 *
	 * @var Swifty_Events The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Swifty_Events An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Include required files
	 */
	private function includes() {
		require_once SWIFTY_EVENTS_PATH . 'includes/class-swifty-events-cpt.php';
		require_once SWIFTY_EVENTS_PATH . 'includes/class-elementor-integration.php';
		require_once SWIFTY_EVENTS_PATH . 'includes/class-swifty-cms.php';
		
		if ( is_admin() ) {
			require_once SWIFTY_EVENTS_PATH . 'includes/admin/class-swifty-events-settings.php';
		}
	}

	/**
	 * Init Hooks
	 */
	private function init_hooks() {
		// Initialize CPT
		$cpt = new Swifty_Events_CPT();
		$cpt->init();

		// Initialize Settings
		if ( is_admin() ) {
			$settings = new Swifty_Events_Settings();
			$settings->init();
		}
		
		// Initialize CMS
		$cms = new Swifty_Events_CMS();
		$cms->init();

		// Initialize Elementor Integration
		add_action( 'plugins_loaded', array( $this, 'init_elementor' ) );
		
		// Enqueue Scripts and Styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Initialize Elementor Integration
	 */
	public function init_elementor() {
		$elementor_integration = new Swifty_Events_Elementor_Integration();
		$elementor_integration->init();
	}
	
	/**
	 * Enqueue Scripts
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'swifty-events-css', SWIFTY_EVENTS_URL . 'assets/css/swifty-events.css', array(), SWIFTY_EVENTS_VERSION );
	}

}

// Kick off the plugin
function swifty_events() {
	return Swifty_Events::instance();
}
swifty_events();
