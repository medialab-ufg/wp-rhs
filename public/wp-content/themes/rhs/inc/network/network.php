<?php

class RHSNetwork {

    const META_KEY_FACEBOOK = 'rhs_data_facebook';
    const META_KEY_TWITTER = 'rhs_data_twitter';
    const META_KEY_VIEW = 'rhs_data_view';

    public function __construct() {
        add_action('wp_ajax_nopriv_add_data_view', array( &$this, 'add_data_view' ) );
    }

    private function get_id($post_id){

        if($post_id == 0){
            $post_id = get_the_ID();
        }

        if($post_id == 0 && !empty($_REQUEST['postID'])){
            $post_id = $_REQUEST['postID'];
        }

        return $post_id;

    }

    public function add_data_facebook($post_id = 0){

        $post_id = $this->get_id($post_id);

        if ( ! add_post_meta( $post_id, META_KEY_FACEBOOK, 1, true ) ) {

            $post_meta = get_post_meta( $post_id, META_KEY_FACEBOOK, true );
            ++$post_meta;

            update_post_meta( $post_id, META_KEY_FACEBOOK, $post_meta );
        }

        if(!empty($_REQUEST['json'])){
            echo json_encode( true );
        }
    }

    public function add_data_twitter($post_id = 0){

        $post_id = $this->get_id($post_id);

        if ( ! add_post_meta( $post_id, META_KEY_TWITTER, 1, true ) ) {

            $post_meta = get_post_meta( $post_id, META_KEY_TWITTER, true );
            ++$post_meta;

            update_post_meta( $post_id, META_KEY_TWITTER, $post_meta );
        }

        if(!empty($_REQUEST['json'])){
            echo json_encode( true );
        }

    }

    public function add_data_view($post_id = 0){

        $post_id = $this->get_id($post_id);

        if ( ! add_post_meta( $post_id, META_KEY_VIEW, 1, true ) ) {

            $post_meta = get_post_meta( $post_id, META_KEY_VIEW, true );
            ++$post_meta;

            update_post_meta( $post_id, META_KEY_VIEW, $post_meta );
        }

        if(!empty($_REQUEST['json'])){
            echo json_encode( true );
        }

    }

}

global $RHSNetwork;
$RHSNetwork = new RHSNetwork();