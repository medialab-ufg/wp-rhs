<?php

/*
*
* Esta Class implementa as funções necessárias para o uso das reCaptcha.
* Pega a key setada no Painel do Admin (Wordpress).
* Com a Função display_recuperar_captcha() mostra na tela o reCaptcha.
*
*/
class RHSCaptcha {

    const SITE_KEY = 'captcha_site_key';
    const SECRET_KEY = 'captcha_secret_key';

    private static $instance;

    function __construct() {

        if ( empty ( self::$instance ) ) {
            add_action( 'wp_enqueue_scripts', array( &$this, 'API_reCAPTCHA' ) );
            add_action( "admin_menu", array( &$this, "no_captcha_recaptcha_menu" ) );
            add_action( "admin_init", array( &$this, "display_recaptcha_options" ) );
            #add_action( "recuperar-senha_form", array( &$this, "display_recuperar_captcha" ) );
            #add_filter( "lostpassword_url", array( &$this, "verify_recuperar_captcha" ), 10, 2 );

        }

        self::$instance = true;

    }

    function API_reCAPTCHA() {
        wp_enqueue_script( 'reCAPTCHA_API', 'https://www.google.com/recaptcha/api.js', true );
    }

    function no_captcha_recaptcha_menu() {
            add_submenu_page( 'rhs_options', 'Opções reCaptcha', 'Opções reCaptcha', 'manage_options', 'recaptcha-options', array( &$this, 'recaptcha_options_page' ) );
    }

    function recaptcha_options_page() { ?>
        <div class="wrap">
            <h1>reCaptcha Options</h1>
            <form autocomplete="off" method="post" action="options.php">
                <?php
                settings_fields( "captcha_header_section" );
                do_settings_sections( "recaptcha-options" );
                submit_button();
                ?>
            </form>
        </div>
    <?php }

    function display_recaptcha_options() {
        add_settings_section( "captcha_header_section", "Keys", array( &$this, "display_recaptcha_content" ),
            "recaptcha-options" );
        add_settings_field( 'captcha_site_key', __( "Site Key" ), array( &$this, "display_captcha_site_key_element" ),
            "recaptcha-options", "captcha_header_section" );
        add_settings_field( "captcha_secret_key", __( "Secret Key" ),
            array( &$this, "display_captcha_secret_key_element" ), "recaptcha-options", "captcha_header_section" );
        register_setting( "captcha_header_section", self::SITE_KEY );
        register_setting( "captcha_header_section", self::SECRET_KEY );
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

    function display_recuperar_captcha() { 
        ?>
        <div class="g-recaptcha" data-sitekey="<?php echo get_option( self::SITE_KEY ); ?>"  data-callback="recaptchaCallback"></div>
        <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" id="hiddenRecaptcha">
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

}

global $RHSCaptcha;
$RHSCaptcha = new RHSCaptcha();