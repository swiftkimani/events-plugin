<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Swifty_Events_Latest_Category_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'swifty_event_latest_category';
	}

	public function get_title() {
		return __( 'Latest Category Events', 'swifty-events' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return [ 'swifty-events' ];
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
			'widget_title',
			array(
				'label' => __( 'Section Title', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Latest Events', 'swifty-events' ),
				'placeholder' => __( 'Enter title', 'swifty-events' ),
			)
		);

		// Get Event Categories
		$categories = get_terms( 'event_category' );
		$options = array();
		if ( ! is_wp_error( $categories ) ) {
			foreach ( $categories as $category ) {
				$options[ $category->term_id ] = $category->name;
			}
		}

		$this->add_control(
			'selected_category',
			array(
				'label' => __( 'Select Category', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'options' => $options,
				'multiple' => false,
				'description' => __( 'Select the category to display events from.', 'swifty-events' ),
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label' => __( 'Number of Events', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 4,
				'min' => 1,
				'max' => 12,
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

		$this->end_controls_section();
		
		// Style Section - Title
		$this->start_controls_section(
			'section_style_title',
			array(
				'label' => __( 'Title Style', 'swifty-events' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
		$this->add_control(
			'title_color',
			array(
				'label' => __( 'Color', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swifty-widget-title' => 'color: {{VALUE}}',
				),
			)
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .swifty-widget-title',
			)
		);
		
		$this->add_responsive_control(
			'title_margin',
			array(
				'label' => __( 'Margin', 'swifty-events' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors' => array(
					'{{WRAPPER}} .swifty-widget-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC', // Latest
		);

		if ( ! empty( $settings['selected_category'] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'event_category',
					'field'    => 'term_id',
					'terms'    => $settings['selected_category'],
				),
			);
		}

		$query = new \WP_Query( $args );

		?>
		<div class="swifty-event-latest-widget swifty-no-sidebar">
			
			<?php if ( ! empty( $settings['widget_title'] ) ) : ?>
				<h2 class="swifty-widget-title"><?php echo esc_html( $settings['widget_title'] ); ?></h2>
			<?php endif; ?>

			<!-- Grid Layout (Forcing Grid for Latest) -->
			<!-- Using swifty-layout-grid class to inherit styles -->
			<!-- Adding width: 100% inline for safety or relying on CSS .swifty-no-sidebar rule -->
			<div class="swifty-layout-grid swifty-flex-row swifty-framer-animation">

				<?php if ( $query->have_posts() ) : ?>
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<?php 
						$event_date = get_post_meta( get_the_ID(), '_swifty_event_date', true );
						?>
						
						<!-- Reusing exact same card markup for consistency -->
						<div class="swifty-event-item swifty-event-card swifty-skin-<?php echo esc_attr( $settings['skin'] ); ?> swifty-anim-fade-up">
							
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="swifty-event-image">
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail( 'large' ); ?>
									</a>
									<?php 
									$cats = get_the_terms( get_the_ID(), 'event_category' );
									if ( $cats && ! is_wp_error( $cats ) ) {
										echo '<span class="swifty-event-cat-badge">' . esc_html( $cats[0]->name ) . '</span>';
									}
									?>
								</div>
							<?php endif; ?>
							
							<div class="swifty-event-content">
								<div class="swifty-event-meta-top">
									<?php if ( $event_date ) : ?>
										<span><i class="eicon-calendar"></i> <?php echo date_i18n( get_option( 'date_format' ), strtotime( $event_date ) ); ?></span>
									<?php endif; ?>
								</div>
								
								<h3 class="swifty-event-title">
									<a href="<?php the_permalink(); ?>" class="swifty-event-title-link">
										<?php the_title(); ?>
									</a>
								</h3>
								
								<?php if ( 'yes' === $settings['show_excerpt'] ) : ?>
								<div class="swifty-event-excerpt">
									<?php echo wp_trim_words( get_the_excerpt(), 12, '...' ); ?>
								</div>
								<?php endif; ?>

								<div class="swifty-event-footer">
									<?php if ( $event_date ) : ?>
										<div class="swifty-event-date-badge">
											<span class="swifty-event-date-day"><?php echo date_i18n( 'd', strtotime( $event_date ) ); ?></span>
											<span class="swifty-event-date-month"><?php echo date_i18n( 'M', strtotime( $event_date ) ); ?></span>
										</div>
									<?php endif; ?>
									
									<a href="<?php the_permalink(); ?>" class="swifty-btn-details">
										<?php _e( 'View Details', 'swifty-events' ); ?> <i class="eicon-arrow-right"></i>
									</a>
								</div>
							</div>
						</div>
						
					<?php endwhile; ?>
				<?php else : ?>
					<div class="swifty-no-events"><?php _e( 'No events found in this category.', 'swifty-events' ); ?></div>
				<?php endif; ?>
				<?php wp_reset_postdata(); ?>

			</div>
		</div>
		<?php
	}
}
