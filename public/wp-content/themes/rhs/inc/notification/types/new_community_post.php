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
        $community_id = str_replace(RHSNotifications::CHANNEL_COMMUNITY, '', $this->getChannel());
        $community = get_term_by('id', $community_id, RHSComunities::TAXONOMY);

        return sprintf(
            '<a href="%s"><strong>%s</strong></a> criou um novo post <a href="%s"><strong>%s</strong></a> na comunidade <a href="%s"><strong>%s</strong></a>',
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
