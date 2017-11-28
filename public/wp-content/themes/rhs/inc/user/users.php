<?php

Class RHSUsers extends RHSMessage {

    static $instance;
    const LINKS_USERMETA = '_rhs_links';
    const SPAM_USERMETA = 'is_spam';
    const ROLE_SPAM = 'spam';
    private $userID;

    function __construct( $userID ) {

        $this->userID = $userID;

        if ( empty( self::$instance ) ) {

            add_action( 'admin_enqueue_scripts', array( &$this, 'addJS' ) );
            add_action( 'show_user_profile', array( &$this, 'extra_profile_fields' ) );
            add_action( 'edit_user_profile', array( &$this, 'extra_profile_fields' ) );
            add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_admin' ) );
            add_action( 'personal_options_update', array( &$this, 'save_extra_profile_fields' ) );
            add_action( 'edit_user_profile_update', array( &$this, 'save_extra_profile_fields' ) );
            add_action('admin_enqueue_scripts', array( &$this, 'admin_theme_style'));
            add_action('pre_get_posts',array( &$this, 'ml_restrict_media_library'));
            //add_filter( 'get_avatar' , array( &$this, 'custom_avatar') , 1 , 5 );
            add_filter( 'pre_get_avatar_data' , array( &$this, 'custom_avatar_url') , 10 , 2 );
            //add_filter( 'get_edit_user_link' , array( &$this, 'custom_edit_user_link') , 5 , 2 );
            add_filter('manage_users_columns', array( &$this, 'admin_new_columns'));
            add_action('manage_users_custom_column', array( &$this, 'admin_new_columns_content'), 10, 3);
            add_action('manage_users_sortable_columns', array( &$this, 'admin_new_sortable_columns'), 10, 3);
            add_filter('pre_get_users', array(&$this, 'filter_rhs_spam_users') );
            add_action('wp_login', array(&$this, 'disable_spam_login'), 50, 2 );
        }

        self::$instance = true;
    }

    /**
    function custom_edit_user_link($link, $user_id){
        return home_url('perfil/'.$user_id);
    }
     */

    function getAvatarImage($userID = 0) {

        $avatar = $this->getAvatar($userID);

        if ( ! empty( $avatar ) ) {
            $avatar = get_site_url() . '/../' . $avatar;
        }

        return $avatar;
    }

    function custom_avatar_url( $args, $id_or_email ) {
        $user = false;


        if ( is_numeric( $id_or_email ) ) {

            $id = (int) $id_or_email;
            $user = get_user_by( 'id' , $id );

        } elseif ( is_object( $id_or_email ) ) {

            if ( ! empty( $id_or_email->user_id ) ) {
                $id = (int) $id_or_email->user_id;
                $user = get_user_by( 'id' , $id );
            }

        } else {
            $user = get_user_by( 'email', $id_or_email );
        }

        if ( $user && is_object( $user )){
            $userObj = new RHSUser($user);

            $avatar = esc_attr( get_the_author_meta( 'rhs_avatar', $userObj->get_id() ));
            
            if ( ! empty( $avatar ) && strpos($avatar, 'http') === false ) {
                $avatar = get_site_url() . '/../' . $avatar;
            }

            if ($avatar) $args['url'] = $avatar;

        }

        return $args;
    }

    // function custom_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
    //     $user = false;


    //     if ( is_numeric( $id_or_email ) ) {

    //         $id = (int) $id_or_email;
    //         $user = get_user_by( 'id' , $id );

    //     } elseif ( is_object( $id_or_email ) ) {

    //         if ( ! empty( $id_or_email->user_id ) ) {
    //             $id = (int) $id_or_email->user_id;
    //             $user = get_user_by( 'id' , $id );
    //         }

    //     } else {
    //         $user = get_user_by( 'email', $id_or_email );
    //     }

    //     if ( $user && is_object( $user )){
    //         $userObj = new RHSUser($user);

    //         if($userObj->get_avatar_url()){
    //             $avatar = "<img alt='{$alt}' src='{$userObj->get_avatar_url()}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
    //         }
    //     }

    //     return $avatar;
    // }

    function getUserId(){
        return $this->userID;
    }

    function ml_restrict_media_library( $wp_query_obj ) {
        global $current_user, $pagenow;
        if( !is_a( $current_user, 'WP_User') )
            return;
        if( 'admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments' )
            return;
        if( !current_user_can('manage_media_library') )
            $wp_query_obj->set('author', $current_user->ID );
        return;
    }

    function addJS() {
        wp_enqueue_script( 'rhs_user', get_template_directory_uri() . '/inc/user/user.js', array( 'jquery' ) );
    }

    function admin_theme_style() {
        wp_enqueue_style('user-admin-style', get_template_directory_uri() . '/inc/user/user.css');
    }

    function get_user_data($field){
        $data = get_userdata($this->userID);

        if(!$data){
            return '';
        }

        if(!empty($data->{$field})){
            return $data->{$field};
        }

        return esc_attr( get_the_author_meta( $field, $this->userID ) );

    }
    
    /*
    * Adiciona novas colunas à tabela da página de administração de usuários
    *
    */
    function admin_new_columns($columns){
        $novasColunas = [
            ['registered', 'Data de cadastro'], 
            ['ultimo-login', 'Último login']
        ];

        foreach($novasColunas as $novaColuna){
            $columns[$novaColuna[0]] = $novaColuna[1];
        }

        return $columns;
    }
    
    /*
    * Adiciona valores às novas colunas da tabela da página de administração de usuário
    */
    function admin_new_columns_content($output, $column_name, $user_id){
        $user = get_userdata($user_id);
        
        switch($column_name){
            case 'registered':
                return $user->user_registered;
            case 'ultimo-login':
                return RHSLogin::get_user_last_login($user_id);
            break;
        }

        return $output;
    }
    
    function admin_new_sortable_columns($columns) {
        $columns['registered'] = 'registered';
        return $columns;
    }

    function extra_profile_fields() {
        if ( function_exists( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        } else {
            wp_enqueue_style( 'thickbox' );
            wp_enqueue_script( 'media-upload' );
            wp_enqueue_script( 'thickbox' );
        }

        $user = new RHSUser(get_userdata($this->userID));

        ?>
        <table class="form-table field-add">
            <tbody>
            <tr class="user-links">
                <th><label for="links"><?php _e( 'Links' ) ?></label></th>
                <td>
                    <div class="panel-body">
                        <?php $user->show_user_links_to_edit($this->userID); ?>
                    </div>
                </td>
            </tr>
            <tr class="user-formation">
                <th>
                    <label for="formation"><?php _e( 'Formação' ) ?></label>
                </th>
                <td>
                    <input type="text" name="rhs_formation" id="formation" value="<?php echo $user->get_formation(); ?>" class="regular-text code">
                </td>
            </tr>
            <?php

            UFMunicipio::form( array(
                'content_before'       => '<tr class="user-state-city">',
                'content_after'        => '</tr>',
                'content_before_field' => '',
                'content_after_field'  => '',
                'state_label'          => 'Estado &nbsp',
                'city_label'           => 'Cidade &nbsp',
                'select_class'         => 'form-control',
                'select_before'        => '<td>',
                'select'               => '</td>',
                'label_before'         => '<th>',
                'label_after'          => '</th>',
                'separator'            => '</tr><tr>',
                'selected_state'       => $user->get_state_id(),
                'selected_municipio'   => $user->get_city_id()
            ) );

            ?>
            <tr class="user-interest">
                <th>
                    <label for="url"><?php _e( 'Interesses' ) ?></label>
                </th>
                <td>
                    <textarea name="rhs_interest" id="interest" rows="5" cols="30"><?php echo $user->get_interest(); ?></textarea>
                </td>
            </tr>
            <tr class="user-avatar">
                <th><label for="pass1-text"><?php _e( 'Foto do Perfil' ); ?></label></th>
                <td>
                    <input class="header_logo_url" type="hidden" name="rhs_avatar" size="60" value="<?php echo $user->get_avatar_url(); ?>">
                    <a class="header_logo_upload"
                       style="<?php echo $user->get_avatar_url() ? '' : 'display: none;' ?>line-height: 0; outline: none !important; box-shadow: none !important;"
                       href="#">
                        <img style="object-fit: cover;" class="header_logo"
                             src="<?php echo $user->get_avatar_url(); ?>" height="100" width="100"/>
                    </a>
                    <div>
                        <button type="button" class="header_logo_upload button wp-generate-pw hide-if-no-js">
                            <?php _e( 'Selecionar imagem' ) ?>
                        </button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <?php
    }

    function enqueue_admin() {
        wp_enqueue_script( 'thickbox' );
        wp_enqueue_style( 'thickbox' );
        wp_enqueue_script( 'media-upload' );
    }

    function save_extra_profile_fields( $userID ) {

        $this->userID = $userID;

        if ( ! current_user_can( 'edit_user', $this->userID ) ) {
            return false;
        }

        if ( ! empty( $_POST['rhs_avatar'] ) ) {
            $url                 = get_site_url();
            $url                 = str_replace( 'wp', '', $url );
            $_POST['rhs_avatar'] = str_replace( $url, '', $_POST['rhs_avatar'] );
        }

        add_user_ufmun_meta( $this->userID, $_POST['municipio'], $_POST['estado']);
        update_user_meta( $this->userID, RHSUsers::LINKS_USERMETA, $_POST['links'] );
        update_user_meta( $this->userID, 'rhs_formation', $_POST['rhs_formation'] );
        update_user_meta( $this->userID, 'rhs_interest', $_POST['rhs_interest'] );
        update_user_meta( $this->userID, 'rhs_avatar', $_POST['rhs_avatar'] );
    }

    function getAvatar($userID = 0) {

        if(!$userID){
            $this->userID;
        }

        return esc_attr( get_the_author_meta( 'rhs_avatar', $userID ) );
    }

    function getFormacao() {

        return esc_attr( get_the_author_meta( 'rhs_formation', $this->userID ) );
    }

    function getInteresses() {

        return esc_attr( get_the_author_meta( 'rhs_interest', $this->userID ) );
    }

    function getSobre() {
        return esc_attr( get_the_author_meta( 'description', $this->userID ) );
    }

    //Notificações por Email

    /*
    * Return the metadata of Promoted_post
    */
    function getPromoted_post($userID = 0){
        //var_dump($userID);
        if(!$userID){
            $userID = $this->userID;
        }
        return esc_attr( get_the_author_meta( 'rhs_email_promoted_post', $userID ) );
    }

    /*
    * Return the metadata of Comment_post
    */
    function getComment_post($userID = 0){
        if(!$userID){
            $userID = $this->userID;
        }

        return esc_attr( get_the_author_meta( 'rhs_email_comment_post', $userID ) );
    }
    
    /*
    * Return the metadata of Comment_post_follow
    */
    function getComment_post_follow($userID = 0){
        if(!$userID){
            $userID = $this->userID;
        }

        return esc_attr( get_the_author_meta( 'rhs_email_comment_post_follow', $userID ) );
    }
    
    /*
    * Return the metadata of New_post_from_user
    */
    function getNew_post_from_user($userID = 0){
        if(!$userID){
            $userID = $this->userID;
        }

        return esc_attr( get_the_author_meta( 'rhs_email_new_post_from_user_follow', $userID ) );
    }
    //End Notificações por Email

    function show_author_links() {
        $links = get_the_author_meta( RHSUsers::LINKS_USERMETA, $this->userID );

        if( !empty( reset($links)["url"]) && ! empty( reset($links)["titulo"])) {
            echo "<p>Links: </p>";

            foreach ($links as $value){
                echo "<span><a href='". $value['url'] ."' target='_blank'>".  $value['titulo'] . "</a></span><br/>";
            }
        }


    }

    /*
     * Exclui usuários com role 'spam' de todas as buscas
     * */
    function filter_rhs_spam_users($user_query) {
        global $pagenow;
        if( !is_admin() && "users.php" != $pagenow ) {
            $user_query->set('role__not_in', self::ROLE_SPAM);
        }
    }

    function disable_spam_login($user_login, $wp_user) {
        if( in_array(self::ROLE_SPAM, $wp_user->roles) ) {
            wp_clear_auth_cookie();
            wp_destroy_current_session();
             wp_redirect( home_url() );
            exit;
        }
    }

}

global $RHSUsers;
$RHSUsers = new RHSUsers( ! empty( $_GET['user_id'] ) ? $_GET['user_id'] : get_current_user_id() );
