<?php

class RHSPerfil extends RHSMessage {

    private static $instance;
    private $userID;

    function __construct($userID) {
        $this->userID = $userID;
        add_action('wp_enqueue_scripts', array(&$this, 'addJS'), 2);
        add_action('wp_ajax_generate_backup_file', array($this, 'generate_backup_file'));
        add_action('wp_ajax_nopriv_generate_backup_file', array($this,'generate_backup_file'));
        add_action('wp_ajax_delete_my_account', array($this, 'delete_my_account'));
        add_action('wp_ajax_nopriv_delete_my_account', array($this,'delete_my_account'));
    }
    

    function addJS() {
        wp_enqueue_script('rhs_profile', get_template_directory_uri() . '/inc/perfil/perfil.js', array('jquery'));
        wp_localize_script('rhs_profile', 'user_vars', array('ajaxurl' => admin_url('admin-ajax.php')));
    }

    function getUserId(){
        return $this->userID;
    }

    public function trigger_by_post() {

        if ( ! empty( $_POST['edit_user_wp'] ) && $_POST['edit_user_wp'] == $this->getKey() ) {

            if ( ! $this->validate_by_post() ) {
                return;
            }

            $current_user = new RHSUser(wp_get_current_user());

            if($current_user->is_admin()){
                $user_id = $_POST['user_id'];
            } else {
                $user_id = get_current_user_id();
            }

            if(isset($_POST['promoted_post'])){$promoted_post = $_POST['promoted_post'];}else{$promoted_post = "";}
            if(isset($_POST['comment_post'])){$comment_post = $_POST['comment_post'];}else{$comment_post = "";}
            if(isset($_POST['comment_post_follow'])){$comment_post_follow = $_POST['comment_post_follow'];}else{$comment_post_follow = "";}
            if(isset($_POST['new_post_from_user_follow'])){$new_post_from_user_follow = $_POST['new_post_from_user_follow'];}else{$new_post_from_user_follow = "";}

            $this->update(
                $user_id,
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['pass'],
                $promoted_post,
                $comment_post,
                $comment_post_follow,
                $new_post_from_user_follow,
                $_POST['description'],
                $_POST['formation'],
                $_POST['interest'],
                $_POST['estado'],
                $_POST['municipio'],
                $_POST['links'],
                $_FILES['avatar']);
        }
    }

    function update($user_id, $first_name, $last_name, $pass = '', $promoted_post = '', $comment_post = '', $comment_post_follow = '', $new_post_from_user_follow = '', $description, $formation = '', $interest = '', $state = '', $city = '', $links = '', $avatar_file){

        $data = array('ID' => $user_id);
        $data['first_name'] = $first_name;
        $data['last_name'] = $last_name;

        if($pass){
            wp_set_password( $pass, $user_id );
        }

        wp_update_user($data);
        
        update_user_meta( $user_id, 'description', $description);
        update_user_meta( $user_id, 'rhs_formation', $formation);
        update_user_meta( $user_id, 'rhs_interest', $interest);
        add_user_ufmun_meta( $user_id, $city, $state);
        update_user_meta( $user_id, 'rhs_city', $city);
        update_user_meta( $user_id, RHSUsers::LINKS_USERMETA, $links);

        //Email Notification
        if($promoted_post == ''){
            update_user_meta( $user_id, 'rhs_email_promoted_post', 1);
        }
        elseif($promoted_post == 'true') {
            if(get_user_meta($user_id, 'rhs_email_promoted_post')){
                delete_user_meta( $user_id, 'rhs_email_promoted_post');
            }
        }
        
        if($comment_post == ''){
            update_user_meta( $user_id, 'rhs_email_comment_post', 1);
        }
        elseif($comment_post == 'true') {
            if(get_user_meta($user_id, 'rhs_email_comment_post')){
                delete_user_meta( $user_id, 'rhs_email_comment_post');
            }
        }

        if($comment_post_follow == ''){
            update_user_meta( $user_id, 'rhs_email_comment_post_follow', 1);
        }
        elseif($comment_post_follow == 'true') {
            if(get_user_meta($user_id, 'rhs_email_comment_post_follow')){
                delete_user_meta( $user_id, 'rhs_email_comment_post_follow');
            }
        }

        if($new_post_from_user_follow == '') {
            update_user_meta( $user_id, 'rhs_email_new_post_from_user_follow', 1);
        }
        elseif($new_post_from_user_follow == 'true'){
            if(get_user_meta($user_id, 'rhs_email_new_post_from_user_follow')){
                delete_user_meta( $user_id, 'rhs_email_new_post_from_user_follow');
            }
        }

        if ($avatar_file) {
            $arquivo_tmp = $avatar_file[ 'tmp_name' ];
            $nome = $avatar_file[ 'name' ];

            $extensao = pathinfo ( $nome, PATHINFO_EXTENSION );
            $extensao = strtolower ( $extensao );

            $novoNome = uniqid ( time () ) . '.' . $extensao;
            $caminho = '/uploads/'. date('Y').'/'.date('m').'/';

            if(!file_exists(WP_CONTENT_DIR . $caminho)){
                mkdir( WP_CONTENT_DIR . $caminho, 0777, true);
            }

            if ( @move_uploaded_file ( $arquivo_tmp, WP_CONTENT_DIR . $caminho . $novoNome ) ) {
                update_user_meta( $user_id, 'rhs_avatar', 'wp-content'.$caminho.$novoNome);
            } else {
                $this->set_alert( '<i class="fa fa-exclamation-triangle"></i> Erro ao salvar o arquivo.');

            }
        }

        $this->set_alert( '<i class="fa fa-check"></i> Informações de perfil salvas com sucesso!');

    }

