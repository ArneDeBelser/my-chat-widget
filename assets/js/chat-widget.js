jQuery(document).ready(initialize_chat_widget);
function initialize_chat_widget($) {
    var $chat_widget = $('.chat-widget');
    if ($chat_widget) {
        var $messages = $chat_widget.find('.chat-messages');
        var $form = $chat_widget.find('.chat-form');
        var $input = $form.find('.chat-input');
        var $send = $form.find('.chat-send');

        function send_chat_message(message) {
            var user_id = get_session_cookie('user_id');

            $.ajax({
                type: 'POST',
                url: myChatWidget.ajaxurl,
                data: {
                    action: 'send_chat_message',
                    message: message,
                    user_id: user_id
                },
                succes(res) {
                    console.log(res);
                },
                error(jqXHR, textStatus, error) {
                    console.log(textStatus, error);
                }
            })
        }

        function update_chat_messages() {
            $.ajax({
                type: 'POST',
                url: myChatWidget.ajaxurl,
                data: {
                    action: 'get_chat_messages',
                    user_id: get_session_cookie('user_id'),
                    last_message_id: $messages.find('.chat-message:last').data('id') || 0,
                },
                dataType: 'json',
                success(messages) {
                    if (messages.length) {
                        for (var i = 0; i < messages.length; i++) {
                            var message = messages[i];
                            var messageDate = new Date(message.timestamp).toLocaleString();
                            var senderClass = message.sender === 'user' ? 'user-message' : 'admin-message';
                            var messageHTML = '<div class="chat-message ' + senderClass + '" data-id="' + message.id + '">' +
                                '<div class="message-sender">' + message.sender + '</div>' +
                                '<div class="message-content">' + message.message + '</div>' +
                                '<div class="message-date">' + messageDate + '</div>' +
                                '</div>';
                            $messages.append(messageHTML);
                        }
                    }
                },
                error(jqXHR, textStatus, error) {
                    console.log(textStatus, error);
                }
            })
        }

        $send.on('click', function (e) {
            e.preventDefault();
            var message = $input.val().trim();
            if (message) {
                send_chat_message(message);
                $input.val('');
            }
        });

        setInterval(update_chat_messages, 2000);

        update_chat_messages();
    }
}

function get_session_cookie(cookie_name) {
    var name = cookie_name + "=";
    var decoded_cookie = decodeURIComponent(document.cookie);
    var cookie_parts = decoded_cookie.split(';');
    for (var i = 0; i < cookie_parts.length; i++) {
        var cookie = cookie_parts[i];
        while (cookie.charAt(0) == ' ') {
            cookie = cookie.substring(1);
        }
        if (cookie.indexOf(name) == 0) {
            return cookie.substring(name.length, cookie.length);
        }
    }
    // Cookie not found, create a new one with a unique ID
    var unique_id = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
    document.cookie = cookie_name + '=' + unique_id + '; path=/';
    return unique_id;
}