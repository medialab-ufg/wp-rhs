<?php

class RHSEmail {

    private $messages;
    
    const EMAIL_HEADERS = ['Content-Type: text/html; charset=UTF-8'];


    function __construct() {

        add_action('admin_menu', array( &$this, 'gerate_admin_menu' ) );
        add_filter("retrieve_password_title", array( &$this, 'filter_retrieve_password_request_email_title'));
        add_filter('retrieve_password_message',  array( &$this, 'filter_retrieve_password_request_email_body'), 10, 4 );
        add_action('rhs_post_promoted', array( &$this,'post_promoted'), 10, 1);
        
        add_filter( 'wp_mail_content_type', array( &$this,'filter_content_type') );
        
        add_action('rhs_new_ticket_posted', array( &$this,'new_ticket'), 10, 5);
        
        add_action('rhs_ticket_replied', array( &$this,'replied_ticket'), 10, 3);

        $this->messages = array(
            'new_user_message' => array(
                'name'=> 'Email de Boas Vindas',
                'var' => array(
                    'site_nome',
                    'login',
                    'password',
                    'email',
                    'nome',
                    'site_link',
                    'site_perfil',
                    'site_novo_topico'
                ),
                'default-subject' => '[%site_nome%] Bem-vindo',
                'default-email' => '<h3>Bem-vindo %nome%.</h3>
                    <p>Você pode acessar o site aqui: %site_link%</p>
                    <p>Edite seu perfil aqui: %site_perfil%</p>
                    <p>Postar um novo tópico: %site_novo_topico%</p>
                    <p></p>
                    <p>Atenciosamente,</p>
                    <p>Equipe Rede HumanizaSUS</p>
                    <p>http://redehumanizasus.net</p>'
            ),
            'retrieve_password_message' => array(
                'name'=> 'Email de Recuperação Senha',
                'var' => array(
                    'site_nome',
                    'login',
                    'email',
                    'nome',
                    'link'
                ),
                'default-subject' => '[%site_nome%] Recuperação de Senha',
                'default-email' => '<p>Você solicitou a recuperação de senha do %login%.</p>
                    <p>Acesse o link: %link%</p>
                    <p></p>
                    <p>Atenciosamente,</p>
                    <p>Equipe Rede HumanizaSUS</p>
                    <p>http://redehumanizasus.net</p>'
            ),
            /*
            'alter_password_message' => array(
                'name'=> 'Email de Edição de Senha',
                'var' => array(
                    'site_nome',
                    'login',
                    'email',
                    'nome',
                    'link'
                ),
                'default-subject' => '[%site_nome%] Recuperação de Senha',
                'default-email' => '
                    <p>Sua senha foi editada <strong>%login%</strong>.</p>
                    <p>Acesse o link: %link%</p>
                    <p></p>
                    <p>Atenciosamente,</p>
                    <p>Equipe Rede HumanizaSUS</p>
                    <p>http://redehumanizasus.net</p>'
            ),
            */
            'new_ticket_message' => array(
                'name'=> 'Email de Novo Contato',
                'var' => array(
                    'site_nome',
                    'ticket_id',
                    'mensagem',
                    'login',
                    'email',
                    'nome',
                    'link'
                ),
                'default-subject' => '[%site_nome%] Novo Contato #%ticket_id%',
                'default-email' => '
                    <h4>Um novo ticket foi criado #%ticket_id%</h4>
                    <p>para acompanhar acesse o link: %link%</p>
                    <p></p>
                    <p>Atenciosamente,</p>
                    <p>Equipe Rede HumanizaSUS</p>
                    <p>http://redehumanizasus.net</p>'
            ),
            'new_ticket_replied' => array(
                'name'=> 'Email de Contato Respondido',
                'var' => array(
                    'site_nome',
                    'ticket_id',
                    'mensagem',
                    'login',
                    'email',
                    'nome',
                    'link'
                ),
                'default-subject' => '[%site_nome%] Nova resposta #%ticket_id%',
                'default-email' => '
                    <h4>Uma nova resposta foi feita no contato de número #%ticket_id%</h4>
                    <p>para acompanhar acesse o link: %link%</p>
                    <p></p>
                    <p>Atenciosamente,</p>
                    <p>Equipe Rede HumanizaSUS</p>
                    <p>http://redehumanizasus.net</p>'
            ),
            'post_promoted' => array(
                'name'=> 'Email de Post Promovido',
                'var' => array(
                    'site_nome',
                    'login',
                    'email',
                    'nome',
                    'link',
                    'post_title'
                ),
                'default-subject' => '[%site_nome%] Parabéns seu post foi publicado',
                'default-email' => '<h4>Parabéns %nome%.</h4>
                    <p>Seu post atingiu a quantidade de votos e foi publicado.</p>
                    <p>Você pode acessar aqui:</p>
                    <p>%link%</p>
                    <p></p>
                    <p>Atenciosamente,</p>
                    <p>Equipe Rede HumanizaSUS</p>
                    <p>http://redehumanizasus.net</p>'
            )
        );
    }
    
