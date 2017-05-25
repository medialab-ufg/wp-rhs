<?php

class RHSRegister extends RHSMenssage {

    private static $instance;

    function __construct() {

        if(empty(self::$instance)){
            add_action('wp_ajax_check_email_exist', array( &$this, 'check_email_exist' ) );
            add_filter( 'register_url', array( &$this, 'register_url' ) );

            if(!empty($_POST['register_user_wp']) && $_POST['register_user_wp'] == $this->getKey()){
                $this->save_by_post();
            }
        }

        self::$instance = true;
    }

    static function register_url( $url ) {
        return home_url( RHSRewriteRules::REGISTER_URL );
    }

    function save_by_post(){

        if(!$_POST){
            return array();
        }

        $this->clear_messages();

        if(!array_key_exists('mail', $_POST)){
           $this->set_messages(array('error' => '<i class="fa fa-exclamation-triangle "></i> Preencha o seu email!'));
            return;
        }

        if(!array_key_exists('first_name', $_POST)){
            $this->set_messages(array('error' => '<i class="fa fa-exclamation-triangle "></i> Preencha o seu primeiro nome!'));
            return;
        }

        if(!array_key_exists('last_name', $_POST)){
            $this->set_messages(array('error' => '<i class="fa fa-exclamation-triangle "></i> Preencha o seu último nome!'));
            return;
        }

        if(!array_key_exists('pass', $_POST)){
            $this->set_messages(array('error' => '<i class="fa fa-exclamation-triangle "></i> Preencha a sua senha!'));
            return;
        }

        $userdata = array(
            'user_login'  =>  $_POST['mail'],
            'user_email' => $_POST['mail'],
            'first_name'  => $_POST['first_name'],
            'last_name'  => $_POST['last_name'],
            'user_url'    =>  '',
            'user_pass'   =>  $_POST['pass'],
            'description' => $_POST['description'] ? $_POST['description'] : '',
            'role' => 'contributor'
        );

        $user_id = wp_insert_user( $userdata ) ;

        add_user_meta( $user_id, 'rhs_state', $_POST['estado']);

        add_user_meta( $user_id, 'rhs_city', $_POST['municipio']);

        $perfil = new RHSPerfil($user_id);
        $perfil->clear_messages();
        $perfil->set_messages('<i class="fa fa-check"></i> Cadastro realizado', false, 'success' );

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

            $login = new RHSLogin();
            $login->clear_messages();

            foreach ($user->get_error_message() as $error){
                $login->set_messages($error, false, 'success');
            }
            wp_redirect( esc_url( home_url( '/login' ) ) );
            return;
        }

        wp_redirect( esc_url( home_url( '/perfil' ) ) );
        exit;

    }

    function check_email_exist(){

        $email = $_POST['email'];

        if ( username_exists( $email ) )
            echo json_encode( true );
        else
            echo json_encode( false );

        exit;

    }

}


global $RHSRegister;
$RHSRegister = new RHSRegister();
