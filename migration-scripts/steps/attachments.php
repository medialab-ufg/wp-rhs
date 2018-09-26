<?php

require_once(ABSPATH . 'wp-admin/includes/image.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');


function handle_upload_from_path( $path, $parent_post_id, $add_to_media = true ) {
    if ( !file_exists($path) ) {
        return array( 'error' => 'File does not exist.' );
    }
    $filename = basename($path);
    $filename_no_ext = pathinfo($path, PATHINFO_FILENAME);
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    // Simulate uploading a file through $_FILES. We need a temporary file for this.
    $tmp = tmpfile();
    $tmp_path = stream_get_meta_data($tmp)['uri'];
    fwrite($tmp, file_get_contents( $path ));
    fseek($tmp, 0); // If we don't do this, WordPress thinks the file is empty
    $fake_FILE = array(
        'name'      => $filename,
        'type'      => 'image/' . $extension,
        'tmp_name'  => $tmp_path,
        'error'     => UPLOAD_ERR_OK,
        'size'      => filesize($path),
    );
    // Trick is_uploaded_file() by adding it to the superglobal
    $_FILES[basename($tmp_path)] = $fake_FILE;
    $result = wp_handle_upload( $fake_FILE, array( 'test_form' => false, 'action' => 'local' ) );
    fclose($tmp); // Close tmp file
    @unlink($tmp_path); // Delete the tmp file. Closing it should also delete it, so hide any warnings with @
    unset( $_FILES[basename($tmp_path)] ); // Clean up our $_FILES mess.
    $result['attachment_id'] = 0;

    if ( empty($result['error']) && $add_to_media ) {
        $args = array(
            'guid' => $result['url'],
            'post_title' => $filename_no_ext,
            'post_content' => '',
            'post_status' => 'publish',
            'post_mime_type' => $result['type'],
        );
        $result['attachment_id'] = wp_insert_attachment( $args, $result['file'], $parent_post_id );
        if ( is_wp_error( $result['attachment_id'] ) ) {
            $result['attachment_id'] = 0;
        }else{
            $attach_data = wp_generate_attachment_metadata( $result['attachment_id'], $result['file'] );
            wp_update_attachment_metadata( $result['attachment_id'], $attach_data );
        }
    }
    return $result;
}

$this->log('Importando anexos dos posts...');
$results = $this->get_results('attachments');

$attachments_path_dir = "/home/andre/Documents/drupal_files/"; //Diretorio de anexos deve ser configurado

$wp_upload_dir = wp_upload_dir();

foreach ($results as $attachment)
{
    if(strpos($attachment['filepath'], '/files') !== false)
    {
        $file_name = explode("files/", $attachment['filepath']);
        $file_name = end($file_name);

        $file_path = $attachments_path_dir . $file_name;

        if(file_exists($file_path))
        {

            $f = explode(".", $file_path);
            $filetype = end($f);
            $filename = $attachment['filename'];

            $up_file = handle_upload_from_path($file_path, $attachment['id'], true);

            $info = $up_file['attachment_id'] . " ==> " . $up_file['file'] . " ============= " . $up_file['url'];
            $logFile = file_put_contents('logs.txt', $info.PHP_EOL , FILE_APPEND | LOCK_EX);
        }
    }
}

$this->log('Anexos importados: ' . count($results));