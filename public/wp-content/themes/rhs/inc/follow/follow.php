<?php

class RHSFollow {
    const FOLLOW_KEY = '_rhs_follow';

    function __construct() {
        add_action('init', array(&$this,'init'));
        add_action('wp_enqueue_scripts', array(&$this, 'addJS'));
        add_action('rhs_author_header_actions', array(&$this, 'show_header_follow_box'));
        add_action('wp_ajax_rhs_follow', array(&$this, 'toggle_follow_user'));
        add_action('wp_ajax_save_meta', array(&$this, 'save_meta'));
    }

    function addJS() {
        wp_enqueue_script('rhs_follow', get_template_directory_uri() . '/inc/follow/follow.js', array('jquery'));
        wp_localize_script('rhs_follow', 'follow', array('ajaxurl' => admin_url('admin-ajax.php')));
    }

    function show_header_follow_box() {
        $current_user = wp_get_current_user();
        $user_id      = $current_user->ID;

        if ($author_id = get_query_var('author')) {
            $author = get_user_by('id',$author_id);
        }

        $isFollowing = get_user_meta($user_id, self::FOLLOW_KEY);

        if ($user_id == $author->ID) {
          $output = '';
        } elseif ($isFollowing && in_array($author->ID, $isFollowing)) {
          $output = "<button class='btn btn-default unfollow-btn' data-author_id='". $author->ID ."'>Parar de Seguir</button>";
        } else {
            $output = "<button class='btn btn-default follow-btn' data-author_id='". $author->ID ."'>Seguir</button>";
        }
        echo $output;
    }

    function toggle_follow_user() {
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $author_id = $_POST['author_id'];
            if (is_numeric($author_id)) {
                $this-> save_meta($current_user->ID, $author_id);
            }
        }
        exit;
    }

    function save_meta($user_id, $author_id) {
        global $wpdb;

        $isFollowing = get_user_meta($user_id, self::FOLLOW_KEY, $author_id);
        $user_meta = get_user_meta(get_current_user_id(), self::FOLLOW_KEY, $author_id);
        $item_count = $wpdb->get_var( "SELECT count(*) FROM $wpdb->usermeta
            WHERE user_id = $user_id AND meta_key = '_rhs_follow'
            AND meta_value = $author_id" );


        if ($isFollowing != $author_id && $item_count < 1) {
            add_user_meta($user_id, self::FOLLOW_KEY, $author_id);
        } else {
            delete_user_meta($user_id, self::FOLLOW_KEY, $author_id);
        }
    }

}

add_action('init', function() {
  $RHSFollow = new RHSFollow();
});

global $RHSFollow;

