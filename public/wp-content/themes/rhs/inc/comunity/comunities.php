<?php

class RHSComunities extends RHSMenssage {

    const TAXONOMY = 'comunity-category';
    const IMAGE = 'rhs-comunity-image';
    const TYPE = 'rhs-comunity-type';
    const TYPE_OPEN = 'open';
    const TYPE_PRIVATE = 'private';
    const TYPE_HIDE = 'hide';
    const MEMBER = 'rhs-comunity-member';
    const MEMBER_FOLLOW = 'rhs-comunity-member-follow';
    const MEMBER_REQUEST = 'rhs-comunity-member-request';
    const CAPABILITY_MODERATOR = 'rhs-comunity-moderator';

    /**
     * @var RHSComunity[]
     */
    static $comunity = array();

    public function __construct() {
        $this->events_wordpress();
    }

    /**
     * Action, Filters e Hooks para o wordpress
     */
    function events_wordpress() {
        add_action( 'init', array( &$this, "register_taxonomy" ) );

        add_action( self::TAXONOMY . '_edit_form_fields', array( &$this, 'edit_category_field' ) );
        add_action( self::TAXONOMY . '_add_form_fields', array( &$this, 'new_category_field' ) );
        add_action( 'edited_' . self::TAXONOMY, array( &$this, 'save_tax_meta' ), 10, 2 );
        add_action( 'create_' . self::TAXONOMY, array( &$this, 'save_tax_meta' ), 10, 2 );

        add_action( 'wp_ajax_comunity_action', array( $this, 'ajax_comunity_action' ) );
        add_action( 'wp_ajax_complete_comunity_members', array( $this, 'ajax_complete_comunity_members' ) );
        add_action( 'wp_ajax_comunity_action_add_member', array( $this, 'ajax_comunity_action_add_member' ) );

        add_action( 'add_meta_boxes', array( &$this, "add_meta_box" ) );
        add_filter( 'wp_insert_post_data', array( &$this, 'filter_post_data' ), '99', 2 );

    }


    /**
     * Retorna as comunidades
     *
     * @param bool $get_empty - Categorias sem posts
     * @param array $filter - Filtro do WP_Terms
     *
     * @return WP_Term[]
     */
    public function get_comunities( $get_empty = false, array $filter = array() ) {

        $filter_default = array( 'taxonomy' => self::TAXONOMY );

        if ( $get_empty ) {
            $filter_default['hide_empty'] = 0;
            $filter_default['pad_counts'] = true;
        }

        if ( $filter ) {
            $filter_default = array_merge( $filter_default, $filter );
        }

        return get_categories( $filter_default );
    }

    /*====================================================================================================
                                                ADMINISTAÇÃO
    ======================================================================================================*/

    /**
     * Registra a taxonomia "Comunidades"
     */
    function register_taxonomy() {
        $labels = array(
            'name'              => 'Comunidades',
            'singular_name'     => 'Comunidade',
            'search_items'      => 'Buscar Comunidade',
            'all_items'         => 'Todas as Comunidades',
            'parent_item'       => 'Comunidades Acima',
            'parent_item_colon' => 'Comunidades Acima:',
            'edit_item'         => 'Editar Comunidade',
            'update_item'       => 'Atualizar Comunidades',
            'add_new_item'      => 'Adicionar Nova Comunidade',
            'new_item_name'     => 'Novo nome da Comunidade',
        );
        register_taxonomy(
            self::TAXONOMY,
            array( 'post' ),
            array(
                'hierarchical'      => true,
                'labels'            => $labels,
                'show_ui'           => true,
                'query_var'         => true,
                'rewrite'           => false,
                'hierarchical'      => false,
                'parent_item'       => null,
                'parent_item_colon' => null,
                'rewrite'           => array(
                    'slug' => 'comunidade'
                )
            )
        );
    }

