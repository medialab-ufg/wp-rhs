<?php

class RHSLostPassword extends RHSMenssage {

    private static $instance;

    function __construct() {

        if ( empty ( self::$instance ) ) {
            add_filter( "lostpassword_url", array( &$this, "url" ), 10, 2 );


            if(!empty($_POST['lostpassword_user_wp']) && $_POST['lostpassword_user_wp'] == $this->getKey()){
                $this->run();
            }
        }

        self::$instance = true;
    }

    function run() {

        $result = array();

        if ( ! empty( $_POST['user_login'] ) ) {

            $return = $this->send_lostpassword();

            if ( $return instanceof WP_Error ) {

                $this->clear_messages();
                $result = array( 'error' => $return->get_error_messages() );

                foreach ($return->get_error_messages() as $error){
                    $this->set_messages($error, false, 'error');
                }

            } else {
                $this->clear_messages();
                $this->set_messages('O email foi enviado, cheque na sua caixa de entrada.', false, 'success');
            }
        }

    }

    function send_lostpassword() {
        $errors = new WP_Error();

        if ( empty( $_POST['user_login'] ) ) {

            $errors->add( 'empty_username', __( '<strong>ERROR</strong>: Enter a username or email address.' ) );
            return $errors;

        } elseif ( strpos( $_POST['user_login'], '@' ) ) {

            $user_data = get_user_by( 'email', trim( wp_unslash( $_POST['user_login'] ) ) );

            if ( empty( $user_data ) ) {
                $errors->add( 'invalid_email', __( '<strong>ERROR</strong>: There is no user registered with that email address.' ) );
                return $errors;
            }
        } else {
            $login     = trim( $_POST['user_login'] );
            $user_data = get_user_by( 'login', $login );
        }

        do_action( 'lostpassword_post', $errors );

        if ( $errors->get_error_code() ) {
            return $errors;
        }

        if ( ! $user_data ) {
            $errors->add( 'invalidcombo', __( '<strong>ERROR</strong>: Invalid username or email.' ) );

            return $errors;
        }

        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;
        $key        = get_password_reset_key( $user_data );

        if ( is_wp_error( $key ) ) {
            return $key;
        }

        $message = __( 'Someone has requested a password reset for the following account:' ) . "\r\n\r\n";
        $message .= network_home_url( '/' ) . "\r\n\r\n";
        $message .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n\r\n";
        $message .= __( 'If this was a mistake, just ignore this email and nothing will happen.' ) . "\r\n\r\n";
        $message .= __( 'To reset your password, visit the following address:' ) . "\r\n\r\n";
        $message .= '<' . network_site_url( "../retrievepassword/?key=$key&login=" . rawurlencode( $user_login ),
                'login' ) . ">\r\n";

        if ( is_multisite() ) {
            $blogname = get_network()->site_name;
        } else {
            $blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
        }

        $title = sprintf( __( '[%s] Password Reset' ), $blogname );

        $title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );

        $message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );

        if ( $message && ! wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {
            $errors->add( 'hostoffiline',
                __( 'The email could not be sent.' ) . "<br />\n" . __( 'Possible reason: your host may have disabled the mail() function.' ) );

            return $errors;
        }

        return true;
    }

    function retrievepassword() {

        $errors = new WP_Error();

        list( $rp_path ) = explode( '?', wp_unslash( $_SERVER['REQUEST_URI'] ) );
        $rp_cookie = 'wp-resetpass-' . COOKIEHASH;
        if ( isset( $_GET['key'] ) ) {
            $value = sprintf( '%s:%s', wp_unslash( $_GET['login'] ), wp_unslash( $_GET['key'] ) );

            setcookie( $rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true );

            wp_safe_redirect( remove_query_arg( array( 'key', 'login' ) ) );
            exit;
        }

        if ( isset( $_COOKIE[ $rp_cookie ] ) && 0 < strpos( $_COOKIE[ $rp_cookie ], ':' ) ) {
            list( $rp_login, $rp_key ) = explode( ':', wp_unslash( $_COOKIE[ $rp_cookie ] ), 2 );
            $_SESSION['rp_key'] = $rp_key;
            $user = check_password_reset_key( $rp_key, $rp_login );

            if ( isset( $_POST['pass1'] ) && ! hash_equals( $rp_key, $_POST['rp_key'] ) ) {
                $user = false;
            }
        } else {
            $user = false;
        }

        if(!empty($_POST['rp_key'])){
            if ( isset($_POST['pass1']) && $_POST['pass1'] != $_POST['pass2'] ){
                $errors->add( 'password_reset_mismatch', __( 'The passwords do not match.' ) );

                foreach ($errors->get_error_messages() as $error){
                    $this->set_messages($error, false, 'error');
                }

                return;
            }


            do_action( 'validate_password_reset', $errors, $user );

            if ( ( ! $errors->get_error_code() ) && isset( $_POST['pass1'] ) && !empty( $_POST['pass1'] ) ) {
                reset_password($user, $_POST['pass1']);
                setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );

                $login = new RHSLogin();
                $login->set_messages(__( 'Your password has been reset.' ), false, 'success');

                unset($_SESSION['rp_key']);
                wp_redirect(wp_login_url());
                exit;

            }
        }

        $this->clear_messages();

        if ( ! $user || is_wp_error( $user ) ) {

            setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
            if ( $user && $user->get_error_code() === 'expired_key' ) {
                $this->set_messages('O link para redefinir a sua senha expirou. Solicite um novo link abaixo.', false, 'error');
                wp_redirect(wp_lostpassword_url());
            } else {
                $this->set_messages('O link para redefinir a sua senha parece ser inválido. Solicite um novo link abaixo.', false, 'error');
                wp_redirect(wp_lostpassword_url());
            }
            exit;
        }

        wp_enqueue_script( 'utils' );
        wp_enqueue_script( 'user-profile' );


    }

    function url( $login_url, $redirect, $force_reauth = '' ) {
        $lost_page = home_url( RHSRewriteRules::LOST_PASSWORD_URL );
        $login_url = add_query_arg( 'redirect_to', $redirect, $lost_page );

        return $login_url;
    }

}

global $RHSLostPassword;
$RHSLostPassword = new RHSLostPassword();
