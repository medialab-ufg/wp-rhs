<?php
/**
Description: Notificação ao autor de um formulário de contato quando sua pergunta é respondida
Short description: Respostas em formulário de contato
 */

class RHSNotification_replied_ticket extends RHSNotification {

    /**
     */
    static function notify($user_from_contact, $post_ID, $content) {
        if (intval($user_from_contact) !== get_current_user_id()) {
            global $RHSNotifications;
            $RHSNotifications->add_notification(RHSNotifications::CHANNEL_PRIVATE, $user_from_contact, self::get_name(), $post_ID);
        }
    }

    function text() {
        $post = $this->getObjectAsPost();
        
        if (!$post)
            return;
                
        return sprintf(
            'Seu <a id="rhs-link-to-post-%d" href="%s" class="rhs-links-to-post">contato</a> foi respondido',
            $post->ID,
            get_permalink($post->ID)
        );
    }

    function textPush() {
        return 'Seu contato foi respondido';
    }

    function image(){        
        return get_template_directory_uri() ."/inc/notification/assets/default_icon.png";
    }


    public function buttons() {
        $type = $this->getType();
        $button[] = (object) array('id' => 'open_' . $type, 'text' => 'Ver Resposta');
        
        return $button;
    }

}
