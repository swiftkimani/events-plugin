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

		// Handle Filtering from $_GET
		$filter_cat = isset( $_GET['swifty_cat'] ) ? sanitize_text_field( $_GET['swifty_cat'] ) : '';
		$filter_search = isset( $_GET['swifty_search'] ) ? sanitize_text_field( $_GET['swifty_search'] ) : '';
		
		// Date Range Filters
		$filter_date_from = isset( $_GET['swifty_date_from'] ) ? sanitize_text_field( $_GET['swifty_date_from'] ) : '';
		$filter_date_to   = isset( $_GET['swifty_date_to'] ) ? sanitize_text_field( $_GET['swifty_date_to'] ) : '';
		
		$filter_action = isset( $_GET['swifty_action'] ) ? sanitize_text_field( $_GET['swifty_action'] ) : ''; // For quick buttons like "Upcoming"

		$args = array(
			'post_type'      => 'event',
			'post_status'    => array( 'publish', 'future' ),
			'posts_per_page' => $settings['posts_per_page'],
		);
		
		// 1. Initial Widget Settings Filter
		if ( ! empty( $settings['category_filter'] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'event_category',
					'field'    => 'term_id',
					'terms'    => $settings['category_filter'],
				),
			);
		}

		// 2. Override/Append with Sidebar Filter
		// TAXONOMY FILTER
		if ( ! empty( $filter_cat ) ) {
			if ( empty( $args['tax_query'] ) ) {
				$args['tax_query'] = array();
			} else {
				$args['tax_query']['relation'] = 'AND';
			}

			$args['tax_query'][] = array(
				'taxonomy' => 'event_category',
				'field'    => 'slug',
				'terms'    => $filter_cat,
			);
		}

		// SEARCH FILTER
		if ( ! empty( $filter_search ) ) {
			$args['s'] = $filter_search;
		}
		
		// DATE FILTER LOGIC
		if ( empty( $args['meta_query'] ) ) {
			$args['meta_query'] = array( 'relation' => 'AND' );
		}

		if ( 'upcoming' === $filter_action ) {
			// Quick Action: Upcoming overrides custom dates
			$today = date( 'Y-m-d' );
			$args['meta_query'][] = array(
				'key'     => '_swifty_event_date',
				'value'   => $today,
				'compare' => '>=',
				'type'    => 'DATE',
			);
			$args['orderby'] = 'meta_value';
			$args['meta_key'] = '_swifty_event_date';
			$args['order'] = 'ASC';
			
			// Clear custom inputs for UI consistency if desired, or keep them to show what "Upcoming" means? 
			// Usually "Upcoming" is a preset. We'll leave inputs empty in form if action is set, handled in form output.
		} else {
			// Handle Custom Date Range
			
			// From Date
			if ( ! empty( $filter_date_from ) ) {
				$args['meta_query'][] = array(
					'key'     => '_swifty_event_date',
					'value'   => $filter_date_from,
					'compare' => '>=',
					'type'    => 'DATE',
				);
			}

			// To Date
			if ( ! empty( $filter_date_to ) ) {
				$args['meta_query'][] = array(
					'key'     => '_swifty_event_date',
					'value'   => $filter_date_to,
					'compare' => '<=',
					'type'    => 'DATE',
				);
			}

			// Order by date if any date filter is active, otherwise default (date desc)
			if ( ! empty( $filter_date_from ) || ! empty( $filter_date_to ) ) {
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = '_swifty_event_date';
				$args['order'] = 'ASC';
			}
		}

		$query = new \WP_Query( $args );

		// START OUTPUT
		$show_sidebar = ( ! empty( $settings['show_filter_sidebar'] ) && 'yes' === $settings['show_filter_sidebar'] );

		if ( $show_sidebar ) {
			echo '<div class="swifty-events-wrapper-with-sidebar swifty-sidebar-right">'; // Added class for Right Sidebar
			
			// --- MOBILE FILTER TRIGGER ---
			echo '<div class="swifty-mobile-filter-trigger-wrapper">';
			echo '<button class="swifty-mobile-filter-btn" onclick="document.querySelector(\'.swifty-events-wrapper-with-sidebar\').classList.add(\'swifty-filter-modal-active\');">';
			echo '<i class="eicon-filter"></i> ' . __( 'Filter Events', 'swifty-events' );
			echo '</button>';
			echo '</div>';
			
			echo '<div class="swifty-events-main-content">';
		}

		// Pagination Setup
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		if ( isset( $_GET['swifty_page'] ) ) {
			$paged = intval( $_GET['swifty_page'] );
		}

		$args = array(
			'post_type'      => 'event',
			'post_status'    => array( 'publish', 'future' ),
			'posts_per_page' => $settings['posts_per_page'],
			'paged'          => $paged,
		);
		
		// 1. Initial Widget Settings Filter
		if ( ! empty( $settings['category_filter'] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'event_category',
					'field'    => 'term_id',
					'terms'    => $settings['category_filter'],
				),
			);
		}

		// 2. Override/Append with Sidebar Filter
		// TAXONOMY FILTER
		if ( ! empty( $filter_cat ) ) {
			if ( empty( $args['tax_query'] ) ) {
				$args['tax_query'] = array();
			} else {
				$args['tax_query']['relation'] = 'AND';
			}

			$args['tax_query'][] = array(
				'taxonomy' => 'event_category',
				'field'    => 'slug',
				'terms'    => $filter_cat,
			);
		}

		// SEARCH FILTER
		if ( ! empty( $filter_search ) ) {
			$args['s'] = $filter_search;
		}
		
		// DATE FILTER LOGIC
		if ( empty( $args['meta_query'] ) ) {
			$args['meta_query'] = array( 'relation' => 'AND' );
		}
		
		// ... (Date filters handled by reuse of existing logic if possible, or just re-add)
		// Re-implementing date filter logic here correctly since we replaced the block
		
		// From Date
		if ( ! empty( $filter_date_from ) ) {
			$args['meta_query'][] = array(
				'key'     => '_swifty_event_date',
				'value'   => $filter_date_from,
				'compare' => '>=',
				'type'    => 'DATE',
			);
		}

		// To Date
		if ( ! empty( $filter_date_to ) ) {
			$args['meta_query'][] = array(
				'key'     => '_swifty_event_date',
				'value'   => $filter_date_to,
				'compare' => '<=',
				'type'    => 'DATE',
			);
		}

		// Order by date default
		if ( empty( $args['orderby'] ) ) {
			$args['orderby'] = 'meta_value';
			$args['meta_key'] = '_swifty_event_date';
			$args['order'] = 'ASC';
		}

		$query = new \WP_Query( $args );
		
		// Framer Animation Wrapper Class
		$wrapper_classes = 'swifty-event-list-wrapper';
		if ( 'yes' === $settings['swifty_framer_animation'] ) {
			$wrapper_classes .= ' swifty-framer-animation';
		}
		
		echo '<div class="' . esc_attr( $wrapper_classes ) . '">';
		
		// --- PAGINATION TOP ---
		if ( 'yes' === $settings['swifty_pagination_enable'] && in_array( $settings['swifty_pagination_position'], array( 'top', 'both' ) ) ) {
			$this->render_pagination( $query, $settings, 'top' );
		}

		if ( $query->have_posts() ) {
			// Logic for Animation Stagger
			$anim_class = '';
			if ( 'fade-up' === $settings['animation'] ) {
				$anim_class = 'swifty-anim-fade-up';
			} elseif ( 'zoom-in' === $settings['animation'] ) {
				$anim_class = 'swifty-anim-zoom-in';
			}

			// Add layout attributes
			$layout_class = 'swifty-layout-' . esc_attr( $settings['layout_mode'] );
			if ( 'flex' === $settings['layout_mode'] ) {
				$layout_class .= ' swifty-flex-' . esc_attr( $settings['flex_direction'] );
			}

			echo '<div class="swifty-event-list ' . $layout_class . ' swifty-skin-' . esc_attr( $settings['skin'] ) . '">';
			
			$delay = 0;
			while ( $query->have_posts() ) {
				$query->the_post();
				$event_date = get_post_meta( get_the_ID(), '_swifty_event_date', true );
				$excerpt = wp_trim_words( get_the_excerpt(), $settings['excerpt_length'], '...' );
				
				// Ensure item is visible if animation is none
				$style = '';
				if ( ! empty( $anim_class ) ) {
					$style = 'style="animation-delay: ' . $delay . 's;"';
				}

				echo '<div class="swifty-event-item ' . esc_attr( $anim_class ) . '" ' . $style . '>';
				
				if ( 'glass' === $settings['skin'] ) {
					// GLASS MINIMAL SKIN
					if ( ! empty( $settings['show_date'] ) && 'yes' === $settings['show_date'] && $event_date ) {
						echo '<div class="swifty-date-box">';
						echo '<span class="swifty-day">' . date_i18n( 'd', strtotime( $event_date ) ) . '</span>';
						echo '<span class="swifty-month">' . date_i18n( 'M', strtotime( $event_date ) ) . '</span>';
						echo '</div>';
					}
					echo '<div class="swifty-content">';
					echo '<h3 class="swifty-event-title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h3>';
					echo '<div class="swifty-event-excerpt">' . $excerpt . '</div>';
					echo '<a href="' . get_the_permalink() . '" class="swifty-read-more">' . __( 'Explore', 'swifty-events' ) . '</a>';
					echo '</div>'; // End content

				} elseif ( 'gradient_glass' === $settings['skin'] ) {
					// GRADIENT GLASS SKIN (Replacing Neon)
					echo '<div class="swifty-event-thumbnail">';
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'medium_large' );
					}
					echo '</div>';
					
					echo '<div class="swifty-event-content">';
					if ( ! empty( $settings['show_date'] ) && 'yes' === $settings['show_date'] && $event_date ) {
						echo '<span class="swifty-event-date">' . date_i18n( get_option( 'date_format' ), strtotime( $event_date ) ) . '</span>';
					}
					echo '<h3 class="swifty-event-title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h3>';
					echo '<div class="swifty-event-excerpt">' . $excerpt . '</div>';
					echo '<a href="' . get_the_permalink() . '" class="swifty-btn-details">' . __( 'Explore Event', 'swifty-events' ) . '</a>';
					echo '</div>'; 

				} else {
					// GH2KENYA PREMIUM CARD (Default)
					if ( has_post_thumbnail() ) {
						echo '<div class="swifty-event-thumbnail">';
						echo '<a href="' . get_the_permalink() . '">';
						the_post_thumbnail( 'large' );
						echo '</a>';
						echo '</div>';
					}
					
					echo '<div class="swifty-event-content">';
					if ( ! empty( $settings['show_date'] ) && 'yes' === $settings['show_date'] && $event_date ) {
						echo '<span class="swifty-event-date">' . date_i18n( get_option( 'date_format' ), strtotime( $event_date ) ) . '</span>';
					}
					echo '<h3 class="swifty-event-title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h3>';
					echo '<div class="swifty-event-excerpt">' . $excerpt . '</div>';
					
					echo '<a href="' . get_the_permalink() . '" class="swifty-btn-details">' . __( 'View Event', 'swifty-events' ) . '</a>';
					echo '</div>'; 
				}
				
				echo '</div>';
				$delay += 0.1; // Increment delay for stagger effect
			}
			echo '</div>';
			wp_reset_postdata();
		} else {
			echo '<div class="swifty-no-events" style="padding: 20px; text-align: center; color: #555; border: 1px dashed #ccc;">' . __( 'No events found.', 'swifty-events' ) . '</div>';
		}

		// --- PAGINATION BOTTOM ---
		if ( 'yes' === $settings['swifty_pagination_enable'] && in_array( $settings['swifty_pagination_position'], array( 'bottom', 'both' ) ) ) {
			$this->render_pagination( $query, $settings, 'bottom' );
		}

		if ( $show_sidebar ) {
			echo '</div>'; // End Main Content
			
			// --- SIDEBAR RENDER (Right Side) ---
			echo '<aside class="swifty-events-filter-sidebar">';
			
			// Modal Close Button (Mobile Only)
			echo '<button class="swifty-modal-close" onclick="document.querySelector(\'.swifty-events-wrapper-with-sidebar\').classList.remove(\'swifty-filter-modal-active\');">&times;</button>';

			// Start Filter Form
			echo '<form class="swifty-filter-main-form" method="GET">';
			
			// 1. Search Bar (Top)
			echo '<div class="swifty-filter-group swifty-search-group">';
			echo '<div class="swifty-search-wrapper">';
			echo '<input type="text" name="swifty_search" class="swifty-search-input" placeholder="' . __( 'Search events...', 'swifty-events' ) . '" value="' . esc_attr( $filter_search ) . '">';
			echo '<button type="submit" class="swifty-search-icon-btn"><i class="eicon-search"></i></button>';
			echo '</div>';
			echo '</div>';

			// 2. Upcoming Quick Button
			echo '<div class="swifty-quick-actions" style="margin-bottom: 28px;">';
			echo '<button type="submit" name="swifty_action" value="upcoming" class="swifty-btn swifty-btn-upcoming ' . ( 'upcoming' === $filter_action ? 'active' : '' ) . '">' . __( 'Upcoming Events', 'swifty-events' ) . '</button>';
			echo '</div>';

			// 3. Categories Dropdown
			echo '<div class="swifty-filter-group">';
			echo '<label class="swifty-filter-label">' . __( 'Category', 'swifty-events' ) . '</label>';
			
			$cat_args = array( 'taxonomy' => 'event_category', 'hide_empty' => true );
			if ( ! empty( $settings['category_filter'] ) ) { $cat_args['include'] = $settings['category_filter']; }
			$categories = get_terms( $cat_args );
			
			echo '<div class="swifty-select-wrapper">';
			echo '<select name="swifty_cat" class="swifty-form-select">';
			echo '<option value="">' . __( 'All Categories', 'swifty-events' ) . '</option>';
			if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
				foreach ( $categories as $cat ) {
					echo '<option value="' . esc_attr( $cat->slug ) . '" ' . selected( $filter_cat, $cat->slug, false ) . '>' . esc_html( $cat->name ) . '</option>';
				}
			}
			echo '</select>';
			echo '</div>';
			echo '</div>';

			// 4. Date Picker Range
			echo '<div class="swifty-filter-group">';
			echo '<label class="swifty-filter-label">' . __( 'Date From', 'swifty-events' ) . '</label>';
			echo '<input type="date" name="swifty_date_from" class="swifty-form-date" min="2020-01-01" value="' . esc_attr( $filter_date_from ) . '" style="margin-bottom: 10px;">';
			echo '<label class="swifty-filter-label">' . __( 'Date To', 'swifty-events' ) . '</label>';
			echo '<input type="date" name="swifty_date_to" class="swifty-form-date" min="2020-01-01" value="' . esc_attr( $filter_date_to ) . '">';
			echo '</div>';

			// 5. Action Buttons
			echo '<div class="swifty-filter-actions">';
			echo '<button type="submit" class="swifty-btn swifty-btn-apply">' . __( 'Apply Filter', 'swifty-events' ) . '</button>';
			
			// Clear Button
			$reset_url = remove_query_arg( array( 'swifty_cat', 'swifty_search', 'swifty_date_from', 'swifty_date_to', 'swifty_action', 'swifty_page' ) ); // Added swifty_page removal
			echo '<a href="' . esc_url( $reset_url ) . '" class="swifty-btn swifty-btn-clear">' . __( 'Clear Filter', 'swifty-events' ) . '</a>';
			echo '</div>';
			
			echo '</form>'; // End Form
			echo '</aside>'; // End Sidebar

			echo '</div>'; // End Wrapper
		}
	}
	
	protected function render_pagination( $query, $settings, $context ) {
		$total_pages = $query->max_num_pages;
		if ( $total_pages < 2 ) { return; }
		
		$current_page = max( 1, get_query_var( 'paged' ), isset( $_GET['swifty_page'] ) ? intval( $_GET['swifty_page'] ) : 1 );
		
		$style_class = 'swifty-pagination-' . $settings['swifty_pagination_style'];
		$pos_class = 'swifty-pagination-' . $context;
		
		echo '<div class="swifty-pagination ' . esc_attr( $style_class ) . ' ' . esc_attr( $pos_class ) . '">';
		
		$big = 999999999; // need an unlikely integer
		$args = array(
			'base' => add_query_arg( 'swifty_page', '%#%' ),
			'format' => '?swifty_page=%#%',
			'current' => $current_page,
			'total' => $total_pages,
			'prev_text' => '<i class="eicon-chevron-left"></i>',
			'next_text' => '<i class="eicon-chevron-right"></i>',
			'type' => 'list',
		);
		
		if ( 'prevnext' === $settings['swifty_pagination_style'] ) {
			$args['mid_size'] = 0;
			$args['end_size'] = 0;
			$args['prev_next'] = true;
		}
		
		echo paginate_links( $args );
		
		echo '</div>';
	}
}
