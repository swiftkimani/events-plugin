<?php
/**
 * Template Name: Single Blog Post (Swifty Style)
 *
 * @package Blog_Widget_Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Enqueue styles if not already loaded (The plugin should load them, but just in case)
wp_enqueue_style( 'blog-widget-styles' );

while ( have_posts() ) :
	the_post();
	
    // Background Color Fix
	?>
	<style>
		body, .site-content, #content, .site-main { background-color: #f2f2f2 !important; }
		.swifty-single-event-container { background-color: #f2f2f2; }
	</style>

	<div class="swifty-single-event-container" style="max-width: 1000px; margin: 0 auto; padding: 40px 20px;">
		
		<!-- Back Button -->
		<div class="swifty-back-wrapper" style="margin-bottom: 20px;">
			<a href="#" onclick="history.back(); return false;" class="swifty-back-btn" style="text-decoration:none; color: #555; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <span style="font-size: 1.2em;">&larr;</span> <?php _e( 'Back', 'swifty-events' ); ?>
            </a>
		</div>

		<div class="swifty-single-card" style="background: #fff; border-radius: 30px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.08);"> 
			
			<!-- Featured Image -->
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="swifty-single-hero" style="width: 100%; height: auto; max-height: 500px; overflow: hidden;">
					<?php the_post_thumbnail( 'full', array( 'style' => 'width: 100%; height: 100%; object-fit: cover;' ) ); ?>
				</div>
			<?php endif; ?>
			
			<div class="swifty-single-card-content" style="padding: 40px;">
				
				<div class="swifty-single-layout-grid" style="display: grid; grid-template-columns: 1fr 300px; gap: 40px;">
					
					<!-- LEFT: Main Info -->
					<div class="swifty-single-main-info">
						
						<!-- Title -->
						<h1 class="swifty-single-title" style="font-size: 2.5rem; color: #0E1B2D; margin-bottom: 20px; line-height: 1.1; font-family: 'Geologica', sans-serif;"><?php the_title(); ?></h1>
		
						<!-- Meta Row -->
						<div class="swifty-single-meta-row" style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 30px; font-size: 1.1rem; color: #555; align-items: center;">
                            <!-- Date -->
							<div class="swifty-meta-item" style="display: flex; align-items: center; gap: 8px;">
                                <span style="color: #72BE44;">&#128197;</span> <?php echo get_the_date(); ?>
                            </div>
                            <!-- Author -->
                            <div class="swifty-meta-item" style="display: flex; align-items: center; gap: 8px;">
                                <span style="color: #72BE44;">&#128100;</span> <?php the_author(); ?>
                            </div>
                            <!-- Category -->
                            <div class="swifty-meta-item" style="display: flex; align-items: center; gap: 8px;">
                                <span style="color: #72BE44;">&#128193;</span> <?php the_category( ', ' ); ?>
                            </div>
						</div>
						
						<!-- Content -->
						<div class="swifty-single-content swifty-content-typography" style="font-family: 'Montserrat', sans-serif; line-height: 1.8; color: #333; font-size: 1.05rem;">
							<?php the_content(); ?>
						</div>
                        
                        <!-- Tags -->
                        <?php if ( has_tag() ) : ?>
                            <div class="swifty-single-tags" style="margin-top: 30px;">
                                <?php the_tags( '<span style="font-weight:bold; color: #0E1B2D;">Tags: </span> ', ', ', '' ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Comments (Optional) -->
                        <?php
                        if ( comments_open() || get_comments_number() ) :
                            comments_template();
                        endif;
                        ?>

					</div>

					<!-- RIGHT: Sidebar Info -->
					<aside class="swifty-single-sidebar-minimal">
						
						<div class="swifty-info-card-minimal" style="background: #f8fafc; padding: 24px; border-radius: 16px; border: 1px solid #eff2f5;">
							<h3 style="margin-top: 0; font-size: 1.2rem; color: #0E1B2D; margin-bottom: 16px;"><?php _e( 'Details', 'swifty-events' ); ?></h3>
							<ul class="swifty-info-list" style="list-style: none; padding: 0; margin: 0;">
                                <li style="margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #eef2f6;">
                                    <strong style="display: block; font-size: 0.85rem; text-transform: uppercase; color: #8898aa; letter-spacing: 0.5px;"><?php _e( 'Author', 'swifty-events' ); ?></strong> 
                                    <span style="color: #0E1B2D; font-weight: 600;"><?php the_author(); ?></span>
                                </li>
                                <li style="margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #eef2f6;">
                                    <strong style="display: block; font-size: 0.85rem; text-transform: uppercase; color: #8898aa; letter-spacing: 0.5px;"><?php _e( 'Date', 'swifty-events' ); ?></strong> 
                                    <span style="color: #0E1B2D; font-weight: 600;"><?php echo get_the_date(); ?></span>
                                </li>
                                <li>
                                    <strong style="display: block; font-size: 0.85rem; text-transform: uppercase; color: #8898aa; letter-spacing: 0.5px;"><?php _e( 'Category', 'swifty-events' ); ?></strong> 
                                    <span style="color: #0E1B2D; font-weight: 600;"><?php the_category( ', ' ); ?></span>
                                </li>
							</ul>
						</div>

					</aside>

				</div> <!-- End Grid -->
				
				<!-- Related Posts -->
				<div class="swifty-related-section-wrapper" style="margin-top: 60px;">
					<?php
                    $related_args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 3,
                        'post__not_in' => array( get_the_ID() ),
                        'category__in' => wp_get_post_categories( get_the_ID() ),
                    );
                    $related_query = new WP_Query( $related_args );

                    if ( $related_query->have_posts() ) : ?>
                        <hr class="swifty-related-divider" style="border: 0; border-top: 1px solid #eee; margin-bottom: 40px;">
                        <div class="swifty-related-events">
                            <h3 style="font-size: 1.5rem; color: #0E1B2D; margin-bottom: 24px;"><?php _e( 'You might also be interested in', 'swifty-events' ); ?></h3>
                            <div class="swifty-related-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 24px;">
                                <?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
                                    <div class="swifty-related-item" style="border-radius: 12px; overflow: hidden; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.2s;">
                                        <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit; display: block;">
                                            <?php if ( has_post_thumbnail() ) : ?>
                                                <div class="swifty-related-thumb" style="height: 160px; overflow: hidden;">
                                                    <?php the_post_thumbnail( 'medium', array( 'style' => 'width: 100%; height: 100%; object-fit: cover;' ) ); ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="swifty-related-info" style="padding: 16px;">
                                                <h4 class="swifty-related-title" style="margin: 0 0 8px 0; font-size: 1.1rem; color: #0E1B2D;"><?php the_title(); ?></h4>
                                                <span class="swifty-related-date" style="font-size: 0.85rem; color: #8898aa;"><?php echo get_the_date(); ?></span>
                                            </div>
                                        </a>
                                    </div>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
				</div>

			</div> <!-- End Card Content -->

		</div> <!-- End Card -->

	</div>

    <!-- Responsive Styles for grid -->
    <style>
        @media (max-width: 991px) {
            .swifty-single-layout-grid {
                grid-template-columns: 1fr !important;
            }
            .swifty-single-card-content {
                padding: 24px !important;
            }
        }
    </style>

	<?php
endwhile;

get_footer();
