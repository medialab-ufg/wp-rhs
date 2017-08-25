<?php
/**
 * Class NotificationsTest
 *
 * @package Rhs
 */

include_once('rhs-tests-setup.php');

/**
 * Sample test case.
 */
class NotificationsTest extends RHS_UnitTestCase {

    
    function test_default_channels() {
        global $RHSNotifications;
        $this->assertContains(sprintf(RHSNotifications::CHANNEL_PRIVATE, self::$users['contributor'][0]), $RHSNotifications::get_user_channels(self::$users['contributor'][0]));
        $this->assertContains(RHSNotifications::CHANNEL_EVERYONE, $RHSNotifications::get_user_channels(self::$users['contributor'][0]));
    }
    
    
    /**
	 * Testa notificações
	 */
	function test_post_promoted() {

        global $RHSVote;
        global $RHSPosts;
        global $RHSNotifications;

        // Cria um post como colaborador1
        wp_set_current_user(self::$users['contributor'][0]);
        $newpost = self::create_post_to_queue();

        // Damos cinco votos ao post
        $RHSVote->add_vote($newpost->getId(), self::$users['voter'][0]);
        $RHSVote->add_vote($newpost->getId(), self::$users['voter'][1]);
        $RHSVote->add_vote($newpost->getId(), self::$users['voter'][2]);
        $RHSVote->add_vote($newpost->getId(), self::$users['voter'][3]);
        $RHSVote->add_vote($newpost->getId(), self::$users['voter'][4]);
        
        // esperamos que tenha uma notificação
        $this->assertEquals(1, $RHSNotifications->get_news_number(self::$users['contributor'][0]));
        $this->assertEquals($newpost->getId(), $RHSNotifications->get_news(self::$users['contributor'][0])[0]->getObjectId());
        
        
        

	}
    
    function test_add_remove_from_channel() {
        
        global $RHSNotifications;
        
        $RHSNotifications->add_user_to_channel(RHSNotifications::CHANNEL_COMMUNITY, 33, self::$users['contributor'][0]);
        
        $this->assertContains(sprintf(RHSNotifications::CHANNEL_COMMUNITY, 33), $RHSNotifications::get_user_channels(self::$users['contributor'][0]));
        
        $RHSNotifications->delete_user_from_channel(RHSNotifications::CHANNEL_COMMUNITY, 33, self::$users['contributor'][0]);
        
        $this->assertNotContains(sprintf(RHSNotifications::CHANNEL_COMMUNITY, 33), $RHSNotifications::get_user_channels(self::$users['contributor'][0]));
        
        
        
    }

    function test_follow() {
        
        global $RHSNotifications;
        global $RHSFollow;
        global $RHSPosts;
        
        // usuario 1 segue o 0
        $RHSFollow->toggle_follow(self::$users['contributor'][0], self::$users['contributor'][1]);
        
        // deve ter sido registrado no canal
        $this->assertContains(sprintf(RHSNotifications::CHANNEL_USER, self::$users['contributor'][0]), $RHSNotifications::get_user_channels(self::$users['contributor'][1]));
        
        // se o usuário criar um post ele tem q receber uma notificação
        wp_set_current_user(self::$users['contributor'][0]);
        $newpost = self::create_post_to_queue();
        
        $this->assertEquals(1, $RHSNotifications->get_news_number(self::$users['contributor'][1]));
        $this->assertEquals($newpost->getId(), $RHSNotifications->get_news(self::$users['contributor'][1])[0]->getObjectId());
        
        
    }




}
