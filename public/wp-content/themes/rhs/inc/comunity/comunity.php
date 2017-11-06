<?php

/**
 * Entidade da Comunidade relacionada com o usuário
 * Class RHSComunity
 */
class RHSComunity {

    private $id;
    private $user_id;
    private $user_role;
    private $name;
    private $image;
    private $type;
    private $members;
    private $posts;
    private $follows;
    private $requests;
    private $members_number;
    private $posts_number;
    private $follows_number;
    private $is_member;
    private $is_follow;
    private $is_moderate;
    private $is_admin;
    private $is_request;
    private $term_object;

    /**
     * RHSComunity construtor.
     *
     * @param WP_Term $comunity
     */
    function __construct(WP_Term $comunity, WP_User $user) {

        $members = RHSComunities::get_members($comunity->term_id);
        $is_member = ($members && in_array($user->ID, $members));
        $type = RHSComunities::get_type($comunity->term_id);
        $user_role = ($user->roles) ? current($user->roles) : '';
        $is_admin = ($user_role == 'administrator' || $user_role == 'editor');

        if(!$comunity || !$this->can_see_list($type, $is_member)){
            return;
        }

        $this->term_object = $comunity;
        $this->id = $comunity->term_id;
        $this->user_id = $user->ID;
        $this->user_role = $user_role;
        $this->name = $comunity->name;
        $this->type = $type;
        $this->members = $members;
        $this->members_number = count($this->members);
        $this->is_member = $is_member;
        $this->posts_number = $comunity->count;
        $this->follows = RHSComunities::get_follows($comunity->term_id);
        $this->follows_number = count($this->follows);
        $this->is_follow = ($this->follows && in_array($this->user_id, $this->follows));
        $this->image = RHSComunities::get_image($comunity->term_id);
        $this->is_moderate = $user->has_cap(RHSComunities::CAPABILITY_MODERATOR.'_'.$comunity->term_id);
        $this->is_admin = $is_admin;
        $this->requests = RHSComunities::get_requests($comunity->term_id);
        $this->is_request = ($this->requests && in_array($this->user_id, $this->requests));
    }


    /**
     * Checa por tipo se a comunidade pode ser listada, usada diretamente no construtor para
     * não construir seus atribultos caso falso
     *
     * @param $type
     * @param $is_member
     *
     * @return bool
     */
    private function can_see_list($type, $is_member){

        switch ($type){
            case RHSComunities::TYPE_OPEN:
                return true;
                break;
            case RHSComunities::TYPE_PRIVATE:
                return true;
                break;
            case RHSComunities::TYPE_HIDE:

                if($is_member){
                    return true;
                }

                return false;

                break;
        }

        return false;
    }

    /**
     * Checa por tipo se ela pode ser vista internamente
     *
     * @param $type
     * @param $is_member
     *
     * @return bool
     */
    function can_see(){

        switch ($this->type){
            case RHSComunities::TYPE_OPEN:
                return true;
                break;
            case RHSComunities::TYPE_PRIVATE:
                if($this->is_member){
                    return true;
                }

                return false;
                break;
            case RHSComunities::TYPE_HIDE:
                if($this->is_member){
                    return true;
                }

                return false;
                break;
        }

        return false;
    }

    /**
     * Retorna o id da comunidade
     *
     * @return int
     */
    function get_id(){
        return $this->id;
    }

    /**
     * Retorna o nome da comunidade
     *
     * @return string
     */
    function get_name(){
        return $this->name;
    }

    /**
     * Retorna a imagem da comunidade
     *
     * @return string
     */
    function get_image(){
        return $this->image;
    }

    /**
     * Retorna o tipo da comunidade
     *
     * @return string
     */
    function get_type(){
        return $this->type;
    }

    /**
     * Retorna os membros da comunidade
     *
     * @return array
     */
    function get_members(){
        return $this->members;
    }

    /**
     * Retorna q quantidade de membros da comunidade
     *
     * @return int
     */
    function get_members_number(){
        return $this->members_number;
    }

    /**
     * Retorna os posts da comunidade
     *
     * @return array
     */
    function get_posts(){
        return $this->posts;
    }

    /**
     * Retorna a quantidade de posts da comunidade
     *
     * @return int
     */
    function get_posts_number(){
        return $this->posts_number;
    }

    /**
     * Retorna os seguidores da comunidade
     *
     * @return array
     */
    function get_follows(){
        return $this->follows;
    }

    /**
     * Retorna q quantidade de seguidores da comunidade
     *
     * @return int
     */
    function get_follows_number(){
        return $this->follows_number;
    }

    /**
     * Retorna o link para a comunidade
     *
     * @return string
     */
    function get_url(){
        return get_term_link($this->id, RHSComunities::TAXONOMY);
    }

    /**
     * Retorna se é para mostrar os membros da comunidade primeiro
     *
     * @return bool
     */
    function is_to_see_members(){

        if(!empty($_GET['rhs_comunity_action']) && $_GET['rhs_comunity_action'] == 'membros'){
            return true;
        }

        return false;

    }

