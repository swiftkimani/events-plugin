<?php class Blog_Widget_Shortcode
{

    public function __construct()
    {
        add_shortcode('blog_widget_shortcode', array($this, 'render_shortcode'));
    }

    public function render_shortcode($atts)
    {
        ob_start();
?>
        <div class="swifty-event-list swifty-blog-list" data-widget-id="blog-widget">
            <!-- Filter Section on the Right -->
            <div class="swifty-events-wrapper-with-sidebar">
                <div class="swifty-events-main-content">
                    <div class="swifty-layout-grid swifty-flex-row swifty-framer-animation">
                        <?php
                        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
                        $query_args = array(
                            'post_type' => 'post',
                            'posts_per_page' => 5,
                            'paged' => $paged,
                        );

                        if (isset($_GET['category']) && $_GET['category'] != 0) {
                            $query_args['cat'] = intval($_GET['category']);
                        }

                        if (isset($_GET['date']) && !empty($_GET['date'])) {
                            $query_args['date_query'] = array(
                                array(
                                    'year' => date('Y', strtotime($_GET['date'])),
                                    'month' => date('m', strtotime($_GET['date'])),
                                ),
                            );
                        }

                        $query = new WP_Query($query_args);

                        if ($query->have_posts()) {
                            while ($query->have_posts()) {
                                $query->the_post();
                        ?>
                                <div class="swifty-event-item swifty-event-card swifty-skin-kenya swifty-anim-fade-up">
                                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                    <p><?php the_excerpt(); ?></p>
                                </div>
                        <?php
                            }

                            echo '<div class="pagination top-pagination">';
                            echo paginate_links(array('total' => $query->max_num_pages));
                            echo '</div>';

                            echo '<div class="pagination bottom-pagination">';
                            echo paginate_links(array('total' => $query->max_num_pages));
                            echo '</div>';
                        } else {
                            echo '<p>No posts found.</p>';
                        }

                        wp_reset_postdata();
                        ?>
                    </div>
                </div>

                <!-- Sidebar Filters -->
                <div class="swifty-blog-filters">
                    <form method="get">
                        <label for="category">Category:</label>
                        <?php wp_dropdown_categories(array('show_option_all' => 'All Categories', 'name' => 'category')); ?>

                        <label for="date">Date:</label>
                        <input type="month" name="date" id="date">

                        <button type="submit">Filter</button>
                    </form>
                </div>
            </div>
        </div>
<?php
        return ob_get_clean();
    }
}

new Blog_Widget_Shortcode();
