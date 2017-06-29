<?php

class RHSEmail {

    private $messages;


    function __construct() {

        add_action('admin_menu', array( &$this, 'gerate_admin_menu' ) );
        add_filter('retrieve_password_message',  array( &$this, 'filter_reset_password_request_email_body'), 10, 3 );
        add_action('rhs_post_promoted', array( &$this,'post_promoted'), 10, 1);

        $this->messages = array(
            /*'new_user_message' => array(
                'name'=> 'Novo Usuário',
                'var' => array(
                    'site_nome',
                    'login',
                    'email',
                    'nome'
                ),
                'default-subject' => '',
                'default-email' => ''
            ),*/
            'retrieve_password_message' => array(
                'name'=> 'Recuperar Senha',
                'var' => array(
                    'site_nome',
                    'login',
                    'email',
                    'nome',
                    'link'
                ),
                'default-subject' => '',
                'subject' => false,
                'default-email' => '
                    <p>Você solicitou a recuperação de senha do %login%.</p>
                    <p>Acesse o link: %link%</p>'
            ),
            'new_ticket_message' => array(
                'name'=> 'Novo Ticket',
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
                'name'=> 'Post Promovido',
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

    function filter_reset_password_request_email_body( $message, $key, $user_id ) {

        $data = get_userdata( $userid );

        $args = array(
            'site_nome' => get_bloginfo('name'),
            'login' => $data->user_login,
            'email' => $data->user_email,
            'nome' => $data->display_name,
            'link' => network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $$data->user_login ), 'login' )
        );

        $this->get_message('retrieve_password_message', $args);

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


        ?>

        <div class="wrap">
            <h2><?php echo __( 'Mensagens de Emails' ); ?></h2>
            <form name="form1" method="post" action="">
                <table class="form-table">
                    <tbody>
                    <?php $i = 0; ?>
                    <?php foreach ($this->messages as $label => $menssage){
                        $var = array_map(function($value) { return '%'.$value.'%'; }, $menssage['var']);
                        ?>
                        <tr>
                            <th scope="row">
                                <label for="input-<?php echo $i; ?>"><?php echo $menssage['name']; ?></label>
                            </th>
                            <td>
                                <?php if(!isset($menssage['subject']) || $menssage['subject'] == true){ ?>
                                <input value="<?php echo $this->get_option($label, 'subject'); ?>" name="<?php echo 'rhs-subject-'.$label ?>" type="text" placeholder="Assunto" class="regular-text" />
                                <?php } ?>
                                <?php

                                $settings = array('media_buttons' => false, 'textarea_rows' => 2);
                                wp_editor( $this->get_option($label, 'email'), 'rhs-email-'.$label, $settings );

                                ?>
                                <?php if(!empty($var)){ ?>
                                    <p><i><?php echo __( 'Variáveis: ' ) . implode(', ', $var); ?></i></p>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php $i++ ?>
                    <?php } ?>
                    </tbody>
                </table>
                <p class="submit">
                    <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ) ?>"/>
                </p>
            </form>
        </div>

        <?php
    }


}

global $RHSEmail;
$RHSEmail = new RHSEmail();
