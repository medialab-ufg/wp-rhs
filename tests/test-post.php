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
    

	function test_construct() {
        
        $post_id = $this->factory->post->create(['post_title' => 'With a Little Help from My Friends']);
        $post = get_post($post_id);
        
        $test = new RHSPost($post_id);
        $test_object = new RHSPost(0, $post);
        
        $this->assertEquals($post_id, $test->getId());
        $this->assertEquals($post_id, $test_object->getId());
    }
    
}