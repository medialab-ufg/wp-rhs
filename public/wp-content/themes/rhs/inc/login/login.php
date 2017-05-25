<?php

/*
*
* Esta Class implementa as funções necessárias para o Login e uso das reCaptcha.
* Pega a key setada no Painel do Admin (Wordpress).
* Com a Função display_recuperar_captcha() mostra na tela o reCaptcha.
*
*/
class RHSLogin extends RHSMenssage {

    private static $instance;

    function __construct() {

        if ( empty ( self::$instance ) ) {
            add_filter( "login_url", array( &$this, "login_url" ), 10, 3 );
            add_filter( "login_redirect", array( &$this, "login_redirect" ), 10, 3 );
            add_filter( 'wp_login_errors', array( &$this, 'check_errors' ), 10, 2 );
        }

        self::$instance = true;
    }

    static function login_url( $login_url, $redirect, $force_reauth ) {
        $login_page = self::url();
        $login_url  = add_query_arg( 'redirect_to', $redirect, $login_page );

        return $login_url;
    }

    static function url(){
        return esc_url(home_url(RHSRewriteRules::LOGIN_URL));
    }

    function login_redirect( $redirect_to, $requested_redirect_to, $user ) {
        if ( empty( $redirect_to ) ) {
            //TODO verificar role do usuário para enviar para a página apropriada
            $redirect_to = admin_url();
        }

        return $redirect_to;
    }

    function login_errors( $errors, $redirect_to ) {

        $_SESSION['login_errors'] = '';
    }
    function check_errors( $errors, $redirect_to ) {

        if ( $errors instanceof WP_Error && ! empty( $errors->errors ) ) {

            if ( $errors->errors ) {

                $this->clear_messages();

                foreach ($errors->get_error_messages() as $error){
                    $this->set_messages($error, false, 'error');
                }
            }

            wp_redirect( self::url() );
            exit;
        }

        return $errors;
    }
}

global $RHSLogin;
$RHSLogin = new RHSLogin();
