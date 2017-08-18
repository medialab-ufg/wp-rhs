<?php

class RHSSearch {

    function __construct() {
        add_action('pre_get_posts', array(&$this, 'pre_get_posts'), 2);
    }

    function pre_get_posts(&$wp_query) {

        if ( $wp_query->is_main_query() && $wp_query->get( 'rhs_busca' ) == 'posts' ) {

            $wp_query->is_home = false;

            $keyword = get_query_var('keyword');
            $uf = get_query_var('uf');
            $municipio = get_query_var('municipio');
            $date_from = get_query_var('date_from');
            $date_to = get_query_var('date_to');
            $order = get_query_var('rhs_order');
            $cat = get_query_var('cat');

            if (!empty($keyword)) {
                $wp_query->set('s', $keyword);
            }

            // DATAS
            if (!empty($date_from) || !empty($date_to)) {
                $date_query = [];
                $has_date_query = false;

                if (!empty($date_from) && $_datefrom = $this->parse_date($date_from)) {
                    $date_query['after'] = $_datefrom;
                    $has_date_query = true;
                }

                if (!empty($date_to) && $_dateto = $this->parse_date($date_to)) {
                    $date_query['before'] = $_dateto;
                    $has_date_query = true;
                }

                if ($has_date_query) {
                    $date_query['inclusive'] = true;
                    $wp_query->set('date_query', [$date_query]);
                }

            }

            // ESTADOS E MUNICIPIOS
            if (!empty($uf) || !empty($municipio)) {
                $meta_query = [];
                $has_meta_query = false;

                if (!empty($municipio)) {

                    preg_match('/([0-9]{7})-.+$', $municipio, $cod_municipio);

                    if (is_numeric($cod_municipio[1])) {
                        $meta_query['municipio'] = [
                            'key' => UFMunicipio::MUN_META,
                            'value' => $cod_municipio[1],
                            'compare' => '='
                        ];
                        $has_meta_query = true;
                    }


                }

            if (!empty($uf) && !isset($meta_query['municipio']) /* se já tem municipio não precisa filtrar por estado tb */ ) {

                    $cod_uf = UFMunicipio::get_uf_id_from_sigla($uf);

                    if (is_numeric($cod_uf)) {
                        $meta_query['uf'] = [
                            'key' => UFMunicipio::UF_META,
                            'value' => $cod_uf,
                            'compare' => '='
                        ];
                        $has_meta_query = true;
                    }

                }

                if ($has_meta_query) {
                    $date_query['relation'] = 'AND';
                    $wp_query->set('meta_query', [$meta_query]);
                }

            }


var_dump($meta_query);


        }

    }

    private function parse_date($str_date) {

        preg_match('/([0-9]{4})-([0-9]{2})-([0-9]{2})', $str_date, $matches);

        if (isset($matches[1]) && isset($matches[2]) && isset($matches[3])) {
            return [
                'year' => $matches[1],
                'month' => $matches[2],
                'day' => $matches[3]
            ];
        }

        return false;

    }


}

global $RHSSearch;
$RHSSearch = new RHSSearch();
