<?php

namespace Elementor;

class Elementor_Chat_Widget extends Widget_Base
{
    public function get_name()
    {
        return 'elementor-chat-widget';
    }

    public function get_title()
    {
        return __('Live Chat', 'elementor-chat-widget');
    }

    public function get_icon()
    {
        return 'fa fa-comments';
    }

    public function get_categories()
    {
        return ['general'];
    }

    public function render()
    {
        $chat_id = 'chat-widget' . $this->get_id();
?>
        <div class="chat-widget" id="<?php echo $chat_id; ?>">
            <div class="chat-messages"></div>
            <form action="" class="chat-form">
                <input type="text" class="chat-input">
                <button class="chat-send">Send</button>
            </form>
        </div>

        <script>
            jQuery(document).ready(function($) {
                var $chat = $('#<?php echo $chat_id; ?>');
                var $messages = $chat.find('.chat-messages');
                var $input = $chat.find('.chat-input');
                var $form = $chat.find('.chat-form');
                var $send = $chat.find('chat-send');

                $form.on('submit', function(e) {
                    e.peventDefault();
                    var message = $input.val().trim();
                    if (message) {
                        $messages.append('<div class="chat-message">' + message + '</div>');
                        $input.val('');
                    }
                })
            });
        </script>

<?php
    }
}