    function filter_content_type($contetType) {
        return 'text/html';
    }
    
    function filter_retrieve_password_request_email_body($message, $key, $user_login, $user_data) {

        $data = get_user_by('login', $user_login);

        if(!$data){
            return;
        }

        $args = array(
            'site_nome' => get_bloginfo('name'),
            'login' => $data->user_login,
            'email' => $data->user_email,
            'nome' => $data->display_name,
            'link' => network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ))
        );

        $message = $this->get_message('retrieve_password_message', $args);

        return $this->get_message('retrieve_password_message', $args);
    }

    function filter_retrieve_password_request_email_title($title) {

        $args = array(
            'site_nome' => get_bloginfo('name')
        );

        $title = $this->get_subject('retrieve_password_message', $args);

        return $title;
    }
    private function get_option($label, $type){

        $option = get_option( 'rhs-'.$type.'-'.$label );

        if(!$option){
            $option = $this->messages[$label]['default-'.$type];
        }

        return $option;
    }

    function get_subject($messages, $args){

        if(empty($this->messages[$messages])){
            return '';
        }

        $subject = $this->get_option($messages, 'subject');

        $vars = $this->messages[$messages]['var'];

        foreach ($vars as $var){
            if (isset($args[$var]))
                $subject = str_replace('%'.$var.'%', $args[$var], $subject);
        }

        return $subject;
    }

    function get_message($messages, $args){

        if(empty($this->messages[$messages])){
            return '';
        }

        $subject = $this->get_option($messages, 'email');

        $vars = $this->messages[$messages]['var'];

        foreach ($vars as $var){
            if (isset($args[$var]))
                $subject = str_replace('%'.$var.'%', $args[$var], $subject);
        }

        return wpautop($subject);
    }

    function gerate_admin_menu() {
        add_submenu_page( 'rhs_options', 'Mensagens de Emails', 'Mensagens de Emails', 'manage_options', 'rhs/rhs-message-email.php', array( &$this, 'rhs_admin_page_email_queue' ) );
    }

    function post_promoted($post_ID){

        $post = get_post($post_ID);
        get_the_author_meta('user_nicename' , $post->post_author);

        $args = array(
            'site_nome' => get_bloginfo('name'),
            'login' => get_the_author_meta('user_login' , $post->post_author),
            'email' => get_the_author_meta('user_email' , $post->post_author),
            'nome' => get_the_author_meta('display_name' , $post->post_author),
            'link' => get_permalink($post_ID),
            'post_title' => $post->post_title
        );

        $subject = $this->get_subject('post_promoted', $args);
        $message = $this->get_message('post_promoted', $args);

        wp_mail(get_the_author_meta('user_email' , $post->post_author), $subject, $message, self::EMAIL_HEADERS);
    }
    
    function new_ticket($post_ID, $content, $responsavel_padrao, $defaultAuthor, $author) {
        if($responsavel_padrao){
            $user = get_userdata($responsavel_padrao);

            $args = array(
                'site_nome' => get_bloginfo('name'),
                'ticket_id' => $post_ID,
                'mensagem' => $content,
                'login' => $user->user_login,
                'email' => $user->user_email,
                'nome' => $user->display_name,
                'link' => '<a href="'.get_permalink($post_ID).'">'. get_permalink($post_ID) . '</a>'
            );

            $subject = $this->get_subject('new_ticket_message', $args);
            $message = $this->get_message('new_ticket_message', $args);

            wp_mail($user->user_email, $subject, $message, self::EMAIL_HEADERS);
        }
    }

    function replied_ticket($user_from_contact_id, $post_ID, $content) {

        $user_not_logged = get_post_meta($post_ID, '_not_logged_user', true) === '1';

        if ($user_not_logged) {
            $user_login = '';
            $user_email = get_post_meta($post_ID, '_author_email', true);
            $user_name  = get_post_meta($post_ID, '_author_name', true);
        } else {
            $user = get_userdata($user_from_contact_id);
            $user_login = $user->user_login;
            $user_email = $user->user_email;
            $user_name = $user->display_name;
        }

        $args = array(
            'site_nome' => get_bloginfo('name'),
            'ticket_id' => $post_ID,
            'mensagem' => $content,
            'login' => $user_login,
            'email' => $user_email,
            'nome' => $user_name,
            'link' => '<a href="'.get_permalink($post_ID).'">'. get_permalink($post_ID) . '</a>'
        );

        $subject = $this->get_subject('new_ticket_replied', $args);
        $message = $this->get_message('new_ticket_replied', $args);
        
        wp_mail($user_email, $subject, $message, self::EMAIL_HEADERS);
    }

    function rhs_admin_page_email_queue() {

        $this->validade_form();

        ?>
        <div class="wrap">
            <h2><?php echo __( 'Mensagens de Emails' ); ?></h2>
        <div class="inside sbwe-inside">
            <form autocomplete="off" method="POST"><table class="widefat">
                    <tbody>
                    <?php $i = 0; ?>
                    <?php foreach ($this->messages as $label => $menssage){ ?>
                        <?php  $var = array_map(function($value) { return '%'.$value.'%'; }, $menssage['var']); ?>
                        <tr class="alternate">
                            <th style="vertical-align: top;">
                                <label for="input-<?php echo $i; ?>">
                                    <strong><?php echo $menssage['name']; ?></strong>
                                </label>
                            </th>
                            <td style=""></td>
                        </tr>
                        <?php if(!isset($menssage['subject']) || $menssage['subject'] == true){ ?>
                        <tr class="">
                            <th style="vertical-align: top;">
                                Assunto
                            </th>
                            <td style="">
                                <input value="<?php echo $this->get_option($label, 'subject'); ?>" name="<?php echo 'rhs-subject-'.$label ?>" type="text" placeholder="Assunto" class="regular-text" />
                            </td>
                        </tr>
                        <?php } ?>
                        <tr class="">
                            <th style="vertical-align: top;">
                                Mensagem
                            </th>
                            <td style="">
                                <?php

                                $settings = array('media_buttons' => false, 'textarea_rows' => 2);
                                wp_editor( $this->get_option($label, 'email'), 'rhs-email-'.$label, $settings );

                                ?>
                                
                                <br/>
                                <p>Variáveis: 
                                <span style="color: #666666; font-size: 10px;">
                                <?php if(!empty($var)){ ?>
                                    <?php echo implode(', ', $var); ?>
                                <?php } ?>
                                </span>
                                </p>
                            </td>
                        </tr>

                        <?php $i++ ?>
                    <?php } ?>
                    <tr class="">
                        <th style="vertical-align: top;">
                        </th>
                        <td style="text-align: right;">
                            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ) ?>"/>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
        </div>
        </div>
        <?php
    }

    private function validade_form(){
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }

        $i = 0;
        if ( ! empty( $_POST ) ) {
            foreach ( $this->messages as $label => $attr ) {

                if ( empty( $_POST ) ) {
                    continue;
                }

                if(!empty($_POST[ 'rhs-subject-'.$label ])){
                    update_option( 'rhs-subject-'.$label, $_POST[ 'rhs-subject-'.$label ] );
                }

                if(!empty($_POST[ 'rhs-email-'.$label ])){
                    update_option( 'rhs-email-'.$label, $_POST[ 'rhs-email-'.$label ] );
                }

                if ( $i == 0 ) {

                    ?>
                    <div class="updated">
                        <p>
                            <strong><?php _e( 'Mensagens salvas.' ); ?></strong>
                        </p>
                    </div>
                    <?php
                }

                $i++;
            }
        }

    }


}

global $RHSEmail;
$RHSEmail = new RHSEmail();
//if ( !function_exists('wp_new_user_notification') ) {
    function rhs_new_user_notification( $user_id, $plaintext_pass = '' ) {
        $user = new WP_User($user_id);

        $user_login = stripslashes($user->user_login);
        $user_email = stripslashes($user->user_email);

        $args = array(
            'site_nome' => get_bloginfo('name'),
            'login' => $user->user_login,
            'password' => $plaintext_pass,
            'email' => $user->user_email,
            'nome' => $user->display_name,
            'site_link' => home_url(),
            'site_perfil'  => get_author_posts_url($user->ID),
            'site_novo_topico'  => home_url(RHSRewriteRules::POST_URL)
        );

        global $RHSEmail;

        $subject = $RHSEmail->get_subject('new_user_message', $args);
        $message = $RHSEmail->get_message('new_user_message', $args);

        if ( empty($plaintext_pass) )
            return;

        wp_mail($user->user_email, $subject, $message, RHSEmail::EMAIL_HEADERS);

    }
//}
