<?php
/**
Description: Notificação ao autor de um novo usuário que o segue
Short description: Novos usuários te seguindo
 */

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
        
        if($user = $this->getUser()) {

            return sprintf(
                'O usuário <a id="rhs-link-to-user-%d" href="%s" class="rhs-link-to-user"><strong>%s</strong></a> começou a te seguir',
                $user->get_id(),
                $user->get_link(),
                $user->get_name()
            );
        }
    }

    function image(){
        if($user = $this->getUser()) {
            return get_avatar_url($user->get_id());
        }
    }
    
    function textPush() {
        if($user = $this->getUser()) {
            return sprintf(
                'O usuário %s começou a te seguir',
                $user->get_name()
            );
        }
    }

    public function buttons() {
        $type = $this->getType();
        $button[] = (object) array('id' => 'open_' . $type, 'text' => 'Ver Usuário');
        
        return $button;
    }
}
