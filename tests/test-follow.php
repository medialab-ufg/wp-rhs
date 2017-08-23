<?php
/**
 * Class FollowTest
 *
 * @package Rhs
 */

include_once('rhs-tests-setup.php');

/**
 * Sample test case.
 */
class FollowTest extends RHS_UnitTestCase {
    
    /**
	 * Testa função de seguir ou não seguir autho
	 */
	function test_toggle_follow() {
        global $RHSFollow;

        $this->assertEquals(2, $RHSFollow->toggle_follow(self::$users['contributor'][0], self::$users['contributor'][1]));
        $this->assertEquals(1, $RHSFollow->toggle_follow(self::$users['contributor'][0], self::$users['contributor'][1]));
        $this->assertEquals(2, $RHSFollow->toggle_follow(self::$users['contributor'][0], self::$users['contributor'][1]));
    }
    
    /**
     * Testa se usuário já segue o autor
     */
    function test_does_user_follow_author() {
        global $RHSFollow;
        $this->assertEquals(2, $RHSFollow->toggle_follow(self::$users['contributor'][0], self::$users['contributor'][1]));
        $this->assertEquals(true, $RHSFollow->does_user_follow_author(self::$users['contributor'][0], self::$users['contributor'][1]));
        $this->assertEquals(1, $RHSFollow->toggle_follow(self::$users['contributor'][0], self::$users['contributor'][1]));
        $this->assertEquals(false, $RHSFollow->does_user_follow_author(self::$users['contributor'][0], self::$users['contributor'][1]));
    }

    /**
     * Testa o retorno da quantidade de usuários (seguido, seguindo)
     */
    function test_get_total_follows() {
        global $RHSFollow;

        $this->assertEquals(2, $RHSFollow->toggle_follow(self::$users['contributor'][0], self::$users['contributor'][1]));
        $this->assertEquals(1, $RHSFollow->get_total_follows(self::$users['contributor'][1], RHSFollow::FOLLOW_KEY));
        $this->assertEquals(1, $RHSFollow->get_total_follows(self::$users['contributor'][0], RHSFollow::FOLLOWED_KEY));
        $this->assertEquals(0, $RHSFollow->get_total_follows(self::$users['contributor'][1], RHSFollow::FOLLOWED_KEY));
    }

    /**
     * Testa retorno de autores que o usuário segue
     */
    function test_get_user_follows() {
        global $RHSFollow;
        // quando não há seguidores
        $get_user_meta_follow = $RHSFollow->get_user_follows(self::$users['contributor'][0]);
        $this->assertEquals($get_user_meta_follow, []);

        // quando não há seguindo
        $get_user_meta_followed = $RHSFollow->get_user_followers(self::$users['contributor'][0]);
        $this->assertEquals($get_user_meta_followed, []);

        // seguindo autor
        $this->assertEquals(2, $RHSFollow->toggle_follow(self::$users['contributor'][1], self::$users['contributor'][0]));

        // quando há seguidores
        $this->assertEquals([],$RHSFollow->get_user_followers(self::$users['contributor'][0]));
        
        // quando há seguindo
        $this->assertTrue(in_array(self::$users['contributor'][1] , $RHSFollow->get_user_follows(self::$users['contributor'][0])));
        $this->assertFalse(in_array(self::$users['editor'][0] , $RHSFollow->get_user_follows(self::$users['contributor'][1])));
    }

    /**
     * Testa se meta key para usuário foi criado ou removido
     */
    function test_add_and_remove_follow() {
        global $RHSFollow;
        $this->assertInternalType("int", $RHSFollow->add_follow(self::$users['contributor'][1], self::$users['contributor'][0]));
        $this->assertEquals(true, $RHSFollow->does_user_follow_author(self::$users['contributor'][1], self::$users['contributor'][0]));
        $this->assertEquals(true, $RHSFollow->remove_follow(self::$users['contributor'][1], self::$users['contributor'][0]));   
        $this->assertEquals(false, $RHSFollow->does_user_follow_author(self::$users['contributor'][1], self::$users['contributor'][0]));
    }
}
