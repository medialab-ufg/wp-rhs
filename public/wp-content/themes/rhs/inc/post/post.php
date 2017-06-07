<?php

class RHSPost extends RHSMenssage {

    private static $instance;
    private $post;

    function __construct($postID = null) {

        if ( !empty($postID) && is_numeric($postID) && current_user_can('edit_post', $postID) ) {
            $this->post = get_post($postID);
        }

        if ( empty ( self::$instance ) ) {
            add_action('wp_ajax_get_tags', array( &$this, 'get_tags' ) );
            add_filter('pre_get_posts', array( &$this, 'pre_get_posts' ) );
            $this->trigger_by_post();
        }

        self::$instance = true;
    }

    function is_post(){
        return (bool) $this->post;
    }

    function get_post(){

        return $this->post;
    }

    function get_post_data($field){

        if($field == 'category_json'){

            $string = 'data: [';

            foreach ( get_categories() as $categori ) :
                $string .= "{id:".$categori->term_id.", name:'".$categori->cat_name."'},";
            endforeach;

            $string .= '],';

            return $string;
        }

        if(empty($this->post->ID)){
            return '';
        }

        if($field == 'tags_json'){

            $tagsData = wp_get_post_tags($this->post->ID);
            $tagsDataArr = array();

            foreach ($tagsData as $tag){
                $tagsDataArr[] = $tag->name;
            }

            if($tagsDataArr){
                return "['".implode("', '",$tagsDataArr)."']";
            } else {
                return '';
            }

        }

        if($field == 'category'){

            $categoriesData = wp_get_post_categories($this->post->ID);

            if($categoriesData){
                return "['".implode("', '",$categoriesData)."']";
            } else {
                return '';
            }

        }

        if($field == 'state'){
            $cur_ufmun = get_post_ufmun($this->post->ID);

            if (is_numeric($cur_ufmun['uf']['id'])){
                return $cur_ufmun['uf']['id'];
            }
        }

        if($field == 'city'){
            $cur_ufmun = get_post_ufmun($this->post->ID);

            if (is_numeric($cur_ufmun['mun']['id'])){
                return $cur_ufmun['mun']['id'];
            }
        }

        if(!empty($this->post->{$field})){
            return $this->post->{$field};
        }

        return '';
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
    
    private function trigger_by_post() {

        if ( ! empty( $_POST['post_user_wp'] ) && $_POST['post_user_wp'] == $this->getKey() ) {

            if ( ! $this->validate_by_post() ) {
                return;
            }
            
            $this->insert(
                $_POST['post_ID'],
                $_POST['title'],
                $_POST['public_post'],
                ( $_POST['status'] == 'draft' ) ? 'draft' : RHSVote::VOTING_QUEUE,
                get_current_user_id(),
                $_POST['category'],
                $_POST['estado'],
                $_POST['municipio'],
                $_POST['tags'],
                $_POST['img_destacada'] );
        }
    }

    public function insert( $ID, $title, $content, $status, $authorId, $category, $state = '', $city = '', array $tags = array(), $featured_image ) {

        $dataPost = array(
            'post_title'    => wp_strip_all_tags( $title ),
            'post_content'  => $content,
            'post_status'   => $status,
            'post_author'   => $authorId,
            'post_category' => (is_array($category)) ? $category : array($category)
        );
        
        if (is_numeric($ID))
            $dataPost['ID'] = $ID;

        $post_ID = wp_insert_post( $dataPost, true );

        if ( $post_ID instanceof WP_Error ) {

            foreach ( $post_ID->get_error_messages() as $error ) {
                $this->set_messages( $error, false, 'error' );
            }

            return;

        }
        
        add_post_ufmun_meta($post_ID, $city, $state);
        wp_set_post_terms( $post_ID, $tags );
        set_post_thumbnail($post_ID, $featured_image);

        if ($status == RHSVote::VOTING_QUEUE) {
            wp_redirect(get_permalink($post_ID));
        } else {
            $this->set_messages(   '<i class="fa fa-check "></i> Rascunho salvo com sucesso! <a href="'.home_url('minhas-postagens').'">Clique aqui</a>  para ver a listagem de seus posts', false, 'success' );
            wp_redirect(get_home_url() . '/' . RHSRewriteRules::POST_URL . '/' . $post_ID);
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

    public function get_tags() {

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

global $RHSPost;
$RHSPost = new RHSPost();
