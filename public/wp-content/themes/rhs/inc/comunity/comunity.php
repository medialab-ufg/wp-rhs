<?php

/**
 * Entidade da Comunidade
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
    private $members_number;
    private $is_member;
    private $posts;
    private $posts_number;
    private $follows;
    private $follows_number;
    private $is_follow;

    /**
     * RHSComunity construtor.
     *
     * @param WP_Term $comunity
     */
    function __construct(WP_Term $comunity, WP_User $user) {

        if(!$comunity){
            return;
        }

        $this->id = $comunity->term_id;
        $this->user_id = $user->ID;
        $this->user_role = ($user->roles) ? current($user->roles) : '';
        $this->name = $comunity->name;
        $this->type = get_term_meta($comunity->term_id, RHSComunities::TYPE, true );
        $this->members = get_term_meta($comunity->term_id, RHSComunities::MEMBER);
        $this->members_number = count($this->members);
        $this->is_member = ($this->members && in_array($this->user_id, $this->members));
        $this->posts_number = $comunity->count;
        $this->follows = get_term_meta($comunity->term_id, RHSComunities::MEMBER_FOLLOW);
        $this->follows_number = count($this->follows);
        $this->is_follow = ($this->follows && in_array($this->user_id, $this->follows));


        // TODO: Pegar informações
        $this->image = 'http://www.teleios.com.br/wp-content/uploads/2006/08/indios1.jpg';
    }

    /**
     * @return int
     */
    function get_id(){
        return $this->id;
    }

    /**
     * @return string
     */
    function get_name(){
        return $this->name;
    }

    /**
     * @return string
     */
    function get_image(){
        return $this->image;
    }

    /**
     * @return string
     */
    function get_type(){
        return $this->type;
    }

    /**
     * @return array
     */
    function get_members(){
        return $this->members;
    }

    /**
     * @return int
     */
    function get_members_number(){
        return $this->members_number;
    }

    /**
     * @return array
     */
    function get_posts(){
        return $this->posts;
    }

    /**
     * @return int
     */
    function get_posts_number(){
        return $this->posts_number;
    }

    /**
     * @return array
     */
    function get_follows(){
        return $this->follows;
    }

    /**
     * @return int
     */
    function get_follows_number(){
        return $this->follows_number;
    }

    /**
     * @return string
     */
    function get_url(){
        return home_url('comunidade/?comunidade_id=' . $this->id);
    }

    /**
     * @return string
     */
    function get_url_edit(){
        return $this->get_url().'&action=edit';
    }

    /**
     * @return string
     */
    function get_url_members(){
        return $this->get_url().'&action=members';
    }

    /**
     * @return string
     */
    function get_url_follow(){
        return $this->get_url().'&action=follow';
    }

    /**
     * @return string
     */
    function get_url_not_follow(){
        return $this->get_url().'&action=not_follow';
    }

    /**
     * @return string
     */
    function get_url_enter(){
        return $this->get_url().'&action=enter';
    }

    /**
     * @return string
     */
    function get_url_leave(){
        return $this->get_url().'&action=leave';
    }

    /**
     * @return bool
     */
    function is_lock(){
        return ($this->type != RHSComunities::TYPE_HIDE || RHSComunities::TYPE_OPEN);
    }

    /**
     * @return bool
     */
    function is_member(){
        return $this->is_member;
    }

    function can_edit(){
        return ($this->user_role == 'administrador' || $this->user_role == 'editor');
    }

    function can_see_members(){
        return true;
    }

    function can_follow(){
       return ($this->is_member && !$this->is_follow);
    }

    function can_not_follow(){
        return  ($this->is_member && $this->is_follow);
    }

    function can_enter(){
        return !$this->is_member;
    }

    function can_leave(){
        return $this->is_member;
    }

}