<?php

add_action('wp_ajax_send_chat_message', 'send_chat_message');
add_action('wp_ajax_nopriv_send_chat_message', 'send_chat_message');

function send_chat_message()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'chat_messages';

    $message = sanitize_text_field($_POST['message']);
    $user_id = sanitize_text_field($_POST['user_id']);

    $data = [
        'message' => $message,
        'user_id' => $user_id,
        'sender' => 'user',
        'timestamp' => current_time('mysql')
    ];

    $format = ['%s', '%s', '%s', '%s'];

    $wpdb->insert($table_name, $data, $format);

    wp_die();
}

add_action('wp_ajax_get_chat_messages', 'get_chat_messages');
add_action('wp_ajax_nopriv_get_chat_messages', 'get_chat_messages');
function get_chat_messages()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'chat_messages';
    $last_message_id = intval($_POST['last_message_id']);
    $user_id = sanitize_text_field($_POST['user_id']); // Get the user ID from the request

    $query = $wpdb->prepare(
        "SELECT * FROM $table_name WHERE id > %d AND user_id = %s ORDER BY id ASC",
        $last_message_id,
        $user_id // Retrieve chat messages based on the user ID
    );

    $messages = $wpdb->get_results($query);

    $response = array();

    foreach ($messages as $message) {
        $response[] = array(
            'id' => $message->id,
            'message' => $message->message,
            'sender' => $message->sender,
            'timestamp' => $message->timestamp,
        );
    }

    echo json_encode($response);

    wp_die();
}
