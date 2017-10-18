<?php
/**
Description: Notificação ao autor de post promovido a home page
Short description: Posts seus promovidos
 */

class RHSNotification_post_promoted extends RHSNotification {

    /**
     * @param $post_id ID do Post
     */
    static function notify($post_id) {

        if(empty($post_id)){
            return;
        }
        
        $post = get_post($post_id);
        
        if (is_object($post)) {
            global $RHSNotifications;
            $RHSNotifications->add_notification(RHSNotifications::CHANNEL_PRIVATE, $post->post_author, self::get_name(), $post_id);
        }
        
    }

    function text() {

        $post = $this->getObjectAsPost();
        
        if (!$post)
            return;
        
        return sprintf(
            'Seu post <a id="rhs-link-to-post-%d" href="%s" class="rhs-links-to-post"><strong>%s</strong></a> foi promovido.',
            $post->ID,
            get_permalink($post->ID),
            $post->post_title
        );
    }

    function textPush() {
        $post = $this->getObjectAsPost();
        
        if (!$post)
            return;
        
        return sprintf(
            'Seu post %s foi promovido.',
            $post->post_title
        );
    }

    function image(){
        $post_ID = $this->getObjectId();
        if (has_post_thumbnail($post_ID)){ 
            return get_the_post_thumbnail_url($post_ID, 'medium');
        } else {
            return get_template_directory_uri() ."/inc/notification/assets/default_icon.png";
        }        
    }
    
    function getImageClass() {
        return 'post-notification';
    }
    
    public function buttons() {
        $type = $this->getType();
        $button[] = (object) array('id' => 'open_' . $type, 'text' => 'Ver Post');
        
        return $button;
    }

}
