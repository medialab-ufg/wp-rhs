<?php


class RHSNotification_contact_reply extends RHSNotification {

    /**
     * @param $user_id ID do Usuário
     */
    static function notify($args) {

        // if(empty($args)){
        //     return;
        // }
        
        // $post = get_userdata($args['post_id']);
        // $user = get_userdata($args['user_id']);
        
        // if (is_object($user)) {
        //     global $RHSNotifications;
        //     $RHSNotifications->add_notification(RHSNotifications::CHANNEL_PRIVATE, $user->ID, self::get_name(), $post->ID);
        // }
        
    }

    function text() {
        // $user_ID = $this->getUserId();
        // $user = new RHSUser(get_userdata($user_ID));

        // return sprintf(
        //     'Seu <a href="%s">contato</a> foi respondivo',
        //     $user->get_link()
        // );
    }

    function image(){
        // $user_ID = $this->getUserId();
        // $user = new RHSUser(get_userdata($user_ID));
        // return $user->get_avatar();
    }

}
