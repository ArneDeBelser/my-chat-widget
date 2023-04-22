<?php

add_action('admin_menu', 'add_chat_messages_page');
function add_chat_messages_page()
{
    add_menu_page(
        'Chat Messages',
        'Chat Messages',
        'manage_options',
        'chat-messages',
        'display_chat_messages_page',
        'dashicons-format-chat',
        30
    );
}

function group_by_user_id($array)
{
    $result = [];

    foreach ($array as $item) {
        $result[$item->user_id][] = $item;
    }

    return $result;
}

function display_chat_messages_page()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'chat_messages';

    // Check if a new response has been submitted

    if (isset($_POST['user_id']) && isset($_POST['message'])) {
        $message = sanitize_text_field($_POST['message']);
        $user_id = sanitize_text_field($_POST['user_id']);
        $sender = 'admin';

        $wpdb->query(
            $wpdb->prepare(
                "INSERT INTO {$table_name} (user_id, message, sender, timestamp) VALUES (%s, %s, %s, %s)",
                $user_id,
                $message,
                $sender,
                current_time('mysql')
            )
        );
    }

    $messages = $wpdb->get_results("SELECT user_id, message, timestamp, sender FROM $table_name ORDER BY timestamp DESC");

    $grouped_messages = group_by_user_id($messages);

    // echo '<pre>';
    // var_dump($grouped_messages);
    // echo '</pre>';


?>
    <div class="wrap">
        <h1>Chat Messages</h1>
        <?php if (empty($grouped_messages)) : ?>
            <div class="no-messages">
                <i class="dashicons dashicons-format-chat"></i>
                <p>No messages yet. Start a conversation with your users!</p>
            </div>
        <?php else : ?>
            <?php foreach ($grouped_messages as $user_id => $user_messages) : ?>
                <div class="user-messages">
                    <?php foreach ($user_messages as $message) : ?>
                        <div class="message">
                            <p><?php echo $message->message; ?></p>
                            <div class="message-meta">
                                <span class="sender"><?php echo $message->sender; ?></span>
                                <span class="timestamp"><?php echo $message->timestamp; ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <form method="post" class="response-form">
                        <input type="hidden" name="user_id" value="<?php echo $message->user_id; ?>">
                        <label for="message">Enter your message:</label>
                        <textarea name="message" id="message" rows="3" required></textarea>
                        <button type="submit">Submit</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
<?php
}
