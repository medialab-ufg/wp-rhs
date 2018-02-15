<?php
/**
 * Class RecommendPostTest
 *
 * @package Rhs
 */

include_once('rhs-tests-setup.php');

/**
 * Recommend Post test case.
 */
class RecommendPostTest extends RHS_UnitTestCase {
    
    /**
	 * Testa função de recomendar o post
	 */
	function test_add_recomment_post() {
        global $RHSRecommendPost;
        $post_id = $this->factory->post->create(['post_title' => 'Hit the road Jack!']);
        $recommend_to_user = self::$users['contributor'][0];
        $current_user = wp_get_current_user();
        
        $data['user'] = array(
            'user_id' => $recommend_to_user,
            'post_id' => $post_id,
            'recommend_from' => $current_user,
            'value' => $current_user->display_name
        );

        $this->assertEquals(0, $RHSRecommendPost->add_recomment_post($post_id, $recommend_to_user, $current_user, $data));
    }
    
}
