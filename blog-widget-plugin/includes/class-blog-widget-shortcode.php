<?php class Blog_Widget_Shortcode
{

    public function __construct()
    {
        add_shortcode('blog_widget_shortcode', array($this, 'render_shortcode'));
    }

    public function render_shortcode($atts)
    {
        ob_start();
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => 6,
            'paged' => $paged,
        );

        // Search
        if (isset($_GET['s']) && !empty($_GET['s'])) {
            $args['s'] = sanitize_text_field($_GET['s']);
        }

        // Category
        if (isset($_GET['category']) && $_GET['category'] != 0) {
            $args['cat'] = intval($_GET['category']);
        }

        // Date Range
        if ( ( isset($_GET['start_date']) && !empty($_GET['start_date']) ) || ( isset($_GET['end_date']) && !empty($_GET['end_date']) ) ) {
            $date_query = array( 'relation' => 'AND' );
            
            if ( isset($_GET['start_date']) && !empty($_GET['start_date']) ) {
                $date_query[] = array(
                    'after'     => sanitize_text_field($_GET['start_date']),
                    'inclusive' => true,
                );
            }
            
            if ( isset($_GET['end_date']) && !empty($_GET['end_date']) ) {
                $date_query[] = array(
                    'before'    => sanitize_text_field($_GET['end_date']),
                    'inclusive' => true,
                );
            }
            
            $args['date_query'] = $date_query;
        }

        $query = new WP_Query($args);
        
        // Base URL for resetting
        $base_url = strtok($_SERVER["REQUEST_URI"], '?');
?>
        <div class="swifty-blog-widget-container" data-widget-id="blog-widget">
            <div class="swifty-events-wrapper-with-sidebar">
                
                <!-- Mobile Filter Toggle (Moved to Top) -->
                <div class="swifty-mobile-filter-toggle">
                    <button type="button" id="swifty-blog-filter-toggle" class="swifty-btn-details" style="width: 100%; justify-content: center; margin-bottom: 20px;">
                        <i class="eicon-filter"></i> <?php _e('Filter Posts', 'swifty-events'); ?>
                    </button>
                </div>

                <!-- Main Content Area -->
                <div class="swifty-events-main-content">
                    
                    <?php if ($query->have_posts()) : ?>
                        <div class="swifty-layout-grid swifty-skin-gh2kenya">
                            <?php while ($query->have_posts()) : $query->the_post(); ?>
                                
                                <div class="swifty-event-item swifty-anim-fade-up">
                                    
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="swifty-event-thumbnail">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('large'); ?>
                                            </a>
                                            <?php
                                            $categories = get_the_category();
                                            if ( ! empty( $categories ) ) {
                                                echo '<span class="swifty-event-category-badge">' . esc_html( $categories[0]->name ) . '</span>';
                                            }
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="swifty-event-content">
                                        <span class="swifty-event-date">
                                            <?php echo get_the_date('M d, Y'); ?>
                                        </span>
                                        
                                        <h3 class="swifty-event-title">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_title(); ?>
                                            </a>
                                        </h3>
                                        
                                        <div class="swifty-event-excerpt">
                                            <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                                        </div>
                                        
                                        <a href="<?php the_permalink(); ?>" class="swifty-btn-details">
                                            <?php _e('Read More', 'swifty-events'); ?>
                                        </a>
                                    </div>
                                    
                                </div>
                                
                            <?php endwhile; ?>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="swifty-pagination">
                            <?php
                            echo paginate_links(array(
                                'total' => $query->max_num_pages,
                                'prev_text' => '<i class="eicon-chevron-left"><</i>',
                                'next_text' => '<i class="eicon-chevron-right">></i>',
                                'type' => 'list',
                            ));
                            ?>
                        </div>
                        
                    <?php else : ?>
                        <p class="swifty-no-events"><?php _e('No posts found matching your criteria.', 'swifty-events'); ?></p>
                    <?php endif; ?>
                    
                    <?php wp_reset_postdata(); ?>
                </div>

                <!-- Sidebar Filters -->
                <aside class="swifty-events-filter-sidebar" id="swifty-blog-sidebar">
                    <div class="swifty-sidebar-inner">
                        <div class="swifty-sidebar-header-mobile">
                            <h3><?php _e('Filters', 'swifty-events'); ?></h3>
                            <button type="button" id="swifty-blog-filter-close" class="swifty-btn-close">&times;</button>
                        </div>
                        <form method="get" class="swifty-filter-form">
                            
                            <!-- 1. Search Bar -->
                            <div class="swifty-filter-group swifty-search-group">
                                <label class="swifty-filter-label"><?php _e('Search', 'swifty-events'); ?></label>
                                <div class="swifty-search-wrapper" style="position:relative;">
                                    <input type="text" name="s" class="swifty-search-input" placeholder="<?php _e('Search posts...', 'swifty-events'); ?>" value="<?php echo get_search_query(); ?>">
                                    <button type="submit" class="swifty-search-icon-btn" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer;"><i class="eicon-search"></i></button>
                                </div>
                            </div>

                            <!-- 2. Recent News Button (Reset) -->
                            <div class="swifty-filter-group">
                                <a href="<?php echo esc_url($base_url); ?>" class="swifty-btn-recent-news swifty-btn-block">
                                    <?php _e('Recent News', 'swifty-events'); ?>
                                </a>
                            </div>

                            <!-- 3. Categories Dropdown -->
                            <div class="swifty-filter-group">
                                <label class="swifty-filter-label" for="category"><?php _e('Filter by Category', 'swifty-events'); ?></label>
                                <?php
                                $args = array(
                                    'show_option_all' => 'All Categories',
                                    'name'            => 'category',
                                    'class'           => 'swifty-filter-select',
                                    'selected'        => isset($_GET['category']) ? $_GET['category'] : 0,
                                    'hide_empty'      => 1,
                                    'value_field'     => 'term_id',
                                );
                                wp_dropdown_categories($args);
                                ?>
                            </div>

                            <!-- 4. Date Range Selector -->
                            <div class="swifty-filter-group">
                                <label class="swifty-filter-label"><?php _e('Filter by Date', 'swifty-events'); ?></label>
                                <div class="swifty-date-range-group" style="display:flex; flex-direction:column; gap:10px;">
                                    <input type="date" name="start_date" class="swifty-date-input" placeholder="Start Date" value="<?php echo isset($_GET['start_date']) ? esc_attr($_GET['start_date']) : ''; ?>">
                                    <input type="date" name="end_date" class="swifty-date-input" placeholder="End Date" value="<?php echo isset($_GET['end_date']) ? esc_attr($_GET['end_date']) : ''; ?>">
                                </div>
                            </div>

                            <!-- 5. Actions -->
                            <div class="swifty-filter-actions" style="margin-top: 20px; display:flex; flex-direction:column; gap:10px;">
                                <button type="submit" class="swifty-btn-apply swifty-btn-block">
                                    <?php _e('Apply Filter', 'swifty-events'); ?>
                                </button>
                                
                                <?php if(isset($_GET['s']) || isset($_GET['category']) || isset($_GET['start_date']) || isset($_GET['end_date'])): ?>
                                    <a href="<?php echo esc_url($base_url); ?>" class="swifty-btn-clear swifty-btn-block">
                                        <?php _e('Clear Filters', 'swifty-events'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                        </form>
                    </div>
                </aside>
                
            </div>
            <!-- Overlay for mobile modal -->
            <div id="swifty-blog-filter-overlay" class="swifty-filter-overlay"></div>
        </div>
<?php
        return ob_get_clean();
    }
}

new Blog_Widget_Shortcode();
