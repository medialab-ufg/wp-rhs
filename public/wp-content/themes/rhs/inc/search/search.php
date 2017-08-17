<?php

class RHSSearch {

    function __construct() {
        add_action('pre_get_posts', array(&$this, 'pre_get_posts'));
    }

    function pre_get_posts($wp_query) {

        if ( $wp_query->is_main_query() && $wp_query->get( 'rhs_busca' ) == 'posts' ) {

            $wp_query->set('s', 'teste');

            

        }

    }


}

global $RHSSearch;
$RHSSearch = new RHSSearch();
