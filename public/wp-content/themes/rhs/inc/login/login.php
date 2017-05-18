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
        $login_page = home_url( RHSRewriteRules::LOGIN_URL );
        $login_url  = add_query_arg( 'redirect_to', $redirect, $login_page );

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
            array( &$this, "recaptcha_options_page" ), "dashicons-pressthis", 100 );
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
    }
    function check_errors( $errors, $redirect_to ) {


        if ( $errors instanceof WP_Error && ! empty( $errors->errors ) ) {

            if ( $errors->errors ) {

                $this->clear_messages();

                foreach ($errors->get_error_messages() as $error){
                    $this->set_messages($error, false, 'error');
                }
            }

            wp_redirect( esc_url( home_url( '/login' ) ) );
            exit;
        }

        return $errors;
    }
}

global $RHSLogin;
$RHSLogin = new RHSLogin();
