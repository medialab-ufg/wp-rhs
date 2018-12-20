<?php

trait emailValidator {

    function getBlacklist() {
        return [
            'ezen74.pl',
            'fast-mail.host',
            'scriptmail.com',
            'nameofname.pw',
            'gmx.com',
            'nwytg.net',
            'geguke@geroev.net',
            'servicesp.bid',
            'gbl-cleaner.de',
            'hovercraft-italia.eu',
            'zzzzg.club',
            'syrob.laohost.net',
        ];
    }

    function is_email_blacklisted($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_domain = substr($email, strpos($email,'@') + 1);
            return (in_array($_domain, $this->getBlacklist()) || $this->is_tld_blacklisted($_domain));
        }

        // Nem e-mail válido é
        return true;
    }

    function is_tld_blacklisted($domain) {
        $_blacklist_tld = ['.pl', 'fun', '.eu'];
        $_TLD = substr($domain, -3);

        return in_array($_TLD, $_blacklist_tld);
    }
}