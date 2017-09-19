<?php


class RHSNotification_new_community_post extends RHSNotification {

    /**
     * @param $args = [$communities - array com nomes das comunidades, $post_id, $author_id]
     */
    static function notify($args) {

        if(empty($args['communities']) || !is_array($args['communities']) || empty($args['post_id'])){
            return;
        }
        
        global $RHSNotifications;
        foreach ($args['communities'] as $community) {
            $term = get_term_by( 'name', $community, RHSComunities::TAXONOMY );
            $RHSNotifications->add_notification(RHSNotifications::CHANNEL_COMMUNITY, $term->term_id, self::get_name(), $args['post_id'], $args['author_id']);
        }
        
        
        
    }

    function text() {

        $post_ID = $this->getObjectId();
        $user = new RHSUser(get_userdata(get_post_field( 'post_author', $post_ID )));
        
        $str_channel = str_replace('%s', '', RHSNotifications::CHANNEL_COMMUNITY);
        
        $community_id = str_replace($str_channel, '', $this->getChannel());

        $community = get_term_by('id', $community_id, RHSComunities::TAXONOMY);

        return sprintf(
            '<a id="%d" href="%s" class="rhs-links-to-user"><strong>%s</strong></a> criou um novo post <a id="%s" href="%s" class="rhs-links-to-post"><strong>%s</strong></a> na comunidade <a id="%d" href="%s" class="rhs-links-to-community"><strong>%s</strong></a>',
            $user->get_id(),
            $post_ID,
            $community_id,
            $user->get_link(),
            $user->get_name(),
            get_permalink($post_ID),
            get_post_field( 'post_title', $post_ID ),
            get_term_link( $community ),
            $community->name
        );
    }

    function image(){

        $post_ID = $this->getObjectId();

        $user = new RHSUser(get_userdata(get_post_field( 'post_author', $post_ID )));
        return $user->get_avatar();
    }

}
