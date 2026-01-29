<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Swifty_Events_Wigh_List_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'swifty_wigh_event_list';
	}

	public function get_title() {
		return __( 'Wigh-Event List', 'swifty-events' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return [ 'swifty-events' ];
	}

	public function get_script_depends() {
		return [ 'swifty-events-frontend' ]; // Ensure frontend scripts are loaded
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'swifty-events' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label' => __( 'Posts Per Page', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 6,
			)
		);

		// Removed generic Category Filter Control since this is specific

		$this->end_controls_section();

		// --- LAYOUT SECTION ---
		$this->start_controls_section(
			'layout_section',
			array(
				'label' => __( 'Layout', 'swifty-events' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		// Layout Mode
		$this->add_control(
			'layout_mode',
			array(
				'label'   => __( 'Layout Mode', 'swifty-events' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => array(
					'grid' => __( 'Grid', 'swifty-events' ),
					'list' => __( 'List (Flex)', 'swifty-events' ),
				),
			)
		);
		
		$this->add_control(
			'skin',
			array(
				'label'   => __( 'Skin', 'swifty-events' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'gh2kenya',
				'options' => array(
					'gh2kenya' => __( 'Swifty Gradient (Kenya)', 'swifty-events' ),
					'glass'    => __( 'Glass Minimal', 'swifty-events' ),
				),
			)
		);
		
		$this->add_control(
			'show_excerpt',
			array(
				'label'        => __( 'Show Excerpt', 'swifty-events' ),
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
				'default'      => 'yes', // Default to yes for this specific list usually
				'separator'    => 'before',
			)
		);
		
		$this->add_control(
			'filter_btn_text',
			array(
				'label' => __( 'Filter Button Text', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Filter Events', 'swifty-events' ),
				'condition' => array(
					'show_filter_sidebar' => 'yes',
				),
			)
		);

		$this->add_control(
			'filter_btn_icon',
			array(
				'label' => __( 'Filter Button Icon', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => array(
					'value' => 'fas fa-filter',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'show_filter_sidebar' => 'yes',
				),
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
		
		// --- FILTER BUTTON STYLE SECTION ---
		$this->start_controls_section(
			'section_style_filter_btn',
			array(
				'label' => __( 'Filter Toggle Button', 'swifty-events' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_filter_sidebar' => 'yes',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'filter_btn_typography',
				'selector' => '{{WRAPPER}} .swifty-mobile-filter-btn',
			)
		);

		$this->start_controls_tabs( 'tabs_filter_btn_style' );

		$this->start_controls_tab(
			'tab_filter_btn_normal',
			array(
				'label' => __( 'Normal', 'swifty-events' ),
			)
		);

		$this->add_control(
			'filter_btn_text_color',
			array(
				'label' => __( 'Text Color', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .swifty-mobile-filter-btn' => 'color: {{VALUE}};',
					'{{WRAPPER}} .swifty-mobile-filter-btn i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .swifty-mobile-filter-btn svg' => 'fill: {{VALUE}};', // Added SVG fill
				),
			)
		);

		$this->add_control(
			'filter_btn_bg_color',
			array(
				'label' => __( 'Background Color', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .swifty-mobile-filter-btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_filter_btn_hover',
			array(
				'label' => __( 'Hover', 'swifty-events' ),
			)
		);

		$this->add_control(
			'filter_btn_hover_color',
			array(
				'label' => __( 'Text Color', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swifty-mobile-filter-btn:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .swifty-mobile-filter-btn:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .swifty-mobile-filter-btn:hover svg' => 'fill: {{VALUE}};', // Added SVG fill
				),
			)
		);

		$this->add_control(
			'filter_btn_hover_bg_color',
			array(
				'label' => __( 'Background Color', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swifty-mobile-filter-btn:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'filter_btn_border',
				'selector' => '{{WRAPPER}} .swifty-mobile-filter-btn',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'filter_btn_border_radius',
			array(
				'label' => __( 'Border Radius', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors' => array(
					'{{WRAPPER}} .swifty-mobile-filter-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'filter_btn_box_shadow',
				'selector' => '{{WRAPPER}} .swifty-mobile-filter-btn',
			)
		);

		$this->add_responsive_control(
			'filter_btn_padding',
			array(
				'label' => __( 'Padding', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors' => array(
					'{{WRAPPER}} .swifty-mobile-filter-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		
		// BASE QUERY: Always restrict to 'wigh-event'
		$args = [
			'post_type' => 'event',
			'posts_per_page' => $settings['posts_per_page'] ?: 6,
			'paged' => $paged,
			'post_status' => 'publish',
			'tax_query' => array(
				array(
					'taxonomy' => 'event_category',
					'field'    => 'slug',
					'terms'    => 'wigh-event', // STRICTLY WIGH-EVENT
				),
			),
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
		
		// Do NOT allow category filtering override
		
		if ( isset( $_GET['location'] ) && ! empty( $_GET['location'] ) ) {
			$args['meta_query'][] = [
				'key' => '_swifty_event_location',
				'value' => sanitize_text_field( $_GET['location'] ),
				'compare' => '='
			];
		}

		if ( isset( $_GET['date_from'] ) && ! empty( $_GET['date_from'] ) ) {
			$args['meta_query'][] = [
				'key' => '_swifty_event_date',
				'value' => sanitize_text_field( $_GET['date_from'] ),
				'compare' => '>=',
				'type' => 'DATE'
			];
		}

		if ( isset( $_GET['date_to'] ) && ! empty( $_GET['date_to'] ) ) {
			$args['meta_query'][] = [
				'key' => '_swifty_event_date',
				'value' => sanitize_text_field( $_GET['date_to'] ),
				'compare' => '<=',
				'type' => 'DATE'
			];
		}
		
		// Order By (Upcoming logic seems to use date filter, but let's keep it simple or default to date)
		if ( isset( $_GET['upcoming'] ) && $_GET['upcoming'] == 'true' ) {
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
		} else {
			// Default order usually by date desc or menu_order
		}

		$events_query = new WP_Query($args);

		// Get all unique locations for filter (Ideally we should scope this to wigh-events only but global is fine for now/performance)
		global $wpdb;
		$locations = $wpdb->get_col("
			SELECT DISTINCT meta_value 
			FROM {$wpdb->postmeta} 
			WHERE meta_key = '_swifty_event_location' 
			AND meta_value != ''
		");


		// Start output
		?>
		<div class="swifty-event-list" data-widget-id="<?php echo esc_attr($this->get_id()); ?>">
			
			<!-- Mobile Filter Trigger -->
			<?php if ( $settings['show_filter_sidebar'] === 'yes' ) : ?>
			<div class="swifty-mobile-filter-trigger-wrapper">
				<button type="button" class="swifty-mobile-filter-btn">
					<span><?php echo esc_html( $settings['filter_btn_text'] ); ?></span>
					<?php \Elementor\Icons_Manager::render_icon( $settings['filter_btn_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</button>
			</div>
			<?php endif; ?>
			
			<!-- Backdrop for Mobile Modal -->
			<div class="swifty-filter-backdrop"></div>

			<!-- Wrapper with Sidebar -->
			<?php 
			$wrapper_class = 'swifty-events-wrapper-with-sidebar';
			if ( $settings['show_filter_sidebar'] !== 'yes' ) {
				$wrapper_class .= ' swifty-no-sidebar';
			}
			?>
			<div class="<?php echo esc_attr( $wrapper_class ); ?>">
				
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
								
								<div class="swifty-event-item swifty-event-card swifty-skin-<?php echo esc_attr($settings['skin']); ?> <?php echo esc_attr($enable_animations == 'yes' ? 'swifty-anim-fade-up' : ''); ?>">
									
									<?php if (has_post_thumbnail()) : ?>
										<div class="swifty-event-image">
											<a href="<?php the_permalink(); ?>">
												<?php the_post_thumbnail('large'); ?>
											</a>
											<?php 
											$categories = get_the_terms(get_the_ID(), 'event_category');
											if ($categories && !is_wp_error($categories)) {
												$first_cat = $categories[0];
												echo '<span class="swifty-event-cat-badge">' . esc_html($first_cat->name) . '</span>';
											}
											?>
										</div>
									<?php endif; ?>
									
									<div class="swifty-event-content">
										<div class="swifty-event-meta-top">
											<?php if ($event_date) : ?>
												<span><i class="eicon-calendar"></i> <?php echo date_i18n(get_option('date_format'), strtotime($event_date)); ?></span>
											<?php endif; ?>
										</div>
										
										<h3 class="swifty-event-title">
											<a href="<?php the_permalink(); ?>" class="swifty-event-title-link">
												<?php the_title(); ?>
											</a>
										</h3>
										
										<?php if ( 'yes' === $settings['show_excerpt'] ) : ?>
										<div class="swifty-event-excerpt">
											<?php 
											$words = isset($settings['excerpt_length']) ? $settings['excerpt_length'] : 20;
											echo wp_trim_words(get_the_excerpt(), $words, '...'); 
											?>
										</div>
										<?php endif; ?>

										<div class="swifty-event-footer">
											<?php if ($event_date) : ?>
												<div class="swifty-event-date-badge">
													<span class="swifty-event-date-day"><?php echo date_i18n('d', strtotime($event_date)); ?></span>
													<span class="swifty-event-date-month"><?php echo date_i18n('M', strtotime($event_date)); ?></span>
												</div>
											<?php endif; ?>
											
											<a href="<?php the_permalink(); ?>" class="swifty-btn-details">
												<?php _e('View Details', 'swifty-events'); ?> <i class="eicon-arrow-right"></i>
											</a>
										</div>
									</div>
								</div>
								
							<?php endwhile; ?>
						<?php else : ?>
							<p class="swifty-no-events"><?php _e('No wigh-events found.', 'swifty-events'); ?></p>
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
			if ( $settings['show_filter_sidebar'] === 'yes' ) :
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


			// 2. Upcoming Toggle (Quick Action)
			echo '<div class="swifty-quick-actions">';
			$upcoming_active = ( isset($_GET['upcoming']) && $_GET['upcoming'] == 'true' ) ? 'active' : '';
			echo '<button type="button" class="swifty-btn swifty-btn-upcoming ' . $upcoming_active . '" style="border-radius: 12px; justify-content: center;">';
			echo '<i class="eicon-calendar"></i> ' . __( 'Upcoming Events', 'swifty-events' );
			echo '</button>';
			echo '<input type="hidden" name="upcoming" class="swifty-upcoming-input" value="' . esc_attr( isset($_GET['upcoming']) ? $_GET['upcoming'] : '' ) . '">';
			echo '</div>';

			// 3. Category Filter REMOVED for Wigh-Event Widget

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
			endif; // End show_filter_sidebar Check
			
			echo '</div>'; // End Wrapper
		?>
		</div>
		<?php
		
		wp_reset_postdata();
		
		// Enqueue JavaScript
		$this->render_frontend_scripts();
	}

	protected function render_frontend_scripts() {
		// Just output simple JS to init the widget, mainly for upcoming toggle and clear
		// We reuse the same logic, but we might need to ensure unique ID selectors if multiple widgets on page
		?>
		<script>
		(function() {
			const initWidget = function() {
				const widgetId = '<?php echo esc_js($this->get_id()); ?>';
				const widgetWrapper = document.querySelector('.swifty-event-list[data-widget-id="' + widgetId + '"]');
				if (!widgetWrapper) return;
				
				// Clear Filters Logic
				const clearBtn = widgetWrapper.querySelector('.swifty-btn-clear');
				if (clearBtn) {
					clearBtn.addEventListener('click', function() {
						const form = widgetWrapper.querySelector('.swifty-filter-main-form');
						if (form) {
							// Reset all inputs
							const inputs = form.querySelectorAll('input:not([type="hidden"]), select');
							inputs.forEach(input => input.value = '');
							// Reset upcoming hidden
							const upcomingInput = form.querySelector('.swifty-upcoming-input');
							if (upcomingInput) upcomingInput.value = '';
							
							form.submit();
						}
					});
				}

				// Upcoming Toggle logic
				const upcomingBtn = widgetWrapper.querySelector('.swifty-btn-upcoming');
				const upcomingInput = widgetWrapper.querySelector('.swifty-upcoming-input');
				if (upcomingBtn && upcomingInput) {
					upcomingBtn.addEventListener('click', function() {
						const isNowActive = !this.classList.contains('active');
						if (isNowActive) {
							upcomingInput.value = 'true';
							this.classList.add('active');
						} else {
							upcomingInput.value = '';
							this.classList.remove('active');
						}
					});
				}
			};

			// Run init
			if (document.readyState === 'loading') {
				document.addEventListener('DOMContentLoaded', initWidget);
			} else {
				initWidget();
			}
		})();
		</script>
		<?php
	}
}
