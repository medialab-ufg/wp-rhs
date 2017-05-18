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
            $this->set_messages(array('error' => '<i class="fa fa-exclamation-triangle "></i> Preencha o sua antiga senha!'));
            return;
        }

        $data = array('ID' => $this->userID);
        $data['first_name'] = $_POST['first_name'];
        $data['last_name'] = $_POST['last_name'];

        if(!empty($_POST['pass'])){

            if(empty($_POST['pass_old'] ) || $_POST['pass_old'] != $this->get_user_data('pass_old')){
                $this->set_messages(array('error' => '<i class="fa fa-exclamation-triangle "></i> Sua senha antiga está incorreta!'));
                return;
            }

            $data['pass'] = $_POST['pass'];
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

        $this->set_messages( '<i class="fa fa-check"></i> Dados salvo com sucesso!', false, 'success');

    }
}

global $RHSPerfil;
$RHSPerfil = new RHSPerfil(!empty( $_GET['user_id'] ) ? $_GET['user_id'] : get_current_user_id());
