<?php


class RHSNotification_post_followed extends RHSNotification {

    /**
     * @param $post_id ID do Post
     */
    static function notify($args) {

        if(empty($args)){
            return;
        }
        
        $post = get_post($args['post_id']);
        
        if (is_object($post)) {
            global $RHSNotifications;
            $RHSNotifications->add_notification(RHSNotifications::CHANNEL_PRIVATE, $post->post_author, self::get_name(), $args['post_id'], $args['user_id']);
        }
        
    }

    function text() {

        $post_ID = $this->getObjectId();
        $user_ID = $this->getUserId();
        $user = new RHSUser(get_userdata($user_ID));
        
        return sprintf(
            'O usuário <a href="%s"><strong>%s</strong></a> está seguindo seu post <a href="%s"><strong>%s</strong></a>',
            $user->get_link(),
            $user->get_name(),
            get_permalink($post_ID),
            get_post_field('post_title', $post_ID)
        );
    }

    function image(){
        $user_ID = $this->getUserId();
        $user = new RHSUser(get_userdata($user_ID));
        return $user->get_avatar();
    }

}
