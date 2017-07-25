<?php

class RHSPost {

    private $id;
    private $title;
    private $content;
    private $status;
    private $authorId;
    private $categories;
    private $categoriesJson;
    private $categoriesId;
    private $categoriesIdJson;
    private $state;
    private $city;
    private $tags;
    private $tags_json;
    private $featuredImage;
    private $featuredImageId;
    private $error;

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
        $this->setCategories(get_the_category( $post->ID ));
        $this->setCategoriesId(wp_get_post_categories( $post->ID ));

        $cur_ufmun = get_post_ufmun( $post->ID );

        if (!empty($cur_ufmun['uf']['id'])) {
            $this->setState($cur_ufmun['uf']['id']);
        }

        if (!empty($cur_ufmun['mun']['id'])) {
            $this->setState($cur_ufmun['mun']['id']);
        }

        $this->setTags(wp_get_post_tags( $post->ID ));

        $thumbnail_id = get_post_thumbnail_id( $post->ID );

        $this->setFeaturedImageId(get_post_thumbnail_id( $post->ID ));
        $this->setFeaturedImage(get_the_post_thumbnail( $post->ID, 'post-thumbnail' ));
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

    /**
     * @return array
     */
    public function getTags() {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags( $tags ) {
        $this->tags = $tags;
    }

    /**
     * @param string $size
     *
     * @return string
     */
    public function getFeaturedImage( $size = 'post-thumbnail' ) {

        if ( is_string( $size ) && $size == 'post-thumbnail' ){
            return $this->featuredImage;
        }

        return get_the_post_thumbnail( $this->id, $size );

    }

    /**
     * @param $featured_image
     * @param string $size
     */
    public function setFeaturedImage( $featuredImage ) {
        $this->featuredImage = $featuredImage;
    }

    /**
     * @return mixed
     */
    public function getCategoriesJson() {

        if ( $this->categoriesJson ) {
            return $this->categoriesJson;
        }

        $string = 'data: [';

        foreach ( get_categories() as $category ) :
            $string .= "{id:" . $category->term_id . ", name:'" . $category->cat_name . "'},";
        endforeach;

        $string .= '],';

        return $this->categoriesJson = $string;
    }

    /**
     * @param mixed $categoriesJson
     */
    public function setCategoriesJson( $categoriesJson ) {
        $this->categoriesJson = $categoriesJson;
    }

    /**
     * @return mixed
     */
    public function getTagsJson() {

        if ( $this->tags_json ) {
            return $this->tags_json;
        }

        $tagsDataArr = array();

        if ( $this->tags ) {
            foreach ( $this->tags as $tag ) {
                $tagsDataArr[] = $tag->name;
            }
        }

        if ( $tagsDataArr ) {
            return $this->tags_json = "['" . implode( "', '", $tagsDataArr ) . "']";
        }

        return $this->tags_json;
    }

    /**
     * @param mixed $tags_json
     */
    public function setTagsJson( $tags_json ) {
        $this->tags_json = $tags_json;
    }

    /**
     * @return array|WP_Error
     */
    public function getCategoriesId() {
        return $this->categoriesId;
    }

    /**
     * @param array|WP_Error $categoriesId
     */
    public function setCategoriesId( $categoriesId ) {
        $this->categoriesId = $categoriesId;
    }

    /**
     * @return mixed
     */
    public function getCategoriesIdJson() {

        if ( $this->categoriesIdJson ) {
            return $this->categoriesIdJson;
        }

        if ( $this->categoriesId ) {
            $this->categoriesIdJson = "['" . implode( "', '", $this->categoriesId ) . "']";
        }

        return $this->categoriesIdJson;
    }

    /**
     * @param mixed $categoriesIdJson
     */
    public function setCategoriesIdJson( $categoriesIdJson ) {
        $this->categoriesIdJson = $categoriesIdJson;
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


}

global $RHSPost;
//$RHSPost = new RHSPost( get_the_ID() );
