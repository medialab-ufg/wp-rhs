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
    function events_wordpress(){
        add_action('init', array( &$this, "register_taxonomy" ));

        add_action( self::TAXONOMY.'_edit_form_fields', array( &$this, 'edit_category_field') );
        add_action( self::TAXONOMY.'_add_form_fields',array( &$this, 'new_category_field') );
        add_action( 'edited_'.self::TAXONOMY, array( &$this,'save_tax_meta'), 10, 2 );
        add_action( 'create_'.self::TAXONOMY, array( &$this,'save_tax_meta'), 10, 2 );
        add_action( 'admin_footer', array ( $this, 'add_script' ) );

        add_action('wp_ajax_enter_comunity', array($this, 'ajax_enter_comunity'));
        add_action('wp_ajax_leave_comunity', array($this, 'ajax_leave_comunity'));
        add_action('wp_ajax_follow_comunity', array($this, 'ajax_follow_comunity'));
        add_action('wp_ajax_not_follow_comunity', array($this, 'ajax_not_follow_comunity'));

        add_action('add_meta_boxes', array( &$this, "add_meta_box"));
        add_filter( 'wp_insert_post_data' , array( &$this,'filter_post_data') , '99', 2 );
        //add_filter('rewrite_rules_array', array( &$this,'my_car_rewrite_rules'));
    }

    /*function my_car_rewrite_rules( $rules ) {
        $newrules = array();

        // add a rule for this kind of url:
        // http://myhost.com/cars/ferrari/used/123
        // => http://myhost.com/index.php?post_type=cars&ferrari=used&p=123

        $newrules['^comunidade/([^/]+)/([^/]+)/([^/]+)$'] = 'index.php?taxonomy='.self::TAXONOMY.'&$matches[1]=$matches[2]&param=$matches[3]';
        return $newrules + $rules;
    }*/


    /**
     * Pega as comunidades
     * @param bool $get_empty - Categorias sem posts
     * @param array $filter - Filtro do WP_Terms
     *
     * @return WP_Term[]
     */
    public function get_comunities($get_empty = false, array $filter = array()){

        $filter_default = array('taxonomy' => self::TAXONOMY);

        if($get_empty){
            $filter_default['hide_empty'] = 0;
            $filter_default['pad_counts'] = true;
        }

        if($filter){
            $filter_default = array_merge($filter_default, $filter);
        }

        return get_categories($filter_default);
    }

    /*====================================================================================================
                                                ADMINISTAÇÃO
    ======================================================================================================*/

    /**
     * Registra a taxonomia da "ComunidadeS"
     */
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
                'rewrite' => array(
                    //'slug' => 'comunidade/%comunity%'
                )
            )
        );
    }

    /**
     * TODO: Resolver problema ao escolher imagem
     * (Taxonomy) Adiciona campo "Tipo" e "Imagem" ao inserir
     * @param $term
     */
    function new_category_field( $term ){
        ?>
        <div class="form-field term-group">
            <label for="category-image-id"><?php _e('Imagem'); ?></label>
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

    /**
     * (Taxonomy) Adiciona campo "Tipo" e "Imagem" ao editar
     * @param $term
     */
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

    /**
     * (Taxonomy) Salva o campo "Tipo" e "Imagem"
     * @param $term_id
     * @param $taxonomy
     */
    function save_tax_meta( $term_id , $taxonomy ){

        if(isset( $_POST['term_meta'][self::TYP])){
            $term_meta = array();

            if ( ! add_term_meta($term_id, self::TYPE, $_POST['term_meta'][self::TYPE], true) ) {

                update_term_meta($term_id, self::TYPE, $_POST['term_meta'][self::TYPE]);
            }
        }
    }

    /**
     * (Taxonomy) Adiciona script para a seleção da imagem
     */
    function add_script() { ?>
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

    /**
     * (Post) Adiciona MetaBox para a escolha da comunidade
     */
    function add_meta_box() {
        add_meta_box('category_comunity', 'Comunidades', array( &$this, 'meta_box_response'), 'post', 'side', 'default');
    }

    /**
     * (Post) HTML da MetaBox
     * @param WP_Post $post
     *
     * @return string
     */
    function meta_box_response($post) {

        $comunities = $this->get_comunities(true);
        $comunities_post = $this->get_comunities_by_post($post->ID);

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
                    <?php foreach ($comunities as $category){ ?>
                        <li id="comunity-<?php echo $category->term_id ?>" class="popular-category">
                            <label class="selectit">
                                <?php

                                $checked = ($comunities_post && $comunities_post->term_id == $category->term_id) ? 'checked' : '';

                                ?>
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

    /**
     * (Post) Pega a comunidade do post
     * @param int $post_id
     *
     * @return array|WP_Error|WP_Term
     */
    function get_comunities_by_post($post_id){
        $comunity = wp_get_post_terms($post_id, self::TAXONOMY);

        if(!$comunity){
            return array();
        }

        return current($comunity);

    }


    function filter_post_data( $data , $postarr ) {

        if(!empty($_POST['post_comunity'])){
            wp_set_post_terms( $postarr['ID'], $_POST['post_comunity'], self::TAXONOMY );
        }

        return $data;
    }

    /*====================================================================================================
                                                CLIENTE
    ======================================================================================================*/

    /**
     * Pega comunidades baseado na pemissão do cliente
     * @param $user_id
     *
     * @return RHSComunity[]
     */
    function get_comunities_by_user($user_id){

        // Singleton para usar em verificação e não fazer a consulta novamente
        if($user_id){
            if(!empty(self::$comunity[$user_id])){
                return self::$comunity[$user_id];
            }
        }

        $filter = array();

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

        $comunities = $this->get_comunities(false, $filter);

        $return = array();

        foreach ($comunities as $comunity){

            $obj_comunity = $this->get_comunity_by_user($comunity, $user_id);

            if($obj_comunity){
                $return[] = $obj_comunity;
            }
        }

        return self::$comunity[$user_id] = $return;

    }

    /**
     * Adiciona membro a comunidade
     *
     * @param $term_id
     * @param $user_id
     */
    function add_user_comunity($term_id, $user_id){
        add_term_meta($term_id, self::MEMBER, $user_id);
    }
    /**
     * Remove membro da comunidade
     *
     * @param $term_id
     * @param $user_id
     */
    function delete_user_comunity($term_id, $user_id){
        delete_term_meta($term_id, self::MEMBER, $user_id);
    }

    /**
     * Adiciona membro a seguidor comunidade
     *
     * @param $term_id
     * @param $user_id
     */
    function add_user_comunity_follow($term_id, $user_id){
        add_term_meta($term_id, self::MEMBER_FOLLOW, $user_id);
    }

    /**
     * Remove membro de seguidor comunidade
     *
     * @param $term_id
     * @param $user_id
     */
    function delete_user_comunity_follow($term_id, $user_id){
        delete_term_meta($term_id, self::MEMBER_FOLLOW, $user_id);
    }

    /**
     * Retorna o HTML dos options do filtro de ordenação
     * @param $name - Nome do select
     * @param $name_serach - Nome do campo de busca
     *
     * @return array
     */
     function filter_value($name, $name_serach){


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

    /**
     * @return array|RHSComunity
     */
    function get_comunity_by_request(){

         if(empty(get_queried_object()->term_id)){
             return array();
         }

        if(!get_current_user_id()){
            return array();
        }

        return $this->get_comunity_by_user(get_term(get_queried_object()->term_id, self::TAXONOMY), get_current_user_id());
    }

    /**
     * @param WP_Term $comunity
     * @param int $user_id
     *
     * @return RHSComunity
     */
    function get_comunity_by_user(WP_Term $comunity, $user_id){
        return new RHSComunity($comunity, get_userdata($user_id));
    }


    /**
     * Checa se tem permissão de ver tela de comunidades
     * @return bool
     */
    function can_see_comunities(){

        if(get_current_user_id()){
            return true;
        }

        return false;

    }

    /*====================================================================================================
                                                AJAX
    ======================================================================================================*/

    function ajax_enter_comunity(){

        $comunity = $this->get_comunity_by_request();

        if($comunity && $comunity->can_enter()){
            $this->add_user_comunity($_POST['comunidade_id'], get_current_user_id());

            echo json_encode(true);
            exit;
        }

        echo json_encode(false);
        exit;
    }

    function ajax_leave_comunity(){
        $comunity = $this->get_comunity_by_request();

        if($comunity && $comunity->can_leave()){
            $this->delete_user_comunity($_POST['comunidade_id'], get_current_user_id());

            echo json_encode(true);
            exit;
        }

        echo json_encode(false);
        exit;
    }

    function ajax_follow_comunity(){

        $comunity = $this->get_comunity_by_request();

        if($comunity && $comunity->can_follow()) {
            $this->add_user_comunity_follow( $_POST['comunidade_id'], get_current_user_id() );

            echo json_encode(true);
            exit;
        }

        echo json_encode(false);
        exit;
    }

    function ajax_not_follow_comunity(){
        $comunity = $this->get_comunity_by_request();

        if($comunity && $comunity->can_not_follow()) {
            $this->delete_user_comunity_follow( $_POST['comunidade_id'], get_current_user_id() );

            echo json_encode(true);
            exit;
        }

        echo json_encode(false);
        exit;
    }

}

global $RHSComunities;
$RHSComunities = new RHSComunities();