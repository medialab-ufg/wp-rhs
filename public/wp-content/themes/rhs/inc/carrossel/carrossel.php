<?php



Class Carrossel {


    function __init() {
    
    
        add_action('manage_posts_custom_column', array('Carrossel', 'select'), 10, 2);
        add_filter('manage_posts_columns',array('Carrossel', 'add_column'));
        add_action('manage_noticias_posts_custom_column', array('Carrossel', 'select'), 10, 2);
        add_action('load-edit.php', array('Carrossel', 'JS'));
        add_action('load-edit-pages.php', array('Carrossel', 'JS'));
        
        add_action('wp_ajax_destaque_add', array('Carrossel', 'add'));
        add_action('wp_ajax_destaque_remove', array('Carrossel', 'remove'));
        
        add_action('pre_get_posts', array('Carrossel', 'pre_get_posts'));
    
    
    }
    
    function add_column($defaults){
        global $post_type;
        if ('post' == $post_type || 'noticias' == $post_type || 'imprensa' == $post_type)
            $defaults['destaques'] = 'Carrossel';
        return $defaults;
    }

    function select($column_name, $id){

        if ($column_name=="destaques"){
            $highlighted = get_post_meta($id, "_home", true) == 1 ?  "checked" : "";
        ?>  
            <input type="checkbox" class="carrossel_button" id="carrossel_<?php echo $id; ?>" <?php echo $highlighted; ?>>
        <?php
        }
    }

    function JS() {
        wp_enqueue_script('carrossel', get_template_directory_uri() . '/inc/carrossel/admin.js', array('jquery'));
        wp_enqueue_style('carrossel', get_template_directory_uri() . '/inc/carrossel/carrossel.css');
        wp_localize_script('carrossel', 'hacklab', array('ajaxurl' => admin_url('admin-ajax.php') ));
    }
    
    function add() {
        update_post_meta($_POST['post_id'], '_home', 1);
        echo 'ok';
        die;
    }

    function remove() {
        delete_post_meta($_POST['post_id'], '_home');
        echo 'ok';
        die;
    }
    
    function pre_get_posts($wp_query) {

        if (!$wp_query->is_main_query())
            return $wp_query;
        
        if (is_front_page()) {
            global $wpdb;
            $wp_query->query_vars['post__not_in'] = $wpdb->get_col("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_home' AND meta_value = 1");
        
        }

    }
    
    function get_posts() {
    
    
        return new WP_Query( 'posts_per_page=-1&meta_key=_home&meta_value=1&ignore_sticky_posts=1&post_type=any' );
    
    
    }
    
    




}


add_action('init', array('Carrossel', '__init'));

require_once('metabox.php');
