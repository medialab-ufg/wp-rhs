<?php


class post_promoted implements INotificationType {

    function __construct() {
        add_action('rhs_notify_post_promoted', array( &$this, 'notify' ));
    }

    /**
     * @param $args - (user_id) ID do Author ; (post_id) ID do Post
     */
    function notify($args) {

        if(empty($args['user_id']) || empty($args['post_id'])){
            return;
        }

        $notification = new RHSNotifications();
        $notification->add_notification(RHSNotifications::CHANNEL_PRIVATE, $args['user_id'], RHSNotifications::POST_PROMOTED, $args['post_id']);

    }

    function text( RHSNotification $news ) {
        // TODO: Implement text() method.
    }

    function image(RHSNotification $news){

    }

}

new post_promoted();