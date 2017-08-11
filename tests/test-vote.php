<?php
/**
 * Class SampleTest
 *
 * @package Rhs
 */

include('rhs-tests-setup.php');

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
            //$postObj->setComunities($_POST['comunity-status']);

            $newpost = $RHSPosts->insert($postObj);

            // verifica se o post foi salvo e está na fila de votação
            $this->assertInternalType("int", $newpost->getId());
            $this->assertEquals(self::$users['contributor'][0], $newpost->getAuthorId());
            $this->assertEquals($RHSVote::VOTING_QUEUE, $newpost->getStatus());

	}





}
