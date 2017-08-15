<?php


class new_post_from_user implements INotificationType {

    function __construct() {
        add_action('rhs_notify_new_post_from_user', array( &$this, 'notify' ));
    }

    /**
     * @param $args - (user_id) ID do Author ; (post_id) ID do Post
     */
    function notify($args) {

        if(empty($args['user_id']) || empty($args['post_id'])){
            return;
        }

        $notification = new RHSNotifications();
        $notification->add_notification(RHSNotifications::CHANNEL_USER, $args['user_id'], RHSNotifications::NEW_POST, $args['post_id']);

    }

    function text( RHSNotification $news ) {

        $post_ID = $news->getObjectId();
        $user = new RHSUser(get_userdata(get_post_field( 'post_author', $post_ID )));

        return sprintf(
            '<a href="%s"><strong>%s</strong></a> criou um novo post <a href="%s"><strong>%s</strong></a>',
            $user->get_link(),
            $user->get_name(),
            get_permalink($post_ID),
            get_post_field( 'post_title', $post_ID )
        );
    }

    function image(RHSNotification $news){

        $post_ID = $news->getObjectId();

        $user = new RHSUser(get_userdata(get_post_field( 'post_author', $post_ID )));
        return $user->get_avatar();
    }

}

new new_post_from_user();