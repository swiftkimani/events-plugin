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

	<div class="swifty-single-event-container swifty-main-padding" style="max-width: 1200px; margin: 0 auto; padding-left: 20px; padding-right: 20px;">
		
		<!-- Back Button Top -->
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
				
				<div class="swifty-single-layout-grid" style="display: grid; grid-template-columns: 1fr 350px; gap: 60px;">
					
					<!-- LEFT: Main Info -->
					<div class="swifty-single-main-info">
						
						<!-- Title -->
						<h1 class="swifty-single-title" style="font-size: 2.5rem; color: #0E1B2D; margin-bottom: 15px; line-height: 1.1; font-family: 'Geologica', sans-serif;"><?php the_title(); ?></h1>
		
						<!-- Minimal Meta Row -->
						<div class="swifty-single-meta-row" style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px; font-size: 0.95rem; color: #8898aa; align-items: center;">
                            <!-- Date -->
							<div class="swifty-meta-item" style="display: flex; align-items: center; gap: 6px;">
                                <span style="color: #72BE44;">&#128197;</span> <?php echo get_the_date(); ?>
                            </div>
                            <!-- Category -->
                            <div class="swifty-meta-item" style="display: flex; align-items: center; gap: 6px;">
                                <span style="color: #72BE44;">&#128193;</span> <?php the_category( ', ' ); ?>
                            </div>
						</div>

                        <!-- Share Buttons (Top) -->
                        <div class="swifty-share-buttons" style="display: flex; gap: 10px; margin-bottom: 30px;">
                            <span style="font-weight: 600; color: #0E1B2D; align-self: center; margin-right: 10px;"><?php _e('Share:', 'swifty-events'); ?></span>
                            <!-- Facebook -->
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" style="background: #3b5998; color: #fff; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.2s;">
                                <i class="eicon-facebook"></i> F
                            </a>
                            <!-- Twitter/X -->
                            <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode(get_the_title()); ?>&url=<?php echo urlencode(get_permalink()); ?>" target="_blank" style="background: #000; color: #fff; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.2s;">
                                <i class="eicon-twitter"></i> X
                            </a>
                             <!-- LinkedIn -->
                             <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" target="_blank" style="background: #0077b5; color: #fff; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.2s;">
                                <i class="eicon-linkedin"></i> In
                            </a>
                             <!-- WhatsApp -->
                             <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' - ' . get_permalink()); ?>" target="_blank" style="background: #25D366; color: #fff; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.2s;">
                                <i class="eicon-whatsapp"></i> W
                            </a>
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

                        <!-- Bottom Back Button -->
                        <div class="swifty-bottom-back-wrapper" style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee;">
                            <a href="#" onclick="history.back(); return false;" class="swifty-btn-details" style="display: inline-flex; width: auto; background: #0E1B2D; color: #fff;">
                                &larr; <?php _e( 'Back to News', 'swifty-events' ); ?>
                            </a>
                        </div>

					</div>

					<!-- RIGHT: Sidebar Info (Related Posts) -->
					<aside class="swifty-single-sidebar-minimal">
						
                        <!-- Related Posts Widget -->
                        <?php
                        $related_args = array(
                            'post_type' => 'post',
                            'posts_per_page' => 4, // Show more in sidebar
                            'post__not_in' => array( get_the_ID() ),
                            'category__in' => wp_get_post_categories( get_the_ID() ),
                        );
                        $related_query = new WP_Query( $related_args );

                        if ( $related_query->have_posts() ) : ?>
                            <div class="swifty-sidebar-widget" style="background: #f8fafc; padding: 24px; border-radius: 16px; border: 1px solid #eff2f5; position: sticky; top: 30px;">
                                <h3 style="margin-top: 0; font-size: 1.2rem; color: #0E1B2D; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;"><?php _e( 'Related News', 'swifty-events' ); ?></h3>
                                
                                <div class="swifty-sidebar-posts-list" style="display: flex; flex-direction: column; gap: 20px;">
                                    <?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
                                        <div class="swifty-sidebar-post-item" style="display: flex; gap: 12px; align-items: start;">
                                            <a href="<?php the_permalink(); ?>" class="swifty-sidebar-thumb-link" style="flex-shrink: 0; width: 80px; height: 80px; border-radius: 8px; overflow: hidden; display: block;">
                                                <?php if ( has_post_thumbnail() ) : ?>
                                                    <?php the_post_thumbnail( 'thumbnail', array( 'style' => 'width: 100%; height: 100%; object-fit: cover;' ) ); ?>
                                                <?php else : ?>
                                                    <div style="width: 100%; height: 100%; background: #e2e8f0;"></div>
                                                <?php endif; ?>
                                            </a>
                                            <div class="swifty-sidebar-post-info">
                                                <h4 style="margin: 0 0 6px 0; font-size: 0.95rem; line-height: 1.3;">
                                                    <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: #0E1B2D; font-weight: 600; transition: color 0.2s;"><?php the_title(); ?></a>
                                                </h4>
                                                <span style="font-size: 0.75rem; color: #8898aa;"><?php echo get_the_date(); ?></span>
                                            </div>
                                        </div>
                                    <?php endwhile; wp_reset_postdata(); ?>
                                </div>
                            </div>
                        <?php endif; ?>

					</aside>

				</div> <!-- End Grid -->
				
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
