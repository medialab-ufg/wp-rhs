<?php

class RHSPerfil extends RHSMenssage {

    private static $instance;
    private $userID;

    function __construct($userID) {
        $this->userID = $userID;
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

            $this->update(
                $user_id,
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['pass'],
                $_POST['description'],
                $_POST['formation'],
                $_POST['interest'],
                $_POST['estado'],
                $_POST['municipio'],
                $_POST['links'],
                $_FILES['avatar']);
        }
    }

    function update($user_id, $first_name, $last_name, $pass = '', $description = '', $formation = '', $interest = '', $state = '', $city = '', $links = '', $avatar_file){

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
        update_user_meta( $user_id, 'rhs_links', RHSUsers::save_links($links));


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
}

global $RHSPerfil;
$RHSPerfil = new RHSPerfil(!empty( $_GET['user_id'] ) ? $_GET['user_id'] : get_current_user_id());
