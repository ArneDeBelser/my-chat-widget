<?php

/**
 * Plugin Name: My Chat Widget
 * Plugin URI: /
 * Description: A custom Elementor widget that displays a live chat with users.
 * Version: 1.0
 * Author: De Belser Arne
 * Author URI: https://www.arnedebelser.be
 * License: GPL2
 */

require_once(plugin_dir_path(__FILE__) . 'includes/database.php');

require_once(plugin_dir_path(__FILE__) . 'includes/chat-messages-page.php');

require_once(plugin_dir_path(__FILE__) . 'includes/ajax-functions.php');

add_action('elementor/widgets/widgets_registered', function () {
    require_once plugin_dir_path(__FILE__) . 'includes/elementor-chat-widget.php';
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\Elementor_Chat_Widget());
}, 999);

add_action('wp_enqueue_scripts', 'my_chat_widget_enqueue_scripts');
function my_chat_widget_enqueue_scripts()
{
    wp_enqueue_style('my-chat-widget', plugins_url('/assets/css/chat-widget.css', __FILE__));
    wp_enqueue_script('my-chat-widget', plugins_url('/assets/js/chat-widget.js', __FILE__), ['jquery'], '1.0', true);
    wp_localize_script('my-chat-widget', 'myChatWidget', [
        'ajaxurl' => admin_url('admin-ajax.php')
    ]);
}

add_action('admin_enqueue_scripts', 'enqueue_chat_messages_css');
function enqueue_chat_messages_css()
{
    $screen = get_current_screen();
    if ($screen->id === 'toplevel_page_chat-messages') {
        wp_enqueue_style('chat-widget-admin', plugins_url('/assets/css/chat-widget-admin.css', __FILE__));
    }
}
