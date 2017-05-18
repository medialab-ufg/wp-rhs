<?php

class RHSPerfil extends RHSMenssage {

    private static $instance;
    private $userID;

    function __construct($userID) {

        $this->userID = $userID;

        if ( empty ( self::$instance ) ) {

            if(!empty($_POST['edit_user_wp']) && $_POST['edit_user_wp'] == $this->getKey()){
                $this->edit_by_post();
            }
        }

        self::$instance = true;
    }

    function edit_by_post(){

        if(!$_POST){
            return array();
        }

        $this->clear_messages();

        if(!array_key_exists('first_name', $_POST)){
            $this->set_messages('<i class="fa fa-exclamation-triangle "></i> Preencha o sua antiga senha!', false, 'error');
            return;
        }

        if(!array_key_exists('last_name', $_POST)){
            $this->set_messages( '<i class="fa fa-exclamation-triangle "></i> Preencha o sua antiga senha!', false, 'error');
            return;
        }

        $data = array('ID' => $this->userID);
        $data['first_name'] = $_POST['first_name'];
        $data['last_name'] = $_POST['last_name'];

        if(!empty($_POST['pass'])){

            $RHSUser = new RHSUser($this->userID);

            if(empty($_POST['pass_old'] ) || !wp_check_password( $_POST['pass_old'], $RHSUser->get_user_data('user_pass'), $this->userID) ){
                $this->set_messages('<i class="fa fa-exclamation-triangle "></i> Sua senha antiga está incorreta!', false, 'error');
                return;
            }

            wp_set_password( $_POST['pass'], $this->userID );
        }

        wp_update_user($data);

        if(!empty($_POST['description'])){
            $k =  update_user_meta( $this->userID, 'description', $_POST['description']);
        }

        if(!empty($_POST['formation'])){
            update_user_meta( $this->userID, 'rhs_formation', $_POST['formation']);
        }


        if(!empty($_POST['rhs_interest'])){
            update_user_meta( $this->userID, 'rhs_interest', $_POST['rhs_interest']);
        }

        if(!empty($_POST['estado'])){
            update_user_meta( $this->userID, 'rhs_state', $_POST['estado']);
        }

        if(!empty($_POST['municipio'])){
            update_user_meta( $this->userID, 'rhs_city', $_POST['municipio']);
        }

        if(!empty($_POST['links'])){
            update_user_meta( $this->userID, 'rhs_links', RHSUser::save_links($_POST['links']));
        }

        if ( isset( $_FILES[ 'avatar' ][ 'name' ] ) && $_FILES[ 'avatar' ][ 'error' ] == 0 ) {
            $arquivo_tmp = $_FILES[ 'avatar' ][ 'tmp_name' ];
            $nome = $_FILES[ 'avatar' ][ 'name' ];

            $extensao = pathinfo ( $nome, PATHINFO_EXTENSION );
            $extensao = strtolower ( $extensao );

            if ( strstr ( '.jpg;.jpeg;.gif;.png', $extensao ) ) {

                if($_FILES[ 'avatar' ][ 'size' ] < 5242880){

                    $novoNome = uniqid ( time () ) . '.' . $extensao;
                    $caminho = '/uploads/'. date('Y').'/'.date('m').'/';

                    if ( @move_uploaded_file ( $arquivo_tmp, WP_CONTENT_DIR . $caminho . $novoNome ) ) {
                        update_user_meta( $this->userID, 'rhs_avatar', 'wp-content/'.$caminho.$novoNome);
                    } else {
                        $this->set_messages( '<i class="fa fa-exclamation-triangle"></i> Erro ao salvar o arquivo.', false, 'error');
                    }

                } else {
                    $this->set_messages( '<i class="fa fa-exclamation-triangle"></i> Tamanho não pode ultrapasar de 5mb', false, 'error');
                }
            } else{
                $this->set_messages( '<i class="fa fa-exclamation-triangle"></i> Você poderá enviar apenas arquivos "*.jpg;*.jpeg;*.gif;*.png', false, 'error');
            }
        }

        $this->set_messages( '<i class="fa fa-check"></i> Dados salvo com sucesso!', false, 'success');

    }
}

global $RHSPerfil;
$RHSPerfil = new RHSPerfil(!empty( $_GET['user_id'] ) ? $_GET['user_id'] : get_current_user_id());
