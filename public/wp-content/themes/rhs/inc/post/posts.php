<?php

class RHSPosts extends RHSMenssage {

    private static $instance;

    function __construct($postID = null) {

        add_action( 'admin_menu', array( &$this, 'remove_meta_boxes') );
        add_action('wp_ajax_get_tags', array( &$this, 'get_tags' ) );
        add_filter('the_editor',array( &$this, 'wpse57907_add_placeholder'));
        add_filter( 'mce_external_plugins', array( &$this, 'add_mce_placeholder_plugin') );

        if ( empty ( self::$instance ) ) {
            add_filter('pre_get_posts', array( &$this, 'pre_get_posts' ) );
            $this->trigger_by_post();
        }

        self::$instance = true;
    }


    function wpse57907_add_placeholder( $html ){
        $html = preg_replace('/<textarea/', '<textarea placeholder="Escreva seu texto aqui." ', $html);
        return $html;
    }



    function add_mce_placeholder_plugin( $plugins ){

        // Optional, check for specific post type to add this
        // if( 'my_custom_post_type' !== get_post_type() ) return $plugins;

        $plugins[ 'placeholder' ] = get_template_directory_uri() . '/assets/js/mce.placeholder.js';

        return $plugins;
    }

    function remove_meta_boxes() {
        remove_meta_box('commentsdiv', 'post', 'normal');
        remove_meta_box('trackbacksdiv', 'post', 'normal');
        remove_meta_box('postcustom', 'post', 'normal');
        remove_meta_box('commentstatusdiv', 'post', 'normal');
        remove_meta_box('authordiv', 'post', 'normal');
        remove_meta_box('tagsdiv-comunity-category', 'post', 'normal');
    }
    
    function pre_get_posts($wp_query) {
        
        if ( $wp_query->is_main_query() && $wp_query->get( 'rhs_login_tpl' ) == RHSRewriteRules::POST_URL ) {

			if ( $wp_query->get( 'rhs_edit_post' ) && is_numeric($wp_query->get( 'rhs_edit_post' )) ) {
            
                $wp_query->set('p', $wp_query->get( 'rhs_edit_post' ));
            
            } else {
                $u = wp_get_current_user();
                $wp_query->set('author', $u->ID);
            
            }

		}
        
    }

    /**
     * @return RHSPost
     */
    function set_by_post(){

        $postObj = new RHSPost();
        $postObj->setTitle(!empty($_POST['title']) ? $_POST['title'] : '');
        $postObj->setContent(!empty($_POST['public_post']) ? $_POST['public_post'] : '');
        $postObj->setStatus(!empty($_POST['status']) ? $_POST['status'] : '');
        $postObj->setCategoriesId(!empty($_POST['category']) ? array($_POST['category']) : '');
        $postObj->setState(!empty($_POST['estado']) ?  $_POST['estado'] : '');
        $postObj->setCity(!empty($_POST['municipio']) ? $_POST['municipio'] : '');
        $postObj->setTags(!empty($_POST['tags']) ? $_POST['tags'] : '');
        $postObj->setFeaturedImage(!empty($_POST['tags']) ? $_POST['img_destacada'] : '');

        return $postObj;

    }
    
    private function trigger_by_post() {

        if ( ! empty( $_POST['post_user_wp'] ) && $_POST['post_user_wp'] == $this->getKey() ) {

            if ( ! $this->validate_by_post() ) {
                return;
            }

            $postObj = new RHSPost();
            $postObj->setId($_POST['post_ID']);
            $postObj->setTitle($_POST['title']);
            $postObj->setContent($_POST['public_post']);
            $postObj->setStatus($_POST['status']);
            $postObj->setAuthorId(get_current_user_id());
            $postObj->setCategoriesId($_POST['category']);
            $postObj->setState($_POST['estado']);
            $postObj->setCity($_POST['municipio']);
            $postObj->setTags($_POST['tags']);
            $postObj->setFeaturedImageId($_POST['img_destacada']);
            
            $this->insert($postObj);
        }
    }

