<?php
/**
Description: Notificação de novos posts de um usuário
Short description: Novos posts de usuários seguidos
 */

class RHSNotification_new_post_from_user extends RHSNotification {

    /**
     * @param $args - (user_id) ID do Author ; (post_id) ID do Post
     */
    static function notify($args) {

        if(empty($args['user_id']) || empty($args['post_id'])){
            return;
        }
        
        $author_id = get_post_field( 'post_author', $args['post_id'] );
        
        global $RHSNotifications;
        $RHSNotifications->add_notification(RHSNotifications::CHANNEL_USER, $args['user_id'], self::get_name(), $args['post_id'], $author_id);

    }

    function text() {

        $post = $this->getObjectAsPost();
        
        if (!$post)
            return;

        if($user = $this->getUser()) {

            return sprintf(
                '<a id="rhs-link-to-user-%d" href="%s" class="rhs-links-to-user"><strong>%s</strong></a> criou um novo post <a id="rhs-link-to-post-%d" href="%s" class="rhs-links-to-post"><strong>%s</strong></a>',
                $user->get_id(),
                $user->get_link(),
                $user->get_name(),
                $post->ID,
                get_permalink($post->ID),
                $post->post_title
            );
        } else {
            return '';
        }
    }

    function textPush() {
        $post = $this->getObjectAsPost();
        
        if (!$post)
            return;
        
        if($user = $this->getUser()) {

            return sprintf(
                '%s criou um novo post %s',
                $user->get_name(),
                $post->post_title
            );
        } else {
            return '';
        }
    }

    function image(){

        if($user = $this->getUser()) {
            return $user->get_avatar();
        } else {
            return '';
        }
        
    }

    public function getButtons() {
        $type = $this->getType();
        $buttons[] = (object) array('id' => 'open_post_' . $type, 'text' => 'Ver Post');
        $buttons[] = (object) array('id' => 'open_user_' . $type, 'text' => 'Ver Usuário');
        
        return $buttons;
    }

}
