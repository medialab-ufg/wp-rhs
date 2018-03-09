<?php
ini_set('memory_limit', '-1');
require_once(ABSPATH . 'wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');
// $_p = include('../sql/posts-meta-attachment.sql');

// var_dump($_p); die;

$this->log('Importando Postmeta de Anexos...');
// $this->query('posts-meta-attachment');
$this->query('../sql/posts-meta-attachment');

$this->log('Importando Anexos...');
$this->query('../sql/posts-attachment');

$this->log('Atualizando Posts com Anexos...');
$attachments = $wpdb->get_results("SELECT `ID`, `post_parent`, `guid` FROM $wpdb->posts WHERE post_type = 'attachment';");

foreach ($attachments as $attachment) {
    $id = $attachment->ID;
    $id_post_parent = $attachment->post_parent;
    $filepath = $attachment->guid;
    $filename = basename($filepath);
    $filetype = wp_check_filetype($filepath);
    $post_id = $attachment->post_parent;
    
    $attachment_data = array(
        'guid' => $filepath,
        'post_mime_type' => $filetype['type'],
        'post_title' => preg_replace( '/\.[^.]+$/', '', $filename ),
        'post_content' => '',
        'post_parent' => $post_id,
    );
    
    wp_generate_attachment_metadata($id, $filepath);
    wp_update_attachment_metadata($id, $attachment_data);

    $post = get_post($id_post_parent);
    $post_content = $post->post_content;
    if($post_content) {
        $post_with_new_content = array(
            'ID'           => $id_post_parent,
            'post_status' => 'publish',
            'post_content' => $post_content . "<p><a href='". $filepath ."'>". $filename ."</a></p>"
        );
        
        $post_id = wp_update_post($post_with_new_content,true);
        if (is_wp_error($post_id)) {
            $errors = $post_id->get_error_messages();
            foreach ($errors as $error) {
                echo $error;
            }
        }
    }
}