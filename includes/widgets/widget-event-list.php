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
			'show_date',
			array(
				'label' => __( 'Show Date', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'swifty-events' ),
				'label_off' => __( 'Hide', 'swifty-events' ),
				'return_value' => 'yes',
				'default' => 'yes',
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
			echo '<div class="swifty-event-list">';
			while ( $query->have_posts() ) {
				$query->the_post();
				$event_date = get_post_meta( get_the_ID(), '_swifty_event_date', true );
				echo '<div class="swifty-event-item">';
				
				if ( has_post_thumbnail() ) {
					echo '<div class="swifty-event-thumbnail">';
					the_post_thumbnail( 'medium' );
					echo '</div>';
				}
				
				echo '<h3 class="swifty-event-title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h3>';
				
				if ( 'yes' === $settings['show_date'] && $event_date ) {
					echo '<p class="swifty-event-date">' . date_i18n( get_option( 'date_format' ), strtotime( $event_date ) ) . '</p>';
				}
				
				echo '<div class="swifty-event-excerpt">' . get_the_excerpt() . '</div>';
				
				echo '</div>';
			}
			echo '</div>';
			wp_reset_postdata();
		} else {
			echo __( 'No events found.', 'swifty-events' );
		}
	}

}
