<?php

class RHSPost extends RHSMenssage {

    private static $instance;

    function __construct() {

        if ( empty ( self::$instance ) ) {
            if ( ! empty( $_POST['post_user_wp'] ) && $_POST['post_user_wp'] == $this->getKey() ) {
                $this->posting();
            }
        }

        self::$instance = true;
    }

    private function posting(){

        if(!$_POST){
            return array();
        }

        $this->clear_messages();

        if(!array_key_exists('title', $_POST)){
            $this->set_messages(array('error' => '<i class="fa fa-exclamation-triangle "></i> Preencha o seu email!'));
            return;
        }

        if(!get_current_user_id()){
            $this->set_messages(array('error' => '<i class="fa fa-exclamation-triangle "></i> Efetue o login para realizar um post'));
            return;
        }

        if(!array_key_exists('category', $_POST)){
            $this->set_messages(array('error' => '<i class="fa fa-exclamation-triangle "></i> Selecione uma categoria!'));
            return;
        }

        $dataPost = array(
            'post_title'    => wp_strip_all_tags( $_POST['title'] ),
            'post_content'  => $_POST['post_content'],
            'post_status'   => (!empty($_POST['draft'])) ? 'draft' : 'publish',
            'post_author'   => get_current_user_id(),
            'post_category' => $_POST['category']
        );

        $post_ID = wp_insert_post( $postarr, true);

        if($post_ID instanceof WP_Error){

            foreach ($post_ID->get_error_messages() as $error){
                $this->set_messages($error, false, 'error');
            }

            return;

        }

        if(array_key_exists('tags', $_POST) && is_numeric($post_ID)){
            wp_set_post_tags( $post_ID, $_POST['tags'], true );
            return;
        }



    }
}

global $RHSPost;
$RHSPost = new RHSPost();
