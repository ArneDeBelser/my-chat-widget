<?php

function create_chat_messages_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'chat_messages';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
        user_id VARCHAR(32) NOT NULL,
        message TEXT NOT NULL,
        timestamp DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
        sender VARCHAR(255) DEFAULT '' NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('init', 'create_chat_messages_table');
