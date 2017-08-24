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

    function text() {

        $post_ID = $this->getObjectId();
        
        return sprintf(
            'Seu post <a href="%s"><strong>%s</strong></a> foi promovido.',
            get_permalink($post_ID),
            get_post_field( 'post_title', $post_ID )
        );
    }

    function image(){
        $post_ID = $this->getObjectId();
        return get_the_post_thumbnail( $post_ID, 'thumbnail');
    }

}
