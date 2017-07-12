<?php

class RHSComunity extends RHSMenssage {

    const TAXONOMY = 'comunity-category';
    const IMAGE = 'rhs-comunity-image';
    const TYPE = 'rhs-comunity-type';
    const TYPE_OPEN = 'open';
    const TYPE_PRIVATE = 'private';
    const TYPE_HIDE = 'hide';
    const MEMBER = 'rhs-comunity-member';
    const MEMBER_FOLLOW = 'rhs-comunity-member-follow';
    static $comunity = array();

    public function __construct() {

        add_action('init', array( &$this, "register_taxonomy" ));

        add_action( self::TAXONOMY.'_edit_form_fields', array( &$this, 'edit_category_field') );
        add_action( self::TAXONOMY.'_add_form_fields',array( &$this, 'new_category_field') );
        add_action( 'edited_'.self::TAXONOMY, array( &$this,'save_tax_meta'), 10, 2 );
        add_action( 'create_'.self::TAXONOMY, array( &$this,'save_tax_meta'), 10, 2 );
        add_action( 'admin_footer', array ( $this, 'add_script' ) );

        add_action('add_meta_boxes', array( &$this, "add_meta_box"));
        add_action( 'publish_post', 'set_category_by_default', 5, 1 );
        //add_filter( 'wp_insert_post_data' , array( &$this,'filter_post_data') , '99', 2 );
    }



    function set_category_by_default( $data , $postarr ) {

        //$data['post_category'] = array($_POST['post_comunity']);
        return $data;
    }

    function add_meta_box() {
        add_meta_box('category_comunity', 'Comunidades', array( &$this, 'meta_box_response'), 'post', 'side', 'default');
    }

