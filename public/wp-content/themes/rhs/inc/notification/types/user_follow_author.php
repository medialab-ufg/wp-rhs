<?php


class RHSNotification_user_follow_author extends RHSNotification {

    /**
     * @param $user_id ID do Usuário
     */
    static function notify($args) {

        if(empty($args)){
            return;
        }
        
        $author = get_userdata($args['author_id']);
        $user = get_userdata($args['user_id']);
        
        if (is_object($user)) {
            global $RHSNotifications;
            $RHSNotifications->add_notification(RHSNotifications::CHANNEL_PRIVATE, $author->ID, self::get_name(), $author->ID, $user->ID);
        }
        
    }

    function text() {
        $user_ID = $this->getUserId();
        if($this->is_valid_user($user_ID)) {
            $user = new RHSUser(get_userdata($user_ID));

            return sprintf(
                'O usuário <a href="%s"><strong>%s</strong></a> passou a te seguir',
                $user->get_link(),
                $user->get_name()
            );
        }
    }

    function image(){
        $user_ID = $this->getUserId();
        if($this->is_valid_user($user_ID)) {
            $user = new RHSUser(get_userdata($user_ID));
            return $user->get_avatar();
        }
    }

}