    private function validate_by_post() {

        if(!array_key_exists('first_name', $_POST)){
            $this->set_alert('<i class="fa fa-exclamation-triangle "></i> Preencha o sua antiga senha!');
            return false;
        }

        if(!array_key_exists('last_name', $_POST)){
            $this->set_alert( '<i class="fa fa-exclamation-triangle "></i> Preencha o sua antiga senha!');
            return false;
        }

        if(array_key_exists('pass', $_POST) && $_POST['pass']){

            $RHSUsers = new RHSUsers($this->userID);

            if(empty($_POST['pass_old'] ) || !wp_check_password( $_POST['pass_old'], $RHSUsers->get_user_data('user_pass'), $this->userID) ){
                $this->set_alert('<i class="fa fa-exclamation-triangle "></i> Sua senha antiga está incorreta!');
                return false;
            }
        }

        if(array_key_exists('avatar', $_FILES)){

            if ( isset( $_FILES['avatar'][ 'name' ] ) &&  $_FILES['avatar'][ 'error' ] == 0 ) {

                $avatar_file = $_FILES['avatar'];
                $arquivo_tmp = $avatar_file[ 'tmp_name' ];
                $nome = $avatar_file[ 'name' ];

                $extensao = pathinfo ( $nome, PATHINFO_EXTENSION );
                $extensao = strtolower ( $extensao );

                if ( !strstr ( '.jpg;.jpeg;.gif;.png', $extensao ) ) {
                    $this->set_alert( '<i class="fa fa-exclamation-triangle"></i> Você poderá enviar apenas arquivos "*.jpg;*.jpeg;*.gif;*.png');
                    return false;
                }

                if($avatar_file[ 'size' ] > 5242880){
                    $this->set_alert( '<i class="fa fa-exclamation-triangle"></i> Tamanho não pode ultrapasar de 5mb');
                    return false;
                }
            } else {
                $_FILES['avatar'] = array();
            }

        } else {
            $_FILES['avatar'] = array();
        }

        if ( ! array_key_exists( 'description', $_POST ) ) {
            $_POST['description'] = '';

            return false;
        }

        if ( ! array_key_exists( 'formation', $_POST ) ) {
            $_POST['formation'] = '';

            return false;
        }

        if ( ! array_key_exists( 'interest', $_POST ) ) {
            $_POST['interest'] = '';

            return false;
        }

        if ( ! array_key_exists( 'estado', $_POST ) ) {
            $_POST['estado'] = '';

            return false;
        }

        if ( ! array_key_exists( 'municipio', $_POST ) ) {
            $_POST['municipio'] = '';

            return false;
        }

        if ( ! array_key_exists( 'municipio', $_POST ) ) {
            $_POST['municipio'] = '';

            return false;
        }


        if ( ! array_key_exists( 'links', $_POST ) ) {
            $_POST['links'] = '';

            return false;
        }


        return true;

    }

    function show_box_to_delete_profile($user_id){
        echo "<a class='btn btn-danger modal-delete-account'>Excluir Perfil</a>";
    }

