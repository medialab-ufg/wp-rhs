<?php
// TODO Mudar o nome disso pra SocialStats
class RHSNetwork {

    const META_KEY_FACEBOOK = 'rhs_data_facebook';
    const META_KEY_TWITTER = 'rhs_data_twitter';
    const META_KEY_WHATSAPP = 'rhs_data_whatsapp';
    const META_KEY_VIEW = 'rhs_data_view';
    const META_KEY_PRINT = 'rhs_data_print';

    public function __construct() {
        add_action('wp_ajax_nopriv_rhs_add_stats_data', array( &$this, 'add_data' ) );
        add_action('wp_ajax_rhs_add_stats_data', array( &$this, 'add_data' ) );
        add_action('wp_body_init', array( &$this, 'js_api_network'));
        add_action('wp_body_init', array( &$this, 'js_api_network'), 10, 1);
        add_action('wp_enqueue_scripts', array( &$this,'network_js'));
    }

    public function network_js(){
        if(is_single()){
            wp_enqueue_script('RHSNetworkJS', get_template_directory_uri() . '/inc/network/network.js', '','', true);
            wp_localize_script('RHSNetworkJS', 'RHSNetworkJS', array(
                'META_KEY_FACEBOOK' => 'rhs_data_facebook',
                'META_KEY_TWITTER' => 'rhs_data_twitter',
                'META_KEY_WHATSAPP' => 'rhs_data_whatsapp',
                'META_KEY_VIEW' => 'rhs_data_view',
                'META_KEY_PRINT' => 'rhs_data_print'
            ));
        }
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

    private function type($type){

        if($type == '' && !empty($_REQUEST['type'])){
            $type = $_REQUEST['type'];
        }

        return $type;

    }

    public function add_data($post_id = 0, $type = ''){

        $post_id = $this->get_id($post_id);
        $type = $this->type($type);

        if(!$type){
            return;
        }


        if ( ! add_post_meta( $post_id, $type, 1, true ) ) {

            $post_meta = get_post_meta( $post_id, $type, true );
            ++$post_meta;

            update_post_meta( $post_id, $type, $post_meta );
        }
        
        do_action('rhs_add_network_data', $post_id, $type);
        
        if(!empty($_REQUEST['json'])){
            echo json_encode( true );
            exit;
        }
    }


    public function get_data($post_id = 0, $type = ''){

        $post_id = $this->get_id($post_id);
        $type = $this->type($type);

        if(!$type){
            return 0;
        }

        return get_post_meta( $post_id, $type, true );
    }

}

global $RHSNetwork;
$RHSNetwork = new RHSNetwork();
