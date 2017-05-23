<?php

// SUBSTITUA  Carrossel pelo slug do metabox

class CarrosselMetabox {

    protected static $metabox_config = array(
        'Carrossel', // slug do metabox
        'Carrossel', // título do metabox
        'post', // array('post','page','etc'), // post types
        'side' // onde colocar o metabox
    );

    static function init() {
        add_action('add_meta_boxes', array(__CLASS__, 'addMetaBox'));
        add_action('save_post', array(__CLASS__, 'savePost'));
    }

    static function addMetaBox() {
        add_meta_box(
            self::$metabox_config[0],
            self::$metabox_config[1],
            array(__CLASS__, 'metabox'), 
            self::$metabox_config[2],
            self::$metabox_config[3]
            
        );
    }

    
    static function metabox(){
        global $post;
        
        wp_nonce_field( 'save_'.__CLASS__, __CLASS__.'_noncename' );
        
        $highlighted = get_post_meta($post->ID, "_home", true) == 1 ?  "checked" : "";
        
        ?>
        <input type="checkbox" id="carrossel_<?php echo $post->ID; ?>" name="RHS_Carrossel" <?php echo $highlighted; ?> value="1">
        <label> Adicionar post ao Carrossel </label>
        <?php
    }

    static function savePost($post_id) {
        // verify if this is an auto save routine. 
        // If it is our form has not been submitted, so we dont want to do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

        // verify this came from the our screen and with proper authorization,
        // because save_post can be triggered at other times

        if (!wp_verify_nonce($_POST[__CLASS__.'_noncename'], 'save_'.__CLASS__))
            return;


        // Check permissions
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id))
                return;
        }
        else {
            if (!current_user_can('edit_post', $post_id))
                return;
        }

        // OK, we're authenticated: we need to find and save the data
        if(isset($_POST['RHS_Carrossel'])){
            
            if ($_POST['RHS_Carrossel'] == 1)
                update_post_meta($post_id, '_home', 1);
            
            
                
        } else {
            delete_post_meta($post_id, '_home');
        }
    }

    
}


CarrosselMetabox::init();
