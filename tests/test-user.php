<?php

include_once ('rhs-tests-setup.php');

class UserTest extends RHS_UnitTestCase {

    /*
     * Testa se o usuário adicionou um link válido ao seu perfil
     * */
    function test_user_valid_url() {
        global $RHSUsers;

        self::assertTrue( $RHSUsers->check_valid_user_link("www.ufg.br") );
        self::assertTrue( $RHSUsers->check_valid_user_link("http://www.ufg.br") );
        self::assertFalse( $RHSUsers->check_valid_user_link("ufg") );
    }
}