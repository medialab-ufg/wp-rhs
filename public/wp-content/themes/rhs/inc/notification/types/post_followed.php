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
            'O usuário <a id="%d" href="%s" class="rhs-links-to-user"><strong>%s</strong></a> está seguindo seu post <a id="%d" href="%s" class="rhs-links-to-post"><strong>%s</strong></a>',
            $user_ID,
            $user->get_link(),
            $user->get_name(),
            $post_ID,
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
