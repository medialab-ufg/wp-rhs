<?php
/**
 * Class FollowPostTest
 *
 * @package Rhs
 */

include_once('rhs-tests-setup.php');

/**
 * Follow Post test case.
 */
class FollowPostTest extends RHS_UnitTestCase {
    
    /**
	 * Testa função de seguir ou não seguir post
	 */
	function test_toggle_follow_post() {
        global $RHSFollowPost;
        $post_id = $this->factory->post->create(['post_title' => 'With a Little Help from My Friends']);

        $this->assertEquals(2, $RHSFollowPost->toggle_follow_post($post_id, self::$users['contributor'][1]));
        $this->assertEquals(1, $RHSFollowPost->toggle_follow_post($post_id, self::$users['contributor'][1]));
        $this->assertEquals(2, $RHSFollowPost->toggle_follow_post($post_id, self::$users['contributor'][1]));
    }
    
    /**
     * Testa se usuário já segue o post
     */
    function test_does_user_follow_post() {
        global $RHSFollowPost;
        $post_id = $this->factory->post->create(['post_title' => 'With a Little Help from My Friends']);
        
        $this->assertEquals(2, $RHSFollowPost->toggle_follow_post($post_id, self::$users['contributor'][1]));
        $this->assertEquals(true, $RHSFollowPost->does_user_follow_post($post_id, self::$users['contributor'][1]));
        
        $this->assertEquals(1, $RHSFollowPost->toggle_follow_post($post_id, self::$users['contributor'][1]));
        $this->assertEquals(false, $RHSFollowPost->does_user_follow_post($post_id, self::$users['contributor'][1]));
    }

    /**
     * Testa se meta key para usuário foi criado ou removido
     */
    function test_add_and_remove_follow_post() {
        global $RHSFollowPost;
        $post_id = $this->factory->post->create(['post_title' => 'With a Little Help from My Friends']);

        $this->assertInternalType("int", $RHSFollowPost->add_follow_post($post_id, self::$users['contributor'][1]));
        $this->assertEquals(true, $RHSFollowPost->does_user_follow_post($post_id, self::$users['contributor'][1]));
        $this->assertEquals(true, $RHSFollowPost->remove_follow_post($post_id, self::$users['contributor'][1]));   
        $this->assertEquals(false, $RHSFollowPost->does_user_follow_post($post_id, self::$users['contributor'][1]));
    }
}
