<?php

class RHSNetwork {

    const META_KEY_FACEBOOK = 'rhs_data_facebook';
    const META_KEY_TWITTER = 'rhs_data_twitter';
    const META_KEY_VIEW = 'rhs_data_view';

    public function __construct() {
        add_action('wp_ajax_nopriv_add_data_view', array( &$this, 'add_data_view' ) );
        add_action('save_post', array( &$this, 'javascript_network'), 10, 1);
    }

    public function javascript_network($postID){

        ?>
        <script>
        var postID = <?php echo $postID; ?>
        </script>
        <script type="text/javascript" src="<?php echo get_template_directory_uri(). 'inc/network/network.js' ?>" ></script>
        <?php

    }

    private function get_id($post_id){

        if($post_id == 0){
            $post_id = get_the_ID();
        }

        if($post_id == 0 && !empty($_REQUEST['postID'])){
            $post_id = $_REQUEST['postID'];
        }

        return $post_id;

    }

    public function add_data_facebook($post_id = 0){

        $post_id = $this->get_id($post_id);

        if ( ! add_post_meta( $post_id, META_KEY_FACEBOOK, 1, true ) ) {

            $post_meta = get_post_meta( $post_id, META_KEY_FACEBOOK, true );
            ++$post_meta;

            update_post_meta( $post_id, META_KEY_FACEBOOK, $post_meta );
        }

        if(!empty($_REQUEST['json'])){
            echo json_encode( true );
        }
    }

    public function add_data_twitter($post_id = 0){

        $post_id = $this->get_id($post_id);

        if ( ! add_post_meta( $post_id, META_KEY_TWITTER, 1, true ) ) {

            $post_meta = get_post_meta( $post_id, META_KEY_TWITTER, true );
            ++$post_meta;

            update_post_meta( $post_id, META_KEY_TWITTER, $post_meta );
        }

        if(!empty($_REQUEST['json'])){
            echo json_encode( true );
        }

    }

    public function add_data_view($post_id = 0){

        $post_id = $this->get_id($post_id);

        if ( ! add_post_meta( $post_id, META_KEY_VIEW, 1, true ) ) {

            $post_meta = get_post_meta( $post_id, META_KEY_VIEW, true );
            ++$post_meta;

            update_post_meta( $post_id, META_KEY_VIEW, $post_meta );
        }

        if(!empty($_REQUEST['json'])){
            echo json_encode( true );
        }

    }

    public function get_data_view($post_id = 0){

        $post_id = $this->get_id($post_id);

        return get_post_meta( $post_id, META_KEY_VIEW, true );
    }

    public function get_data_twitter($post_id = 0){

        $post_id = $this->get_id($post_id);

        return get_post_meta( $post_id, META_KEY_TWITTER, true );
    }

    public function get_data_facebook($post_id = 0){

        $post_id = $this->get_id($post_id);

        return get_post_meta( $post_id, META_KEY_FACEBOOK, true );
    }

}

global $RHSNetwork;
$RHSNetwork = new RHSNetwork();