    function meta_box_response($post) {

        $data = array(
            'taxonomy' => self::TAXONOMY,
            'hide_empty' => 0,
            'pad_counts' => true
        );

        $categories = get_categories($data);

        $categories_post = wp_get_post_terms($post->ID, self::TAXONOMY);

        $comunity_post = array();

        foreach ($categories_post as $category_post){
            if($category_post->taxonomy == self::TAXONOMY){
                $comunity_post[] = $category_post->term_id;
            }
        }

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
                    <?php foreach ($categories as $category){ ?>
                        <?php
                         $checked = '';

                         if($comunity_post && in_array($category->term_id, $comunity_post)){
                             $checked = 'checked';
                         }

                        ?>
                    <li id="comunity-<?php echo $category->term_id ?>" class="popular-category">
                        <label class="selectit">
                            <input <?php echo $checked; ?> value="<?php echo $category->name ?>" type="radio" name="post_comunity" id="in-comunity-<?php echo $category->term_id ?>" /> <?php echo $category->name ?>
                        </label>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <?php
        return '';
    }

    function register_taxonomy()
    {
        $labels = array(
            'name' =>'Comunidades',
            'singular_name' => 'Comunidade',
            'search_items' => 'Buscar Comunidade',
            'all_items' => 'Todas as Comunidades',
            'parent_item' => 'Comunidades Acima',
            'parent_item_colon' => 'Comunidades Acima:',
            'edit_item' => 'Editar Comunidade',
            'update_item' => 'Atualizar Comunidades',
            'add_new_item' => 'Adicionar Nova Comunidade',
            'new_item_name' => 'Novo nome da Comunidade',
        );
        register_taxonomy(
            self::TAXONOMY,
            array('post'),
            array(
                'hierarchical' => true,
                'labels' => $labels,
                'show_ui' => true,
                'query_var' => true,
                'rewrite' => false,
                'hierarchical' => false,
                'parent_item'  => null,
                'parent_item_colon' => null,
            )
        );
    }

    function new_category_field( $term ){
        ?>
        <div class="form-field term-group">
            <label for="category-image-id"><?php _e('Image', 'hero-theme'); ?></label>
            <input type="hidden" id="category-image-id" name="category-image-id" class="custom_media_url" value="">
            <div id="category-image-wrapper"></div>
            <p>
                <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e( 'Adicionar Imagem'  ); ?>" />
                <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e( 'Remover'  ); ?>" />
            </p>
        </div>
        <div class="form-field term-parent-wrap">
            <label for="term_meta[<?php echo self::TYPE ?>]">Tipo</label>
            <br />
            <fieldset>
                <label>
                    <input checked type="radio" name="term_meta[<?php echo self::TYPE ?>]" value="open" />
                    <span>Aberto</span>
                </label>
                <label>
                    <input type="radio" name="term_meta[<?php echo self::TYPE ?>]" value="private" />
                    <span>Privado</span>
                </label>
                <label>
                    <input type="radio" name="term_meta[<?php echo self::TYPE ?>]" value="hide" />
                    <span>Oculto</span>
                </label>
            </fieldset>
        </div>
        <?php
    }

    function edit_category_field( $term ){
        $term_meta = '';
        if($term instanceof WP_Term){
            $term_id = $term->term_id;
            $term_meta = get_term_meta($term_id, self::TYPE, true );
        }



        ?>
        <tr class="form-field term-parent-wrap">
            <th scope="row">
                <label>Tipo</label>
            </th>
            <td>
                <fieldset>
                    <label>
                        <input <?php echo ($term_meta == 'open' || !$term_meta) ? 'checked' : ''; ?> type="radio" name="term_meta[<?php echo self::TYPE ?>]" value="open" />
                        <span>Aberto</span>
                    </label>
                    <br />
                    <label>
                        <input <?php echo ($term_meta == 'private') ? 'checked' : ''; ?> type="radio" name="term_meta[<?php echo self::TYPE ?>]" value="private" />
                        <span>Privado</span>
                    </label>
                    <br />
                    <label>
                        <input <?php echo ($term_meta == 'hide') ? 'checked' : ''; ?> type="radio" name="term_meta[<?php echo self::TYPE ?>]" value="hide" />
                        <span>Oculto</span>
                    </label>
                </fieldset>
            </td>
        </tr>

        <?php
    }

    function save_tax_meta( $term_id , $taxonomy ){

        if(isset( $_POST['term_meta']['rhs-comunity-type'])){
            $term_meta = array();

            if ( ! add_term_meta($term_id, self::TYPE, $_POST['term_meta'][self::TYPE], true) ) {

                update_term_meta($term_id, self::TYPE, $_POST['term_meta'][self::TYPE]);
            }
        }
    }

    public function add_script() { ?>
        <script>
            jQuery(document).ready( function($) {
                function ct_media_upload(button_class) {
                    var _custom_media = true,
                        _orig_send_attachment = wp.media.editor.send.attachment;
                    $('body').on('click', button_class, function(e) {
                        var button_id = '#'+$(this).attr('id');
                        var send_attachment_bkp = wp.media.editor.send.attachment;
                        var button = $(button_id);
                        _custom_media = true;
                        wp.media.editor.send.attachment = function(props, attachment){
                            if ( _custom_media ) {
                                $('#category-image-id').val(attachment.id);
                                $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                                $('#category-image-wrapper .custom_media_image').attr('src',attachment.sizes.thumbnail.url).css('display','block');
                            } else {
                                return _orig_send_attachment.apply( button_id, [props, attachment] );
                            }
                        }
                        wp.media.editor.open(button);
                        return false;
                    });
                }
                ct_media_upload('.ct_tax_media_button.button');
                $('body').on('click','.ct_tax_media_remove',function(){
                    $('#category-image-id').val('');
                    $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
                });
                // Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-category-ajax-response
                $(document).ajaxComplete(function(event, xhr, settings) {
                    var queryStringArr = settings.data.split('&');
                    if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
                        var xml = xhr.responseXML;
                        $response = $(xml).find('term_id').text();
                        if($response!=""){
                            // Clear the thumb image
                            $('#category-image-wrapper').html('');
                        }
                    }
                });
            });
        </script>
    <?php }

