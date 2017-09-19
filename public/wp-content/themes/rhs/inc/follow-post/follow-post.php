<?php

class RHSFollowPost {
    const FOLLOW_POST_KEY = '_rhs_follow_post';
    const FOLLOWED_POST_KEY = '_rhs_followed_by';

    function __construct() {
        add_action('wp_enqueue_scripts', array(&$this, 'addJS'));
        add_action('rhs_follow_post_box', array(&$this, 'show_header_follow_post_box'));
        add_action('wp_ajax_rhs_follow_post', array(&$this, 'ajax_callback'));
    }

    function addJS() {
        wp_enqueue_script('rhs_follow_post', get_template_directory_uri() . '/inc/follow-post/follow-post.js', array('jquery'));
        wp_localize_script('rhs_follow_post', 'follow_post', array('ajaxurl' => admin_url('admin-ajax.php')));
    }

    /**
     * Show button to follow post
     * 
     * @param int $post_id The post ID to check if is already followed
     * @return mixed it returns button to follow or unfoloww user and check if user is post author, in this case this button must be hide
     */
    function show_header_follow_post_box($post_id) {
        
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;
            $author_id = get_post_field('post_author', $post_id);
            if ($user_id == $author_id) {
                return;
            }
            
            $isFollowing = $this->does_user_follow_post($post_id);

            $button_html = "<button class='btn-rhs follow-post-btn' data-post_id='". $post_id ."'>";
            $button_html .= ($isFollowing) ? "<span class='fa-stack' title='Deixar de Seguir'><i class='fa fa-rss fa-stack-1x'></i><i class='fa fa-remove fa-stack-2x'></i></span>" : "<span class='fa-stack follow-post'><i class='fa fa-rss'></i></span>";
            $button_html .= "</button>";
            echo $button_html;
        }
    }

    function ajax_callback() {
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $post_id = $_POST['post_id'];
            if (is_numeric($post_id)) {
                echo json_encode($this->toggle_follow_post($post_id, $current_user->ID));
            }
        }
        exit;
    }

    /**
     * Check if anuser follows current post
     *
     * @param int $post_id The post ID
     * @param int $user_id (optional) The ID of the user you want to check if follows post
     * @return bool true if user follows post, false if dont
     */
    function does_user_follow_post($post_id, $user_id = null) {
        if (is_null($user_id)) {
            $current_user = wp_get_current_user();
            if (!$current_user)
                return false;
            $user_id = $current_user->ID;
        }
        $follows_post = $this->get_post_followers($post_id);
        return in_array($user_id, $follows_post);
    }

    /**
     * Toggle function to check if user follow post and return params for use in other functions
     * 
     * @param int $post_id The post id to check if user follows post
     * @param int $user_id The user id to check if user follows post by they id
     * @return int return '1' if already follow and '2' if not, if not correspond some condition it will return 'false'
     */
    function toggle_follow_post($post_id, $user_id) {
        if ($this->does_user_follow_post($post_id, $user_id)) {
            if (false !== $this->remove_follow_post($post_id, $user_id))
                return 1;
        } else {
            if (false !== $this->add_follow_post($post_id, $user_id))
                return 2;
        }
        return false;
    }


    /**
     * Return list of ids of the users that follow a post
     * 
     * @param int $post_id 
     * @return mixed Will be an array if user_id is not specified or if third param is false (is false in default). Will be value of meta_value field if third value is true. 
     */
    function get_post_followers($post_id) {
        return get_post_meta($post_id, self::FOLLOWED_POST_KEY);
    }

    /**
     * Return meta user specific for user id to show follows of specific user
     * 
     * @param int $user_id 
     * @return mixed Will be an array if user_id is not specified or if third param is false (is false in default). Will be value of meta_value field if third value is true. 
     */
    function get_posts_followed_by_user($user_id) {
        return get_user_meta($user_id, self::FOLLOW_POST_KEY);
    }

    /**
     * Add meta_key to identify post followed by user
     *  
     * @param int $post_id The post id to check and add new usermeta
     * @param int $user_id The user id to check and add new usermeta
     * @return int/bool If user dont follows post it returns true with primary key id (umeta_id), false if already follow
     * @see rhs_add_user_meta_unique function declared on functions.php
     */
    function add_follow_post($post_id, $user_id) {
        rhs_add_user_meta_unique($user_id, self::FOLLOW_POST_KEY, $post_id);
        $return = rhs_add_post_meta_unique($post_id, self::FOLLOWED_POST_KEY, $user_id);
        if ($return)
            do_action('rhs_add_user_follow_post', ['user_id' => $user_id, 'post_id' => $post_id]);
        return $return;
    }

    /**
     * Remove meta_key to identify post followed by user
     * 
     * @param int $post_id The post id to check and remove usermeta
     * @param int $user_id The user id to check and remove usermeta
     * @return bool true if action is completed, removing relation between user_id, meta_key and meta_value, false if dont
     * @see delete_user_meta on wordpress documentation
     */
    function remove_follow_post($post_id, $user_id) {
        delete_user_meta($user_id, self::FOLLOW_POST_KEY, $post_id);
        $return = delete_post_meta($post_id, self::FOLLOWED_POST_KEY, $user_id);
        if ($return)
            do_action('rhs_delete_user_follow_post', ['user_id' => $user_id, 'post_id' => $post_id]);
        return $return;
    }


}

add_action('init', function() {
    global $RHSFollowPost;
    $RHSFollowPost = new RHSFollowPost();
});
