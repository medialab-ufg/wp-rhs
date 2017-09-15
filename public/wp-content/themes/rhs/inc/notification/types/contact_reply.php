<?php


class RHSNotification_contact_reply extends RHSNotification {

    /**
     */
    static function notify($user_id, $post_id) {
        global $RHSNotifications;
        $RHSNotifications->add_notification(RHSNotifications::CHANNEL_PRIVATE, $user_id, self::get_name(), $post_id);       
    }

    function text() {
        $post = get_post($post_id);

        return sprintf(
            'Seu <a href="%s">contato</a> foi respondivo',
            $post->get_permalink()
        );
    }

    function image(){
        
    }

}
