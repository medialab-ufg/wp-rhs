<?php

class RHSPost extends RHSMenssage {

    private static $instance;

    function __construct() {

        if ( empty ( self::$instance ) ) {
            add_action('wp_ajax_get_tags', array( &$this, 'get_tags' ) );
            $this->trigger_by_post();

        }

        self::$instance = true;
    }

    private function trigger_by_post() {

        if ( ! empty( $_POST['post_user_wp'] ) && $_POST['post_user_wp'] == $this->getKey() ) {

            if ( ! $this->validate_by_post() ) {
                return;
            }

            $this->insert(
                $_POST['title'],
                $_POST['public_post'],
                ( $_POST['type'] == 'draft' ) ? 'draft' : 'publish',
                get_current_user_id(),
                $_POST['category'],
                $_POST['estado'],
                $_POST['municipio'],
                $_POST['tags'] );
        }
    }

    public function insert( $title, $content, $status, $authorId, $category, $state = '', $city = '', array $tags = array() ) {

        $dataPost = array(
            'post_title'    => wp_strip_all_tags( $title ),
            'post_content'  => $content,
            'post_status'   => $status,
            'post_author'   => $authorId,
            'post_category' => array($category)
        );

        $post_ID = wp_insert_post( $dataPost, true );

        if ( $post_ID instanceof WP_Error ) {

            foreach ( $post_ID->get_error_messages() as $error ) {
                $this->set_messages( $error, false, 'error' );
            }

            return;

        }

        if ( ! empty( $state ) ) {
            add_post_meta( $post_ID, 'state', $state, true );
        }

        if ( ! empty( $city ) ) {
            add_post_meta( $post_ID, 'city', $city, true );
        }

        foreach ($tags as $tag){
            wp_set_post_terms( $post_ID, array($tag) );
        }

        wp_redirect(get_permalink($post_ID));
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

            return false;
        }

        if ( ! array_key_exists( 'municipio', $_POST ) ) {
            $_POST['municipio'] = '';

            return false;
        }

        if ( ! array_key_exists( 'type', $_POST ) ) {
            $_POST['type'] = '';

            return false;
        }

        if ( ! array_key_exists( 'tags', $_POST ) ) {
            $_POST['tags'] = array();

            return false;
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
                'id'   => $tag->term_id,
                'name' => trim( $tag->name )
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
        $author_query = array('posts_per_page' => '-1','author' => $current_user->ID);
        $author_posts = new WP_Query($author_query);
        global $RHSVote;
        while($author_posts->have_posts()) : $author_posts->the_post();
        ?>
            <tr>
                <td><?php the_ID(); ?></td>
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
            </tr>   
        <?php           
        endwhile;
    }
}

global $RHSPost;
$RHSPost = new RHSPost();
