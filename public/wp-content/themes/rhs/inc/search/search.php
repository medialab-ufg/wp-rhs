<?php

class RHSSearch {

    const BASE_URL = 'busca';
    const BASE_USERS_URL = 'busca/usuarios';

    function __construct() {
        add_action('pre_get_posts', array(&$this, 'pre_get_posts'), 2);
    }

    function pre_get_posts(&$wp_query) {

        if ( $wp_query->is_main_query() && $wp_query->get( 'rhs_busca' ) == 'posts' ) {

            $wp_query->is_home = false;

            $keyword =      $this->get_param('keyword');
            $uf =           $this->get_param('uf');
            $municipio =    $this->get_param('municipio');
            $date_from =    $this->get_param('date_from');
            $date_to =      $this->get_param('date_to');
            $order =        $this->get_param('rhs_order');
            //$cat =          $this->get_param('cat');

            /**
            * Tags e categorias são buscadas automaticamente passando os parametros padrão do WP
            * Ex: &cat=3&tag=2
            */

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

            $meta_query = [];
            $has_meta_query = false;

            // ESTADOS E MUNICIPIOS
            if (!empty($uf) || !empty($municipio)) {

                if (!empty($municipio)) {

                    preg_match('/([0-9]{7})-.+$/', $municipio, $cod_municipio);

                    if (is_numeric($cod_municipio[1])) {
                        $meta_query['municipio_clause'] = [
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
                        $meta_query['uf_clause'] = [
                            'key' => UFMunicipio::UF_META,
                            'value' => $cod_uf,
                            'compare' => '='
                        ];
                        $has_meta_query = true;
                    }

                }

            }

            // ORDER
            switch ($order) {
                case 'comments':
                    $q_order = 'DESC';
                    $q_order_by = 'comment_count';
                    break;

                // META KEYS
                case 'votes':
                    $q_order_meta = RHSVote::META_TOTAL_VOTES;
                    break;
                case 'shares':
                    $q_order_meta = RHSNetwork::META_KEY_TOTAL_SHARES;
                    break;
                case 'views':
                    $q_order_meta = RHSNetwork::META_KEY_VIEW;
                    break;

                case 'date':
                default:
                    $q_order = 'DESC';
                    $q_order_by = 'post_date';
                    break;
            }

            if (!empty($q_order_meta)) {
                $meta_query['rhs_meta_order'] = [
                    'key' => $q_order_meta,
                    'compare' => 'EXISTS',
                    'type' => 'numeric'
                ];
                $has_meta_query = true;
                $q_order_by = ['rhs_meta_order' => 'DESC'];
                $q_order = 'DESC';
            }

            if ($has_meta_query) {
                $meta_query['relation'] = 'AND';
                $wp_query->set('meta_query', [$meta_query]);
            }

            $wp_query->set('order', $q_order);
            $wp_query->set('orderby', $q_order_by);

        }

    }

    private function parse_date($str_date) {

        preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $str_date, $matches);

        if (isset($matches[1]) && isset($matches[2]) && isset($matches[3])) {
            return [
                'year' => $matches[1],
                'month' => $matches[2],
                'day' => $matches[3]
            ];
        }

        return false;

    }

    public function get_param($param) {
        if (isset($_GET[$param]))
            return $_GET[$param];
        return get_query_var($param);
    }


}

global $RHSSearch;
$RHSSearch = new RHSSearch();
