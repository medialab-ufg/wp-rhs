<?php
/**
 * Class PostTest
 *
 * @package Rhs
 */

include_once('rhs-tests-setup.php');

/**
 * Follow Post test case.
 */
class PostTest extends RHS_UnitTestCase {
    
    function test_postInstanciationIdOnly(){
        $postId = $this->factory->post->create(
            ['post_title' => 'Portal 2', 'post_status' => 'publish', 'post_content' => 'The cake is a lie!', 'post_author' => self::$users['editor'][0]]
        );

        $test = new RHSPost($postId);
        $this->assertEquals($postId, $test->getId());
    }

    function test_postInstanciationObjectOnly(){
        $postId = $this->factory->post->create(
            ['post_title' => 'Portal 2', 'post_status' => 'publish', 'post_content' => 'The cake is a lie!', 'post_author' => self::$users['editor'][0]]
        );
        
        $post = get_post($postId);
        // Verifica se é instancia de WP_Post
        $this->assertInstanceOf('WP_Post', $post);

        $testObject = new RHSPost(0, $post);
        $this->assertEquals($postId, $testObject->getId());
    }

    function test_postEditOtherUser(){
        // Cria um post
        $postId = $this->factory->post->create(
            ['post_title' => 'Portal 2', 'post_status' => 'publish', 'post_content' => 'The cake is a lie!', 'post_author' => self::$users['editor'][0]]
        );

        $post = get_post($postId);
        // Verifica se é instancia de WP_Post
        $this->assertInstanceOf('WP_Post', $post);
        // Verifica condição que apenas o autor pode editar o post, sendo only_current = true
        // O id se torna null se o usuário atual não é o autor.
        $testPostOtherUser = new RHSPost(0, $post, true);
        $this->assertNull($testPostOtherUser->getId());
    }

    function test_postEditCorrectUser(){
        // Cria um post
        $postId = $this->factory->post->create(
            ['post_title' => 'Portal 2', 'post_status' => 'publish', 'post_content' => 'The cake is a lie!', 'post_author' => self::$users['editor'][0]]
        );

        $post = get_post($postId);
        // Verifica se é instancia de WP_Post
        $this->assertInstanceOf('WP_Post', $post);

        // Muda o usuário atual para o autor do post
        $user = get_user_by('login', 'editor1');
        wp_set_current_user($user->ID);
        
        // Instancia
        $testPostAuthor = new RHSPost(0, $post, true);

        // Verifica se com o autor, o post foi instanciado
        $this->assertEquals($postId, $testPostAuthor->getId());
    }

    // Verifica retorno e adição de categoria do post
    function test_getAndSetCategories(){

        ####### Teste com post existente #######
        ########################################

        // Cria um post
        $postId = $this->factory->post->create(
            ['post_title' => 'Portal 2', 'post_status' => 'publish', 'post_content' => 'The cake is a lie!', 'post_author' => self::$users['editor'][0]]
        );
        // Cria uma categoria
        $category1Id = $this->factory->term->create(['name' => 'Category 1', 'taxonomy' => 'category', 'slug' => 'category-1']);

        // Adiciona e verifica adição de uma categoria ao post
        $categories = wp_set_post_categories($postId, $category1Id, true);
        $this->assertInternalType('array', $categories);
        $this->assertCount(1, $categories);

        $post = get_post($postId);
        // Verifica se é instancia de WP_Post
        $this->assertInstanceOf('WP_Post', $post);

        $newPost = new RHSPost(0, $post);
        $categories = $newPost->getCategories();

        // Verifica se retorno é um array
        $this->assertInternalType('array', $categories);
        // Verifica se é um array de objetos WP_Term
        $this->assertContainsOnlyInstancesOf('WP_Term', $categories);

        ####### Teste com post novo #######
        ###################################

        $newPost = self::create_post_to_queue();
        $newPost->setCategoriesByIds($category1Id);

        $categoria = $newPost->getCategories();
        $this->assertCount(1, $categoria);

        $categoriaId = $newPost->getCategoriesIds();
        $this->assertEquals($category1Id, $categoriaId[0]);
    }

