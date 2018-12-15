<?php
include_once ('rhs-tests-setup.php');

class RegisterTest extends RHS_UnitTestCase {

    function test_is_email_blacklisted() {
        global $RHSRegister;

        $list = $RHSRegister->getBlacklist();
        $index = rand(0, (count($list) - 1));
        $random = $list[$index];

        self::assertTrue( $RHSRegister->is_email_blacklisted("anymail.me@$random"));
        self::assertTrue( $RHSRegister->is_email_blacklisted('opa@fast-mail.host'));
        self::assertTrue( $RHSRegister->is_email_blacklisted('anything-here@done.pl'));
        self::assertTrue( $RHSRegister->is_email_blacklisted('random@dnane.fun'));
    }
}