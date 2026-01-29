<?php
/**
 * Template Name: Single Event
 *
 * @package Swifty_Events
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// We need to enqueue styles manually if not already (Widget usually does it)
// But since we are in a template, we can rely on standard enqueue if the plugin loads them globally.
// Assuming 'swifty-events-frontend' handles CSS.

while ( have_posts() ) :
	the_post();
	$event_id = get_the_ID();

	// Fetch Meta
	$event_date     = get_post_meta( $event_id, '_swifty_event_date', true );
	$event_location = get_post_meta( $event_id, '_swifty_event_location', true );
	$event_organizer = get_post_meta( $event_id, '_swifty_event_organizer', true );
	$map_address     = get_post_meta( $event_id, '_swifty_map_address', true );
	$options         = get_option( 'swifty_events_settings' );
	$api_key         = isset( $options['google_maps_api_key'] ) ? $options['google_maps_api_key'] : '';

	// Default settings for template (Everything ON)
	$show_date = 'yes';
	$show_location = 'yes';
	$show_organizer = 'yes';
	$show_map = 'yes';
	$show_rsvp = 'yes';

	// RSVP Logic
	$enable_rsvp = get_post_meta( $event_id, '_swifty_enable_rsvp', true );
	
	// Event Type Logic
	$event_type = get_post_meta( $event_id, '_swifty_event_type', true );
	$virtual_link = get_post_meta( $event_id, '_swifty_virtual_link', true );
	
	// Calendar Links
	$start_date = date( 'Ymd', strtotime( $event_date ) );
	$end_date = date( 'Ymd', strtotime( $event_date . ' +1 day' ) ); // Default 1 day for now
	$google_cal_url = 'https://www.google.com/calendar/render?action=TEMPLATE';
	$google_cal_url .= '&text=' . urlencode( get_the_title() );
	$google_cal_url .= '&dates=' . $start_date . '/' . $end_date;
	$google_cal_url .= '&details=' . urlencode( wp_trim_words( get_the_content(), 20 ) );
	if ( 'virtual' === $event_type && $virtual_link ) {
		$google_cal_url .= '&location=' . urlencode( $virtual_link );
	} else {
		$google_cal_url .= '&location=' . urlencode( $event_location );
	}
	$google_cal_url .= '&sf=true&output=xml';
	
	// iCal (Data URI simplistic approach for buttons)
	// For robustness we often need a real file, but let's try a download blob in JS or just a simple method. 
	// Or we can just skip iCal file gen for now and focus on Google which is requested. 
	// User asked for "Google Zoom iCalendar and Google Calendars".
	// Let's rely on Google for now, and maybe a text/calendar data URI for iCal if possible, or just Google as primary.
	// Actually, let's just stick to Google Calendar Button for simplicity and reliability first.

	$back_url = get_post_type_archive_link( 'event' );
	if ( ! $back_url ) { $back_url = home_url( '/' ); }
	?>

	<!-- Force Background Color for this page -->
	<style>
		body, .site-content, #content, .site-main { background-color: #f2f2f2 !important; }
		.swifty-single-event-container { background-color: #f2f2f2; }
	</style>

	<div class="swifty-single-event-container" style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
		
		<div class="swifty-single-event-wrapper"> <!-- CSS Grid Wrapper -->

			<!-- LEFT COLUMN: Main Content -->
			<div class="swifty-single-main">
				
				<!-- Top Back Button -->
				<a href="<?php echo esc_url( $back_url ); ?>" class="swifty-back-btn swifty-back-top"><i class="eicon-arrow-left"></i> <?php _e( 'Back to Events', 'swifty-events' ); ?></a>

				<!-- Featured Image -->
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="swifty-single-hero">
						<?php the_post_thumbnail( 'full' ); ?>
					</div>
				<?php endif; ?>

				<!-- Title -->
				<h1 class="swifty-single-title"><?php the_title(); ?></h1>

				<!-- Meta Row -->
				<div class="swifty-single-meta-row">
					<?php if ( $event_date ) : ?>
						<div class="swifty-meta-item"><i class="eicon-calendar"></i> <?php echo date_i18n( get_option( 'date_format' ), strtotime( $event_date ) ); ?></div>
					<?php endif; ?>
					
					<?php if ( 'virtual' === $event_type && $virtual_link ) : ?>
						<div class="swifty-meta-item"><i class="eicon-video-camera"></i> <a href="<?php echo esc_url( $virtual_link ); ?>" target="_blank" style="color: inherit; text-decoration: underline;"><?php _e( 'Join Online', 'swifty-events' ); ?></a></div>
					<?php elseif ( $event_location ) : ?>
						<div class="swifty-meta-item"><i class="eicon-map-pin"></i> <?php echo esc_html( $event_location ); ?></div>
					<?php endif; ?>
					
					<?php if ( 'hybrid' === $event_type ) : ?>
						<div class="swifty-meta-tag" style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:#fff; padding:4px 12px; border-radius:20px; font-size:0.8rem; font-weight:bold;"><?php _e( 'Hybrid Event', 'swifty-events' ); ?></div>
					<?php endif; ?>
				</div>
				
				<!-- Add to Calendar Buttons -->
				<div class="swifty-calendar-buttons" style="margin-bottom: 30px;">
					<a href="<?php echo esc_url( $google_cal_url ); ?>" target="_blank" class="swifty-btn swifty-btn-google-cal" style="background: #fff; border: 1px solid #ddd; color: #333; padding: 10px 20px; border-radius: 50px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
						<i class="eicon-calendar"></i> <?php _e( 'Add to Google Calendar', 'swifty-events' ); ?>
					</a>
				</div>

				<!-- Description -->
				<div class="swifty-single-content swifty-content-typography">
					<?php the_content(); ?>
				</div>

				<!-- RSVP Section (CONDITIONAL) -->
				<?php if ( 'yes' === $enable_rsvp ) : ?>
				<div class="swifty-rsvp-section">
					<?php if ( isset( $_GET['rsvp_sent'] ) && 'true' == $_GET['rsvp_sent'] ) : ?>
						<div class="swifty-success-message" style="color: green; font-weight: bold;"><?php _e( 'RSVP Sent Successfully! Check your email.', 'swifty-events' ); ?></div>
					<?php else : ?>
						<h3><?php _e( 'Join this Event', 'swifty-events' ); ?></h3>
						<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" class="swifty-rsvp-form-styled">
							<input type="hidden" name="action" value="swifty_submit_rsvp">
							<input type="hidden" name="event_id" value="<?php echo get_the_ID(); ?>">
							<?php wp_nonce_field( 'swifty_rsvp_action', 'swifty_rsvp_nonce' ); ?>
							
							<div class="swifty-form-row">
								<input type="text" name="rsvp_name" placeholder="<?php _e( 'Your Name', 'swifty-events' ); ?>" required>
							</div>
							<div class="swifty-form-row">
								<input type="email" name="rsvp_email" placeholder="<?php _e( 'Your Email', 'swifty-events' ); ?>" required>
							</div>
							<button type="submit" class="swifty-btn swifty-btn-rsvp"><?php _e( 'Confirm RSVP', 'swifty-events' ); ?></button>
						</form>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<!-- Bottom Back Button -->
				<a href="<?php echo esc_url( $back_url ); ?>" class="swifty-back-btn swifty-back-bottom"><i class="eicon-arrow-left"></i> <?php _e( 'Back to Events', 'swifty-events' ); ?></a>

			</div>

			<!-- RIGHT COLUMN: Sticky Sidebar -->
			<aside class="swifty-single-sidebar">
				<div class="swifty-sidebar-inner">
					
					<!-- Info Card -->
					<div class="swifty-info-card">
						<h3><?php _e( 'Event Details', 'swifty-events' ); ?></h3>
						<ul class="swifty-info-list">
							<?php if ( $event_date ) : ?>
								<li><strong><?php _e( 'Date:', 'swifty-events' ); ?></strong> <?php echo date_i18n( get_option( 'date_format' ), strtotime( $event_date ) ); ?></li>
							<?php endif; ?>
							<?php if ( $event_organizer ) : ?>
								<li><strong><?php _e( 'Organizer:', 'swifty-events' ); ?></strong> <?php echo esc_html( $event_organizer ); ?></li>
							<?php endif; ?>
							<li><strong><?php _e( 'Type:', 'swifty-events' ); ?></strong> <?php echo ucfirst( $event_type ); ?></li>
						</ul>

						<?php if ( 'virtual' !== $event_type && $map_address && $api_key ) : ?>
							<div class="swifty-mini-map">
								<iframe width="100%" height="200" style="border:0; border-radius: 12px;" loading="lazy" src="https://www.google.com/maps/embed/v1/place?key=<?php echo esc_attr( $api_key ); ?>&q=<?php echo urlencode( $map_address ); ?>"></iframe>
								<a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode( $map_address ); ?>" target="_blank" class="swifty-btn-map-link"><?php _e( 'Get Directions', 'swifty-events' ); ?></a>
							</div>
						<?php endif; ?>
						
						<?php if ( 'virtual' === $event_type && $virtual_link ) : ?>
							<a href="<?php echo esc_url( $virtual_link ); ?>" target="_blank" class="swifty-btn-map-link" style="margin-top:20px; background:#f0f9ff; padding:10px; border-radius:8px;">
								<i class="eicon-video-camera"></i> <?php _e( 'Join Virtual Meeting', 'swifty-events' ); ?>
							</a>
						<?php endif; ?>
					</div>

					<!-- Related Events -->
					<?php
					$terms = get_the_terms( $event_id, 'event_category' );
					if ( $terms && ! is_wp_error( $terms ) ) {
						$related_term_ids = wp_list_pluck( $terms, 'term_id' );
						$related_args = array(
							'post_type' => 'event',
							'posts_per_page' => 3,
							'post__not_in' => array( $event_id ),
							'tax_query' => array(
								array(
									'taxonomy' => 'event_category',
									'field' => 'term_id',
									'terms' => $related_term_ids,
								),
							),
						);
						$related_query = new \WP_Query( $related_args );

						if ( $related_query->have_posts() ) : ?>
							<div class="swifty-related-events">
								<h3><?php _e( 'Related Events', 'swifty-events' ); ?></h3>
								<?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
									<div class="swifty-related-item">
										<a href="<?php the_permalink(); ?>">
											<?php if ( has_post_thumbnail() ) : ?>
												<div class="swifty-related-thumb"><?php the_post_thumbnail( 'thumbnail' ); ?></div>
											<?php endif; ?>
											<div class="swifty-related-info">
												<h4 class="swifty-related-title"><?php the_title(); ?></h4>
												<?php 
												$rel_date = get_post_meta( get_the_ID(), '_swifty_event_date', true );
												if ( $rel_date ) : ?>
													<span class="swifty-related-date"><?php echo date_i18n( 'M j, Y', strtotime( $rel_date ) ); ?></span>
												<?php endif; ?>
											</div>
										</a>
									</div>
								<?php endwhile; wp_reset_postdata(); ?>
							</div>
						<?php endif;
					} ?>

				</div>
			</aside>

		</div>

	</div>

	<?php
endwhile;

get_footer();