    // Verifica retorno e adição de tag do post
    function test_getAndSetTags(){

        ####### Teste com post existente #######
        ########################################

        // Cria um post
        $postId = $this->factory->post->create(
            ['post_title' => 'Portal 2', 'post_status' => 'publish', 'post_content' => 'The cake is a lie!', 'post_author' => self::$users['editor'][0]]
        );
        // Cria uma Tag
        $tag1Id = $this->factory->term->create(['name' => 'Tag 1', 'taxonomy' => 'post_tag', 'slug' => 'tag-1']);

        // Adiciona e verifica adição de uma tag ao post
        $tags = wp_set_post_tags($postId, $tag1Id, true);
        $this->assertInternalType('array', $tags);
        $this->assertCount(1, $tags);

        $post = get_post($postId);
        // Verifica se é instancia de WP_Post
        $this->assertInstanceOf('WP_Post', $post);

        $newPost = new RHSPost(0, $post);
        $tags = $newPost->getTags();

        // Verifica se retorno é um array
        $this->assertInternalType('array', $tags);
        // Verifica se é um array de objetos WP_Term
        $this->assertContainsOnlyInstancesOf('WP_Term', $tags);

        ####### Teste com post novo #######
        ###################################
        
        $newPost = self::create_post_to_queue();

        // Nome de tag que não existe no banco de dados (quando a tag é definida pelo usuário)
        $newPost->setTagsByIdsOrNames(['Nova tag', '2018']);
        
        $tags = $newPost->getTags();
        $tag_id = $tags[0]->term_id;
        $tag_id2 = $tags[1]->term_id;
        
        $this->assertContainsOnlyInstancesOf('WP_Term', $tags);
        
        $tagId = $newPost->getTagsIds();
        $this->assertEquals($tag_id, $tagId[0]);
        $this->assertEquals($tag_id2, $tagId[1]);

        // Tentantiva de inserir tag que já existe como se fosse tag nova        
        $newPost->setTagsByIdsOrNames(['Nova tag', '2018']);
        
        $tags = $newPost->getTags();
        $tag_id = $tags[0]->term_id;
        $tag_id2 = $tags[1]->term_id;
        
        $this->assertContainsOnlyInstancesOf('WP_Term', $tags);
        
        $tagId = $newPost->getTagsIds();
        $this->assertEquals($tag_id, $tagId[0]);
        $this->assertEquals($tag_id2, $tagId[1]);
    }
    
    // Verifica retorno do título do post
    function test_getAndSetTitle(){

        ####### Teste com post existente #######
        ########################################

        // Cria um post
        $postId = $this->factory->post->create(
            ['post_title' => 'Portal 2', 'post_status' => 'publish', 'post_content' => 'The cake is a lie!', 'post_author' => self::$users['editor'][0]]
        );

        $post = get_post($postId);
        // Verifica se é instancia de WP_Post
        $this->assertInstanceOf('WP_Post', $post);

        $newPost = new RHSPost(0, $post);
        $returnTitle = $newPost->getTitle();
        $postTitleFromDatabase = get_post($postId)->post_title;

        // Verifica se é uma string
        $this->assertInternalType('string', $returnTitle);
        // Verifica se essa string é a mesma que está no banco de dados
        $this->assertEquals($returnTitle, $postTitleFromDatabase);

        ####### Teste com post novo #######
        ###################################

        $newPost = self::create_post_to_queue();
        $newPost->setTitle('teste1');
        $this->assertEquals('teste1', $newPost->getTitle());
    }

    // Verifica adição e retorno do conteúdo do post
    function test_getAndSetContent(){

        ####### Teste com post existente #######
        ########################################

        // Cria um post
        $postId = $this->factory->post->create(
            ['post_title' => 'Portal 2', 'post_status' => 'publish', 'post_content' => 'The cake is a lie!', 'post_author' => self::$users['editor'][0]]
        );

        $post = get_post($postId);
        // Verifica se é instancia de WP_Post
        $this->assertInstanceOf('WP_Post', $post);

        $newPost = new RHSPost(0, $post);
        $returnContent = $newPost->getContent();
        $postContentFromDatabase = get_post($postId)->post_content;

        $this->assertInternalType('string', $returnContent);
        $this->assertEquals($returnContent, $postContentFromDatabase);

        ####### Teste com post novo #######
        ###################################

        $newPost = self::create_post_to_queue();
        $this->assertEquals('teste1', $newPost->getContent());
        
    }

