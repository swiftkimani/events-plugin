<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Swifty_Events_Settings {

	public function init() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	public function add_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=event',
			__( 'Settings', 'swifty-events' ),
			__( 'Settings', 'swifty-events' ),
			'manage_options',
			'swifty-events-settings',
			array( $this, 'settings_page_html' )
		);
	}

	public function register_settings() {
		register_setting( 'swifty_events_options', 'swifty_events_settings' );

		add_settings_section(
			'swifty_events_general_section',
			__( 'General Settings', 'swifty-events' ),
			null,
			'swifty-events-settings'
		);

		add_settings_field(
			'google_maps_api_key',
			__( 'Google Maps API Key', 'swifty-events' ),
			array( $this, 'google_maps_api_key_callback' ),
			'swifty-events-settings',
			'swifty_events_general_section'
		);
	}

	public function google_maps_api_key_callback() {
		$options = get_option( 'swifty_events_settings' );
		$value   = isset( $options['google_maps_api_key'] ) ? $options['google_maps_api_key'] : '';
		echo '<input type="text" name="swifty_events_settings[google_maps_api_key]" value="' . esc_attr( $value ) . '" class="regular-text">';
	}

	public function settings_page_html() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'swifty_events_options' );
				do_settings_sections( 'swifty-events-settings' );
				submit_button( __( 'Save Settings', 'swifty-events' ) );
				?>
			</form>
		</div>
		<?php
	}
}
