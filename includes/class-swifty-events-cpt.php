<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Swifty_Events_CPT
 * Maximum robustness!
 */
class Swifty_Events_CPT {

	public function init() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_rsvp_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_box_data' ) );
		
		// Force Custom Template
		add_filter( 'template_include', array( $this, 'load_single_event_template' ) );
		
		// Admin Enqueue
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}
	
	public function enqueue_admin_scripts() {
		// Enqueue Flatpickr for cute modern calendar
		wp_enqueue_style( 'flatpickr-css', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css' );
		// Cute theme for flatpickr (optional, or we style it manually in our js/css)
		// wp_enqueue_style( 'flatpickr-theme', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css' ); 
		
		wp_enqueue_script( 'flatpickr-js', 'https://cdn.jsdelivr.net/npm/flatpickr', array(), null, true );
		
		wp_enqueue_style( 'swifty-admin-css', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/swifty-admin.css' );
		wp_enqueue_script( 'swifty-admin-js', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/swifty-admin.js', array( 'jquery', 'flatpickr-js' ), null, true );
	}

	public function load_single_event_template( $template ) {
		if ( is_singular( 'event' ) ) {
			$plugin_template = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/single-event.php';
			if ( file_exists( $plugin_template ) ) {
				return $plugin_template;
			}
		} elseif ( is_post_type_archive( 'event' ) || is_tax( 'event_category' ) ) {
            $archive_template = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/archive-event.php';
            if ( file_exists( $archive_template ) ) {
                return $archive_template;
            }
        }
		return $template;
	}

	public function register_taxonomy() {
		$labels = array(
			'name'                       => _x( 'Event Categories', 'Taxonomy General Name', 'swifty-events' ),
			'singular_name'              => _x( 'Event Category', 'Taxonomy Singular Name', 'swifty-events' ),
			'menu_name'                  => __( 'Event Category', 'swifty-events' ),
			'all_items'                  => __( 'All Categories', 'swifty-events' ),
			'parent_item'                => __( 'Parent Category', 'swifty-events' ),
			'parent_item_colon'          => __( 'Parent Category:', 'swifty-events' ),
			'new_item_name'              => __( 'New Category Name', 'swifty-events' ),
			'add_new_item'               => __( 'Add New Category', 'swifty-events' ),
			'edit_item'                  => __( 'Edit Category', 'swifty-events' ),
			'update_item'                => __( 'Update Category', 'swifty-events' ),
			'view_item'                  => __( 'View Category', 'swifty-events' ),
			'separate_items_with_commas' => __( 'Separate categories with commas', 'swifty-events' ),
			'add_or_remove_items'        => __( 'Add or remove categories', 'swifty-events' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'swifty-events' ),
			'popular_items'              => __( 'Popular Categories', 'swifty-events' ),
			'search_items'               => __( 'Search Categories', 'swifty-events' ),
			'not_found'                  => __( 'Not Found', 'swifty-events' ),
			'no_terms'                   => __( 'No categories', 'swifty-events' ),
			'items_list'                 => __( 'Categories list', 'swifty-events' ),
			'items_list_navigation'      => __( 'Categories list navigation', 'swifty-events' ),
		);
		$args   = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'show_in_rest'               => true,
		);
		register_taxonomy( 'event_category', array( 'event' ), $args );
	}

	public function register_rsvp_post_type() {
		$labels = array(
			'name'                  => _x( 'RSVPs', 'Post Type General Name', 'swifty-events' ),
			'singular_name'         => _x( 'RSVP', 'Post Type Singular Name', 'swifty-events' ),
			'menu_name'             => __( 'RSVPs', 'swifty-events' ),
			'name_admin_bar'        => __( 'RSVP', 'swifty-events' ),
			'add_new'               => __( 'Add New', 'swifty-events' ),
			'add_new_item'          => __( 'Add New RSVP', 'swifty-events' ),
			'edit_item'             => __( 'Edit RSVP', 'swifty-events' ),
		);
		$args   = array(
			'label'               => __( 'RSVP', 'swifty-events' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'custom-fields' ),
			'public'              => false,  // Not public
			'show_ui'             => true,   // Show in admin
			'show_in_menu'        => 'edit.php?post_type=event', // Show under Events menu
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
		);
		register_post_type( 'swifty_rsvp', $args );
	}

	public function register_post_type() {
		$labels = array(
			'name'                  => _x( 'Events', 'Post Type General Name', 'swifty-events' ),
			'singular_name'         => _x( 'Event', 'Post Type Singular Name', 'swifty-events' ),
			'menu_name'             => __( 'Events', 'swifty-events' ),
			'name_admin_bar'        => __( 'Event', 'swifty-events' ),
			'archives'              => __( 'Event Archives', 'swifty-events' ),
			'attributes'            => __( 'Event Attributes', 'swifty-events' ),
			'parent_item_colon'     => __( 'Parent Event:', 'swifty-events' ),
			'all_items'             => __( 'All Events', 'swifty-events' ),
			'add_new_item'          => __( 'Add New Event', 'swifty-events' ),
			'add_new'               => __( 'Add New', 'swifty-events' ),
			'new_item'              => __( 'New Event', 'swifty-events' ),
			'edit_item'             => __( 'Edit Event', 'swifty-events' ),
			'update_item'           => __( 'Update Event', 'swifty-events' ),
			'view_item'             => __( 'View Event', 'swifty-events' ),
			'view_items'            => __( 'View Events', 'swifty-events' ),
			'search_items'          => __( 'Search Event', 'swifty-events' ),
			'not_found'             => __( 'Not found', 'swifty-events' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'swifty-events' ),
			'featured_image'        => __( 'Featured Image', 'swifty-events' ),
			'set_featured_image'    => __( 'Set featured image', 'swifty-events' ),
			'remove_featured_image' => __( 'Remove featured image', 'swifty-events' ),
			'use_featured_image'    => __( 'Use as featured image', 'swifty-events' ),
			'insert_into_item'      => __( 'Insert into event', 'swifty-events' ),
			'uploaded_to_this_item' => __( 'Uploaded to this event', 'swifty-events' ),
			'items_list'            => __( 'Events list', 'swifty-events' ),
			'items_list_navigation' => __( 'Events list navigation', 'swifty-events' ),
			'filter_items_list'     => __( 'Filter events list', 'swifty-events' ),
		);
		$args   = array(
			'label'               => __( 'Event', 'swifty-events' ),
			'description'         => __( 'Post Type for Events', 'swifty-events' ),
			'labels'              => $labels,
			'labels'              => $labels,
			'supports'            => array( 'title', 'thumbnail' ), // Reverted: Removed 'editor'
			'taxonomies'          => array( 'category', 'post_tag' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-calendar-alt',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest'        => false, // Disable Gutenberg to use Classic Meta Boxes
		);
		register_post_type( 'event', $args );
	}

	public function add_meta_boxes() {
		add_meta_box(
			'swifty_events_details',
			__( 'Event Details', 'swifty-events' ),
			array( $this, 'render_meta_box' ),
			'event',
			'normal',
			'high'
		);
		
		add_meta_box(
			'swifty_events_description',
			__( 'Event Description', 'swifty-events' ),
			array( $this, 'render_description_meta_box' ),
			'event',
			'normal',
			'high'
		);
	}

	public function render_meta_box( $post ) {
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'swifty_events_save_meta_box_data', 'swifty_events_meta_box_nonce' );
		
		$event_date     = get_post_meta( $post->ID, '_swifty_event_date', true );
		$event_location = get_post_meta( $post->ID, '_swifty_event_location', true );
		$event_organizer = get_post_meta( $post->ID, '_swifty_event_organizer', true );
		
		// Recurring Fields
		$is_recurring = get_post_meta( $post->ID, '_swifty_is_recurring', true );
		$recurrence_type = get_post_meta( $post->ID, '_swifty_recurrence_type', true );

		// Map Fields
		$map_address = get_post_meta( $post->ID, '_swifty_map_address', true );

		echo '<p>';
		echo '<label for="swifty_event_date">' . __( 'Event Date', 'swifty-events' ) . '</label>';
		echo '<input type="text" id="swifty_event_date" name="swifty_event_date" value="' . esc_attr( $event_date ) . '" class="widefat swifty-date-picker" placeholder="' . __( 'Select Date', 'swifty-events' ) . '" />';
		echo '</p>';

		echo '<p>';
		echo '<label for="swifty_event_location">' . __( 'Location Name', 'swifty-events' ) . '</label>';
		echo '<input type="text" id="swifty_event_location" name="swifty_event_location" value="' . esc_attr( $event_location ) . '" class="widefat" />';
		echo '</p>';

		echo '<p>';
		echo '<label for="swifty_map_address">' . __( 'Map Address (for Google Maps)', 'swifty-events' ) . '</label>';
		echo '<input type="text" id="swifty_map_address" name="swifty_map_address" value="' . esc_attr( $map_address ) . '" class="widefat" />';
		echo '</p>';
		
		echo '<p>';
		echo '<label for="swifty_event_organizer">' . __( 'Organizer', 'swifty-events' ) . '</label>';
		echo '<input type="text" id="swifty_event_organizer" name="swifty_event_organizer" value="' . esc_attr( $event_organizer ) . '" class="widefat" />';
		echo '</p>';
		
		echo '<hr>';
		echo '<p>';
		echo '<label for="swifty_is_recurring"><input type="checkbox" id="swifty_is_recurring" name="swifty_is_recurring" value="yes" ' . checked( $is_recurring, 'yes', false ) . ' /> ' . __( 'Is Recurring Event?', 'swifty-events' ) . '</label>';
		echo '</p>';
		
		echo '<p>';
		echo '<label for="swifty_recurrence_type">' . __( 'Recurrence Type', 'swifty-events' ) . '</label>';
		echo '<select id="swifty_recurrence_type" name="swifty_recurrence_type" class="widefat">';
		echo '<option value="daily" ' . selected( $recurrence_type, 'daily', false ) . '>' . __( 'Daily', 'swifty-events' ) . '</option>';
		echo '<option value="weekly" ' . selected( $recurrence_type, 'weekly', false ) . '>' . __( 'Weekly', 'swifty-events' ) . '</option>';
		echo '<option value="monthly" ' . selected( $recurrence_type, 'monthly', false ) . '>' . __( 'Monthly', 'swifty-events' ) . '</option>';
		echo '<option value="yearly" ' . selected( $recurrence_type, 'yearly', false ) . '>' . __( 'Yearly', 'swifty-events' ) . '</option>';
		echo '</select>';
		echo '</p>';
		
		// RSVP Checkbox UI
		$enable_rsvp = get_post_meta( $post->ID, '_swifty_enable_rsvp', true );
		echo '<p>';
		echo '<label for="swifty_enable_rsvp"><input type="checkbox" id="swifty_enable_rsvp" name="swifty_enable_rsvp" value="yes" ' . checked( $enable_rsvp, 'yes', false ) . ' /> ' . __( 'Enable RSVP / Join Event Button', 'swifty-events' ) . '</label>';
		echo '</p>';
		
		echo '<hr>';
		
		// Event Type (Physical / Virtual)
		$event_type = get_post_meta( $post->ID, '_swifty_event_type', true );
		if ( ! $event_type ) { $event_type = 'physical'; }
		echo '<p>';
		echo '<label for="swifty_event_type">' . __( 'Event Type', 'swifty-events' ) . '</label>';
		echo '<select id="swifty_event_type" name="swifty_event_type" class="widefat">';
		echo '<option value="physical" ' . selected( $event_type, 'physical', false ) . '>' . __( 'Physical (In-Person)', 'swifty-events' ) . '</option>';
		echo '<option value="virtual" ' . selected( $event_type, 'virtual', false ) . '>' . __( 'Virtual (Online)', 'swifty-events' ) . '</option>';
		echo '<option value="hybrid" ' . selected( $event_type, 'hybrid', false ) . '>' . __( 'Hybrid (Both)', 'swifty-events' ) . '</option>';
		echo '</select>';
		echo '</p>';
		
		// Virtual Link
		$virtual_link = get_post_meta( $post->ID, '_swifty_virtual_link', true );
		echo '<p>';
		echo '<label for="swifty_virtual_link">' . __( 'Virtual Meeting Link (Zoom/Google Meet)', 'swifty-events' ) . '</label>';
		echo '<input type="url" id="swifty_virtual_link" name="swifty_virtual_link" value="' . esc_attr( $virtual_link ) . '" class="widefat" placeholder="https://zoom.us/j/..." />';
		echo '</p>';
	}

	public function save_meta_box_data( $post_id ) {
		// Check if our nonce is set.
		if ( ! isset( $_POST['swifty_events_meta_box_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['swifty_events_meta_box_nonce'], 'swifty_events_save_meta_box_data' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Make sure that it is set.
		if ( isset( $_POST['swifty_event_date'] ) ) {
			update_post_meta( $post_id, '_swifty_event_date', sanitize_text_field( $_POST['swifty_event_date'] ) );
		}
		if ( isset( $_POST['swifty_event_location'] ) ) {
			update_post_meta( $post_id, '_swifty_event_location', sanitize_text_field( $_POST['swifty_event_location'] ) );
		}
		if ( isset( $_POST['swifty_event_organizer'] ) ) {
			update_post_meta( $post_id, '_swifty_event_organizer', sanitize_text_field( $_POST['swifty_event_organizer'] ) );
		}
		
		// New Fields
		if ( isset( $_POST['swifty_map_address'] ) ) {
			update_post_meta( $post_id, '_swifty_map_address', sanitize_text_field( $_POST['swifty_map_address'] ) );
		}
		
		if ( isset( $_POST['swifty_is_recurring'] ) ) {
			update_post_meta( $post_id, '_swifty_is_recurring', 'yes' );
		} else {
			update_post_meta( $post_id, '_swifty_is_recurring', 'no' );
		}
		
		if ( isset( $_POST['swifty_recurrence_type'] ) ) {
			update_post_meta( $post_id, '_swifty_recurrence_type', sanitize_text_field( $_POST['swifty_recurrence_type'] ) );
		}
		
		// RSVP Toggle
		if ( isset( $_POST['swifty_enable_rsvp'] ) ) {
			update_post_meta( $post_id, '_swifty_enable_rsvp', 'yes' );
		} else {
			update_post_meta( $post_id, '_swifty_enable_rsvp', 'no' );
		}
		
		// Event Type & Link
		if ( isset( $_POST['swifty_event_type'] ) ) {
			update_post_meta( $post_id, '_swifty_event_type', sanitize_text_field( $_POST['swifty_event_type'] ) );
		}
		if ( isset( $_POST['swifty_virtual_link'] ) ) {
			update_post_meta( $post_id, '_swifty_virtual_link', esc_url_raw( $_POST['swifty_virtual_link'] ) );
		}
		
		// Save Description
		if ( isset( $_POST['swifty_event_description'] ) ) {
			$post_data = array(
				'ID'           => $post_id,
				'post_content' => wp_kses_post( $_POST['swifty_event_description'] ),
			);
			// Validate to prevent infinite loop
			if ( ! wp_is_post_revision( $post_id ) ) {
				// Unhook to prevent loop
				remove_action( 'save_post', array( $this, 'save_meta_box_data' ) );
				wp_update_post( $post_data );
				add_action( 'save_post', array( $this, 'save_meta_box_data' ) );
			}
		}
	}
	
	public function render_description_meta_box( $post ) {
		$content = $post->post_content;
		wp_editor( $content, 'swifty_event_description', array(
			'media_buttons' => true,
			'textarea_rows' => 10,
		) );
	}

}
