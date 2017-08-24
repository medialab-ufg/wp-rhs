<?php


class new_community_post implements INotificationType {

    function __construct() {
        add_action('rhs_notify_new_community_post', array( &$this, 'notify' ));
    }

    /**
     * @param $args - (community_id) ID do Author ; (post_id) ID do Post
     */
    function notify($args) {

        if(empty($args['community_id']) || empty($args['post_id'])){
            return;
        }

        $notification = new RHSNotifications();
        $notification->add_notification(RHSNotifications::CHANNEL_COMMUNITY, $args['community_id'], $this->get_name(), $args['post_id']);
    }

    function text( RHSNotification $news ) {
        // TODO: Implement text() method.
    }

    function image(RHSNotification $news){

    }

}
