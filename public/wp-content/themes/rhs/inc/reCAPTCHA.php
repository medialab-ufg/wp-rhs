<?php
/*
*
* Função reCAPTCHA
*
*/
function API_reCAPTCHA(){
  wp_enqueue_script( 'reCAPTCHA_API', 'https://www.google.com/recaptcha/api.js', true );
}
add_action('wp_enqueue_scripts', 'API_reCAPTCHA');

function no_captcha_recaptcha_menu() {
  add_menu_page("reCapatcha Options", "reCaptcha Options", "manage_options", "recaptcha-options", "recaptcha_options_page", "", 100);
}
function recaptcha_options_page() { ?>
  <div class="wrap">
  <h1>reCaptcha Options</h1>
  <form method="post" action="options.php">
      <?php 
          settings_fields("header_section");
          do_settings_sections("recaptcha-options");
          submit_button(); 
      ?>  
  </form>
  </div>
<?php }
add_action("admin_menu", "no_captcha_recaptcha_menu");
function display_recaptcha_options() {
  add_settings_section("header_section", "Keys", "display_recaptcha_content", "recaptcha-options");
  add_settings_field("captcha_site_key", __("Site Key"), "display_captcha_site_key_element", "recaptcha-options", "header_section");
  add_settings_field("captcha_secret_key", __("Secret Key"), "display_captcha_secret_key_element", "recaptcha-options", "header_section");
  register_setting("header_section", "captcha_site_key");
  register_setting("header_section", "captcha_secret_key");
}
function display_recaptcha_content() {
  echo __('<p>You need to <a href="https://www.google.com/recaptcha/admin" rel="external">register you domain</a> and get keys to make this plugin work.</p>');
  echo __("Enter the key details below");
}
function display_captcha_site_key_element() { ?>
  <input type="text" name="captcha_site_key" id="captcha_site_key" value="<?php echo get_option('captcha_site_key'); ?>" />
<?php }
function display_captcha_secret_key_element() { ?>
  <input type="text" name="captcha_secret_key" id="captcha_secret_key" value="<?php echo get_option('captcha_secret_key'); ?>" />
<?php }
add_action("admin_init", "display_recaptcha_options");


/*reCAPTCHA Recuperar Pass*/

function recuperar_recaptcha_script() {
  wp_register_script("recaptcha_login", "https://www.google.com/recaptcha/api.js");
  wp_enqueue_script("recaptcha_login");
}
add_action("page_enqueue_scripts", "recuperar_recaptcha_script");
function display_recuperar_captcha() { ?>
  <div class="g-recaptcha" data-sitekey="<?php echo get_option('captcha_site_key'); ?>"></div>
<?php }
add_action( "page_form", "display_recuperar_captcha" );
function verify_recuperar_captcha($user, $password) {
  if (isset($_POST['g-recaptcha-response'])) {
  $recaptcha_secret = get_option('captcha_secret_key');
  $response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=". $recaptcha_secret ."&response=". $_POST['g-recaptcha-response']);
  $response = json_decode($response["body"], true);
  if (true == $response["success"]) {
  return $user;
  } else {
  return new WP_Error("Captcha Invalid", __("<strong>ERROR</strong>: You are a bot"));
  } 
  } else {
  return new WP_Error("Captcha Invalid", __("<strong>ERROR</strong>: You are a bot. If not then enable JavaScript"));
  }  
}
add_filter("wp_lostpassword", "verify_recuperar_captcha", 10, 2);