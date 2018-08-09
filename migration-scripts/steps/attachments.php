<?php
$this->log('Importando anexos dos posts...');
$results = $this->get_results('attachments');

$attachments_path_dir = "";//Diretorio de anexos deve ser configurado

foreach ($results as $attachment)
{
    if(strpos($attachment['filepath'], '/files') !== false)
    {
        $file_path = $attachments_path_dir . end(explode("files/", $attachment['filepath']));
        if(file_exists($file_path))
        {
            $filetype = end(explode(".", $file_path));
            $filename = $attachment['filename'];
            $attach = array(
                'guid'           => $file_path,
                'post_mime_type' => $filetype,
                'post_title'     => $filename,
                'post_content'   => '',
                'post_status'    => 'inherit'
            );

            $attach_id = wp_insert_attachment( $attach, $filename, $attachment['id'] );

            require_once( ABSPATH . 'wp-admin/includes/image.php' );

            $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
            wp_update_attachment_metadata( $attach_id, $attach_data );
        }
    }
}

$this->log('Anexos importados');