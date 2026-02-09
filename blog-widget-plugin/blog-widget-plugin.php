<?php
/*
Plugin Name: Blog Widget Plugin
Description: A plugin that adds a widget for the native WordPress blog with a beautiful UI, filter section, and pagination.
Version: 1.0
Author: Your Name
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/class-blog-widget.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-blog-widget-shortcode.php';

// Register the widget
function blog_widget_register_widget()
{
    register_widget('Blog_Widget');
}
add_action('widgets_init', 'blog_widget_register_widget');

// Enqueue styles and scripts
function blog_widget_enqueue_assets()
{
    wp_enqueue_style('blog-widget-styles', plugin_dir_url(__FILE__) . 'assets/css/blog-widget.css');
    wp_enqueue_script('blog-widget-scripts', plugin_dir_url(__FILE__) . 'assets/js/blog-widget.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'blog_widget_enqueue_assets');