    /**
     * (Taxonomy) Adiciona campo "Tipo" ao inserir
     *
     * @param $term
     */
    function new_category_field( $term ) {
        ?>
        <div class="form-field term-parent-wrap">
            <label for="term_meta[<?php echo self::TYPE ?>]">Tipo</label>
            <br/>
            <fieldset>
                <label>
                    <input checked type="radio" name="term_meta[<?php echo self::TYPE ?>]" value="open"/>
                    <span>Aberto</span>
                </label>
                <label>
                    <input type="radio" name="term_meta[<?php echo self::TYPE ?>]" value="private"/>
                    <span>Privado</span>
                </label>
                <label>
                    <input type="radio" name="term_meta[<?php echo self::TYPE ?>]" value="hide"/>
                    <span>Oculto</span>
                </label>
            </fieldset>
        </div>
        <?php
    }

    /**
     * (Taxonomy) Adiciona campo "Tipo" ao editar
     *
     * @param $term
     */
    function edit_category_field( $term ) {
        $term_meta = '';
        if ( $term instanceof WP_Term ) {
            $term_id   = $term->term_id;
            $term_meta = get_term_meta( $term_id, self::TYPE, true );
        }


        ?>
        <tr class="form-field term-parent-wrap">
            <th scope="row">
                <label>Tipo</label>
            </th>
            <td>
                <fieldset>
                    <label>
                        <input <?php echo ( $term_meta == 'open' || ! $term_meta ) ? 'checked' : ''; ?> type="radio"
                                                                                                        name="term_meta[<?php echo self::TYPE ?>]"
                                                                                                        value="open"/>
                        <span>Aberto</span>
                    </label>
                    <br/>
                    <label>
                        <input <?php echo ( $term_meta == 'private' ) ? 'checked' : ''; ?> type="radio"
                                                                                           name="term_meta[<?php echo self::TYPE ?>]"
                                                                                           value="private"/>
                        <span>Privado</span>
                    </label>
                    <br/>
                    <label>
                        <input <?php echo ( $term_meta == 'hide' ) ? 'checked' : ''; ?> type="radio"
                                                                                        name="term_meta[<?php echo self::TYPE ?>]"
                                                                                        value="hide"/>
                        <span>Oculto</span>
                    </label>
                </fieldset>
            </td>
        </tr>

        <?php
    }

    /**
     * (Taxonomy) Salva o campo "Tipo"
     *
     * @param $term_id
     * @param $taxonomy
     */
    function save_tax_meta( $term_id, $taxonomy ) {

        if ( isset( $_POST['term_meta'][ self::TYPE ] ) ) {
            $term_meta = array();

            if ( ! add_term_meta( $term_id, self::TYPE, $_POST['term_meta'][ self::TYPE ], true ) ) {

                update_term_meta( $term_id, self::TYPE, $_POST['term_meta'][ self::TYPE ] );
            }
        }
    }

    /**
     * (Post) Adiciona MetaBox para a escolha da comunidade
     */
    function add_meta_box() {
        add_meta_box( 'category_comunity', 'Comunidades', array( &$this, 'meta_box_response' ), 'post', 'side',
            'default' );
    }

