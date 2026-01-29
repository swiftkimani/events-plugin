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

	$back_url = get_post_type_archive_link( 'event' );
	if ( ! $back_url ) { $back_url = home_url( '/' ); }
	?>

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
					<?php if ( $event_location ) : ?>
						<div class="swifty-meta-item"><i class="eicon-map-pin"></i> <?php echo esc_html( $event_location ); ?></div>
					<?php endif; ?>
				</div>

				<!-- Description -->
				<div class="swifty-single-content swifty-content-typography">
					<?php the_content(); ?>
				</div>

				<!-- RSVP Section -->
				<div class="swifty-rsvp-section">
					<?php if ( isset( $_GET['rsvp_sent'] ) && 'true' == $_GET['rsvp_sent'] ) : ?>
						<div class="swifty-success-message" style="color: green; font-weight: bold;"><?php _e( 'RSVP Sent Successfully!', 'swifty-events' ); ?></div>
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
						</ul>

						<?php if ( $map_address && $api_key ) : ?>
							<div class="swifty-mini-map">
								<iframe width="100%" height="200" style="border:0; border-radius: 12px;" loading="lazy" src="https://www.google.com/maps/embed/v1/place?key=<?php echo esc_attr( $api_key ); ?>&q=<?php echo urlencode( $map_address ); ?>"></iframe>
								<a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode( $map_address ); ?>" target="_blank" class="swifty-btn-map-link"><?php _e( 'Get Directions', 'swifty-events' ); ?></a>
							</div>
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
