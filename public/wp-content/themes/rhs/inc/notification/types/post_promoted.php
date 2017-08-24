<?php


class post_promoted implements INotificationType {

    function __construct() {
        add_action('rhs_post_promoted', array( &$this, 'notify' ));
    }

    /**
     * @param $post_id ID do Post
     */
    function notify($post_id) {

        if(empty($post_id){
            return;
        }
        
        $post = get_post($post_id);
        
        if (is_object($post)) {
            global $RHSNotifications;
            $RHSNotifications->add_notification(RHSNotifications::CHANNEL_PRIVATE, $post->post_author, $this->get_name(), $post_id);
        }
        
    }

    function text( RHSNotification $news ) {
        // TODO: Implement text() method.
    }

    function image(RHSNotification $news){

    }

}
