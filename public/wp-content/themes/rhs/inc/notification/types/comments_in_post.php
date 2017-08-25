<?php


class RHSNotification_comments_in_post extends RHSNotification {

    /**
     * @param Object|Int $comment (ID ou objeto)
     */
    static function notify($comment) {

        $c = is_object($comment) ? $comment : get_comment($comment);
        
        if (1 == $c->comment_approved) {
            global $RHSNotifications;
            $RHSNotifications->add_notification(RHSNotifications::CHANNEL_COMMENTS, $c->comment_post_ID, self::get_name(), $c->comment_ID, $c->user_id);
        }

        
    }

    function text() {
        $comment_ID = $this->getObjectId();
        $c = get_comment($comment_ID);
        $post_ID = $c->comment_post_ID;
        
        $user = new RHSUser(get_userdata(get_post_field( 'post_author', $post_ID )));
        
        return sprintf(
            '<a href="%s"><strong>%s</strong></a> comentou no post <a href="%s"><strong>%s</strong></a>',
            $user->get_link(),
            $user->get_name(),
            get_permalink($post_ID),
            get_post_field( 'post_title', $post_ID )
        );
    }

    function image() {
        $comment_ID = $this->getObjectId();
        $c = get_comment($comment_ID);
        $post_ID = $c->comment_post_ID;

        $user = new RHSUser(get_userdata(get_post_field( 'post_author', $post_ID )));
        return $user->get_avatar();
    }

}
