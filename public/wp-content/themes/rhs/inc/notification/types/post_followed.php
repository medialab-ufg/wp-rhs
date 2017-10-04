<?php
/**
Description: Notificação ao autor de novo usuário que segue um post
Short description: Novos usuários seguindo seu post
 */

class RHSNotification_post_followed extends RHSNotification {

    /**
     * @param $post_id ID do Post
     */
    static function notify($args) {

        if(empty($args)){
            return;
        }
        
        $post = get_post($args['post_id']);
        
        if (is_object($post)) {
            global $RHSNotifications;
            $RHSNotifications->add_notification(RHSNotifications::CHANNEL_PRIVATE, $post->post_author, self::get_name(), $args['post_id'], $args['user_id']);
        }
        
    }

    function text() {

        $post = $this->getObjectAsPost();
        
        if (!$post)
            return;
        
        if($user = $this->getUser()) { 
            return sprintf(
                'O usuário <a id="rhs-link-to-user-%d" href="%s" class="rhs-links-to-user"><strong>%s</strong></a> está seguindo seu post <a id="rhs-link-to-post-%d" href="%s" class="rhs-links-to-post"><strong>%s</strong></a>',
                $user->get_id(),
                $user->get_link(),
                $user->get_name(),
                $post->ID,
                get_permalink($post->ID),
                $post->post_title
            );
        }
    }

    function textPush() {
        $post = $this->getObjectAsPost();
        
        if (!$post)
            return;
        
        if($user = $this->getUser()) {
        
            return sprintf(
                'O usuário %s está seguindo seu post %s',
                $user->get_name(),
                $post->post_title
            );
        }
    }

    function image(){
        if($user = $this->getUser()) {
            return $user->get_avatar();
        }
    }

    function imageSrc(){
        if($user = $this->getUser()) {
            return get_avatar_url($user->get_id());
        }
    }

    public function buttons() {
        $type = $this->getType();
        $buttons[] = (object) array('id' => 'open_' . $type, 'text' => 'Ver Post');
        $buttons[] = (object) array('id' => 'open_user_' . $type, 'text' => 'Ver Usuário');
        
        return $buttons;
    }

}
