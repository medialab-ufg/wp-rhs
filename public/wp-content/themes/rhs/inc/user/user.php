<?php

Class RHSUser extends RHSMenssage {

    static $instance;
    const SEPARATE = "&#44;";
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
        }

        self::$instance = true;
    }

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

    function extra_profile_fields() {
        if ( function_exists( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        } else {
            wp_enqueue_style( 'thickbox' );
            wp_enqueue_script( 'media-upload' );
            wp_enqueue_script( 'thickbox' );
        }


        ?>
        <table class="form-table field-add">
            <tbody>
            <tr class="user-links">
                <th><label for="links"><?php _e( 'Links' ) ?></label></th>
                <td>
                    <div class="input-group">
                        <?php foreach ( $this->getLinks( true ) as $key => $link ) { ?>
                            <p>
                                <input placeholder="Titulo" type="text" name="rhs_links[title][]" id="links"
                                       value="<?php echo $link['title'] ?>" class="regular-text code">
                                <input placeholder="Url" type="url" name="rhs_links[url][]" id="links"
                                       value="<?php echo $link['url'] ?>" class="regular-text code">
                                <i><a onclick="removerLinkUser(this)" title="Remover Link" class="remove" href="javascript:;">X</a></i>
                            </p>
                        <?php } ?>
                    </div>
                    <div class="help-block">
                        <a class="js-add-user-link"
                           style="outline: none !important; box-shadow: none !important; text-decoration: none;">
                            + <?php _e( 'Adicionar' ); ?>
                        </a>
                    </div>
                </td>
            </tr>
            <tr class="user-formation">
                <th><label for="formation"><?php _e( 'Formação' ) ?></label></th>
                <td><input type="text" name="rhs_formation" id="formation"
                           value="<?php echo $this->getFormacao(); ?>"
                           class="regular-text code"></td>
            </tr>
            <tr class="user-interest">
                <th><label for="url"><?php _e( 'Interesses' ) ?></label></th>
                <td><textarea name="rhs_interest" id="interest" rows="5"
                              cols="30"><?php echo $this->getInteresses(); ?></textarea></td>
            </tr>
            <tr class="user-avatar">
                <th><label for="pass1-text"><?php _e( 'Foto do Perfil' ); ?></label></th>
                <td>
                    <input class="header_logo_url" type="hidden" name="rhs_avatar" size="60"
                           value="<?php echo $this->getAvatar(); ?>">
                    <a class="header_logo_upload"
                       style="<?php echo $this->getAvatar() ? '' : 'display: none;' ?>line-height: 0; outline: none !important; box-shadow: none !important;"
                       href="#">
                        <img style="object-fit: cover;" class="header_logo"
                             src="<?php echo $this->getAvatarImage(); ?>" height="100" width="100"/>
                    </a>
                    <div>
                        <button type="button"
                                class="header_logo_upload button wp-generate-pw hide-if-no-js"><?php _e( 'Selecionar imagem' ) ?></button>
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

    static function save_links($links_post){

        if ( ! empty( $links_post ) && is_array( $links_post ) ) {

            if ( ! empty( $links_post['title'] ) ) {
                $links_post['title'] = array_filter( $links_post['title'] );
                $links_post['title'] = implode( self::SEPARATE, $links_post['title'] );
            }

            if ( ! empty( $links_post['url'] ) ) {
                $links_post['url'] = array_filter( $links_post['url'] );
                $links_post['url'] = implode( self::SEPARATE, $links_post['url'] );
            }

            return json_encode( $links_post );

        }

        return array();

    }

    function save_extra_profile_fields( $userID ) {

        $this->userID = $userID;

        if ( ! current_user_can( 'edit_user', $this->userID ) ) {
            return false;
        }

        $_POST['rhs_links'] = self::save_links(! empty( $_POST['rhs_links'] ) ? $_POST['rhs_links'] : array());

        if ( ! empty( $_POST['rhs_avatar'] ) ) {
            $url                 = get_site_url();
            $url                 = str_replace( 'wp', '', $url );
            $_POST['rhs_avatar'] = str_replace( $url, '', $_POST['rhs_avatar'] );
        }

        update_user_meta( $this->userID, 'rhs_links', $_POST['rhs_links'] );
        update_user_meta( $this->userID, 'rhs_formation', $_POST['rhs_formation'] );
        update_user_meta( $this->userID, 'rhs_interest', $_POST['rhs_interest'] );
        update_user_meta( $this->userID, 'rhs_avatar', $_POST['rhs_avatar'] );
    }

    function getAvatarImage() {

        $avatar = $this->getAvatar();

        if ( ! empty( $avatar ) ) {
            $avatar = get_site_url() . '/../' . $avatar;
        }

        return $avatar;
    }

    function getAvatar() {

        return esc_attr( get_the_author_meta( 'rhs_avatar', $this->userID ) );
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

    function getLinks( $default = false ) {

        $links = get_the_author_meta( 'rhs_links', $this->userID );
        $data  = array();

        if ( $default ) {
            $data[] = array( 'title' => '', 'url' => '' );
        }

        if ( ! empty( $links ) ) {

            $links = json_decode( $links, true );

            if ( ! empty( $links['title'] ) ) {

                $data = array();

                $links['title'] = explode( self::SEPARATE, $links['title'] );
                $links['url']   = explode( self::SEPARATE, $links['url'] );

                foreach ( $links['title'] as $key => $link ) {

                    $data[] = array(
                        'title' => $links['title'][ $key ],
                        'url'   => $links['url'][ $key ]
                    );

                }
            }
        }

        return $data;
    }

}

global $RHSUser;
$RHSUser = new RHSUser( ! empty( $_GET['user_id'] ) ? $_GET['user_id'] : get_current_user_id() );