    // Verifica retorno e adição de comunidades do post
    function test_getAndSetComunities(){

        ####### Teste com post existente #######
        ########################################

        $postId = $this->factory->post->create(
            ['post_title' => 'Portal 2', 'post_status' => 'public', 'post_content' => 'The cake is a lie!', 'post_author' => self::$users['editor'][0]]
        );

        $comunit1Id = $this->factory->term->create(
            ['name' => 'Comunidade 1', 'description' => 'Comunidade', 'slug' => 'comunit-1']
        );

        $comunities = wp_set_post_terms($postId, $comunit1Id, RHSComunities::TAXONOMY, true);
        $this->assertInternalType('array', $comunities);
        $this->assertCount(1, $comunities);

        $post = get_post($postId);
        // Verifica se é instancia de WP_Post
        $this->assertInstanceOf('WP_Post', $post);

        $newPost = new RHSPost(0, $post);
        $returnComunities = $newPost->getComunities();
        $this->assertInternalType('array', $returnComunities);
        // Verifica se é um array de objetos WP_Term
        $this->assertContainsOnlyInstancesOf('WP_Term', $returnComunities);

        $returnComuId = $newPost->getComunitiesId();
        $this->assertInternalType('array', $returnComuId);
        $this->assertCount(1, $returnComuId);

        ####### Teste com post novo #######
        ###################################
        wp_set_current_user(self::$users['contributor'][0]);
        $comunitId = self::create_community('private');
        $newPost = self::create_post_to_private_community($comunitId);

        #$newPost->setComunitiesName();
        #$newPost->setComunitiesId();

        $returnComunities = $newPost->getComunities();
        $this->assertInternalType('array', $returnComunities);
        
        // Verifica se é um array de objetos WP_Term
        //$this->assertContainsOnlyInstancesOf('WP_Term', $returnComunities);
        // TODO: refatorar classe RHSPost para setters and getters de comunidades ficarem mais consistente
        // melhorar a questão de dar um setComunity passando só 'public'
        // seguir padrão usado para categorias
        // 
        // $returnComuId = $newPost->getComunitiesId();
        // $this->assertInternalType('array', $returnComuId);
        // $this->assertCount(1, $returnComuId);

    }

    // Verifica adição e retorno de cidade e estado
    function getAndSetStateCity(){

        ####### Teste com post existente #######
        ########################################

        $postId = $this->factory->post->create(
            ['post_title' => 'Portal 2', 'post_status' => 'publish', 'post_content' => 'The cake is a lie!', 'post_author' => self::$users['editor'][0]]
        );

        $UF = 'GO';
        $city = 'Goiânia';

        UFMunicipio::add_post_meta($postId, $city, $UF);

        $post = get_post($postId);

        $newPost = new RHSPost(0, $post);
        $returnUF = $newPost->getState();
        $this->assertInternalType('string', $returnUF);
        $this->assertEquals($returnUF, $UF);

        $returnCity = $newPost->getCity();
        $this->assertInternalType('string', $returnCity);
        $this->assertEquals($returnUF, $city);

        ####### Teste com post novo #######
        ###################################

        $newPost = self::create_post_to_queue();
        // Adiciona UF e Cidade
        UFMunicipio::add_post_meta($newPost->getId(), $city, $UF);

        $returnUF = $newPost->getState();
        $this->assertInternalType('string', $returnUF);
        $this->assertEquals($returnUF, $UF);

        $returnCity = $newPost->getCity();
        $this->assertInternalType('string', $returnCity);
        $this->assertEquals($returnUF, $city);
    }

    // Verifica adição e retorno de autor do post
    function test_getAndSetAuthor(){

        ####### Teste com post existente #######
        ########################################

        $post = $this->factory->post->create_and_get(
            ['post_title' => 'Portal 2', 'post_status' => 'publish', 'post_content' => 'The cake is a lie!', 'post_author' => self::$users['editor'][0]]
        );

        $newPost = new RHSPost(0, $post);
        $this->assertEquals($post->post_author, $newPost->getAuthorId());

        ####### Teste com post novo #######
        ###################################

        $newPost = self::create_post_to_queue();
        $this->assertEquals(get_current_user_id(), $newPost->getAuthorId());

        $newPost->setAuthorId(1);
        $this->assertEquals(1, $newPost->getAuthorId());
    }

    // Verificar adição e retorno de status
    function test_getAndSetStatus(){
        
        ####### Teste com post existente #######
        ########################################

        $post = $this->factory->post->create_and_get(
            ['post_title' => 'Portal 2', 'post_status' => 'publish', 'post_content' => 'The cake is a lie!', 'post_author' => self::$users['editor'][0]]
        );

        $newPost = new RHSPost(0, $post);
        $this->assertEquals($post->post_status, $newPost->getStatus());

        ####### Teste com post novo #######
        ###################################

        $newPost = self::create_post_to_queue();
        $newPost->setStatus('publish');
        
        $this->assertEquals('publish', $newPost->getStatus());
    }

    // Verificar adição e retorno da imagem destacada do post
    function test_getAndSetFeaturedImage(){

        ####### Teste com post novo #######
        ###################################

        $newPost = self::create_post_to_queue();

        $newPost->setFeaturedImageId(1);
        $this->assertEquals(1, $newPost->getFeaturedImageId());

        $newPost->setFeaturedImage('image');
        // Por não ter imagem no banco de dados o retorno será 'image' (ou vazio)
        $this->assertEquals('image', $newPost->getFeaturedImage());
    }
}