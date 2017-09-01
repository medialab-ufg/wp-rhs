<?php
/**
 * Class StatisticsTest
 *
 * @package Rhs
 */

include_once('rhs-tests-setup.php');

/**
 * Sample test case.
 */
class StatisticsTest extends RHS_UnitTestCase {

    
	function test_post_promoted() {

        global $RHSVote;
        global $RHSPosts;
        global $RHSStats;

        // Cria um post como colaborador1
        wp_set_current_user(self::$users['contributor'][0]);
        $newpost = self::create_post_to_queue();

        // Damos cinco votos ao post
        $RHSVote->add_vote($newpost->getId(), self::$users['voter'][0]);
        $RHSVote->add_vote($newpost->getId(), self::$users['voter'][1]);
        $RHSVote->add_vote($newpost->getId(), self::$users['voter'][2]);
        $RHSVote->add_vote($newpost->getId(), self::$users['voter'][3]);
        $RHSVote->add_vote($newpost->getId(), self::$users['voter'][4]);
        
        // esperamos que tenha gerado um registro
        $this->assertEquals(1, $RHSStats->get_total_events_by_action(RHSStats::ACTION_POST_PROMOTED));

	}
    

    function test_follow() {
        
        global $RHSStats;
        global $RHSFollow;
        
        // usuario 1 segue o 0
        $RHSFollow->toggle_follow(self::$users['contributor'][0], self::$users['contributor'][1]);
        
        // deve ter gerado registro
        $this->assertEquals(1, $RHSStats->get_total_events_by_action(RHSStats::ACTION_FOLLOW_USER));
        
        $RHSFollow->toggle_follow(self::$users['contributor'][0], self::$users['contributor'][1]);
        $this->assertEquals(1, $RHSStats->get_total_events_by_action(RHSStats::ACTION_UNFOLLOW_USER));
        
        
    }
    
    function test_follow_post() {
        
        global $RHSStats;
        global $RHSFollowPost;
        
        wp_set_current_user(self::$users['editor'][0]);
        $newpost = self::create_post_to_queue();
        
        // usuario 1 o post
        $RHSFollowPost->toggle_follow_post($newpost->getId(), self::$users['contributor'][1]);
        
        // deve ter gerado registro
        $this->assertEquals(1, $RHSStats->get_total_events_by_action(RHSStats::ACTION_FOLLOW_POST));
        
        $RHSFollowPost->toggle_follow_post($newpost->getId(), self::$users['contributor'][1]);
        $this->assertEquals(1, $RHSStats->get_total_events_by_action(RHSStats::ACTION_UNFOLLOW_POST));
        
        
    }
    /*
    function test_communities() {
        
        global $RHSNotifications;
        
        $c = self::create_community('private');
        
        // membro
        RHSComunities::add_user_comunity($c, self::$users['contributor'][0]);
        
        //membro e seguidor
        RHSComunities::add_user_comunity($c, self::$users['contributor'][1]);
        RHSComunities::add_user_comunity_follow($c, self::$users['contributor'][1]);
        
        // membro e seguidor deve ter sido registrado no canal, mas o q é só membro não
        $this->assertContains(sprintf(RHSNotifications::CHANNEL_COMMUNITY, $c), $RHSNotifications::get_user_channels(self::$users['contributor'][1]));
        $this->assertNotContains(sprintf(RHSNotifications::CHANNEL_COMMUNITY, $c), $RHSNotifications::get_user_channels(self::$users['contributor'][0]));
        
        // coloca o outro pra seguir tb
        RHSComunities::add_user_comunity_follow($c, self::$users['contributor'][0]);
        
        // cria um post na comunidade
        wp_set_current_user(self::$users['contributor'][0]);
        $newpost = self::create_post_to_private_community($c);
        
        $this->assertEquals(1, $RHSNotifications->get_news_number(self::$users['contributor'][1]));
        $this->assertEquals($newpost->getId(), $RHSNotifications->get_news(self::$users['contributor'][1])[0]->getObjectId());
        
        // o autor do post não deve receber notificação do seu proprio post
        $this->assertEquals(0, $RHSNotifications->get_news_number(self::$users['contributor'][0]));
        
    }
    */


}