    /**
     * (Post) HTML da MetaBox
     *
     * @param WP_Post $post
     *
     * @return string
     */
    function meta_box_response( $post ) {

        $comunities      = $this->get_comunities( true );
        $comunities_post = self::get_comunities_by_post( $post->ID );

        ?>
        <div id="taxonomy-category" class="categorydiv">
            <ul id="category-tabs" class="category-tabs">
                <li class="tabs">
                    <a href="#category-all">Todas as comunidades</a>
                </li>
            </ul>
            <div id="category-all" class="tabs-panel">
                <input type="hidden" name="post_comunity[]" value="0">
                <ul class="categorychecklist">
                    <?php foreach ( $comunities as $category ) { ?>
                        <li id="comunity-<?php echo $category->term_id ?>" class="popular-category">
                            <label class="selectit">
                                <?php
                                $checked = ( $comunities_post && in_array($category->term_id, $comunities_post) ) ? 'checked' : '';
                                ?>
                                <input <?php echo $checked; ?> value="<?php echo $category->name ?>" type="radio"
                                                               name="post_comunity"
                                                               id="in-comunity-<?php echo $category->term_id ?>"/> <?php echo $category->name ?>
                            </label>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <?php
        return '';
    }

    /**
     * (Post) Pega a comunidade escolhida
     *
     * @param int $post_id
     *
     * @return array|WP_Error|WP_Term
     */
    static function get_comunities_by_post( $post_id ) {
        $comunities = wp_get_post_terms( $post_id, self::TAXONOMY );

        if ( ! $comunities ) {
            return array();
        }

        $return = array();

        foreach ($comunities as $comunity){
            $return[] = $comunity->term_id;
        }

        return $return;
    }

    /**
     * (Post) Insere a comunidade escolhida
     *
     * @param $data
     * @param $postarr
     *
     * @return mixed
     */
    function filter_post_data( $data, $postarr ) {

        if ( ! empty( $_POST['post_comunity'] ) ) {
            wp_set_post_terms( $postarr['ID'], $_POST['post_comunity'], self::TAXONOMY );
        }

        return $data;
    }

    /*====================================================================================================
                                                CLIENTE
    ======================================================================================================*/

    /**
     * Salva imagem pelo post
     */
    public function trigger_by_post() {
        if ( ! empty( $_POST['edit_image_comunity_wp'] ) && $_POST['edit_image_comunity_wp'] == $this->getKey() ) {
            $this->update_image_post( $_POST['comunity_id'], $_FILES['avatar_comunity'] );
        }
    }

    /**
     * Salva imagem
     *
     * @param $comunity_id
     * @param $avatar_file
     */
    function update_image_post( $comunity_id, $avatar_file ) {

        if ( $avatar_file ) {
            $arquivo_tmp = $avatar_file['tmp_name'];
            $nome        = $avatar_file['name'];

            $extensao = pathinfo( $nome, PATHINFO_EXTENSION );
            $extensao = strtolower( $extensao );

            $novoNome = uniqid( time() ) . '.' . $extensao;
            $caminho  = '/uploads/' . date( 'Y' ) . '/' . date( 'm' ) . '/';

            if ( ! file_exists( WP_CONTENT_DIR . $caminho ) ) {
                mkdir( WP_CONTENT_DIR . $caminho, 0777, true );
            }

            if ( @move_uploaded_file( $arquivo_tmp, WP_CONTENT_DIR . $caminho . $novoNome ) ) {

                self::update_image( $comunity_id, 'wp-content' . $caminho . $novoNome );
            } else {
                $this->set_messages( '<i class="fa fa-exclamation-triangle"></i> Erro ao salvar o arquivo.', false,
                    'error' );

            }
        }

    }

    /**
     * Pega comunidades baseado na pemissão do cliente
     *
     * @param $user_id
     *
     * @return RHSComunity[]
     */
    function get_comunities_by_user( $user_id ) {

        // Singleton para usar em verificação e não fazer a consulta novamente
        if ( $user_id ) {
            if ( ! empty( self::$comunity[ $user_id ] ) ) {
                return self::$comunity[ $user_id ];
            }
        }

        $comunities = $this->get_comunities( true );

        $return = array();

        foreach ( $comunities as $comunity ) {

            $obj_comunity = $this->get_comunity_by_user( $comunity, $user_id );

            if ( $obj_comunity->get_id() ) {
                $return[] = $obj_comunity;
            }
        }

        return self::$comunity[ $user_id ] = $return;

    }

    /**
     * Adiciona membro a comunidade
     *
     * @param $term_id
     * @param $user_id
     */
    static function add_user_comunity( $term_id, $user_id ) {
        add_term_meta( $term_id, self::MEMBER, $user_id );
        self::delete_user_comunity_request( $term_id, $user_id );
    }

    /**
     * Remove membro da comunidade
     *
     * @param $term_id
     * @param $user_id
     */
    static function delete_user_comunity( $term_id, $user_id ) {
        delete_term_meta( $term_id, self::MEMBER, $user_id );
        self::delete_user_comunity_moderate( $term_id, $user_id );
        self::delete_user_comunity_follow( $term_id, $user_id );
        self::delete_user_comunity_request( $term_id, $user_id );
    }

    /**
     * Adiciona membro a seguidor comunidade
     *
     * @param $term_id
     * @param $user_id
     */
    static function add_user_comunity_follow( $term_id, $user_id ) {
        add_term_meta( $term_id, self::MEMBER_FOLLOW, $user_id );
    }

    /**
     * Remove membro de seguidor comunidade
     *
     * @param $term_id
     * @param $user_id
     */
    static function delete_user_comunity_follow( $term_id, $user_id ) {
        delete_term_meta( $term_id, self::MEMBER_FOLLOW, $user_id );
    }

    /**
     * Adiciona o pedido do membro para entrar na comunidade
     *
     * @param $term_id
     * @param $user_id
     */
    static function add_user_comunity_request( $term_id, $user_id ) {
        add_term_meta( $term_id, self::MEMBER_REQUEST, $user_id );
    }

    /**
     * Deleta o pedido do membro para entrar na comunidade
     *
     * @param $term_id
     * @param $user_id
     */
    static function delete_user_comunity_request( $term_id, $user_id ) {
        delete_term_meta( $term_id, self::MEMBER_REQUEST, $user_id );
    }

    /**
     * Atribui o usuário como moderador da comunidade
     *
     * @param $term_id
     * @param $user_id
     */
    static function add_user_comunity_moderate( $term_id, $user_id ) {
        $user = get_userdata( $user_id );
        $user->add_cap( RHSComunities::CAPABILITY_MODERATOR . '_' . $term_id );
    }

    /**
     * Remove o usuário como moderador da comunidade
     *
     * @param $term_id
     * @param $user_id
     */
    static function delete_user_comunity_moderate( $term_id, $user_id ) {
        $user = get_userdata( $user_id );
        $user->remove_cap( RHSComunities::CAPABILITY_MODERATOR . '_' . $term_id );
    }

    /**
     * Adiciona ou atualiza avatar da comunidade
     *
     * @param $term_id
     * @param $image_path
     */
    static function update_image( $term_id, $image_path ) {
        if ( ! add_term_meta( $term_id, self::IMAGE, $image_path, true ) ) {
            update_term_meta( $term_id, self::IMAGE, $image_path );
        };
    }

    /**
     * Retorna avatar da comunidade
     *
     * @param $term_id
     */
    static function get_image( $term_id ) {

        $meta = get_term_meta( $term_id, self::IMAGE, true );

        if ( ! $meta ) {
            return 'http://2.gravatar.com/avatar/53d8710f31508b6b3a8e014835f380e0?s=96&d=mm&r=g';
        }

        return home_url( $meta );
    }

    /**
     * Retorna os membros da comunidade
     *
     * @param $term_id
     */
    static function get_members( $term_id ) {
        return get_term_meta( $term_id, self::MEMBER );
    }


    /**
     * Retorna os seguidores da comunidade
     *
     * @param $term_id
     */
    static function get_follows( $term_id ) {
        return get_term_meta( $term_id, self::MEMBER_FOLLOW );
    }

    /**
     * Retorna os seguidores da comunidade
     *
     * @param $term_id
     */
    static function get_requests( $term_id ) {
        return get_term_meta( $term_id, self::MEMBER_REQUEST );
    }

    /**
     * Retorna o tipo da comunidade
     *
     * @param $term_id
     */
    static function get_type( $term_id ) {
        return get_term_meta( $term_id, self::TYPE, true );
    }

    /**
     * Retorna entidade da comunidade por objeto da sessão
     * @return array|RHSComunity
     */
    function get_comunity_by_request() {

        if ( empty( get_queried_object()->term_id ) ) {
            return array();
        }

        if ( ! get_current_user_id() ) {
            return array();
        }

        return $this->get_comunity_by_param_id( get_queried_object()->term_id, get_current_user_id() );
    }


    /**
     * Retorna entidade da comunidade por parâmentros
     *
     * @param $term_id
     * @param $user_id
     *
     * @return RHSComunity
     */
    function get_comunity_by_param_id( $term_id, $user_id ) {
        return $this->get_comunity_by_user( get_term( $term_id, self::TAXONOMY ), $user_id );
    }

    /**
     * Retorna entidade da comunidade por id do usuário
     *
     * @param WP_Term $comunity
     * @param int $user_id
     *
     * @return RHSComunity
     */
    function get_comunity_by_user( WP_Term $comunity, $user_id ) {
        return new RHSComunity( $comunity, get_userdata( $user_id ) );
    }


    /**
     * Checa se tem permissão de ver tela de comunidades
     * @return bool
     */
    function can_see_comunities() {

        if ( get_current_user_id() ) {
            return true;
        }

        return false;

    }

    /**
     * (Ajax) de interação
     */
    function ajax_comunity_action() {

        if ( ! empty( $_POST['type'] ) ) {

            $user_id = ! empty( $_POST['user_id'] ) ? $_POST['user_id'] : get_current_user_id();
            $term_id = ! empty( $_POST['term_id'] ) ? $_POST['term_id'] : get_queried_object()->term_id;

            $comunity = $this->get_comunity_by_param_id( $term_id, $user_id );

            $this->clear_messages();
            $data = array();

            $name_user = ! empty( $_POST['user_out'] ) ? get_userdata( $user_id )->display_name : 'Você';

            switch ( $_POST['type'] ) {
                case 'enter':

                    if ( $comunity->can_enter() ) {
                        self::add_user_comunity( $term_id, $user_id );
                        $this->set_messages( $name_user . ' entrou na ' . $comunity->get_name(),
                            false, 'success' );
                    } else {
                        $this->set_messages( $name_user . ' não pode entrar na ' . $comunity->get_name(),
                            false, 'error' );
                    }

                    $data['refresh'] = true;

                    break;
                case 'leave':

                    if ( $comunity->can_leave() ) {
                        self::delete_user_comunity( $term_id, $user_id );
                        $this->set_messages( $name_user . ' saiu da ' . $comunity->get_name(),
                            false, 'success' );
                    } else {
                        $this->set_messages( $name_user . ' não pode sair da ' . $comunity->get_name(),
                            false, 'error' );
                    }

                    $data['refresh'] = true;

                    break;
                case 'follow':

                    if ( $comunity->can_follow() ) {

                        self::add_user_comunity_follow( $term_id, $user_id );
                        $this->set_messages( $name_user . ' começou seguiu a ' . $comunity->get_name(),
                            false, 'success' );
                    } else {
                        $this->set_messages( $name_user . ' não pode seguir a ' . $comunity->get_name(),
                            false, 'error' );
                    }

                    break;
                case 'not_follow':

                    if ( $comunity->can_not_follow() ) {
                        self::delete_user_comunity_follow( $term_id, $user_id );
                        $this->set_messages( $name_user . ' deixou de seguir a ' . $comunity->get_name(),
                            false, 'success' );
                    } else {
                        $this->set_messages( $name_user . ' não pode deixar de seguir a ' . $comunity->get_name(),
                            false, 'error' );
                    }

                    break;

                case 'request':

                    if ( $comunity->can_request() ) {
                        self::add_user_comunity_request( $term_id, $user_id );
                        $this->set_messages( $name_user . ' requisitou a entrada na ' . $comunity->get_name(),
                            false, 'success' );
                    } else {
                        $this->set_messages( $name_user . ' não pode requisitar a entrada na ' . $comunity->get_name(),
                            false, 'error' );
                    }

                    $data['refresh'] = true;

                    break;
                case 'moderate':

                    if ( $comunity->can_moderate() ) {
                        self::add_user_comunity_moderate( $term_id, $user_id );
                        $this->set_messages( $name_user . ' é o novo moderador da ' . $comunity->get_name(),
                            false, 'success' );
                    } else {
                        $this->set_messages( $name_user . ' não pode ser moderador na ' . $comunity->get_name(),
                            false, 'error' );
                    }

                    break;
                case 'not_moderate':

                    if ( $comunity->can_not_moderate() ) {
                        self::delete_user_comunity_moderate( $term_id, $user_id );
                        $this->set_messages( $name_user . ' foi removido como moderador da ' . $comunity->get_name(),
                            false, 'success' );
                    } else {
                        $this->set_messages( $name_user . ' não pode ser removido da morderação da ' . $comunity->get_name(),
                            false, 'error' );
                    }

                    break;
                case 'accept_request':

                    if ( $comunity->can_accept_request() ) {
                        self::add_user_comunity( $term_id, $user_id );
                        $this->set_messages( $name_user . ' foi aceito na ' . $comunity->get_name(),
                            false, 'success' );
                    } else {
                        $this->set_messages( $name_user . ' não pode ser aceita na ' . $comunity->get_name(),
                            false, 'error' );
                    }

                    break;
                case 'reject_request':

                    if ( $comunity->can_reject_request() ) {
                        self::delete_user_comunity( $term_id, $user_id );
                        $this->set_messages( $name_user . ' foi rejeitado da ' . $comunity->get_name(),
                            false, 'success' );
                    } else {
                        $this->set_messages( $name_user . ' não pode ser rejeitado na ' . $comunity->get_name(),
                            false, 'error' );
                    }

                    break;

                default:
                    break;
            }

            $permissions = array(
                'members'        => false,
                'enter'          => false,
                'leave'          => false,
                'follow'         => false,
                'not_follow'     => false,
                'request'        => false,
                'wait_request'   => false,
                'moderate'       => false,
                'not_moderate'   => false,
                'accept_request' => false,
                'reject_request' => false
            );

            $comunity = $this->get_comunity_by_param_id( $term_id, $user_id );

            foreach ( $permissions as $permission => $value ) {

                if ( ! method_exists( $comunity, 'can_' . $permission ) ) {
                    echo $permission;
                    exit;
                }

                $data['permissions'][ $permission ] = $comunity->{'can_' . $permission}();
            }

            $data['messages'] = $this->messages();


            $this->clear_messages();
        }

        echo json_encode( $data );
        exit;

    }

    /**
     * (Ajax) Busca membros para adicionar a comunidade
     */
    function ajax_complete_comunity_members() {

        $data = array( "query" => "Unit", 'suggestions' => array() );

        if ( ! $_POST['comunity_id'] || ! $_POST['string'] ) {

            $data['suggestions'][] = array(
                'data'  => 0,
                'value' => 'Nenhum membro encontrado.'
            );

            echo json_encode( $data );
            exit;
        }

        $users = new WP_User_Query( array(
            'search'         => '*' . esc_attr( $_POST['string'] ) . '*',
            'search_columns' => array(
                'user_nicename'
            ),
            'number'         => 7,
            'orderby'        => 'display_name',
        ) );

        foreach ( $users->results as $user ) {

            $data['suggestions'][] = array(
                'data'  => $user->ID,
                'value' => $user->display_name
            );
        }

        echo json_encode( $data );
        exit;

    }

    /**
     * (Ajax) Adiciona membro a comunidade
     */
    function ajax_comunity_action_add_member() {

        $this->clear_messages();

        if ( ! $_POST['user_id'] || ! $_POST['comunity_id'] ) {

            $this->set_messages( 'Faltando informações para adicionar o membro',
                false, 'error' );

            $data['messages'] = $this->messages();

            echo json_encode( $data );
            exit;
        }

        $comunity = $this->get_comunity_by_param_id( $_POST['comunity_id'], $_POST['user_id'] );

        if ( $comunity->is_member() ) {
            $this->set_messages( 'Esse membro já faz parte da comunidade',
                false, 'error' );

            $data['messages'] = $this->messages();

            echo json_encode( $data );
            exit;
        }

        $return = self::add_user_comunity( $_POST['comunity_id'], $_POST['user_id'] );

        $user     = new RHSUser( get_userdata( $_POST['user_id'] ) );
        $comunity = $this->get_comunity_by_param_id( $_POST['comunity_id'], $_POST['user_id'] );

        $data['user'] = array(
            'user_id'     => $_POST['user_id'],
            'comunity_id' => $_POST['comunity_id'],
            'avatar'      => $user->get_avatar(),
            'name'        => $user->get_name(),
            'local'       => $user->get_city() . ' / ' . $user->get_state_uf(),
            'date'        => $user->get_date_registered( 'Y' ),
            'buttons'     =>
                $comunity->get_button_moderate( 'Adicionar como moderador' ) .
                $comunity->get_button_not_moderate( 'Remover como moderador' ) .
                $comunity->get_button_leave( 'Remover da comunidade' ) .
                $comunity->get_button_accept_request() .
                $comunity->get_button_reject_request()
        );

        $this->set_messages( $user->get_name() . ' foi adicionado',
            false, 'error' );

        $data['messages'] = $this->messages();

        echo json_encode( $data );
        exit;
    }

}

global $RHSComunities;
$RHSComunities = new RHSComunities();