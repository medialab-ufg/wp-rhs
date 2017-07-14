<?php

class RHSEmail {

    private $messages;


    function __construct() {

        add_action('admin_menu', array( &$this, 'gerate_admin_menu' ) );
        add_filter("retrieve_password_title", array( &$this, 'filter_reset_password_request_email_title'));
        add_filter('retrieve_password_message',  array( &$this, 'filter_reset_password_request_email_body'), 10, 4 );
        add_action('rhs_post_promoted', array( &$this,'post_promoted'), 10, 1);

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
                    <p>Você pode acessar o site aqui: <a href="%site_link%">%site_link%</a></p>
                    <p>Edite seu perfil aqui: <a href="%site_perfil%">%site_perfil%</a></p>
                    <p>Postar um novo tópico: <a href="%site_novo_topico%">%site_novo_topico%</a></p>'
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
                'default-email' => '
                    <p>Você solicitou a recuperação de senha do %login%.</p>
                    <p>Acesse o link: %link%</p>'
            ),
            'new_ticket_message' => array(
                'name'=> 'Email de Novo Ticket',
                'var' => array(
                    'site_nome',
                    'ticket_id',
                    'mensagem',
                    'login',
                    'email',
                    'nome',
                    'link'
                ),
                'default-subject' => '[%site_nome%] Novo Ticket #%ticket_id%',
                'default-email' => '<h3>Parabéns %nome%.</h3>
                    <p>Seu ticket foi criado com sucesso, para acompanhar o ticket acesse o link:</p>
                    <p><a href="\&quot;%link%\&quot;">%link%</a></p>'
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
                'default-email' => '<h3>Parabéns %nome%.</h3>
                    <p>Seu post atingiu a quantidade de votos e foi publicado.</p>
                    <p>Você pode acessar aqui:</p>
                    <p><a href="%link%">%link%</a></p>'
            )
        );
    }

    function filter_reset_password_request_email_body($message, $key, $user_login, $user_data) {

        $data = get_user_by('login', $user_login);

        if($data){
            return;
        }

        $args = array(
            'site_nome' => get_bloginfo('name'),
            'login' => $data->user_login,
            'email' => $data->user_email,
            'nome' => $data->display_name,
            'link' => home_url( "resetar-senha/?key=$key&login=" . rawurlencode( $user_login ))
        );

        return $this->get_message('retrieve_password_message', $args);
    }

    function filter_reset_password_request_email_title() {

        $args = array(
            'site_nome' => get_bloginfo('name')
        );

        $message = $this->get_message('retrieve_password_title', $args);

        return $message;
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
            $subject = str_replace('%'.$var.'%', $args[$var], $subject);
        }

        return $subject;
    }

    function gerate_admin_menu() {
        add_options_page( 'Mensagens de Emails', 'Mensagem de Emails', 'manage_options', 'rhs/rhs-message-email.php', array( &$this, 'rhs_admin_page_voting_queue' ) );
    }

    function post_promoted($post_ID){

        $post = get_post($post_ID);
        get_the_author_meta('user_nicename' , $post->post_author);

        $args = array(
            'site_nome' => get_bloginfo('name'),
            'login' => get_the_author_meta('user_login' , $user->post_author),
            'email' => get_the_author_meta('user_email' , $user->post_author),
            'nome' => get_the_author_meta('display_name' , $user->post_author),
            'link' => get_permalink($post_ID),
            'post_title' => $post->post_title
        );

        $subject = $RHSEmail->get_subject('post_promoted', $args);
        $message = $RHSEmail->get_message('post_promoted', $args);

        wp_mail($user->user_email, $subject, $message);
    }

    function rhs_admin_page_voting_queue() {

        $this->validade_form();

        ?>
        <div class="wrap">
            <h2><?php echo __( 'Mensagens de Emails' ); ?></h2>
        <div class="inside sbwe-inside">
            <form method="POST"><table class="widefat">
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
                                <?php if(!empty($var)){ ?>
                                <div style="font-size: 10px; color: gray;">
                                    <?php echo __( 'Variáveis: ' ) . implode(', ', array($var[0])); ?>
                                </div>
                                <?php } ?>
                            </th>
                            <td style="">
                                <input value="<?php echo $this->get_option($label, 'subject'); ?>" name="<?php echo 'rhs-subject-'.$label ?>" type="text" placeholder="Assunto" class="regular-text" />
                            </td>
                        </tr>
                        <?php } ?>
                        <tr class="">
                            <th style="vertical-align: top;">
                                Mensagem
                                <?php if(!empty($var)){ ?>
                                    <div style="font-size: 10px; color: gray;">
                                        <?php echo __( 'Variáveis: ' ) . implode(', ', $var); ?>
                                    </div>
                                <?php } ?>
                            </th>
                            <td style="">
                                <?php

                                $settings = array('media_buttons' => false, 'textarea_rows' => 2);
                                wp_editor( $this->get_option($label, 'email'), 'rhs-email-'.$label, $settings );

                                ?>
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

if ( !function_exists('wp_new_user_notification') ) {
    function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
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

        $user_login = stripslashes(get_option('rhs-subject-new_user_message'));
        $user_email = stripslashes(get_option('rhs-message-new_user_message'));

        if ( empty($plaintext_pass) )
            return;

        wp_mail($user_email, $subject, $message);

    }
}
