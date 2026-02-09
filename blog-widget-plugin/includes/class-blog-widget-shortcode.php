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
            'posts_per_page' => 6, // Match grid layout better
            'paged' => $paged,
        );

        if (isset($_GET['category']) && $_GET['category'] != 0) {
            $args['cat'] = intval($_GET['category']);
        }

        if (isset($_GET['date']) && !empty($_GET['date'])) {
            $args['date_query'] = array(
                array(
                    'year' => date('Y', strtotime($_GET['date'])),
                    'month' => date('m', strtotime($_GET['date'])),
                ),
            );
        }

        $query = new WP_Query($args);
?>
        <div class="swifty-event-list swifty-blog-list" data-widget-id="blog-widget">
            <div class="swifty-events-wrapper-with-sidebar">
                
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
                        <p class="swifty-no-events"><?php _e('No posts found.', 'swifty-events'); ?></p>
                    <?php endif; ?>
                    
                    <?php wp_reset_postdata(); ?>
                </div>

                <!-- Sidebar Filters -->
                <aside class="swifty-events-filter-sidebar">
                    <div class="swifty-sidebar-inner">
                        <form method="get" class="swifty-filter-group">
                            <label class="swifty-filter-label" for="category"><?php _e('Categories', 'swifty-events'); ?></label>
                            
                             <ul class="swifty-category-list">
                                <?php
                                $categories = get_categories(array('hide_empty' => true));
                                $current_cat = isset($_GET['category']) ? intval($_GET['category']) : 0;
                                echo '<li><a href="?category=0"' . ($current_cat === 0 ? ' style="background:#eff2f5;"' : '') . '>All Categories</a></li>';
                                
                                foreach ($categories as $category) {
                                    $active_style = ($current_cat === $category->term_id) ? ' style="background:#eff2f5; color:var(--color-deep-blue);"' : '';
                                    echo '<li><a href="?category=' . $category->term_id . '"' . $active_style . '>' . $category->name . ' <span style="float: right; color: #aaa; font-size: 0.8em;">(' . $category->count . ')</span></a></li>';
                                }
                                ?>
                            </ul>

                            <div style="margin-top: 24px;">
                                <label class="swifty-filter-label" for="date"><?php _e('Filter by Date', 'swifty-events'); ?></label>
                                <input type="month" name="date" id="date" class="swifty-search-input" value="<?php echo isset($_GET['date']) ? esc_attr($_GET['date']) : ''; ?>" onchange="this.form.submit()">
                            </div>
                            
                            <?php if(isset($_GET['category']) || isset($_GET['date'])): ?>
                                <div style="margin-top: 20px;">
                                    <a href="<?php echo remove_query_arg(array('category', 'date')); ?>" class="swifty-btn-details" style="width:100%; justify-content:center; background:#8898aa;">
                                        Clear Filters
                                    </a>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </aside>
                
            </div>
        </div>
<?php
        return ob_get_clean();
    }
}

new Blog_Widget_Shortcode();