    /**
     * Retorna a url de membros da comunidade
     *
     * @return string
     */
    function get_url_members(){
        return $this->get_url().'?rhs_comunity_action=membros';
    }

    /**
     * Checa se ela não é aberta
     *
     * @return bool
     */
    function is_lock(){
        return ($this->type != RHSComunities::TYPE_OPEN);
    }

    /**
     * Checa se o usuário é membro da comunidade
     *
     * @return bool
     */
    function is_member(){
        return ($this->is_member);
    }

    /**
     * Checa se o usuário é moderador da comunidade
     *
     * @return bool
     */
    function is_moderate(){
        return $this->is_moderate;
    }

    /**
     * Checa se o usuário está pedindo para entra na comunidade
     *
     * @return bool
     */
    function is_request(){
        return $this->is_request;
    }

    /**
     * Checa se o usuário pode editar a comunidade
     *
     * @return bool
     */
    function can_edit(){
        return ($this->is_moderate || $this->is_admin);
    }

    /**
     * Checa se o usuário pode ver os membros da comunidade
     *
     * @return bool
     */
    function can_members(){
       return $this->can_see();
    }

    /**
     * Checa se o usuário pode seguir a comunidade
     *
     * @return bool
     */
    function can_follow(){
       return ($this->is_member && !$this->is_follow);
    }

    /**
     * Checa se o usuário pode deixar de seguir a comunidade
     *
     * @return bool
     */
    function can_not_follow(){
        return  ($this->is_member && $this->is_follow);
    }

    /**
     * Checa se o usuário pode entrar na comunidade
     *
     * @return bool
     */
    function can_enter(){
        return (!$this->is_member && ($this->type == RHSComunities::TYPE_OPEN || $this->is_admin));
    }

    /**
     * Checa se o usuário pode requerir a entrada na comunidade
     *
     * @return bool
     */
    function can_request(){
        return (!$this->is_member && !$this->is_admin && $this->type == RHSComunities::TYPE_PRIVATE && !$this->is_request);
    }

    /**
     * Checa se o usuário está esperando a aprovação para entrar na comunidade
     *
     * @return bool
     */
    function can_wait_request(){
        return ($this->type == RHSComunities::TYPE_PRIVATE && $this->is_request && !$this->is_admin);
    }

    /**
     * Checa se o usuário pode sair da comunidade
     *
     * @return bool
     */
    function can_leave(){
        return $this->is_member;
    }

    /**
     * Checa se o usuário pode moderar a comunidade
     *
     * @return bool
     */
    function can_moderate(){
        return (!$this->is_moderate && $this->is_member) ;
    }

    /**
     * Checa se o usuário pode moderar a comunidade
     *
     * @return bool
     */
    function can_not_moderate(){
        return ($this->is_moderate && $this->is_member) ;
    }

    /**
     * Checa se o usuário está pedindo para entrar e pode ser aceito na comunidade
     *
     * @return bool
     */
    function can_accept_request(){
        return ($this->is_request) ;
    }

    /**
     * Checa se o usuário está pedindo para entrar e pode ser rejeitado na comunidade
     *
     * @return bool
     */
    function can_reject_request(){
        return ($this->is_request) ;
    }

    /**
     * Checa se o usuário é um administrador/editor
     *
     * @return bool
     */
    function is_admin(){
        return $this->is_admin;
    }

    /**
     * Retorna os membros separados por tipo
     *
     * @return RHSUser[]
     */
    function get_members_saparete_by_capability(){

        $users = array();

        if($this->is_moderate) {
            $requests = RHSComunities::get_requests( $this->id );

            foreach ( $requests as $request ) {
                $userdata = get_userdata( $request );

                $comunity = new RHSComunity( $this->term_object, $userdata );

                $users[] = new RHSUser( $userdata );
            }
        }

        foreach ($this->get_members() as $member){

            $userdata = get_userdata($member);

            $comunity = new RHSComunity($this->term_object, $userdata);

            $users[] = new RHSUser($userdata);

        }

        return $users;

    }

    /**
     * @param string $text
     *
     * @return string
     */
    function get_button_members($text = 'Ver membros'){
        return '<a class="btn btn-default btn-rhs"
                   data-type="members"
                   title="'.__($text).'"
                   href="'.$this->get_url_members().'"
                   '.(! $this->can_members() ? 'style="display: none;"' : '').'
                   data-toggle="tooltip" data-placement="top">
                        <i class="fa fa-users"></i>
                </a>';
    }

    /**
     * @param string $text
     *
     * @return string
     */
    function get_button_follow($text = 'Seguir a comunidade'){
        return '<a class="btn btn-default btn-rhs"
                   data-type="follow"
                   title="'.__($text).'"
                   href="javascript:;"
                   '.(! $this->can_follow() ? 'style="display: none;"' : '').'
                   data-toggle="tooltip" data-placement="top">
                    <i class="fa fa-rss"></i>
                </a>';
    }

