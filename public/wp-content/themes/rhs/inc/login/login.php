<?php

/*
*
* Esta Class implementa as funções necessárias para o uso das reCaptcha.
* Pega a key setada no Painel do Admin (Wordpress).
* Com a Função display_recuperar_captcha() mostra na tela o reCaptcha.
*
*/

class RHSLogin {

    const SITE_KEY = 'captcha_site_key';
    const SECRET_KEY = 'captcha_secret_key';
    const LOGIN_URL = 'login';
    const LOST_PASSWORD_URL = 'lostpassword';
    const RETRIEVE_PASSWORD_URL = 'retrievepassword';
    const RESET_PASS = 'resetpass';
    const REGISTER_URL = 'register';
    const RP = 'rp';

    static $instance;

    function __construct() {

        if ( empty ( self::$instance ) ) {
            add_action( 'wp_enqueue_scripts', array( &$this, 'API_reCAPTCHA' ) );
            add_action( "admin_menu", array( &$this, "no_captcha_recaptcha_menu" ) );
            add_action( "admin_init", array( &$this, "display_recaptcha_options" ) );
            add_action( "recuperar-senha_form", array( &$this, "display_recuperar_captcha" ) );
            add_filter( "lostpassword_url", array( &$this, "verify_recuperar_captcha" ), 10, 2 );
            add_filter( "login_url", array( &$this, "login_url" ), 10, 3 );
            add_filter( "lostpassword_url", array( &$this, "lostpassword_url" ), 10, 2 );
            add_filter( "login_redirect", array( &$this, "login_redirect" ), 10, 3 );
            add_filter( "register_url", array( &$this, "register_url" ) );
            add_filter( 'wp_login_errors', array( &$this, 'login_errors' ), 10, 2 );
            add_action( 'init', array( &$this, 'check_session' ), 1 );

            add_action( 'generate_rewrite_rules', array( &$this, 'rewrite_rules' ), 10, 1 );
            add_filter( 'query_vars', array( &$this, 'rewrite_rules_query_vars' ) );
            add_filter( 'template_include', array( &$this, 'rewrite_rule_template_include' ) );

        }
        self::$instance = true;
    }

    function login_url( $login_url, $redirect, $force_reauth ) {
        $login_page = home_url( self::LOGIN_URL );
        $login_url  = add_query_arg( 'redirect_to', $redirect, $login_page );

        return $login_url;
    }

    function register_url( $url ) {
        return home_url( self::REGISTER_URL );
    }

    function lostpassword_url( $login_url, $redirect, $force_reauth = '' ) {
        $lost_page = home_url( self::LOST_PASSWORD_URL );
        $login_url = add_query_arg( 'redirect_to', $redirect, $lost_page );

        return $login_url;
    }

    function login_redirect( $redirect_to, $requested_redirect_to, $user ) {
        if ( empty( $redirect_to ) ) {
            //TODO verificar role do usuário para enviar para a página apropriada
            $redirect_to = admin_url();
        }

        return $redirect_to;
    }

    function rewrite_rules( &$wp_rewrite ) {
        $new_rules         = array(
            self::LOGIN_URL . "/?$"             => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::LOGIN_URL,
            self::REGISTER_URL . "/?$"          => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::REGISTER_URL,
            self::LOST_PASSWORD_URL . "/?$"     => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::LOST_PASSWORD_URL,
            self::RETRIEVE_PASSWORD_URL . "/?$" => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::RETRIEVE_PASSWORD_URL,
            self::RESET_PASS . "/?$"            => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::RESET_PASS,
            self::RP . "/?$"            => "index.php?rhs_custom_login=1&rhs_login_tpl=" . self::RP

        );
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;

    }

    function rewrite_rules_query_vars( $public_query_vars ) {

        $public_query_vars[] = "rhs_custom_login";
        $public_query_vars[] = "rhs_login_tpl";

        return $public_query_vars;

    }

    function rewrite_rule_template_include( $template ) {
        global $wp_query;

        if ( $wp_query->get( 'rhs_login_tpl' ) ) {

            if ( file_exists( STYLESHEETPATH . '/' . $wp_query->get( 'rhs_login_tpl' ) . '.php' ) ) {
                return STYLESHEETPATH . '/' . $wp_query->get( 'rhs_login_tpl' ) . '.php';
            }

        }

        return $template;


    }

    function API_reCAPTCHA() {
        wp_enqueue_script( 'reCAPTCHA_API', 'https://www.google.com/recaptcha/api.js', true );
    }

    function no_captcha_recaptcha_menu() {
        add_menu_page( "Opções reCaptcha", "Opções reCaptcha", "manage_options", "recaptcha-options",
            array( &$this, "recaptcha_options_page" ), "", 100 );
    }

    function recaptcha_options_page() { ?>
        <div class="wrap">
            <h1>reCaptcha Options</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( "header_section" );
                do_settings_sections( "recaptcha-options" );
                submit_button();
                ?>
            </form>
        </div>
    <?php }

