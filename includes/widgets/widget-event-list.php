<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Swifty_Events_Event_List_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'swifty_event_list';
	}

	public function get_title() {
		return __( 'Event List', 'swifty-events' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return array( 'swifty-events' );
	}

	public function get_style_depends() {
		return array( 'swifty-events-css' );
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
			'posts_per_page',
			array(
				'label'   => __( 'Number of Events', 'swifty-events' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 3,
			)
		);

		$terms = get_terms( array(
			'taxonomy' => 'event_category',
			'hide_empty' => false,
		) );
		
		$options = array();
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->term_id ] = $term->name;
			}
		}

		$this->add_control(
			'category_filter',
			array(
				'label' => __( 'Category', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $options,
				'description' => __( 'Select categories to show events from.', 'swifty-events' ),
			)
		);
		
		$this->add_control(
			'layout_mode',
			array(
				'label' => __( 'Layout Mode', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => array(
					'grid' => __( 'Grid', 'swifty-events' ),
					'flex' => __( 'Flexbox', 'swifty-events' ),
				),
			)
		);

		$this->add_control(
			'flex_direction',
			array(
				'label' => __( 'Flex Direction', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'row',
				'options' => array(
					'row' => __( 'Row (Horizontal)', 'swifty-events' ),
					'column' => __( 'Column (Vertical)', 'swifty-events' ),
				),
				'condition' => array(
					'layout_mode' => 'flex',
				),
			)
		);

		$this->add_control(
			'skin',
			array(
				'label' => __( 'Skin', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'gh2kenya',
				'options' => array(
					'gh2kenya' => __( 'Gh2Kenya Premium', 'swifty-events' ),
					'glass' => __( 'Glass Minimal', 'swifty-events' ),
					'gradient_glass' => __( 'Gradient Glass', 'swifty-events' ),
				),
			)
		);
		
		$this->add_control(
			'animation',
			array(
				'label' => __( 'Entry Animation', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'fade-up',
				'options' => array(
					'none' => __( 'None', 'swifty-events' ),
					'fade-up' => __( 'Fade Up', 'swifty-events' ),
					'zoom-in' => __( 'Zoom In', 'swifty-events' ),
			),
		) );

		// Get Event Categories
		$categories = get_terms( 'event_category', array( 'hide_empty' => false ) );
		$cat_options = array( '' => __( 'All Categories', 'swifty-events' ) );
		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			foreach ( $categories as $category ) {
				$cat_options[ $category->term_id ] = $category->name;
			}
		}

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
			'excerpt_length',
			array(
				'label'   => __( 'Description Length (Words)', 'swifty-events' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 20,
				'min'     => 5,
				'max'     => 100,
			)
		);

		$this->add_control(
			'show_filter_sidebar',
			array(
				'label'        => __( 'Show Filter Sidebar', 'swifty-events' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'swifty-events' ),
				'label_off'    => __( 'Hide', 'swifty-events' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'    => 'before',
			)
		);
		
		// --- PAGINATION CONTROLS ---
		$this->add_control(
			'swifty_pagination_enable',
			array(
				'label'        => __( 'Enable Pagination', 'swifty-events' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'swifty-events' ),
				'label_off'    => __( 'No', 'swifty-events' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);
		
		$this->add_control(
			'swifty_pagination_position',
			array(
				'label' => __( 'Pagination Position', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'bottom',
				'options' => array(
					'top' => __( 'Top', 'swifty-events' ),
					'bottom' => __( 'Bottom', 'swifty-events' ),
					'both' => __( 'Both (Top & Bottom)', 'swifty-events' ),
				),
				'condition' => array(
					'swifty_pagination_enable' => 'yes',
				),
			)
		);
		
		$this->add_control(
			'swifty_pagination_style',
			array(
				'label' => __( 'Pagination Style', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'numbers',
				'options' => array(
					'numbers' => __( 'Standard Numbers', 'swifty-events' ),
					'pill' => __( 'Modern Pill', 'swifty-events' ),
					'prevnext' => __( 'Prev/Next Only', 'swifty-events' ),
				),
				'condition' => array(
					'swifty_pagination_enable' => 'yes',
				),
			)
		);
		
		// --- ANIMATION CONTROLS ---
		$this->add_control(
			'swifty_framer_animation',
			array(
				'label'        => __( 'Framer-like Animations', 'swifty-events' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Enabled', 'swifty-events' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Enables smooth, spring-like entry and hover effects.', 'swifty-events' ),
			)
		);
		
		$this->add_control(
			'animation_stagger',
			array(
				'label' => __( 'Stagger Delay (ms)', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 100,
				'min' => 0,
				'max' => 500,
				'condition' => array(
					'swifty_framer_animation' => 'yes',
				),
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
			'title_color',
			array(
				'label' => __( 'Title Color', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swifty-event-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		
		// Query
		$paged = max(1, get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1));
		if ( isset( $_GET['swifty_page'] ) ) {
			$paged = intval( $_GET['swifty_page'] );
		}
		
		$args = [
			'post_type' => 'event',
			'posts_per_page' => $settings['posts_per_page'] ?: 6,
			'paged' => $paged,
			'post_status' => 'publish',
		];

		// Settings Mapping (Adapter)
		$layout_type = isset($settings['layout_mode']) ? $settings['layout_mode'] : 'grid';
		$enable_animations = isset($settings['swifty_framer_animation']) ? $settings['swifty_framer_animation'] : 'yes';
		$pagination_enable = isset($settings['swifty_pagination_enable']) ? $settings['swifty_pagination_enable'] : 'yes';
		$pagination_style = isset($settings['swifty_pagination_style']) ? $settings['swifty_pagination_style'] : 'numbers';
		$pagination_pos = isset($settings['swifty_pagination_position']) ? $settings['swifty_pagination_position'] : 'bottom';

		// Handle Filtering from $_GET
		if ( isset( $_GET['search'] ) && ! empty( $_GET['search'] ) ) {
			$args['s'] = sanitize_text_field( $_GET['search'] );
		}
		
		if ( isset( $_GET['category'] ) && ! empty( $_GET['category'] ) ) {
			$args['tax_query'] = [
				[
					'taxonomy' => 'event_category',
					'field' => 'slug',
					'terms' => sanitize_text_field( $_GET['category'] ),
				]
			];
		}
		
		// Location Filter
		if ( isset( $_GET['location'] ) && ! empty( $_GET['location'] ) ) {
			$args['meta_query'][] = [
				'key' => '_swifty_event_location',
				'value' => sanitize_text_field( $_GET['location'] ),
				'compare' => '=',
			];
		}
		
		// Date Range
		if ( isset( $_GET['date_from'] ) && ! empty( $_GET['date_from'] ) ) {
			$args['meta_query'][] = [
				'key' => '_swifty_event_date',
				'value' => sanitize_text_field( $_GET['date_from'] ),
				'compare' => '>=',
				'type' => 'DATE',
			];
		}
		
		if ( isset( $_GET['date_to'] ) && ! empty( $_GET['date_to'] ) ) {
			$args['meta_query'][] = [
				'key' => '_swifty_event_date',
				'value' => sanitize_text_field( $_GET['date_to'] ),
				'compare' => '<=',
				'type' => 'DATE',
			];
		}
		
		// Upcoming Quick Action
		if ( isset( $_GET['upcoming'] ) && 'true' === $_GET['upcoming'] ) {
			$today = date('Y-m-d');
			$args['meta_query'][] = [
				'key' => '_swifty_event_date',
				'value' => $today,
				'compare' => '>=',
				'type' => 'DATE'
			];
			$args['orderby'] = 'meta_value';
			$args['meta_key'] = '_swifty_event_date';
			$args['order'] = 'ASC';
		}

		$events_query = new WP_Query($args);

		// Get all categories for filter
		$categories = get_terms([
			'taxonomy' => 'event_category',
			'hide_empty' => true,
		]);

		// Get all unique locations for filter
		global $wpdb;
		$locations = $wpdb->get_col("
			SELECT DISTINCT meta_value 
			FROM {$wpdb->postmeta} 
			WHERE meta_key = '_swifty_event_location' 
			AND meta_value != ''
		");

		// Get all unique organizers for filter
		$organizers = $wpdb->get_col("
			SELECT DISTINCT meta_value 
			FROM {$wpdb->postmeta} 
			WHERE meta_key = '_swifty_event_organizer' 
			AND meta_value != ''
		");

		// Start output
		?>
		<div class="swifty-event-list" data-widget-id="<?php echo esc_attr($this->get_id()); ?>">
			
			<!-- Mobile Filter Trigger -->
			<div class="swifty-mobile-filter-trigger-wrapper">
				<button type="button" class="swifty-mobile-filter-btn">
					<span><?php _e('Filter Events', 'swifty-events'); ?></span>
					<i class="eicon-filter"></i>
				</button>
			</div>
			
			<!-- Backdrop for Mobile Modal -->
			<div class="swifty-filter-backdrop"></div>

			<!-- Wrapper with Sidebar -->
			<div class="swifty-events-wrapper-with-sidebar">
				
				<!-- Sidebar Filter Area (Moved to top in HTML for Flex Order 1? No, user wants right. HTML Order: Sidebar 2nd) -->
				<!-- However, strictly speaking, Mobile modal needs it accessible. -->
				
				<!-- Main Content Area -->
				<div class="swifty-events-main-content">
					
					<!-- Events Grid/Flex Container -->
					<div class="swifty-layout-<?php echo esc_attr($layout_type); ?> swifty-layout-<?php echo esc_attr($layout_type == 'grid' ? 'grid' : 'flex'); ?> swifty-flex-<?php echo esc_attr($layout_type == 'grid' ? 'row' : 'row'); ?> <?php echo esc_attr($enable_animations == 'yes' ? 'swifty-framer-animation' : ''); ?>">
						
						<?php if ($events_query->have_posts()) : ?>
							<?php while ($events_query->have_posts()) : $events_query->the_post(); ?>
								<?php 
								$event_date = get_post_meta(get_the_ID(), '_swifty_event_date', true);
								$event_location = get_post_meta(get_the_ID(), '_swifty_event_location', true);
								$event_organizer = get_post_meta(get_the_ID(), '_swifty_event_organizer', true);
								$enable_rsvp = get_post_meta(get_the_ID(), '_swifty_enable_rsvp', true);
								?>
								
								<div class="swifty-event-item swifty-skin-<?php echo esc_attr($settings['skin']); ?> <?php echo esc_attr($enable_animations == 'yes' ? 'swifty-anim-fade-up' : ''); ?>">
									
									<?php if (has_post_thumbnail()) : ?>
										<div class="swifty-event-thumbnail">
											<?php the_post_thumbnail('large'); ?>
										</div>
									<?php endif; ?>
									
									<div class="swifty-event-content">
										<?php if ($event_date) : ?>
											<span class="swifty-event-date">
												<?php echo date_i18n(get_option('date_format'), strtotime($event_date)); ?>
											</span>
										<?php endif; ?>
										
										<h3 class="swifty-event-title">
											<a href="<?php the_permalink(); ?>">
												<?php the_title(); ?>
											</a>
										</h3>
										
										<?php if ($event_location) : ?>
											<p class="swifty-event-location">
												<strong><?php _e('Location:', 'swifty-events'); ?></strong> 
												<?php echo esc_html($event_location); ?>
											</p>
										<?php endif; ?>
										
										<?php if ($event_organizer) : ?>
											<p class="swifty-event-organizer">
												<strong><?php _e('Organizer:', 'swifty-events'); ?></strong> 
												<?php echo esc_html($event_organizer); ?>
											</p>
										<?php endif; ?>
										
										<a href="<?php the_permalink(); ?>" class="swifty-btn-details">
											<?php _e('View Details', 'swifty-events'); ?>
										</a>
										
										<?php if ($enable_rsvp == 'yes') : ?>
											<button class="swifty-btn-rsvp" data-event-id="<?php echo get_the_ID(); ?>">
												<?php _e('Join Event', 'swifty-events'); ?>
											</button>
										<?php endif; ?>
									</div>
								</div>
								
							<?php endwhile; ?>
						<?php else : ?>
							<p class="swifty-no-events"><?php _e('No events found.', 'swifty-events'); ?></p>
						<?php endif; ?>
						
					</div>
					
					<!-- Pagination -->
					<?php if ($pagination_enable == 'yes' && $events_query->max_num_pages > 1) : ?>
						<div class="swifty-pagination swifty-pagination-<?php echo esc_attr($pagination_style); ?>">
							<?php
							echo paginate_links([
								'base' => add_query_arg( 'swifty_page', '%#%' ),
								'format' => '?swifty_page=%#%',
								'current' => max(1, $paged),
								'total' => $events_query->max_num_pages,
								'prev_text' => '&laquo;',
								'next_text' => '&raquo;',
								'type' => 'list',
								'add_args' => false,
							]);
							?>
						</div>
					<?php endif; ?>
					
				</div>
				

				<?php
			// --- SIDEBAR RENDER (Right Side) ---
			echo '<aside class="swifty-events-filter-sidebar">';
			
			// Modal Close Button (Mobile Only)
			echo '<button class="swifty-modal-close" type="button">&times;</button>';

			// Start Filter Form
			echo '<form class="swifty-filter-main-form" method="GET">';
			
			// 1. Search Bar (Top)
			echo '<div class="swifty-filter-group swifty-search-group">';
			echo '<div class="swifty-search-wrapper">';
			echo '<input type="text" name="search" class="swifty-search-input" placeholder="' . __( 'Search events...', 'swifty-events' ) . '" value="' . esc_attr( isset($_GET['search']) ? $_GET['search'] : '' ) . '">';
			echo '<button type="submit" class="swifty-search-icon-btn"><i class="eicon-search"></i></button>';
			echo '</div>';
			echo '</div>';

			// 2. Upcoming Quick Button (Colored)
			echo '<div class="swifty-filter-group swifty-quick-actions">';
			$is_upcoming = ( isset($_GET['upcoming']) && $_GET['upcoming'] == 'true' );
			echo '<button type="button" class="swifty-btn swifty-btn-upcoming ' . ( $is_upcoming ? 'active' : '' ) . '">';
			echo '<i class="eicon-calendar"></i> ' . __( 'Show Upcoming Events', 'swifty-events' );
			echo '</button>';
			// Hidden input to handle the state submission if form is submitted normally
			echo '<input type="hidden" name="upcoming" class="swifty-upcoming-input" value="' . ( $is_upcoming ? 'true' : '' ) . '">';
			echo '</div>';

			// 3. Categories Dropdown
			echo '<div class="swifty-filter-group">';
			echo '<label class="swifty-filter-label">' . __( 'Category', 'swifty-events' ) . '</label>';
			
			echo '<div class="swifty-select-wrapper">';
			echo '<select name="category" class="swifty-form-select">';
			echo '<option value="">' . __( 'All Categories', 'swifty-events' ) . '</option>';
			if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
				foreach ( $categories as $cat ) {
					$selected = ( isset($_GET['category']) && $_GET['category'] == $cat->slug ) ? 'selected' : '';
					echo '<option value="' . esc_attr( $cat->slug ) . '" ' . $selected . '>' . esc_html( $cat->name ) . '</option>';
				}
			}
			echo '</select>';
			echo '</div>';
			echo '</div>';

			// 4. Date Picker Range (Aligned Row)
			echo '<div class="swifty-filter-group">';
			echo '<label class="swifty-filter-label">' . __( 'Date Range', 'swifty-events' ) . '</label>';
			echo '<div class="swifty-date-range-row">'; // New Flex Row Wrapper
			echo '<input type="date" name="date_from" class="swifty-form-date" placeholder="' . __( 'From', 'swifty-events' ) . '" value="' . esc_attr( isset($_GET['date_from']) ? $_GET['date_from'] : '' ) . '">';
			echo '<input type="date" name="date_to" class="swifty-form-date" placeholder="' . __( 'To', 'swifty-events' ) . '" value="' . esc_attr( isset($_GET['date_to']) ? $_GET['date_to'] : '' ) . '">';
			echo '</div>';
			echo '</div>';

			// 5. Location Filter
			if ( ! empty( $locations ) ) {
				echo '<div class="swifty-filter-group">';
				echo '<label class="swifty-filter-label">' . __( 'Location', 'swifty-events' ) . '</label>';
				echo '<div class="swifty-select-wrapper">';
				echo '<select name="location" class="swifty-form-select">';
				echo '<option value="">' . __( 'All Locations', 'swifty-events' ) . '</option>';
				foreach ( $locations as $loc ) {
					$selected = ( isset($_GET['location']) && $_GET['location'] == $loc ) ? 'selected' : '';
					echo '<option value="' . esc_attr( $loc ) . '" ' . $selected . '>' . esc_html( $loc ) . '</option>';
				}
				echo '</select>';
				echo '</div>';
				echo '</div>';
			}

			// 6. Action Buttons
			echo '<div class="swifty-filter-actions">';
			echo '<button type="submit" class="swifty-btn swifty-btn-apply">' . __( 'Apply Filters', 'swifty-events' ) . '</button>';
			
			// Clear Button
			echo '<button type="button" class="swifty-btn swifty-btn-clear">' . __( 'Clear Filters', 'swifty-events' ) . '</button>';
			echo '</div>';
			
			echo '</form>'; // End Form
			echo '</aside>'; // End Sidebar

			echo '</div>'; // End Wrapper
		?>
		</div>
		<?php
		
		wp_reset_postdata();
		
		// Enqueue JavaScript
		$this->render_frontend_scripts();
	}
	
	private function render_frontend_scripts() {
		?>
		<script>
		document.addEventListener('DOMContentLoaded', function() {
			const widgetWrapper = document.querySelector('[data-widget-id="<?php echo esc_js($this->get_id()); ?>"]');
			if (!widgetWrapper) return;
			
			const mainWrapper = widgetWrapper.querySelector('.swifty-events-wrapper-with-sidebar');
			const sidebar = widgetWrapper.querySelector('.swifty-events-filter-sidebar');
			const mobileBtn = widgetWrapper.querySelector('.swifty-mobile-filter-btn');
			const closeBtn = widgetWrapper.querySelector('.swifty-modal-close');
			const backdrop = widgetWrapper.querySelector('.swifty-filter-backdrop'); // Assuming this exists outside wrapper in PHP, check placement
			
			// Modal Logic
			function openModal() {
				if(mainWrapper) mainWrapper.classList.add('swifty-filter-modal-active');
				document.body.style.overflow = 'hidden';
			}
			
			function closeModal() {
				if(mainWrapper) mainWrapper.classList.remove('swifty-filter-modal-active');
				document.body.style.overflow = '';
			}
			
			if (mobileBtn) mobileBtn.addEventListener('click', openModal);
			if (closeBtn) closeBtn.addEventListener('click', closeModal);
			if (backdrop) backdrop.addEventListener('click', closeModal);
			
			// Clear Filters
			const clearBtn = widgetWrapper.querySelector('.swifty-btn-clear');
			if (clearBtn) {
				clearBtn.addEventListener('click', function() {
					// Reload page without query params
					window.location.href = window.location.href.split('?')[0];
				});
			}

			// Upcoming Toggle logic
			const upcomingBtn = widgetWrapper.querySelector('.swifty-btn-upcoming');
			const upcomingInput = widgetWrapper.querySelector('.swifty-upcoming-input');
			if (upcomingBtn && upcomingInput) {
				upcomingBtn.addEventListener('click', function() {
					if (upcomingInput.value === 'true') {
						upcomingInput.value = '';
						this.classList.remove('active');
					} else {
						upcomingInput.value = 'true';
						this.classList.add('active');
					}
					// Auto submit? Or wait for Apply? User flow suggests waiting for Apply usually, but toggle buttons often auto-trigger. 
					// Let's stick to manual apply for consistency with other filters, or users get confused if page reloads immediately.
					// User logic seemed to want it to be a button.
				});
			}
		});
		</script>
		<?php
	}
}
