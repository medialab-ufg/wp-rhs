<?php
/**
 * Class SampleTest
 *
 * @package Rhs
 */

/**
 * Sample test case.
 */
abstract class RHS_UnitTestCase extends WP_UnitTestCase {


    /**
     * Usuários para testes
     */
    const testUsers = [
        [
            'user_login' => 'editor1',
            'user_pass'  => '123',
            'user_email' => 'editor1@test.com',
            'display_name' => 'Editor 1',
            'role' => 'editor'
        ],
        [
            'user_login' => 'editor2',
            'user_pass'  => '123',
            'user_email' => 'editor2@test.com',
            'display_name' => 'Editor 2',
            'role' => 'editor'
        ],
        [
            'user_login' => 'votante1',
            'user_pass'  => '123',
            'user_email' => 'votante1@test.com',
            'display_name' => 'votante1',
            'role' => 'voter'
        ],
        [
            'user_login' => 'votante2',
            'user_pass'  => '123',
            'user_email' => 'votante2@test.com',
            'display_name' => 'votante2',
            'role' => 'voter'
        ],
        [
            'user_login' => 'votante3',
            'user_pass'  => '123',
            'user_email' => 'votante3@test.com',
            'display_name' => 'votante3',
            'role' => 'voter'
        ],
        [
            'user_login' => 'votante4',
            'user_pass'  => '123',
            'user_email' => 'votante4@test.com',
            'display_name' => 'votante4',
            'role' => 'voter'
        ],
        [
            'user_login' => 'votante5',
            'user_pass'  => '123',
            'user_email' => 'votante5@test.com',
            'display_name' => 'votante5',
            'role' => 'voter'
        ],
        [
            'user_login' => 'colaborador1',
            'user_pass'  => '123',
            'user_email' => 'colaborador1@test.com',
            'display_name' => 'colaborador1',
            'role' => 'contributor'
        ],
        [
            'user_login' => 'colaborador2',
            'user_pass'  => '123',
            'user_email' => 'colaborador2@test.com',
            'display_name' => 'colaborador2',
            'role' => 'contributor'
        ],

    ];

    protected static $users;
    protected static $test_cat;


    /**
	 * Setup Fixtures
	 */
	public static function setUpBeforeClass() {

        // Create users

		$__users = [];

        foreach (self::testUsers as $user) {
			$uid = wp_insert_user( $user ) ;
            if (!isset($__users[$user['role']]))
                $__users[$user['role']] = [];
            $__users[$user['role']][] = $uid;
        }

        self::$users = $__users;

        // Cria uma categoria para testes
        $my_cat = array('cat_name' => 'My Category', 'category_description' => 'A Cool Category', 'category_nicename' => 'category-slug', 'category_parent' => '');

        // Create the category
        //$this->test_category_id = wp_insert_category($my_cat);
        self::$test_cat = wp_insert_category($my_cat);


	}
}
