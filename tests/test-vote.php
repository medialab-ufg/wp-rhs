<?php
/**
 * Class SampleTest
 *
 * @package Rhs
 */

include_once('rhs-tests-setup.php');

/**
 * Sample test case.
 */
class VoteTest extends RHS_UnitTestCase {

    /**
     * Testa se o valor de votos para promoção está default
     */
    function test_default_votes_to_approval() {
        global $RHSVote;

        $this->assertEquals($RHSVote->votes_to_approval_default, $RHSVote->votes_to_approval);

        // Assumimos que o padrão é 5, pois os testes dependem disso
        $this->assertEquals(5, $RHSVote->votes_to_approval);
    }
    /**
	 * Testa se o valor de votos para promoção está default
	 */
	function test_contributor_add_post() {

        global $RHSVote;
        global $RHSPosts;

        // Cria um post como colaborador1
            wp_set_current_user(self::$users['contributor'][0]);

            // emulando o méodo RHSPosts::trigger_by_post();
            $postObj = new RHSPost();
            //$postObj->setId( $_POST['post_ID'] );
            $postObj->setTitle( 'teste1' );
            $postObj->setContent( 'teste1' );
            $postObj->setStatus( 'publish' ); // status que vem do formulário. A intenção é q nesse caso vá pra fila de votação
            $postObj->setAuthorId( get_current_user_id() );
            $postObj->setCategoriesId( [$this->test_category_id] );
            //$postObj->setState( $_POST['estado'] );
            //$postObj->setCity( $_POST['municipio'] );
            //$postObj->setTags( $_POST['tags'] );
            //$postObj->setFeaturedImageId( $_POST['img_destacada'] );
            $postObj->setComunities(['public']);

            $newpost = $RHSPosts->insert($postObj);

            // verifica se o post foi salvo e está na fila de votação
            $this->assertInternalType("int", $newpost->getId());
            $this->assertEquals(self::$users['contributor'][0], $newpost->getAuthorId());
            $this->assertEquals($RHSVote::VOTING_QUEUE, $newpost->getStatus());

            // Se modificar e salvar o post, o status deve continuar o mesmo.
            $newpost->setContent( 'teste1 teste' );
            $newpost->setStatus( 'publish' );
            $savedPost = $RHSPosts->insert($newpost);
            $this->assertEquals($RHSVote::VOTING_QUEUE, $savedPost->getStatus());

            /// outro post de outro usuário como rascunho primeiro
            wp_set_current_user(self::$users['contributor'][1]);

            $postObj = new RHSPost();
            //$postObj->setId( $_POST['post_ID'] );
            $postObj->setTitle( 'teste2' );
            $postObj->setContent( 'teste2' );
            $postObj->setStatus( 'draft' ); // status que vem do formulário. A intenção é q nesse caso fique como rascunho
            $postObj->setAuthorId( get_current_user_id() );
            $postObj->setCategoriesId( [$this->test_category_id] );
            $postObj->setComunities(['public']);

            $newpost = $RHSPosts->insert($postObj);
            // verifica se o post foi salvo e está como rascunho
            $this->assertInternalType("int", $newpost->getId());
            $this->assertEquals(self::$users['contributor'][1], $newpost->getAuthorId());
            $this->assertEquals('draft', $newpost->getStatus());

            // Se modificar e puiblicar o post, tem q ir pra fila de votação
            $newpost->setContent( 'teste2 teste' );
            $postObj->setStatus( 'publish' );
            $savedPost = $RHSPosts->insert($newpost);
            $this->assertEquals($RHSVote::VOTING_QUEUE, $savedPost->getStatus());


            // Colaborador 1 tenta votar no post do colaborador 2 e não consegue pq não pode.
            wp_set_current_user(self::$users['contributor'][0]);
            $this->assertEquals(false, $RHSVote->add_vote($savedPost->getId()));

            // O colaborador 2 não tem o role voter
            $this->assertEquals(false, user_can(self::$users['contributor'][1], 'voter'));

            // 3 votantes votam no post do colaborador 2
            $this->assertEquals( true, $RHSVote->add_vote($savedPost->getId(), self::$users['voter'][0]) );
            $this->assertEquals( true, $RHSVote->add_vote($savedPost->getId(), self::$users['voter'][1]) );
            $this->assertEquals( true, $RHSVote->add_vote($savedPost->getId(), self::$users['voter'][2]) );

            // O total de votos desse post tem q ser 3
            $this->assertEquals( 3, $RHSVote->get_total_votes($savedPost->getId()) );

            // o votante 2 vai tentar votar de novo e não pode conseguir
            $this->assertEquals( false, $RHSVote->add_vote($savedPost->getId(), self::$users['voter'][2]) );

            // Mais dois votos de outros dois votantes
            $this->assertEquals( true, $RHSVote->add_vote($savedPost->getId(), self::$users['voter'][3]) );
            $this->assertEquals( true, $RHSVote->add_vote($savedPost->getId(), self::$users['voter'][4]) );

            // ESTAMOS ASSUMINDO QUE O PADRÃO DE VOTOS É 5
            
            // verificar se o total de votos que esse usuário tem é 5
            $this->assertEquals(5, $RHSVote->get_total_votes_by_author(self::$users['contributor'][1]));

            // O colaborador 2 agora é pra ter o role voter
            $this->assertEquals(true, user_can(self::$users['contributor'][1], 'voter'));

            // O post deve ter sido promovido
            $updatedPost = new RHSPost($savedPost->getId());
            $this->assertEquals('publish', $updatedPost->getStatus());
            // O post tem o metadado indicando q foi promovido
            $this->assertEquals('1', get_post_meta($savedPost->getId(), RHSVote::META_PUBISH, true));


            // Stats
            global $RHSNetwork;

            // adiciona alguns views e shares ao post
            $RHSNetwork->add_data($savedPost->getId(), RHSNetwork::META_KEY_VIEW);
            $RHSNetwork->add_data($savedPost->getId(), RHSNetwork::META_KEY_VIEW);
            $RHSNetwork->add_data($savedPost->getId(), RHSNetwork::META_KEY_VIEW);
            $RHSNetwork->add_data($savedPost->getId(), RHSNetwork::META_KEY_VIEW);
            $RHSNetwork->add_data($savedPost->getId(), RHSNetwork::META_KEY_VIEW);
            $RHSNetwork->add_data($savedPost->getId(), RHSNetwork::META_KEY_FACEBOOK);
            $RHSNetwork->add_data($savedPost->getId(), RHSNetwork::META_KEY_FACEBOOK);
            $RHSNetwork->add_data($savedPost->getId(), RHSNetwork::META_KEY_WHATSAPP);
            $RHSNetwork->add_data($savedPost->getId(), RHSNetwork::META_KEY_WHATSAPP);
            $RHSNetwork->add_data($savedPost->getId(), RHSNetwork::META_KEY_WHATSAPP);
            $RHSNetwork->add_data($savedPost->getId(), RHSNetwork::META_KEY_TWITTER);

            // checamos a função get data e se os totais salvaram corretamente
            $this->assertEquals(5, $RHSNetwork->get_data($savedPost->getId(), RHSNetwork::META_KEY_VIEW));
            $this->assertEquals(2, $RHSNetwork->get_data($savedPost->getId(), RHSNetwork::META_KEY_FACEBOOK));
            $this->assertEquals(3, $RHSNetwork->get_data($savedPost->getId(), RHSNetwork::META_KEY_WHATSAPP));
            $this->assertEquals(1, $RHSNetwork->get_data($savedPost->getId(), RHSNetwork::META_KEY_TWITTER));
            $this->assertEquals(5, $RHSNetwork->get_data($savedPost->getId(), RHSNetwork::META_KEY_TOTAL_SHARES));



	}




}
