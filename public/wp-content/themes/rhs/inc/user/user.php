<?php

/**
 * Created by PhpStorm.
 * User: MediaLab01
 * Date: 05/05/2017
 * Time: 11:49
 */
Class RHSUser {

    static $instance;

    private $separate = "&#44;";
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

            self::$instance = true;
        }
    }

    function addJS() {
        wp_enqueue_script( 'rhs_user', get_template_directory_uri() . '/inc/user/user.js', array( 'jquery' ) );
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
                            </p>
                        <?php } ?>
                    </div>
                    <div class="help-block">
                        <a onclick="addLinkUser();"
                           style="outline: none !important; box-shadow: none !important; text-decoration: none;"
                           href="javascritp:;">
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


    function save_extra_profile_fields( $userID ) {

        $this->userID = $userID;

        if ( ! current_user_can( 'edit_user', $this->userID ) ) {
            return false;
        }

        if ( ! empty( $_POST['rhs_links'] ) && is_array( $_POST['rhs_links'] ) ) {

            if ( ! empty( $_POST['rhs_links']['title'] ) ) {
                $_POST['rhs_links']['title'] = array_filter( $_POST['rhs_links']['title'] );
                $_POST['rhs_links']['title'] = implode( $this->separate, $_POST['rhs_links']['title'] );
            }

            if ( ! empty( $_POST['rhs_links']['url'] ) ) {
                $_POST['rhs_links']['url'] = array_filter( $_POST['rhs_links']['url'] );
                $_POST['rhs_links']['url'] = implode( $this->separate, $_POST['rhs_links']['url'] );
            }

            $_POST['rhs_links'] = json_encode( $_POST['rhs_links'] );

        }

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

                $links['title'] = explode( $this->separate, $links['title'] );
                $links['url']   = explode( $this->separate, $links['url'] );

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