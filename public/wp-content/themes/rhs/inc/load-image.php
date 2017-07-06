<?php

if ( defined( 'WP_DEBUG' ) && WP_DEBUG && isset( $_SERVER['REQUEST_URI'] ) ) {

    $matches = array();

    if(strpos( $_SERVER['REQUEST_URI'], '/wp-content/uploads/' ) !== false){
        preg_match( '|wp-content/uploads/[^?]*|', $_SERVER['REQUEST_URI'], $matches );
    }

    if(strpos( $_SERVER['REQUEST_URI'], '/sites/default/files/' ) !== false){
        preg_match( '|sites/default/files/[^?]*|', $_SERVER['REQUEST_URI'], $matches );
    }

    if($matches){

        $filename = $matches[0];

        $path = dirname( $filename );

        if ( ! is_dir( ABSPATH . '/' . $path ) ) {
            mkdir( ABSPATH . '/' . $path, 0777, true );
        }

        $file_contents = file_get_contents( 'http://redehumanizasus.net/' . $filename );

        if ( $file_contents ) {
            file_put_contents( ABSPATH . '/' . $filename, $file_contents );
        }

        echo $file_contents;
        exit();
    }

}