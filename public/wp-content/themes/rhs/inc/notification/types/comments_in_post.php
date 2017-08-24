<?php


class RHSNotification_comments_in_post extends RHSNotification {

    function __construct() {
        add_action('rhs_notify_comments_in_post', array( &$this, 'notify' ));
    }

    /**
     * @param $args - (post_id) ID do post comentado ; (comment_id) ID do comentário
     */
    static function notify($args) {

        if(empty($args['post_id']) || empty($args['comment_id'])){
            return;
        }

        global $RHSNotifications;
        $RHSNotifications->add_notification(RHSNotifications::CHANNEL_COMMENTS, $args['post_id'], $this->get_name(), $args['comment_id']);
    }

    function text() {
        // TODO: Implement text() method.
    }

    function image() {

    }

}
