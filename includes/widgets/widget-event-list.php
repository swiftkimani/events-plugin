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
					'grid' => __( 'Grid (Strict)', 'swifty-events' ),
					'flex' => __( 'Flexbox (Flow)', 'swifty-events' ),
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
					'neon' => __( 'Neon Tech', 'swifty-events' ),
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
			'category_filter',
			array(
				'label'   => __( 'Filter by Category', 'swifty-events' ),
				'type'    => \Elementor\Controls_Manager::SELECT2,
				'options' => $cat_options,
				'multiple' => true,
				'label_block' => true,
			)
		);
		
		$this->add_control(
			'show_calendar_btn',
			array(
				'label' => __( 'Show Add to Calendar', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
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

		$args = array(
			'post_type'      => 'event',
			'posts_per_page' => $settings['posts_per_page'],
		);
		
		if ( ! empty( $settings['category_filter'] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'event_category',
					'field'    => 'term_id',
					'terms'    => $settings['category_filter'],
				),
			);
		}

		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) {
			// Logic for Animation Stagger
			$anim_class = '';
			if ( 'fade-up' === $settings['animation'] ) {
				$anim_class = 'swifty-anim-fade-up';
			} elseif ( 'zoom-in' === $settings['animation'] ) {
				$anim_class = 'swifty-anim-zoom-in';
			}

			echo '<div class="swifty-event-list swifty-layout-' . esc_attr( $settings['layout_mode'] ) . ' swifty-skin-' . esc_attr( $settings['skin'] ) . '">';
			
			$delay = 0;
			while ( $query->have_posts() ) {
				$query->the_post();
				$event_date = get_post_meta( get_the_ID(), '_swifty_event_date', true );
				$excerpt = wp_trim_words( get_the_excerpt(), $settings['excerpt_length'], '...' );
				$google_cal_link = 'https://www.google.com/calendar/render?action=TEMPLATE&text=' . urlencode( get_the_title() ) . '&dates=' . date( 'Ymd', strtotime( $event_date ) ) . '/' . date( 'Ymd', strtotime( $event_date ) ) . '&details=' . urlencode( get_the_excerpt() );

				// Inline animation delay
				$style_delay = $delay > 0 ? 'style="animation-delay: ' . $delay . 's;"' : '';
				
				echo '<div class="swifty-event-item ' . esc_attr( $anim_class ) . '" ' . $style_delay . '>';
				
				if ( 'glass' === $settings['skin'] ) {
					// GLASS MINIMAL SKIN
					if ( 'yes' === $settings['show_date'] && $event_date ) {
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

				} elseif ( 'neon' === $settings['skin'] ) {
					// NEON TECH SKIN
					echo '<div class="swifty-event-content">';
					if ( 'yes' === $settings['show_date'] && $event_date ) {
						echo '<div class="swifty-event-date">> SYSTEM_DATE: ' . date_i18n( 'Y.m.d', strtotime( $event_date ) ) . '</div>';
					}
					echo '<h3 class="swifty-event-title">' . get_the_title() . '</h3>';
					echo '<div class="swifty-event-excerpt">' . $excerpt . '</div>';
					echo '<a href="' . get_the_permalink() . '" style="color:var(--color-primary-start); text-transform:uppercase; font-size:0.8rem; letter-spacing:1px;">[ ACCESS_DATA ]</a>';
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
					if ( 'yes' === $settings['show_date'] && $event_date ) {
						echo '<span class="swifty-event-date">' . date_i18n( get_option( 'date_format' ), strtotime( $event_date ) ) . '</span>';
					}
					echo '<h3 class="swifty-event-title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h3>';
					echo '<div class="swifty-event-excerpt">' . $excerpt . '</div>';
					
					if ( 'yes' === $settings['show_calendar_btn'] ) {
						echo '<a href="' . esc_url( $google_cal_link ) . '" target="_blank" class="swifty-add-calendar-btn"><span class="dashicons dashicons-calendar-alt"></span> ' . __( 'Add to Calendar', 'swifty-events' ) . '</a>';
					}
					
					echo '<a href="' . get_the_permalink() . '" class="swifty-btn-details">' . __( 'View Event', 'swifty-events' ) . '</a>';
					echo '</div>'; 
				}
				
				echo '</div>';
				$delay += 0.1; // Increment delay for stagger effect
			}
			echo '</div>';
			wp_reset_postdata();
		} else {
			echo __( 'No events found.', 'swifty-events' );
		}
	}

}
