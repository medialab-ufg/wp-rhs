<?php

class RHSPosts extends RHSMenssage {

    private static $instance;
    const META_DATE_ORDER = 'rhs-post-date-order';
    const META_COMUNITY = 'rhs-comunity-status';

    function __construct( $postID = null ) {

        add_action( 'admin_menu', array( &$this, 'remove_meta_boxes' ) );
        add_action( 'wp_ajax_get_tags', array( &$this, 'get_tags' ) );
        add_filter( 'the_editor', array( &$this, 'add_placeholder_editor' ) );
        add_filter( 'mce_external_plugins', array( &$this, 'add_mce_placeholder_plugin' ) );
        add_filter( 'save_post', array( &$this, 'add_meta_date' ) );

        if ( empty ( self::$instance ) ) {
            add_filter( 'pre_get_posts', array( &$this, 'pre_get_posts' ) );
            add_action( 'wp_footer', array( &$this, 'add_message_script_footer'));
        }

        self::$instance = true;
    }

    /*====================================================================================================
                                               ADMINISTAÇÃO
   ======================================================================================================*/

    /**
     * Remove checkboxs padrões do wordpress que não será utilizado no sistema
     */
    function remove_meta_boxes() {
        remove_meta_box( 'commentsdiv', 'post', 'normal' );
        remove_meta_box( 'trackbacksdiv', 'post', 'normal' );
        remove_meta_box( 'postcustom', 'post', 'normal' );
        remove_meta_box( 'commentstatusdiv', 'post', 'normal' );
        remove_meta_box( 'authordiv', 'post', 'normal' );
        remove_meta_box( 'tagsdiv-comunity-category', 'post', 'normal' );
    }

    /*====================================================================================================
                                                CLIENTE
    ======================================================================================================*/

    /**
     * Adiciona alerta de mensagens no footer
     */
    function add_message_script_footer(){

        if(!$this->get_alert()){
            return;
        }

        ?>
        <script>

            jQuery( function( $ ) {
                swal({title:'<?php echo $this->get_alert(); ?>', html: true});
            });

        </script>
        <?php

        $this->clear_alert();
    }

    /**
     * Adiciona um placehoder(Texto de instrução dentro do input) no wp_editor
     *
     * @param $html
     *
     * @return mixed
     */
    function add_placeholder_editor( $html ) {
        $html = preg_replace( '/<textarea/', '<textarea placeholder="Escreva seu texto aqui." ', $html );

        return $html;
    }

    /**
     * Plugin para adicionar o placehoder
     *
     * @param $plugins
     *
     * @return mixed
     */
    function add_mce_placeholder_plugin( $plugins ) {

        // Optional, check for specific post type to add this
        // if( 'my_custom_post_type' !== get_post_type() ) return $plugins;

        $plugins['placeholder'] = get_template_directory_uri() . '/assets/js/mce.placeholder.js';

        return $plugins;
    }

    /**
     * Mundaça da query que pega os posts
     *
     * @param WP_Query $wp_query
     */
    function pre_get_posts( $wp_query ) {

        if ( $wp_query->is_main_query() && $wp_query->get( 'rhs_login_tpl' ) == RHSRewriteRules::POST_URL ) {

            if ( $wp_query->get( 'rhs_edit_post' ) && is_numeric( $wp_query->get( 'rhs_edit_post' ) ) ) {

                $wp_query->set( 'p', $wp_query->get( 'rhs_edit_post' ) );

            } else {
                $u = wp_get_current_user();
                $wp_query->set( 'author', $u->ID );

            }

        }

        if ( $wp_query->is_main_query() && $wp_query->is_home() ) {

            $wp_query->set('meta_key', self::META_DATE_ORDER);
            $wp_query->set('orderby', 'meta_value');
            $wp_query->set('order', 'DESC');
        }

    }

    /**
     * Caso redirecione ou post volte sem adicionar,
     * salva as informações no objeto para não perder tudo
     * e o usuário precisar digitar novamente
     *
     * @return RHSPost
     */
    function set_by_post() {

        $postObj = new RHSPost();
        $postObj->setTitle( ! empty( $_POST['title'] ) ? $_POST['title'] : '' );
        $postObj->setContent( ! empty( $_POST['public_post'] ) ? $_POST['public_post'] : '' );
        $postObj->setStatus( ! empty( $_POST['status'] ) ? $_POST['status'] : '' );
        $postObj->setCategoriesId( ! empty( $_POST['category'] ) ? array( $_POST['category'] ) : '' );
        $postObj->setState( ! empty( $_POST['estado'] ) ? $_POST['estado'] : '' );
        $postObj->setCity( ! empty( $_POST['municipio'] ) ? $_POST['municipio'] : '' );
        $postObj->setTags( ! empty( $_POST['tags'] ) ? $_POST['tags'] : '' );
        $postObj->setFeaturedImage( ! empty( $_POST['tags'] ) ? $_POST['img_destacada'] : '' );

        return $postObj;

    }

