<?php


class RHSNotification_new_post_from_user extends RHSNotification {

    /**
     * @param $args - (user_id) ID do Author ; (post_id) ID do Post
     */
    static function notify($args) {

        if(empty($args['user_id']) || empty($args['post_id'])){
            return;
        }
        
        $author_id = get_post_field( 'post_author', $args['post_id'] );
        
        global $RHSNotifications;
        $RHSNotifications->add_notification(RHSNotifications::CHANNEL_USER, $args['user_id'], self::get_name(), $args['post_id'], $author_id);

    }

    function text() {

        $post_ID = $this->getObjectId();
        $user_data = get_userdata(get_post_field( 'post_author', $post_ID ));

        if (is_object($user_data)) {
            $user = new RHSUser($user_data);

            return sprintf(
                '<a href="%s"><strong>%s</strong></a> criou um novo post <a href="%s"><strong>%s</strong></a>',
                $user->get_link(),
                $user->get_name(),
                get_permalink($post_ID),
                get_post_field( 'post_title', $post_ID )
            );
        } else {
            return '';
        }
    }

    function image(){

        $post_ID = $this->getObjectId();
        $user_data = get_userdata(get_post_field( 'post_author', $post_ID ));
        if (is_object($user_data)) {
            $user = new RHSUser($user_data);
            return $user->get_avatar();
        } else {
            return '';
        }
        
    }

}
