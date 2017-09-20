<?php

class RHSPost {

    private $id;
    private $title;
    private $content;
    private $status;
    private $authorId;
    private $categories;
    private $categoriesObjArray;
    private $categoriesId;
    private $state;
    private $city;
    private $tags;
    private $featuredImage;
    private $featuredImageId;
    private $comunities;
    private $comunitiesId;
    private $comunitiesName;
    private $error;

    /**
     * RHSPost constructor.
     *
     * @param int $postId
     * @param WP_Post|null $post
     * @param bool $only_current apenas author pode ser esse post
     */
    function __construct($postId = 0, WP_Post $post = null, $only_current = false) {

        if(!$postId && !$post){
            return;
        }

        $post = !$post ? get_post( $postId ) : $post;

        if ( ! $post ) {
            return;
        }

        $this->setId($post->ID);

        if($only_current && !$this->isCurrentAuthor()){
            $this->id = null;
            return;
        }

        $this->setTitle($post->post_title);
        $this->setContent($post->post_content);
        $this->setStatus($post->post_status);
        $this->setAuthorId($post->post_author);

        $this->setCategories(get_the_category($this->getId()));

        $this->setTags(wp_get_post_tags($this->getId()));

        $this->setStateCity();

        $this->setFeaturedImageId(get_post_thumbnail_id($this->getId()));

        $this->setComunities(wp_get_post_terms( $this->getId() , RHSComunities::TAXONOMY ));

        $this->setComunitiesId();
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId( $id ) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle( $title ) {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent( $content ) {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus( $status ) {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getAuthorId() {
        return $this->authorId;
    }

    /**
     * @param int $authorId
     */
    public function setAuthorId( $authorId ) {
        $this->authorId = $authorId;
    }

    /**
     * @return array
     */
    public function getCategories() {
        return $this->categories;
    }

    /**
     * @param array $categories
     */
    public function setCategories( $categories ) {
        $this->categories = $categories;
    }

    /**
     * @param array $term_ids
     */
     function setCategoriesByIds($term_ids) {
        if (empty($term_ids))
            return $this->setCategories([]);
            
        $cats = get_categories(['include' => $term_ids, 'hide_empty' => false]);
        $this->setCategories($cats);
    }

    /**
     * @return array|WP_Error
     */
     public function getCategoriesIds() {
        $tags = $this->getCategories();
        $return = [];
        if (is_array($tags)) {
            foreach ($tags as $tag) {
                $return[] = $tag->term_id;
            }
        } 
        return $return;
    }

    /**
     * @return int
     */
    public function getState() {
        return $this->state;
    }

    /**
     * @param int $state
     */
    public function setState( $state ) {
        $this->state = $state;
    }

    /**
     * @return int
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @param int $city
     */
    public function setCity( $city ) {
        $this->city = $city;
    }

    private function setStateCity(){
        $cur_ufmun = get_post_ufmun( $this->id );

        if (!empty($cur_ufmun['uf']['id'])) {
            $this->setState($cur_ufmun['uf']['id']);
        }

        if (!empty($cur_ufmun['mun']['id'])) {
            $this->setCity($cur_ufmun['mun']['id']);
        }
    }

    /**
     * @return array
     */
    public function getTags() {
        return $this->tags;
    }

    
    public function getTagsIds() {
        $tags = $this->getTags();
        $return = [];
        if (is_array($tags)) {
            foreach ($tags as $tag) {
                $return[] = $tag->term_id;
            }
        } 
        return $return;
    }


    /**
     * @param array $tags
     */
    public function setTags( $tags ) {
        $this->tags = $tags;
    }
    
    /* Verifica se erro é term exist e retorna o array modificado com o id do termo existente */
    function verifyErrorTermExist($term_id, $terms, $index){
        if(is_wp_error($term_id)){
            if($term_id->get_error_code() == 'term_exists'){
                $terms[$index] = $term_id->get_error_data();
            }
        }
        else{
            $terms[$index] = $term_id['term_id'];
        }

        return $terms;
    }

    /*
    * Seta tags do post a partir de um array de ids ou nomes (pode ser misturado)
    * No caso de Ids, serão atribuídas tags existentes, no caso de nomes, serão criadas novas tags
    */
    function setTagsByIdsOrNames($terms){
        if(empty($terms)){
            return $this->setTags([]);
        }

        foreach($terms as $index => $term){
            if(!(is_numeric($term)) && !(is_integer($term))){
                try{
                    $term_id = wp_insert_term($term, 'post_tag');
                    $terms = $this->verifyErrorTermExist($term_id, $terms, $index);
                } 
                catch(Error $e){
                    wp_delete_term($term_id['term_id'], 'post_tag');
                }
            }
            /*
            * Quando uma TAG com apenas números nova é igual a ID de tag que já existe, essa tag nova nova não é criada e inserida no post
            * #TODO: 1 - Verifica se é possível o magic suggest ter como valores ao mesmo tempo ID e Nome da tag existente; 
            * Se 1 possível -> 2 - Implementar condição que verifica se existe TAG com mesmo ID e Nome, se verdade, faz nada, se falso ou 'Nome' vazio, cria nova tag.
            */
            else if((term_exists(((int) $term))) == 0 || (term_exists(((int) $term))) == NULL){
                try{
                    $term_id = wp_insert_term(((string)$term), 'post_tag');
                    $terms = $this->verifyErrorTermExist($term_id, $terms, $index);

                } catch(Error $e){
                    wp_delete_term($term_id['term_id'], 'post_tag');
                }
            }
        }

        $tags = get_tags(['include' => $terms, 'hide_empty' => false]);
        $this->setTags($tags);
    }

    /**
     * @param string|array $size
     *
     * @return string
     */
    public function getFeaturedImage( $size = 'post-thumbnail' ) {

        if($this->featuredImage){
             return $this->featuredImage;
        }

        $try = get_the_post_thumbnail( $this->id, $size);
        
        if ( !empty($try))
            return $try; // existe uma imagem destacada no banco
        
        if (!empty($this->getFeaturedImageId())) {
            // a informação não está salva no banco mas o id foi setado.
            return wp_get_attachment_image($this->getFeaturedImageId(), $size);
        }
        
    }

    public function setFeaturedImage($featuredImage){
        return $this->featuredImage = $featuredImage;
    }

    /**
     * @return int|string
     */
    public function getFeaturedImageId() {
        return $this->featuredImageId;
    }

    /**
     * @param int|string $featuredImageId
     */
    public function setFeaturedImageId( $featuredImageId ) {
        $this->featuredImageId = $featuredImageId;
    }

    /**
     * @return array
     */
    public function getError() {
        return $this->error;
    }

    /**
     * @param array $error
     */
    public function setError(WP_Error $error ) {
        $this->error = $error->get_error_messages();
    }

    public function isCurrentAuthor(){
        return current_user_can('edit_post', $this->id);
    }

    /**
     * @return WP_Term[]
     */
    public function getComunities() {
        return $this->comunities;
    }

    /**
     * @param mixed $comunities
     */
    public function setComunities( $comunities ) {
        $this->comunities = $comunities;
    }

    /**
     * @return mixed
     */
    public function getComunitiesId() {
        return $this->comunitiesId;
    }

    public function setComunitiesId() {
        $comunities = $this->getComunities();

        foreach ($comunities as $category){
            if($category instanceof WP_Term){
                $this->comunitiesId[] = $category->term_id;
            }
        }
    }

}

global $RHSPost;
//$RHSPost = new RHSPost( get_the_ID() );