    public static function generate_backup_file() {
        global $wp_query;
        global $wpdb;
        global $RHSVote;
        global $RHSNetwork;
        global $RHSSearch;
        
        header('Content-Type: application/vnd.ms-excel');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $args = array(
            'author'         =>  get_current_user_id(),
            'orderby'        =>  'post_date',
            'order'          =>  'ASC',
            'posts_per_page' => -1
        );
        
        $content_file = get_posts($args);
    
        foreach($content_file as $post) {
            $get_title = html_entity_decode(get_the_title($post->ID), ENT_QUOTES, "UTF-8");
            
            $raw_content = get_post_field('post_content', $post->ID);
            $post_content = iconv( "utf-8", "utf-8", $raw_content );
            $post_content = strip_html_tags( $post_content );
            $post_content = html_entity_decode($post_content, ENT_QUOTES, "UTF-8");

            $get_date = get_the_date('d/m/Y H:i:s', $post->ID);
            $get_author = get_the_author_meta('user_firstname', $post->post_author) . " " . get_the_author_meta('user_lastname', $post->post_author);
            $get_link = $post->guid;
            $get_views = $RHSNetwork->get_post_total_views($post->ID);
            $get_shares = $RHSNetwork->get_post_total_shares($post->ID);
            $get_comments = wp_count_comments($post->ID);
            $get_votes = $RHSVote->get_total_votes($post->ID);

            $views = return_value_or_zero($get_views);
            $shares = return_value_or_zero($get_shares);
            $votes = return_value_or_zero($get_votes);
            $comments = return_value_or_zero($get_comments);
            
            $post_ufmun = get_post_ufmun($post->ID);
            $uf = $post_ufmun['uf']['sigla'];
            $mun = $post_ufmun['mun']['nome'];

            $row_data[] = [
                'titulo'=> $get_title,
                'conteudo' => $post_content,
                'data'=> $get_date,
                'autor' => $get_author,
                'link' => $get_link,
                'visualizacoes' => $views,
                'compartilhamentos' => $shares,
                'votos' => $votes,
                'comentarios' => $comments,
                'estado' => return_value_or_dash($uf),
                'cidade' => return_value_or_dash($mun)
            ];
        }

        $head_table .= "<table>
            <thead align='left' style='display: table-header-group'>
            <tr>
                <th>Título</th>
                <th>Conteúdo</th>
                <th>Data</th>
                <th>Autor</th>
                <th>Link</th>
                <th>Visualizações</th>
                <th>Compartilhamentos</th>
                <th>Votos</th>
                <th>Comentários</th>
                <th>Estado</th>
                <th>Cidade</th>
            </tr>
            </thead>
            <tbody>";

        foreach ($row_data as $row) {
            $row_table .=  "<tr>
                                <td>" . $row['titulo'] . "</td>
                                <td>" . $row['conteudo'] . "</td>
                                <td>" . $row['data'] . "</td>
                                <td>" . $row['autor'] . "</td>
                                <td>" . $row['link'] . "</td>
                                <td>" . $row['visualizacoes'] . "</td>
                                <td>" . $row['compartilhamentos'] . "</td>
                                <td>" . $row['votos'] . "</td>
                                <td>" . $row['comentarios'] . "</td>
                                <td>" . $row['estado'] . "</td>
                                <td>" . $row['cidade'] . "</td>
                            </tr>";
        }
        
        $footer_table = "</tbody></table>";

        $file = $head_table . $row_table . $footer_table;

        mb_convert_encoding($file, 'UTF-16LE', 'UTF-8');
        echo $file;
    }

    public static function delete_my_account() {
        $user_id = get_current_user_id();
        $meta = get_user_meta($user_id);
        $send_to_legacy_user = $_POST['send_to_legacy_user'];
        $legacy_user = get_user_by('email', 'legado@redehumanizasus.net');
        if($legacy_user == null) {
            $legacy_user = get_user_by('id', 1);
        }
        
        // Remove meta de usuários
        foreach ($meta as $key => $val) {
            delete_user_meta($user_id, $key);
        }

        // Desloga usuário
        wp_logout();

        // Remove usuário
        if($send_to_legacy_user == 'true') {
            $deleted = wp_delete_user($user_id);
        } else {
            $deleted = wp_delete_user($user_id, $legacy_user_id);
        }

    }
    
}

global $RHSPerfil;
$RHSPerfil = new RHSPerfil(!empty( $_GET['user_id'] ) ? $_GET['user_id'] : get_current_user_id());
