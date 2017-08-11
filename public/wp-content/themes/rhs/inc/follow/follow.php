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
      * Check if anuser follows an author (another user)
      *
      * @param int $author_id The author that we want to check to see if he/she is followed by the user
      * @param int $user_id (optional) The ID of the user you want to check if he/she follows the author
      * @return bool true if user follows author, false if dont
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
     * Toggle function to check if user follow author and return params for use in other functions
     * 
     * @param int $author_id The author id to check if user follow author by they id
     * @param int $user_id The user id to check if user follow author by they id
     * @return int return '1' if already follow and '2' if not, if not correspond some condition it will return 'false'
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
     * Return meta user specific for user id to show followers of specific user
     * 
     * @param int $user_id Current user id to get meta keys
     * @return mixed Will be an array if user_id is not specified or if third param is false (is false in default). Will be value of meta_value field if third value is true. 
     */
    function get_user_followers($user_id) {
        return get_user_meta($user_id, self::FOLLOWED_KEY);
    }

    /**
     * Return meta user specific for user id to show follows of specific user
     * 
     * @param int $user_id 
     * @return mixed Will be an array if user_id is not specified or if third param is false (is false in default). Will be value of meta_value field if third value is true. 
     */
    function get_user_follows($user_id) {
        return get_user_meta($user_id, self::FOLLOW_KEY);
    }

    /**
     * This function have two actions:
     * 1) Add user meta to identify user who follow an author, meta_key in this case is called by '_rhs_follow'
     * 2) Add user meta to identify author followed by user, meta_key in this case is called by '_rhs_followed'
     *  
     * @param int $author_id The author id to check and add new usermeta
     * @param int $user_id The user id to check and add new usermeta
     * @return int/bool If user dont follows author it returns true with primary key id (umeta_id), false if already follow
     * @see rhs_add_user_meta_unique function declared on functions.php
     */
    function add_follow($author_id, $user_id) {
        rhs_add_user_meta_unique($user_id, self::FOLLOW_KEY, $author_id);
        return rhs_add_user_meta_unique($author_id, self::FOLLOWED_KEY, $user_id);
        // muito difícil acontecer um erro só em um dos metadados, então parece seguro retornar só o retorno da segunda chamada
    }

    /**
     * This function have two actions:
     * 1) Removes user meta to identify user who follow an author, meta_key in this case is called by '_rhs_follow'
     * 2) Removes user meta to identify author followed by user, meta_key in this case is called by '_rhs_followed'
     * 
     * @param int $author_id The author id to check and remove usermeta
     * @param int $user_id The user id to check and remove usermeta
     * @return bool true if action is completed, removing relation between user_id, meta_key and meta_value, false if dont
     * @see delete_user_meta on wordpress documentation
     */
    function remove_follow($author_id, $user_id) {
        delete_user_meta($user_id, self::FOLLOW_KEY, $author_id);
        return delete_user_meta($author_id, self::FOLLOWED_KEY, $user_id);
        // muito difícil acontecer um erro só em um dos metadados, então parece seguro retornar só o retorno da segunda chamada
    }

}

add_action('init', function() {
  global $RHSFollow;
  $RHSFollow = new RHSFollow();
});



