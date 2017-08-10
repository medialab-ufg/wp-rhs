<?php

class RHSFollow {
    const FOLLOW_KEY = '_rhs_follow';
    const FOLLOWED_KEY = '_rhs_followed';

    function __construct() {
        add_action('wp_enqueue_scripts', array(&$this, 'addJS'));
        add_action('rhs_author_header_actions', array(&$this, 'show_header_follow_box'));
        add_action('wp_ajax_rhs_follow', array(&$this, 'ajax_callback'));
    }

    function addJS() {
        wp_enqueue_script('rhs_follow', get_template_directory_uri() . '/inc/follow/follow.js', array('jquery'));
        wp_localize_script('rhs_follow', 'follow', array('ajaxurl' => admin_url('admin-ajax.php')));
    }

    function show_header_follow_box($author_id) {
        $current_user = wp_get_current_user();
        $user_id      = $current_user->ID;

        if ($user_id == $author_id) {
          return;
        }

        $isFollowing = $this->does_user_follow_author($author_id);

        $button_html = "<button class='btn btn-default follow-btn' data-author_id='". $author_id ."'>";
        $button_html .= ($isFollowing) ? "Parar de Seguir" : "Seguir";
        $button_html .= "</button>";
        echo $button_html;
    }

    function ajax_callback() {
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $author_id = $_POST['author_id'];
            if (is_numeric($author_id)) {
                echo json_encode($this->toggle_follow($author_id, $current_user->ID));
            }
        }
        exit;
    }


     /**
      *  
      * @param type $author_id 
      * @param type|null $user_id 
      * @return type
      */
    function does_user_follow_author($author_id, $user_id = null) {

        if (is_null($user_id)) {
            $current_user = wp_get_current_user();
            if (!$current_user)
                return false;
            $user_id = $current_user->ID;
        }
        $follows = $this->get_user_follows($user_id);
        return in_array($author_id, $follows);

    }


    /**
     *  
     * @param type $author_id 
     * @param type $user_id 
     * @return type
     */
    
    function toggle_follow($author_id, $user_id) {

        if ($this->does_user_follow_author($author_id, $user_id)) {
            if (false !== $this->remove_follow($author_id, $user_id))
                return 1;
        } else {
            if (false !== $this->add_follow($author_id, $user_id))
                return 2;
        }

        return false;

    }

    /**
     *  
     * @param type $user_id 
     * @return type
     */
    function get_user_followers($user_id) {
        return get_user_meta($user_id, self::FOLLOWED_KEY);
    }

    /**
     *  
     * @param type $user_id 
     * @return type
     */
    function get_user_follows($user_id) {
        return get_user_meta($user_id, self::FOLLOW_KEY);
    }

    /**
     *  
     * @param type $author_id 
     * @param type $user_id 
     * @return type
     */
    function add_follow($author_id, $user_id) {
        rhs_add_user_meta_unique($user_id, self::FOLLOW_KEY, $author_id);
        return rhs_add_user_meta_unique($author_id, self::FOLLOWED_KEY, $user_id);
        // muito difícil acontecer um erro só em um dos metadados, então parece seguro retornar só o retorno da segunda chamada
    }

    /**
     *  
     * @param type $author_id 
     * @param type $user_id 
     * @return type
     */
    function remove_follow($author_id, $user_id) {
        delete_user_meta($user_id, self::FOLLOW_KEY, $author_id);
        return delete_user_meta($author_id, self::FOLLOWED_KEY, $user_id);
    }

}

add_action('init', function() {
  global $RHSFollow;
  $RHSFollow = new RHSFollow();
});



