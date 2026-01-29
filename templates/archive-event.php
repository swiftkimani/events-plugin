<?php
/**
 * Template Name: Event Archive
 *
 * @package Swifty_Events
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Enqueue styles if not already loaded
wp_enqueue_style( 'swifty-events-css' );
wp_enqueue_script( 'swifty-events-js' );

// Get Settings (or defaults)
$options = get_option( 'swifty_events_settings' );
// Use defaults for archive if no specific archive settings exist
$layout_mode = 'grid'; 
$skin = 'gh2kenya';

?>

<div class="swifty-archive-container" style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
	
    <header class="swifty-archive-header" style="margin-bottom: 40px; text-align: center;">
        <h1 class="swifty-archive-title"><?php post_type_archive_title(); ?></h1>
        <?php if ( category_description() ) : ?>
            <div class="swifty-archive-description"><?php echo category_description(); ?></div>
        <?php endif; ?>
    </header>

	<!-- Wrapper with Sidebar (Reusing Widget Structure for Consistency) -->
	<div class="swifty-events-wrapper-with-sidebar">
		
		<!-- Main Content Area -->
		<div class="swifty-events-main-content">
			
			<?php if ( have_posts() ) : ?>
				
				<div class="swifty-layout-grid swifty-layout-grid swifty-skin-<?php echo esc_attr( $skin ); ?>">
					
					<?php while ( have_posts() ) : the_post(); 
						$event_date = get_post_meta( get_the_ID(), '_swifty_event_date', true );
						$event_location = get_post_meta( get_the_ID(), '_swifty_event_location', true );
					?>
						
						<div class="swifty-event-item swifty-anim-fade-up">
                            
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="swifty-event-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail( 'large' ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="swifty-event-content">
                                <?php if ( $event_date ) : ?>
                                    <span class="swifty-event-date">
                                        <?php echo date_i18n( get_option( 'date_format' ), strtotime( $event_date ) ); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <h3 class="swifty-event-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                
                                <div class="swifty-event-excerpt">
                                    <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="swifty-btn-details">
                                    <?php _e( 'View Event', 'swifty-events' ); ?>
                                </a>
                            </div>
                            
						</div>
						
					<?php endwhile; ?>
					
				</div>
				
				<!-- Pagination -->
				<div class="swifty-pagination swifty-pagination-numbers">
					<?php
					echo paginate_links( array(
						'prev_text' => '<i class="eicon-chevron-left"></i>',
						'next_text' => '<i class="eicon-chevron-right"></i>',
                        'type' => 'list',
					) );
					?>
				</div>

			<?php else : ?>
				<p class="swifty-no-events"><?php _e( 'No events found.', 'swifty-events' ); ?></p>
			<?php endif; ?>
			
		</div>
		
		<!-- Sidebar Filter Area -->
		<aside class="swifty-events-filter-sidebar">
            <div class="swifty-sidebar-inner">
                
                <!-- Search -->
                <form role="search" method="get" class="swifty-filter-group swifty-search-group" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="swifty-search-wrapper">
                        <input type="text" name="s" class="swifty-search-input" placeholder="<?php _e( 'Search events...', 'swifty-events' ); ?>" value="<?php echo get_search_query(); ?>">
                        <input type="hidden" name="post_type" value="event">
                        <button type="submit" class="swifty-search-icon-btn"><i class="eicon-search"></i></button>
                    </div>
                </form>

                <!-- Categories Widget Logic -->
                <div class="swifty-filter-group">
                    <label class="swifty-filter-label"><?php _e( 'Categories', 'swifty-events' ); ?></label>
                    <ul class="swifty-category-list" style="list-style: none; padding: 0; margin: 0;">
                        <?php
                        $cats = get_terms( array( 'taxonomy' => 'event_category', 'hide_empty' => true ) );
                        if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
                            foreach ( $cats as $cat ) {
                                echo '<li style="margin-bottom: 8px;"><a href="' . get_term_link( $cat ) . '" style="text-decoration: none; color: #555; font-weight: 500; display: block; padding: 8px 12px; background: #f8fafc; border-radius: 8px; transition: 0.2s;">' . $cat->name . ' <span style="float: right; color: #aaa; font-size: 0.8em;">(' . $cat->count . ')</span></a></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
                
                <!-- Quick Link -->
                <div class="swifty-quick-actions" style="margin-top: 30px;">
                    <a href="<?php echo get_post_type_archive_link('event'); ?>" class="swifty-btn swifty-btn-clear" style="width: 100%; display: block; text-align: center; border: 1px dashed #ddd;">
                        <?php _e( 'View All Events', 'swifty-events' ); ?>
                    </a>
                </div>

            </div>
		</aside>

	</div>
</div>

<?php get_footer(); ?>