    function display_recaptcha_options() {
        add_settings_section( "header_section", "Keys", array( &$this, "display_recaptcha_content" ),
            "recaptcha-options" );
        add_settings_field( 'captcha_site_key', __( "Site Key" ), array( &$this, "display_captcha_site_key_element" ),
            "recaptcha-options", "header_section" );
        add_settings_field( "captcha_secret_key", __( "Secret Key" ),
            array( &$this, "display_captcha_secret_key_element" ), "recaptcha-options", "header_section" );
        register_setting( "header_section", self::SITE_KEY );
        register_setting( "header_section", self::SECRET_KEY );
    }

    function display_recaptcha_content() {
        echo __( '<p>You need to <a href="https://www.google.com/recaptcha/admin" rel="external">register you domain</a> and get keys to make this plugin work.</p>' );
        echo __( "Enter the key details below" );
    }

    function display_captcha_site_key_element() { ?>
        <input type="text" name="captcha_site_key" id="captcha_site_key"
               value="<?php echo get_option( self::SITE_KEY ); ?>"/>
    <?php }

    function display_captcha_secret_key_element() { ?>
        <input type="text" name="captcha_secret_key" id="captcha_secret_key"
               value="<?php echo get_option( self::SECRET_KEY ); ?>"/>
    <?php }

    /*reCAPTCHA Recuperar Pass*/

    function display_recuperar_captcha() { ?>
        <div class="g-recaptcha" data-sitekey="<?php echo get_option( self::SITE_KEY ); ?>"></div>
    <?php }

    function verify_recuperar_captcha( $user, $password ) {
        if ( isset( $_POST['g-recaptcha-response'] ) ) :
            $recaptcha_secret = get_option( self::SECRET_KEY );
            $response         = wp_remote_get( "https://www.google.com/recaptcha/api/siteverify?secret=" . $recaptcha_secret . "&response=" . $_POST['g-recaptcha-response'] );
            $response         = json_decode( $response["body"], true );
            if ( true == $response["success"] ) {
                return $user;
            } else {
                return new WP_Error( "Captcha Invalid", __( "<strong>ERROR</strong>: You are a bot" ) );
            }
        else :
            return new WP_Error( "Captcha Invalid",
                __( "<strong>ERROR</strong>: You are a bot. If not then enable JavaScript" ) );
        endif;
    }

    function login_errors( $errors, $redirect_to ) {

        $_SESSION['login_errors'] = '';

        if ( $errors instanceof WP_Error && ! empty( $errors->errors ) ) {

            if ( $errors->errors ) {
                $_SESSION['login_errors'] = $errors->get_error_messages();
            }

            wp_redirect( esc_url( home_url( '/login' ) ) );
            exit;
        }

        return $errors;
    }


    function check_session() {
        if ( ! session_id() ) {
            session_start();
        }
    }

    function lostpassword() {

        $result = array();

        if ( ! empty( $_POST['user_login'] ) ) {
            $return = $this->send_lostpassword();

            if ( $return instanceof WP_Error ) {
                $result = array( 'error' => $errors->get_error_messages() );
            } else {
                $result = array( 'success' => array( 'O email foi enviado, cheque na sua caixa de entrada.' ) );
            }
        }

        if(!empty($_SESSION['lostpassowrd_errors'])){
            $result = array( 'error' => $_SESSION['lostpassowrd_errors']);
            unset($_SESSION['lostpassowrd_errors']);
        }

        return $result;

    }

    function send_lostpassword() {
        $errors = new WP_Error();

        if ( empty( $_POST['user_login'] ) ) {
            $errors->add( 'empty_username', __( '<strong>ERROR</strong>: Enter a username or email address.' ) );
        } elseif ( strpos( $_POST['user_login'], '@' ) ) {
            $user_data = get_user_by( 'email', trim( wp_unslash( $_POST['user_login'] ) ) );
            if ( empty( $user_data ) ) {
                $errors->add( 'invalid_email',
                    __( '<strong>ERROR</strong>: There is no user registered with that email address.' ) );
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
                return $errors->get_error_messages();
            }


            do_action( 'validate_password_reset', $errors, $user );

            if ( ( ! $errors->get_error_code() ) && isset( $_POST['pass1'] ) && !empty( $_POST['pass1'] ) ) {
                reset_password($user, $_POST['pass1']);
                setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );

                $_SESSION['login_messages'][] = __( 'Your password has been reset.' ) ;
                unset($_SESSION['rp_key']);
                wp_redirect(wp_login_url());
                exit;

            }
        }

        $_SESSION['lostpassowrd_errors'] = array();

        if ( ! $user || is_wp_error( $user ) ) {
            setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
            if ( $user && $user->get_error_code() === 'expired_key' ) {
                $_SESSION['lostpassowrd_errors'][] = 'O link para redefinir a sua senha expirou. Solicite um novo link abaixo.';
                wp_redirect(wp_lostpassword_url());
            } else {
                $_SESSION['lostpassowrd_errors'][] = 'O link para redefinir a sua senha parece ser inválido. Solicite um novo link abaixo.';
                wp_redirect(wp_lostpassword_url());
            }
            exit;
        }

        wp_enqueue_script( 'utils' );
        wp_enqueue_script( 'user-profile' );


    }
}

global $RHSLogin;
$RHSLogin = new RHSLogin();
