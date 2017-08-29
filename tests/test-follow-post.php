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
     * Testa retorno de autores que o usuário segue
     */
    function test_get_post_followers() {
        global $RHSFollowPost;
        $post_id = $this->factory->post->create(['post_title' => 'With a Little Help from My Friends']);

        // quando um post não tem seguidores
        $get_post_meta_follow = $RHSFollowPost->get_post_followers($post_id);
        $this->assertEquals([], $get_post_meta_follow);

        // post sendo seguido
        $this->assertEquals(2, $RHSFollowPost->toggle_follow_post($post_id, self::$users['contributor'][0]));

        // quando há um post sendo seguido
        $this->assertTrue(in_array(self::$users['contributor'][0], $RHSFollowPost->get_post_followers($post_id)));
        $this->assertFalse(in_array(self::$users['editor'][0], $RHSFollowPost->get_post_followers($post_id)));
    }

    function test_get_posts_followed_by_user() {
        global $RHSFollowPost;
        $post_id = $this->factory->post->create(['post_title' => 'With a Little Help from My Friends']);

        // quando um usuário não está seguindo um post
        $get_user_meta_followed = $RHSFollowPost->get_posts_followed_by_user(self::$users['contributor'][0]);
        $this->assertEquals($get_user_meta_followed, []);

        // passando a seguir post
        $this->assertEquals(2, $RHSFollowPost->toggle_follow_post($post_id, self::$users['contributor'][0]));

        // quando há um post sendo seguido
        $this->assertTrue(in_array($post_id, $RHSFollowPost->get_posts_followed_by_user(self::$users['contributor'][0])));
        $this->assertFalse(in_array(self::$users['editor'][0], $RHSFollowPost->get_posts_followed_by_user(self::$users['contributor'][0])));
    }

    /**
     * Testa se meta key para usuário foi criado ou removido
     */
    function test_add_and_remove_follow_post() {
        global $RHSFollowPost;
        $post_id = $this->factory->post->create(['post_title' => 'With a Little Help from My Friends']);

        $this->assertEquals(false, $RHSFollowPost->does_user_follow_post($post_id, self::$users['contributor'][1]));
        $this->assertInternalType("int", $RHSFollowPost->add_follow_post($post_id, self::$users['contributor'][1]));
        $this->assertEquals(true, $RHSFollowPost->does_user_follow_post($post_id, self::$users['contributor'][1]));
        $this->assertContains(self::$users['contributor'][1], $RHSFollowPost->get_post_followers($post_id));
        $this->assertEquals(true, $RHSFollowPost->remove_follow_post($post_id, self::$users['contributor'][1]));   
        $this->assertEquals(false, $RHSFollowPost->does_user_follow_post($post_id, self::$users['contributor'][1]));
    }
}