    /**
     * Quando enviado o formulário para salvar ou editor um postagem
     */
    public function trigger_by_post() {

        if ( ! empty( $_POST['post_user_wp'] ) && $_POST['post_user_wp'] == $this->getKey() ) {

            if ( ! $this->validate_by_post() ) {
                return;
            }

            $postObj = new RHSPost();
            $postObj->setId( $_POST['post_ID'] );
            $postObj->setTitle( $_POST['title'] );
            $postObj->setContent( $_POST['public_post'] );
            $postObj->setStatus( $_POST['status'] );
            $postObj->setAuthorId( get_current_user_id() );
            $postObj->setCategoriesId( $_POST['category'] );
            $postObj->setState( $_POST['estado'] );
            $postObj->setCity( $_POST['municipio'] );
            $postObj->setTags( $_POST['tags'] );
            $postObj->setFeaturedImageId( $_POST['img_destacada'] );
            $postObj->setComunities($_POST['comunity-status']);

            $newpost = $this->insert( $postObj );

            if($newpost->getStatus() == RHSVote::VOTING_QUEUE || $newpost->getStatus() == 'public'){
                wp_redirect( get_permalink( $newpost->getId() ) );
            } else {
                wp_redirect( home_url('minhas-postagens') );
            }

            exit;
        }
    }

    /**
     * Insere ou edita publicação
     *
     * @param RHSPost $post
     */
    function insert( RHSPost $post ) {

        /**
         * Informações para salvar/editar
         */
        $data = array(
            'post_title'     => wp_strip_all_tags( $post->getTitle() ),
            'post_content'   => $post->getContent(),
            'post_status'    => $post->getStatus(),
            'post_author'    => $post->getAuthorId(),
            'post_category'  => $post->getCategoriesId(),
            'comment_status' => 'open'
        );

        /**
         * Edição
         */
        if ( $post->getId() ) {

            /**
             * Obejtco com Informações antigas da postagem
             */
            $oldPost = new RHSPost( $post->getId() );

            if($data['post_status'] == 'draft'){
                $setStatus = 'draft';

            } else if (!in_array('public',$post->getComunities())){
                $setStatus = 'private';

            } else if ($data['post_status'] == 'publish' && !get_post_meta($post->getId(), RHSVote::META_PUBISH) ) {
                $setStatus = RHSVote::VOTING_QUEUE;

            } else if($data['post_status'] == 'publish'){
                $setStatus == 'publish';

            } else {
                /**
                 * Se o usuário estiver editando um post, que esteja na fila de votação
                 * ou que já foi publicado, mantemos o status que já está no post,
                 * por isso retiramos das informações
                 */
                $setStatus =  $oldPost->getStatus();
            }

            $data['post_status'] = $setStatus;
            $post->setStatus($setStatus);

            $data['ID'] = $post->getId();

            $return = wp_update_post( $data, true );
        } /**
         * Inserção
         */
        else {

            if($data['post_status'] == 'draft'){
                $setStatus = 'draft';

            } else if ($post->getComunities() && !in_array('public',$post->getComunities())){
                $setStatus = 'private';

            } else {
                $setStatus = RHSVote::VOTING_QUEUE;
            }

            $data['post_status'] = $setStatus;
            $post->setStatus($setStatus);
            $return = wp_insert_post( $data, true );

        }

        /**
         * Se não conseguiu salvar/editar, salva com os erros
         */
        if ( $return instanceof WP_Error ) {
            $post->setError( $return );
        } else {

            // Notificação
            if(!$post->getId()){
                do_action( 'rhs_notify_new_post_from_user', array('user_id'=>$post->getAuthorId(), 'post_id'=>$return) );
            }

            $post->setId( $return );
        }

        if ( $post->getError() ) {
            foreach ( $post->getError() as $error ) {
                $this->set_messages( $error, false, 'error' );
            }

            return;
        }

        /**
         * Salvar/edita informações Cidade/Estado
         */
        add_post_ufmun_meta( $post->getId(), $post->getCity(), $post->getState() );

        /**
         * Salvar/edita tags
         */
        wp_set_post_terms( $post->getId(), $post->getTags() );

        $comunities = $post->getComunities();

        $key = array_search('public', $comunities);

        if(strlen($key)){
            unset($comunities[$key]);
        }

        wp_set_post_terms( $post->getId(), $comunities, RHSComunities::TAXONOMY );

        /**
         * Salvar/edita a thumbnail
         */
        set_post_thumbnail($post->getId(), $post->getFeaturedImageId());


        if(!empty($data['ID'])){
            $this->set_alert('<i class="fa fa-check"></i> Post editado!');
        } else if($post->getStatus() == 'draft'){
            $this->set_alert('<i class="fa fa-check"></i> Rascunho salvo!');
        } else if($post->getStatus() == 'public'){
            $this->set_alert('<i class="fa fa-check"></i> Post publicado!');
        } else if($post->getStatus() == 'private') {
            $this->set_alert('<i class="fa fa-check"></i> Post privado publicado nas comunidades!');
        } else if($post->getStatus() == RHSVote::VOTING_QUEUE) {
            $this->set_alert('<i class="fa fa-check"></i> Post publicado na fila de votação, ele será publicado na página incial quando atingir '.get_option( 'vq_votes_to_approval' ).' votos!');
        }

        return $post;

    }