    /**
     * @param string $text
     *
     * @return string
     */
    function get_button_not_follow($text = 'Deixar de Seguir a comunidade'){
        return '<a class="btn btn-default btn-rhs"
                   data-type="not_follow"
                   title="'.__($text).'"
                   href="javascript:;"
                   '.(! $this->can_not_follow() ? 'style="display: none;"' : '').'
                   data-toggle="tooltip" data-placement="top">
                          <i class="fa fa-rss"></i>
                          <i class="fa fa-remove text-danger" style="margin-right: -9px;top: -6px;position: relative;"></i>
                </a>';
    }

    /**
     * @param string $text
     *
     * @return string
     */
    function get_button_enter($text = 'Participar da comunidade'){
        return '<a class="btn btn-default btn-rhs"
                   data-type="enter"
                   title="'.__($text).'"
                   href="javascript:;"
                   '.(! $this->can_enter() ? 'style="display: none;"' : '').'
                   data-toggle="tooltip" data-placement="top">
                        <i class="fa fa-sign-in"></i>
                </a>';
    }

    /**
     * @param string $text
     *
     * @return string
     */
    function get_button_leave($text = 'Sair da comunidade'){
        return '<a class="btn btn-default btn-rhs leave-community"
                   data-type="leave"
                   title="'.__($text).'"
                   href="javascript:;"
                   '.(! $this->can_leave() ? 'style="display: none;"' : '').'
                   data-toggle="tooltip" data-placement="top">
                        <i class="fa fa-remove"></i>
                </a>';
    }

    /**
     * @param string $text
     *
     * @return string
     */
    function get_button_request($text = 'Pedir para fazer parte da comunidade'){
        return '<a class="btn btn-default btn-rhs"
                   data-type="request"
                   title="'.__($text).'"
                   href="javascript:;"
                   '.(! $this->can_request() ? 'style="display: none;"' : '').'
                   data-toggle="tooltip" data-placement="top">
                        <i class="fa fa-external-link"></i>
                </a>';
    }

    /**
     * @param string $text
     *
     * @return string
     */
    function get_button_wait_request($text = 'Seu pedido está em analise, você será notificado da resposta!'){
        return '<a class="btn btn-default btn-rhs"
                   data-type="wait_request"
                   title="'.__($text).'"
                   href="javascript:void(0);"
                   '.(! $this->can_wait_request() ? 'style="display: none;"' : '').'
                   data-toggle="tooltip" data-placement="top">
                        <i class="fa fa-send"></i>
                </a>';
    }

    /**
     * @param string $text
     *
     * @return string
     */
    function get_button_moderate($text = 'Moderar'){
        return '<a class="btn btn-default btn-rhs"
                   data-type="moderate"
                   title="'.__($text).'"
                   href="javascript:;"
                   '.(! $this->can_moderate() ? 'style="display: none;"' : '').'
                   data-toggle="tooltip" data-placement="top">
                        <span class="fa-stack fa-near fa-lg">
                          <i class="fa fa-user fa-stack-1x"></i>
                          <i class="fa fa-long-arrow-up fa-stack-1x"></i>
                        </span>
                </a>';
    }

    /**
     * @param string $text
     *
     * @return string
     */
    function get_button_not_moderate($text = 'Remover da moderação'){
        return '<a class="btn btn-default btn-rhs"
                   data-type="not_moderate"
                   title="'.__($text).'"
                   href="javascript:;"
                   '.(! $this->can_not_moderate() ? 'style="display: none;"' : '').'
                   data-toggle="tooltip" data-placement="top">
                        <span class="fa-stack fa-near fa-lg">
                          <i class="fa fa-user fa-stack-1x"></i>
                          <i class="fa fa-long-arrow-down fa-stack-1x"></i>
                        </span>
                </a>';
    }

    /**
     * @param string $text
     *
     * @return string
     */
    function get_button_accept_request($text = 'Aceitar o pedido para entrar'){
        return '<a class="btn btn-default btn-rhs accept-request"
                   data-type="accept_request"
                   title="'.__($text).'"
                   href="javascript:;"
                   '.(! $this->can_accept_request() ? 'style="display: none;"' : '').'
                   data-toggle="tooltip" data-placement="top">
                        <i class="fa fa-check"></i>
                </a>';
    }

    function get_button_reject_request($text = 'Rejeitar o pedido para entrar'){
        return '<a class="btn btn-default btn-rhs reject-request"
                   data-type="reject_request"
                   title="'.__($text).'"
                   href="javascript:;"
                   '.(! $this->can_reject_request() ? 'style="display: none;"' : '').'
                   data-toggle="tooltip" data-placement="top">
                        <i class="fa fa-remove"></i>
                </a>';
    }

    /**
     *  Pesquisa membros baseado na string
     * @param $string
     *
     * @return array
     */
    function get_members_by_string($string){

        $data = array();

        foreach ($this->members as $membro){
            $user = get_userdata($membro);

            if(!$user){
                continue;
            }

            if(strpos($user->display_name, $string) === false){
                continue;
            }

            $data[] = $user;
        }

        return $data;

    }
}