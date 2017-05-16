<?php

class RHSRegister {


    function __construct() {

        add_action('wp_ajax_check_email_exist', array( &$this, 'check_email_exist' ) );
        add_action( 'init', array( &$this, 'check_session' ), 1 );

        if($_POST['register_user_wp']){
            $this->save_post_when_post();
        }
    }


    function save_post_when_post(){

        if(!$_POST){
            return array();
        }

        $_SESSION['login_errors'] = array();

        if(!array_key_exists('mail', $_POST)){
            $_SESSION['register_messages'][] = array('error' => '<i class="fa fa-exclamation-triangle "></i> Preencha o seu email!');
            return;
        }

        if(!array_key_exists('first_name', $_POST)){
            $_SESSION['register_messages'][] = array('error' => '<i class="fa fa-exclamation-triangle "></i> Preencha o seu primeiro nome!');
            return;
        }

        if(!array_key_exists('last_name', $_POST)){
            $_SESSION['register_messages'][] = array('error' => '<i class="fa fa-exclamation-triangle "></i> Preencha o seu último nome!');
            return;
        }

        if(!array_key_exists('pass', $_POST)){
            $_SESSION['register_messages'][] = array('error' => '<i class="fa fa-exclamation-triangle "></i> Preencha a sua senha!');
            return;
        }

        $userdata = array(
            'user_login'  =>  $_POST['mail'],
            'email' => $_POST['mail'],
            'first_name'  => $_POST['first_name'],
            'last_name'  => $_POST['last_name'],
            'user_url'    =>  '',
            'user_pass'   =>  $_POST['pass'],
            'description' => $_POST['description'] ? $_POST['description'] : ''
        );

        $user_id = wp_insert_user( $userdata ) ;

        $_SESSION['register_messages'][] = array('success' => '<i class="fa fa-check"></i> Cadastro realizado');

        $user_login     = esc_attr($_POST["mail"]);
        $user_password  = esc_attr($_POST["pass"]);

        $creds = array();
        $creds['user_login'] = $user_login;
        $creds['user_password'] = $user_password;
        $creds['remember'] = true;

        $user = wp_signon( $creds, false );

        wp_set_current_user( $user_id, $user_login );
        wp_set_auth_cookie( $user_id, true, false );
        do_action( 'wp_login', $user_login );

        if ( is_wp_error( $user ) ) {
            $_SESSION['register_messages'][] = array('error' =>  $user->get_error_message());
            return;
        }

        return;

    }

    function check_email_exist(){

        $email = $_POST['email'];

        if ( username_exists( $email ) )
            echo json_encode( true );
        else
            echo json_encode( false );

        exit;

    }

    function check_session() {
        if ( ! session_id() ) {
            session_start();
        }
    }

}


global $RHSRegister;
$RHSRegister = new RHSRegister();