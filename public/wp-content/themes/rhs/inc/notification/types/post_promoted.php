<?php


class RHSNotification_post_promoted extends RHSNotification {

    /**
     * @param $post_id ID do Post
     */
    static function notify($post_id) {

        if(empty($post_id)){
            return;
        }
        
        $post = get_post($post_id);
        
        if (is_object($post)) {
            global $RHSNotifications;
            $RHSNotifications->add_notification(RHSNotifications::CHANNEL_PRIVATE, $post->post_author, self::get_name(), $post_id);
        }
        
    }

    function text( RHSNotification $news ) {
        // TODO: Implement text() method.
    }

    function image(RHSNotification $news){

    }

}