    function insert(RHSPost $post) {

        $data = array(
            'post_title'    => wp_strip_all_tags( $post->getTitle() ),
            'post_content'  => $post->getContent(),
            'post_status'   => $post->getStatus(),
            'post_author'   => $post->getAuthorId(),
            'post_category' => $post->getCategoriesId(),
            'comment_status' => 'open'
        );
        
        if ($post->getId()){

            $postObj = new RHSPost($post->getId());

            if($postObj->getStatus() == 'draft' && $data['post_status'] == 'publish'){
                $data['post_status'] = RHSVote::VOTING_QUEUE;
                $post->setStatus(RHSVote::VOTING_QUEUE);
            } else {
                unset($data['post_status']);
            }

            $data['ID'] = $post->getId();

            $return = wp_update_post( $data, true );
        } else {

            $data['post_status'] = ($data['post_status'] == 'draft' ) ? 'draft' : RHSVote::VOTING_QUEUE;

            $return = wp_insert_post( $data, true );
        }

        if ( $return instanceof WP_Error ) {
            $post->setError($return);
        } else {
            $post->setId($return);
        }

        if($post->getError()){
            foreach ($post->getError() as $error ) {
                $this->set_messages( $error, false, 'error' );
            }

            return;
        }
        
        add_post_ufmun_meta($post->getId(), $post->getCity(), $post->getState());
        wp_set_post_terms( $post->getId(), $post->getTags() );
        set_post_thumbnail($post->getId(), $post->getFeaturedImageId());

        if ($post->getStatus() == RHSVote::VOTING_QUEUE) {
            wp_redirect(get_permalink($post->getId()));
        } else {
            $this->set_messages(   '<i class="fa fa-check "></i> Rascunho salvo com sucesso! <a href="'.home_url('minhas-postagens').'">Clique aqui</a>  para ver a listagem de seus posts', false, 'success' );
            wp_redirect(get_home_url() . '/' . RHSRewriteRules::POST_URL . '/' . $post->getId());
        } 
        
        exit;
        
    }

    private function validate_by_post() {

        $this->clear_messages();

        if ( ! array_key_exists( 'title', $_POST ) ) {
            $this->set_messages('<i class="fa fa-exclamation-triangle "></i> Preencha o seu email!', false, 'error' );

            return false;
        }

        if ( ! get_current_user_id() ) {
            $this->set_messages(  '<i class="fa fa-exclamation-triangle "></i> Efetue o login para realizar um post', false, 'error'  );

            return false;
        }

        if ( ! array_key_exists( 'public_post', $_POST ) ) {
            $this->set_messages(   '<i class="fa fa-exclamation-triangle "></i> Escreva o conteúdo do post!', false, 'error' );

            return false;
        }



        if ( ! array_key_exists( 'category', $_POST ) ) {
            $this->set_messages(   '<i class="fa fa-exclamation-triangle "></i> Selecione uma categoria!', false, 'error' );

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
    static function minhasPostagens(){
        global $current_user;
        wp_get_current_user();
        $author_query = array('posts_per_page' => '-1','author' => $current_user->ID, 'post_status' => array('draft', 'publish', 'voting-queue'));
        $author_posts = new WP_Query($author_query);
        global $RHSVote;
        while($author_posts->have_posts()) : $author_posts->the_post();
        
            $post_status = get_post_status(get_the_ID());
            
            if ($post_status == 'publish') {
                $status_label = 'Publicado';
            } elseif ($post_status == 'draft') {
                $status_label = 'Rascunho';
            } elseif (array_key_exists($post_status, $RHSVote->get_custom_post_status())) {
                $status_label = $RHSVote->get_custom_post_status()[$post_status]['label'];
            } else {
                $status_label = $post_status;
            }
            
            
        ?>
            <tr>
                <td><?php the_title(); ?></td>
                <td><?php the_time('D, d/m/Y - H:i'); ?></td>
                <td></td>
                <td>
                    <?php
                        if ( comments_open() ) :
                          comments_popup_link( '0', '1 ', '%', '', '<i class="fa fa-ban" aria-hidden="true"></i>');
                        endif;
                    ?>
                </td>
                <td>
                <?php
                    $votos = $RHSVote->get_total_votes(get_the_ID());
                    if($votos <= 0){
                        echo '0';
                    }else {
                        echo $votos;
                    }
                ?>
                </td>
                <td>
                    <?php echo $status_label; ?>
                    
                    <?php if(current_user_can('edit_post', get_the_ID())): ?>
                        <a href="<?php echo get_home_url() . '/' . RHSRewriteRules::POST_URL . '/' . get_the_ID(); ?>">
                            (Editar)
                        </a>
                    <?php endif; ?>
                    
                </td>
            </tr>   
        <?php           
        endwhile;
    }
}

global $RHSPosts;
$RHSPosts = new RHSPosts();
