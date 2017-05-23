<?php

$logFilesCreatedFilename = WP_CONTENT_DIR . "/uploads/post-thumbnails-imported.json";

$this->log('Limpando base de dados de thumbnails');
$tids = $wpdb->get_col("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '_thumbnail_id'");

if (sizeof($tids) > 0) {
    $tids = implode(',', $tids);
    $wpdb->query("DELETE FROM $wpdb->posts WHERE ID IN ($tids) ");
    $wpdb->query("DELETE FROM $wpdb->postmeta WHERE post_ID IN ($tids) ");
    $wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key = '_thumbnail_id' ");
}

$this->log('Limpando arquivos gerados se o script já foi rodado antes');
$files = @file_get_contents($logFilesCreatedFilename);
if ($files) {
    $files = json_decode($files);
    if (is_array($files)) {
        foreach ($files as $f)
            @unlink($f);
    }
}
@unlink($logFilesCreatedFilename);

$this->log('Setando tamanho dos thumbnails para 450px');
update_option( 'thumbnail_size_w', 450 );
update_option( 'thumbnail_size_h', 450 );
update_option( 'thumbnail_crop', 0 );

$this->log('Pegando imagens destacadas no drupal');
$images = $wpdb->get_results('select distinct * from '. RHS_DRUPALDB . '.field_data_field_home_image 
                JOIN '. RHS_DRUPALDB . '.file_managed f ON f.fid = field_home_image_fid 
                JOIN '. RHS_DRUPALDB . '.node on nid = entity_id ');

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

// Save all the files created so we can clean up the next time we run the script
$filesCreated = [];

foreach ($images as $image) {
    
    // get placeholder file in the upload dir with a unique, sanitized filename
    $upload = wp_upload_bits( $image->filename, null, '', date('Y/m', $image->created) ); // passando a data de criação do post
    if ( $upload['error'] ) {
        $this->log('Erro ao criar imagem: ' . $image->filename . ' (fid ' . $image->fid . ')');
        $error++;
        continue;
    }
    
    $filesCreated[] = $upload['file'];
    
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
        $att_metadata = wp_generate_attachment_metadata( $post_id, $upload['file'] );
        wp_update_attachment_metadata( $post_id, $att_metadata );
        add_post_meta($image->nid, '_thumbnail_id', $post_id);

        if (is_array($att_metadata['sizes'])) {
        
            foreach ($att_metadata['sizes'] as $size => $sizefile) {
            
                if (is_array($sizefile) & isset($sizefile['file']))
                    $filesCreated[] = WP_CONTENT_DIR . '/uploads/' . date('Y/m/', $image->created) . $sizefile['file'];
            
            }
        
        }
        
        $success ++;
        echo '.';
    
    } else {
    
        $this->log('Erro ao copiar imagem: ' . $sourcefile . ' (fid ' . $image->fid . ')');
        $error++;
        continue;
    
    } 
    
}

if ($success > 0) {
    echo "\n";

    $filesCreatedJson = fopen($logFilesCreatedFilename, "w");
    fwrite($filesCreatedJson, json_encode($filesCreated));
    fclose($filesCreatedJson);
    $this->log('Registro das imagens criadas salvo em ' . $logFilesCreatedFilename);

}

$this->log('Total de erros: ' . $error);
$this->log('Total de imagens copiadas: ' . $success);

