<?php


class RHSNotification_replied_ticket extends RHSNotification {

    /**
     */
    static function notify($user_from_contact, $post_ID, $content) {
        global $RHSNotifications;
        $RHSNotifications->add_notification(RHSNotifications::CHANNEL_PRIVATE, $user_from_contact, self::get_name(), $post_ID);
    }

    function text() {
        $post = $this->getObjectId();
                
        return sprintf(
            'Seu <a href="%s">contato</a> foi respondido',
            get_permalink($post)
        );
    }

    function image(){
        
        return "<img src='". get_template_directory_uri() ."/inc/notification/assets/default_icon.png'>";
    }

}