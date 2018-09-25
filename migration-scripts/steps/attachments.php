<?php
$this->log('Importando anexos dos posts...');
$results = $this->get_results('attachments');

$attachments_path_dir = ""; //Diretorio de anexos deve ser configurado

$wp_upload_dir = wp_upload_dir();

foreach ($results as $attachment)
{
    if(strpos($attachment['filepath'], '/files') !== false)
    {
        $var = explode("files/", $attachment['filepath']);
        $file_path = $attachments_path_dir . end($var);
        if(file_exists($file_path))
        {
            $f = explode(".", $file_path);
            $filetype = end($f);
            $filename = $attachment['filename'];
            $guid = $wp_upload_dir['url'] . '/' . basename( $filename );
            $attach = array(
                // 'guid'           => $file_path,
                'guid'           => $guid,
                'post_mime_type' => $filetype,
                'post_title'     => $filename,
                'post_content'   => '',
                'post_status'    => 'inherit'
            );

            $attach_id = wp_insert_attachment( $attach, $filename, $attachment['id'] );
            $info = $attach_id . " ==> " . $file_path . " ============= " . $guid;
            print ($info . "\n");
            $logFile = file_put_contents('logs.txt', $info.PHP_EOL , FILE_APPEND | LOCK_EX);

            require_once( ABSPATH . 'wp-admin/includes/image.php' );

            $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
            wp_update_attachment_metadata( $attach_id, $attach_data );
        }
    }
}

$this->log('Anexos importados: ' . count($results));