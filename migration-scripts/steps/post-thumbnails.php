<?php

$this->log('Limpando base de dados de thumbnails');
$tids = $wpdb->get_col("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '_thumbnail_id'");

if (sizeof($tids) > 0) {
    $tids = implode(',', $tids);
    $wpdb->query("DELETE FROM $wpdb->posts WHERE ID IN ($tids) ");
    $wpdb->query("DELETE FROM $wpdb->postmeta WHERE post_ID IN ($tids) ");
    $wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key = '_thumbnail_id' ");
}


$this->log('Setando tamanho dos thumbnails para 450px');
update_option( 'thumbnail_size_w', 450 );
update_option( 'thumbnail_size_h', 450 );
update_option( 'thumbnail_crop', 0 );

$this->log('Pegando imagens destacadas no drupal');
$images = $wpdb->get_results('select distinct * from '. RHS_DRUPALDB . '.field_data_field_home_image JOIN '. RHS_DRUPALDB . '.file_managed f ON f.fid = field_home_image_fid JOIN '. RHS_DRUPALDB . '.node on nid = entity_id');

require_once ABSPATH . 'wp-admin/includes/import.php';
require_once ABSPATH . 'wp-admin/includes/image.php';  

function get_root_path() {
    if ( file_exists( ABSPATH . 'wp-config.php') ) {

        return ABSPATH;

    } elseif ( @file_exists( dirname( ABSPATH ) . '/wp-config.php' ) && ! @file_exists( dirname( ABSPATH ) . '/wp-settings.php' ) ) {

        return dirname( ABSPATH ) ;
        
    }
}

$success = 0;
$error = 0;

foreach ($images as $image) {
    

    // get placeholder file in the upload dir with a unique, sanitized filename
    $upload = wp_upload_bits( $image->filename, null, '', date('Y/m', $image->created) ); // passando a data de criação do post
    if ( $upload['error'] ) {
        $this->log('Erro ao criar imagem: ' . $image->filename . ' (fid ' . $image->fid . ')');
        $error++;
        continue;
    }
    
    // move the file
    $sourcefile = str_replace('public://', get_root_path() . '/sites/default/files/', $image->uri);
    if (!file_exists($sourcefile)) {
        $this->log('Erro ao localizar imagem: ' . $sourcefile . ' (fid ' . $image->fid . ')');
        $error++;
        continue;
    }
    
    if (copy($sourcefile, $upload['file'])) {
        
        $newatt = array(
                'post_date_gmt' => date('Y/m/d H:i:s', $image->timestamp), 
                'post_title' => $image->field_home_image_title,
                'post_author' => $image->uid,
                //'post_status' => 'publish', 
                'post_parent' => $image->nid,
                'post_content' => $image->field_home_image_alt,
                'post_type' => 'attachment',
                'post_mime_type' => $image->filemime,
                'guid' => $upload['url'],
            );
        
        
        $post_id = wp_insert_attachment( $newatt, $upload['file'] );
        wp_update_attachment_metadata( $post_id, wp_generate_attachment_metadata( $post_id, $upload['file'] ) );
        
        add_post_meta($image->nid, '_thumbnail_id', $post_id);
        
        $success ++;
        echo '.';
    
    } else {
    
        $this->log('Erro ao copiar imagem: ' . $sourcefile . ' (fid ' . $image->fid . ')');
        $error++;
        continue;
    
    } 
    
}

$this->log('Total de erros: ' . $error);
$this->log('Total de imagens copiadas: ' . $success);