    private function validate_by_post() {

        $this->clear_messages();

        if ( ! array_key_exists( 'title', $_POST ) ) {
            $this->set_messages( '<i class="fa fa-exclamation-triangle "></i> Preencha o seu email!', false, 'error' );

            return false;
        }

        if ( ! get_current_user_id() ) {
            $this->set_messages( '<i class="fa fa-exclamation-triangle "></i> Efetue o login para realizar um post',
                false, 'error' );

            return false;
        }

       if ( ! array_key_exists( 'comunity-status', $_POST ) ) {
           $this->set_messages( '<i class="fa fa-exclamation-triangle "></i> Selecione o status do post!', false,
               'error' );

           return false;
       }

        if ( ! array_key_exists( 'public_post', $_POST ) ) {
            $this->set_messages( '<i class="fa fa-exclamation-triangle "></i> Escreva o conteúdo do post!', false,
                'error' );

            return false;
        }


        if ( ! array_key_exists( 'category', $_POST ) ) {
            $this->set_messages( '<i class="fa fa-exclamation-triangle "></i> Selecione uma categoria!', false,
                'error' );

            return false;
        }

        if ( ! array_key_exists( 'estado', $_POST ) ) {
            $_POST['estado'] = '';

        }

        if ( ! array_key_exists( 'municipio', $_POST ) ) {
            $_POST['municipio'] = '';

        }

        if ( ! array_key_exists( 'post_ID', $_POST ) ) {
            $_POST['post_ID'] = null;

        }

        if ( ! array_key_exists( 'tags', $_POST ) ) {
            $_POST['tags'] = array();

        }

        return true;

    }

    function get_tags() {

        $result_tags = array();

        if ( empty( $_POST['query'] ) ) {
            echo json_encode( $result_tags );
            exit;
        }

        $tags = get_tags( array( 'name__like' => $_POST['query'] ) );

        foreach ( $tags as $tag ) {
            $result_tags[] = array(
                'id'   => $tag->name,
                'name' => $tag->name
            );
        }

        echo json_encode( $result_tags );
        exit;
    }


    /*
    * Function que lista as postagens na página minhas-postagens
    */
    static function minhasPostagens() {
        global $current_user;
        global $RHSNetwork;
        wp_get_current_user();
        $author_query = array(
            'posts_per_page' => '-1',
            'author'         => $current_user->ID,
            'post_status'    => array( 'draft', 'publish', RHSVote::VOTING_QUEUE, 'private' )
        );
        $author_posts = new WP_Query( $author_query );
        global $RHSVote;
        while ( $author_posts->have_posts() ) : $author_posts->the_post();

            $post_status = get_post_status( get_the_ID() );

            if ( $post_status == 'publish' ) {
                $status_label = 'Publicado';
            } elseif ( $post_status == 'draft' ) {
                $status_label = 'Rascunho';
            } else if ( $post_status == 'private' ) {
                $status_label = 'Privado';
            } elseif ( array_key_exists( $post_status, $RHSVote->get_custom_post_status() ) ) {
                $status_label = $RHSVote->get_custom_post_status()[ $post_status ]['label'];
            } else {
                $status_label = $post_status;
            }

            ?>
            <tr>
                <td>
                    <a href="<?php echo get_permalink( get_the_ID() ) ?>">
                        <?php the_title(); ?>
                    </a>
                </td>
                <td>
                    <?php the_time( 'D, d/m/Y - H:i' ); ?>
                </td>
                <td>
                    <?php echo $RHSNetwork->get_data( get_the_ID(), RHSNetwork::META_KEY_VIEW ); ?>
                </td>
                <td>
                    <?php
                    if ( comments_open() ) :
                        comments_popup_link( '0', '1 ', '%', '', '<i class="fa fa-ban" aria-hidden="true"></i>' );
                    endif;
                    ?>
                </td>
                <td>
                    <?php
                    $votos = $RHSVote->get_total_votes( get_the_ID() );
                    if ( $votos <= 0 ) {
                        echo '0';
                    } else {
                        echo $votos;
                    }
                    ?>
                </td>
                <td>
                    <?php echo $status_label; ?>
                    <?php if ( current_user_can( 'edit_post', get_the_ID() ) ): ?>
                        <a href="<?php echo get_home_url() . '/' . RHSRewriteRules::POST_URL . '/' . get_the_ID(); ?>">
                            (Editar)
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php
        endwhile;
    }
    
    function add_meta_date( $postID ) {

        $data = get_post($postID);
        
        /**
         * Quando cria o post pela primeira vez e ele ainda não tem nenhum voto
         * adiciona o metadado para ele não ficar vazio e não gerar inconsistencia
         * add_post_meta só adicinoa, se o metadado já exitir, não faz nada.
         */ 
        add_post_meta( $postID, self::META_DATE_ORDER, $data->post_date, true );
    }

    function update_date_order($postID){
        update_post_meta( $postID, self::META_DATE_ORDER, current_time('mysql') );
    }
}

global $RHSPosts;
$RHSPosts = new RHSPosts();
