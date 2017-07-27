<?php
/**
 * Entidade do Usuário
 * Class RHSUser
 */
class RHSUser {

    private $id;
    private $avatar;
    private $name;
    private $date_registered;
    private $city;
    private $state;
    private $state_uf;
    private $link;
    private $user_object;
    private $is_admin;
    private $user_role;

    /**
     * RHSUser constructor.
     *
     * @param WP_User $user
     */
    function __construct(WP_User $user) {

        if(!$user){
            return;
        }

        $this->id = $user->ID;
        $this->avatar = get_avatar($this->id);
        $this->name = $user->display_name;
        $this->date_registered = $user->user_registered;
        $this->link = esc_url(get_author_posts_url($this->id));
        $this->user_object = $user;
        $this->user_role = ($user->roles) ? current($user->roles) : '';;
        $this->is_admin = ($this->user_role == 'administrator' || $this->user_role == 'editor');

        $info_location = get_user_ufmun($user->ID);

        $this->city = !empty($info_location['mun']['nome']) ? $info_location['mun']['nome'] : '';
        $this->state = !empty($info_location['uf']['nome']) ?  $info_location['uf']['nome'] : '';
        $this->state_uf = !empty($info_location['uf']['sigla']) ?  $info_location['uf']['sigla'] : '';
    }

    /**
     * @return int
     */
    function get_id(){
        return $this->id;
    }

    /**
     * @return false|string
     */
    function get_avatar(){
        return $this->avatar;
    }

    /**
     * @return string
     */
    function get_name(){
        return $this->name;
    }

    /**
     * @param string $format
     *
     * @return false|string
     */
    function get_date_registered($format = 'd/m/Y'){

        return date($format, strtotime($this->date_registered));

    }

    /**
     * @return string
     */
    function get_city(){
        return $this->city;
    }

    /**
     * @return string
     */
    function get_state(){
        return $this->state;
    }

    /**
     * @return string
     */
    function get_state_uf(){
        return $this->state_uf;
    }

    /**
     * @return string
     */
    function get_link(){
        return $this->link;
    }

    /**
     * @param $comunityID
     *
     * @return RHSComunity
     */
    function get_comunity($comunityID){
        return new RHSComunity(get_term($comunityID), $this->user_object);
    }

    /**
     * @return bool
     */
    function is_admin(){
        return $this->is_admin;
    }

}