    public function get_comunities($user_id){

        if(!empty(self::$comunity[$user_id])){
            return self::$comunity[$user_id];
        }

        $filter = array(
            //'taxonomy' => self::TAXONOMY,
            //'hide_empty' => 0,
            //'pad_counts' => true
        );

        if(!empty($_REQUEST['search'])){
            // TODO: Filtrar por palavra
        }

        if(!empty($_REQUEST['sort_order'])){

            switch ($_REQUEST['sort_order']){
                case 'alpha':
                    $filter['orderby'] = 'name';
                    $filter['order'] = 'ASC';
                    break;
                case 'bigger_member':
                    // TODO: Filtrar por maior quantidade de membros
                    break;
                case 'bigger_posts':
                    $filter['orderby'] = 'count';
                    $filter['order'] = 'DESC';
                    break;
                case 'smaller_member':
                    // TODO: Filtrar por menor quantidade de membros
                    break;
                case 'smaller_posts':
                    $filter['orderby'] = 'count';
                    $filter['order'] = 'ASC';
                    break;
            }
        }

        $comunities = get_categories($filter);

        $return = array();

        foreach ($comunities as $comunity){

            $members = get_term_meta( $comunity->term_id, self::MEMBER);
            $members_follow = get_term_meta( $comunity->term_id, self::MEMBER_FOLLOW);
            $type = get_term_meta($comunity->term_id, self::TYPE, true );
            $image = get_term_meta($comunity->term_id, self::IMAGE, true );

            if(!$members && $type == self::TYPE_HIDE){
                continue;
            }

            if($members && !in_array($user_id ,$members) && $type != self::TYPE_HIDE){
                continue;
            }

            $can_edit = false;
            $can_members = true;
            $can_follow = false;
            $can_not_follow = false;
            $can_enter = false;
            $can_leave = false;

            if(!$members || !in_array($user_id ,$members)){
                $can_enter = true;
            }

            if($members && in_array($user_id ,$members)){
                $can_leave = true;

                if(!$members_follow || !in_array($user_id ,$members_follow)){
                    $can_follow = true;
                }

                if($members_follow && in_array($user_id ,$members_follow)){
                    $can_not_follow = true;
                }
            }

            $return[] = array(
                'id' => $comunity->term_id,
                'name' => $comunity->name,
                'image' => $image,
                'type' => $type,
                'members' => count($members),
                'posts' => $comunity->count,
                'can_edit' => $can_edit,
                'can_members' => $can_members,
                'can_follow' => $can_follow,
                'can_not_follow' => $can_not_follow,
                'can_enter' => $can_enter,
                'can_leave' => $can_leave,
                'user_inside' => false
            );
        }

        return self::$comunity[$user_id] = $return;

    }

    public function add_user_comunity($user_id){
        add_term_meta($term_id, self::MEMBER, $user_id);
    }

    public function filter_value($name, $name_serach){


        $search = !empty($_REQUEST[$name_serach]) ? $_REQUEST[$name_serach] : '';
        $url = home_url('comunidades');

        return array(
            $url.'?'.$name.'=alpha&'.$name_serach.'='.$search => array('nome' => 'Ordem Alfabetica', 'selected' => (!empty($_REQUEST[$name]) && $_REQUEST[$name] == 'alpha') ? 1 : 0),
            $url.'?'.$name.'=bigger_member&'.$name_serach.'='.$search => array('nome' => 'Maior quantidade de membros', 'selected' => (!empty($_REQUEST[$name]) && $_REQUEST[$name] == 'bigger_member') ? 1 : 0),
            $url.'?'.$name.'=bigger_posts&'.$name_serach.'='.$search => array('nome' => 'Maior quantidade de posts', 'selected' => (!empty($_REQUEST[$name]) && $_REQUEST[$name] == 'bigger_posts') ? 1 : 0),
            $url.'?'.$name.'=smaller_member&'.$name_serach.'='.$search => array('nome' => 'Menor quantidade de membros', 'selected' => (!empty($_REQUEST[$name]) && $_REQUEST[$name] == 'smaller_member') ? 1 : 0),
            $url.'?'.$name.'=smaller_posts&'.$name_serach.'='.$search => array('nome' => 'Menor quantidade de posts', 'selected' => (!empty($_REQUEST[$name]) && $_REQUEST[$name] == 'smaller_posts') ? 1 : 0),
        );

    }

    public function check_comunity(){

        if(empty($_REQUEST['comunidade_id'])){
            return;
        }

        $data = array(
            'taxonomy' => self::TAXONOMY,
            'hide_empty' => 0,
            'pad_counts' => true
        );

        $category = get_category($_REQUEST['comunidade_id'], OBJECT,$data);

        if(!$category){

        }

        echo '<pre>';
        print_r($category);
        exit;
    }

}

global $RHSComunity;
$RHSComunity = new RHSComunity();