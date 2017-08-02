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
	function test_sample() {
		
        global $RHSVote;
        
        //var_dump(get_user_by('login', 'editor1'));
        
        $this->assertEquals($RHSVote->votes_to_approval_default, $RHSVote->votes_to_approval);
        
	}
}
