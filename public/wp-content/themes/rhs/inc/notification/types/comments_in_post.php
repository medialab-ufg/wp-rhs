<?php
/**
Description: Notificação de novos comentários em um post
Short description: Comentários em posts seguidos
 */

class RHSNotification_comments_in_post extends RHSNotification {

    /**
     * @param Object|Int $comment (ID ou objeto)
     */
    static function notify($comment) {
        $c = is_object($comment) ? $comment : get_comment($comment);
        /*echo "<pre>";
        print_r($c);
        echo "</pre>";
        exit();*/
        // apenas cometarios aprovados e q não são de algum tipo especial, tipo pingback
        // e apenas comentários que foram feitos por algum usuário logado
        if (1 == $c->comment_approved && empty($c->comment_type) && !empty($c->user_id))
        {
            // apenas cometarios aprovados e q não são de algum tipo especial, tipo pingback
            global $RHSNotifications;
            $RHSNotifications->add_notification(RHSNotifications::CHANNEL_COMMENTS, $c->comment_post_ID, self::get_name(), $c->comment_ID, $c->user_id);
        }

        
    }



    function text() {
        $comment_ID = $this->getObjectId();
        $c = get_comment($comment_ID);

        if($c) {
            $post_ID = $c->comment_post_ID;

            if($user = $this->getUser()) {

                return sprintf(
                    '<a id="rhs-link-to-user-%d" href="%s" class="rhs-links-to-user"><strong>%s</strong></a> comentou no post <a id="rhs-link-to-post-%d" href="%s" class="rhs-link-to-post"><strong>%s</strong></a>',
                    $user->get_id(),
                    $user->get_link(),
                    $user->get_name(),
                    $post_ID,
                    get_permalink($post_ID),
                    get_post_field( 'post_title', $post_ID )
                );
            }
        }
        
    }
   

    function textPush() {
        $comment_ID = $this->getObjectId();
        $c = get_comment($comment_ID);
        
        if (!$c)
            return;
        
        $post_ID = $c->comment_post_ID;

        $user = $this->getUser();
        
        return sprintf(
            '%s comentou no post %s</a>',
            $user->get_name(),
            get_post_field( 'post_title', $post_ID )
        );
    }

    function image() {
        $comment_ID = $this->getObjectId();
        $c = get_comment($comment_ID);        
       
        if($c) {
            $post_ID = $c->comment_post_ID;

            if($user = $this->getUser()) {
                return $user->get_avatar_url($user->get_id());
            }
        }        
    }

    function imageSrc(){
        $comment_ID = $this->getObjectId();
        $c = get_comment($comment_ID);        
       
        if($c) {
            $post_ID = $c->comment_post_ID;

            if($user = $this->getUser()) {
                return get_avatar_url($user->get_id());
            }
        }
    }

    public function buttons() {
        $type = $this->getType();
        $buttons[] = (object) array('id' => 'open_' . $type, 'text' => 'Ver Comentário');
        $buttons[] = (object) array('id' => 'open_user' . $type, 'text' => 'Ver Usuário');
        
        return $buttons;
    }

}
