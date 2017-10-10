<?php

class RHSSearch {

    const BASE_URL = 'busca';
    const BASE_USERS_URL = 'busca/usuarios';
    const USERS_PER_PAGE = 12;

    function __construct() {
        add_action('pre_get_posts', array(&$this, 'pre_get_posts'), 2);
        add_action('wp_enqueue_scripts', array(&$this, 'addJS'), 2);
    }
    
    function addJS() {
        if (get_query_var('rhs_busca')) {
            wp_enqueue_script('rhs_search', get_template_directory_uri() . '/inc/search/search.js', array('bootstrap-datapicker', 'magicJS'));
            wp_localize_script( 'rhs_search', 'search_vars', array( 
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'selectedTags' => $this->get_param('tag')
            ) );
        }
    }
    
    static function get_query_string_for_search_urls() {
        $q = [];
        if (self::get_param('keyword')) $q[] = 'keyword=' . self::get_param('keyword');
        if (self::get_param('uf')) $q[] = 'uf=' . self::get_param('uf');
        if (self::get_param('municipio')) $q[] = 'municipio=' . self::get_param('municipio');
        return '?' . implode('&', $q);
    }
    
    /**
     * Monta nova URL de busca, mantendo os parametros de busca atuais, mas removendo a paginação e acrescentando o paramentro de ordenação
     * @param  string $neworder tipo de rodenação
     * @return string           Nova URL
     */
    static function get_search_neworder_urls($neworder) {
        
        
        $base = get_query_var('rhs_busca') == 'users' ? self::BASE_USERS_URL : self::BASE_URL;
        $busca_atual = $_GET;
        
        // Nos certificamos de adicionar estado e cidade a busca, pq eles podem ter vindo
        // na URL (ex: /CE/1234567-fortaleza) e não estar no GET
        $busca_atual['uf'] = self::get_param('uf');
        $busca_atual['municipio'] = self::get_param('municipio');
        
        // Adicionamos a ordenação
        $busca_atual['rhs_order'] = $neworder;
        
        // isso não deve existir, mas não custa excluir
        // apenas de retornar a URL nova, sem o /page/X já resolve
        unset($busca_atual['paged']);
        
        return add_query_arg( $busca_atual, home_url($base) ); 
    }
    
    static function get_search_url() {
        $querystring = self::get_query_string_for_search_urls();
        return home_url(self::BASE_URL) . $querystring;
    }
    
    static function get_users_search_url() {
        $querystring = self::get_query_string_for_search_urls();
        return home_url(self::BASE_USERS_URL) . $querystring;
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

            /**
            * Tags e categorias são buscadas automaticamente passando os parametros padrão do WP
            * Ex: &cat=3&tag=2
            *
            * A informação de paginação também já é magicamente tratada pelo WP, pq jogamos ela pra query_var 'paged' lá na rewrite rule
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

                    if (is_numeric($municipio)) {
                        $meta_query['municipio_clause'] = [
                            'key' => UFMunicipio::MUN_META,
                            'value' => $municipio,
                            'compare' => '='
                        ];
                        $has_meta_query = true;
                    }


                }

                if (!empty($uf) && !isset($meta_query['municipio']) /* se já tem municipio não precisa filtrar por estado tb */ ) {

                    $cod_uf = is_numeric($uf) ? $uf : UFMunicipio::get_uf_id_from_sigla($uf);
                    
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
            $wp_query->set('post_type', 'post');

        }

    }
    
    /**
     * Faz parse de uma string de data no formato YYYY-MM-DD e retorna 
     * um array no formato utilizado nas meta_queries do WP.
     *
     * Se a string não for no formato esperado, retorna False.
     * 
     * @param  string $str_date string no formato YYYY-MM-DD
     * @return array|false           array composto de de três elementos, com as chaves year, month e day e seus respectivos valores. Falso caso a string não seja no formato esperado
     */
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
    
    /**
     * Retorna o valor do parâmetro, que pode estar sendo passado via URL (rewrite rule)
     * ou diretamente via query string. 
     * 
     * Por exemplo, a função retorna o valor para 'uf' e para 'paged' nos dois formatos:
     * /busca/BA/page/2
     * /busca?uf=BA&paged=2
     * 
     * @param  string $param o nome do parâmetro
     * @return string        o valor do parâmetro
     */
    static public function get_param($param) {
        if (isset($_GET[$param]))
            return $_GET[$param];
            
        $value = get_query_var($param);
        
        if (empty($value) && $param == 'keyword')
            $value = get_query_var('s');
        
        if ($param == 'municipio') {
            // podemos passar só o ID do município, ex: 2900702
            // ou o formato da URL, com {id}-{slug}, ex: 2900702-alagoinhas
            preg_match('/^([0-9]{7})(-.+)?$/', $value, $cod_municipio);
            if (is_array($cod_municipio) && isset($cod_municipio[1]) && is_numeric($cod_municipio[1]))
                $value = $cod_municipio[1];
        }
        
        if ($param == 'uf' && !is_numeric($value))
            $value = UFMunicipio::get_uf_id_from_sigla($value);
        
        return $value;
    }
    

    /**
     * Busca usuários com filtros específicos da RHS
     *
     * Pode-se chamar o método sem nenhum parâmetro (como no template search-users.php). Neste Cadastro
     * serão usados os parâmetros disponíveis na URL de busca.
     * 
     * Opcionalmente pode-se chamar o método passando manualmente os filtros de busca em um array.
     *
     * Os parâmetros possíveis são:
     * $params = array(
     *     'uf' => 'BA', // pode ser a sigla ou id do estado
     *     'municipio' => '2922999' // pode ser o id ou url do municipio (ex: 2922999-nome-da-cidade)
     *     'keyword' => 'aless' // string de busca por nome do usuário
     *     'paged' => 2 // número da página para paginação de resultados,
     *     'rhs_order' => 'votes' // ordenação dos resultados. Valores possíveis são: name, register_date, posts, votes
     * )
     *
     * Exemplos de URs que funcionam:
     * /busca/usuarios/BA
     * /busca/usuarios/BA/page/2/?keyword=caetano
     * /busca/usuarios/?uf=BA&keyword=caetano&paged=2&rhs_order=votes
     * /busca/usuarios/?uf=29&keyword=caetano&paged=2&rhs_order=votes
     *
     * 
     * @param  array  $params opcional, os filtros de busca
     * @return Object WP_User_Query 
     */
    public function search_users($params = array()) {
        $users_per_page = self::USERS_PER_PAGE;
        $meta_query = [];
        $has_meta_query = false;
        
        $_filters = array_merge([
            'uf' => $this->get_param('uf'),
            'keyword' => $this->get_param('keyword'),
            'municipio' => $this->get_param('municipio'),
            'rhs_order' => $this->get_param('rhs_order'),
            'paged' => get_query_var('paged'),
        ], $params);
        
        $keyword =      $_filters['keyword'];
        $uf =           $_filters['uf'];
        $municipio =    $_filters['municipio'];
        $rhs_order =    $_filters['rhs_order'];
        $paged =    $_filters['paged'] ? $_filters['paged'] : 1;
        
      
        if (!empty($uf) || !empty($municipio)) {
            if (!empty($municipio)) {
                // podemos passar só o ID do município, ex: 2900702
                // ou o formato da URL, com {id}-{slug}, ex: 2900702-alagoinhas
                preg_match('/^([0-9]{7})(-.+)?$/', $municipio, $cod_municipio);
                if (is_numeric($cod_municipio[1])) {
                    $meta_query['_municipio'] = [
                        'key' => UFMunicipio::MUN_META,
                        'value' => $cod_municipio[1],
                        'compare' => '='
                    ];
                    $cod_municipio = $cod_municipio[1];
                    $has_meta_query = true;
                }

            }

            if (!empty($uf) && !isset($meta_query['municipio']) /* se já tem municipio não precisa filtrar por estado tb */ ) {           
                $cod_uf = is_numeric($uf) ? $uf : UFMunicipio::get_uf_id_from_sigla($uf);
                if (is_numeric($cod_uf)) {
                    $meta_query['_uf'] = [
                        'key' => UFMunicipio::UF_META,
                        'value' => $cod_uf,
                        'compare' => '='
                    ];
                    $has_meta_query = true;
                }
            }
        }
        
        $q_has_publish_posts = false;

        switch ($rhs_order) {
            case 'name':
                $q_order = 'ASC';
                $q_order_by = 'display_name';
                break;
            
            case 'register_date':
                $q_order = 'DESC';
                $q_order_by = 'registered';
                break;
            
            case 'posts':
                $q_order = 'DESC';
                $q_order_by = 'post_count';
                $q_has_publish_posts = true;
                break;
                
            case 'votes':
                $q_order_meta = RHSVote::META_TOTAL_VOTES;
                break;
            
            case 'last_login':
                $q_order_meta = RHSLogin::META_KEY_LAST_LOGIN;
                $q_order_by = 'meta_value';
                $q_order = 'DESC';
                break;

            default:
                $q_order = 'ASC';
                $q_order_by = 'post_date';
                break;
        }

        if (!empty($q_order_meta)) {
            if($q_order_meta == RHSLogin::META_KEY_LAST_LOGIN) {
                $meta_query['rhs_order'] = [
                    'key' => $q_order_meta,
                    'value' => date("Y-m-d H:i:s"),
                    'compare' => '<',
                    'type' => 'DATE'
                ];
            } else {
                $meta_query['rhs_order'] = [
                    'key' => $q_order_meta,
                    'compare' => 'EXISTS',
                    'type' => 'numeric'
                ];

                $q_order_by = ['rhs_order' => 'DESC'];
                $q_order = 'DESC';
            }
            $has_meta_query = true;            
        }

        $offset = $users_per_page * ($paged - 1);
        $cod_uf = ($uf) ? $uf : '' ;
        $cod_municipio = ($municipio) ? $municipio : '' ;
        
        $filters = array(
            //'role'       => 'contributor',
            'order'      => $q_order,
            'orderby'    => $q_order_by,
            'search'     => '*' . esc_attr($this->get_param('keyword')) . '*',
            'paged'     => $paged,
            'number'    => $users_per_page,
            'offset'    => $offset,
            'has_published_posts' => $q_has_publish_posts,
         );
         
         if ($has_meta_query) {
             $filters['meta_query'] = $meta_query;
         }
        
        // Retorna o objeto com a lista de usuários encontrados
        return new WP_User_Query($filters);      
        
    }
    /**
     * Show pagination 
     * 
     * @return mixed Return html with paginate links
     */
    function show_users_pagination($users) {
        $paged = $users->query_vars['paged'];
        $users_per_page = self::USERS_PER_PAGE;
        $total_pages = 1;
        $total_pages = ceil($users->total_users / $users_per_page);
        $big = 999999999;
        $content = paginate_links( array(
            'base'         => str_replace($big, '%#%', get_pagenum_link($big)),
            'format'       => '/page/%#%',
            'prev_text'    => __('&laquo; Anterior'),
            'next_text'    => __('Próxima &raquo;'), 
            'total'        => $total_pages,
            'current'      => $paged,
            'end_size'     => 4,
            'type'         => 'array',
            'mid_size'     => 8,
            'prev_next'    => true,
        ));
        
        if (is_array($content)) {
            $current_page = $paged;
            echo '<ul class="pagination">';
            foreach ($content as $i => $page) {
                echo "<li>$page</li>";
            }
            echo '</ul>';
        }
    }
}

global $RHSSearch;
$RHSSearch = new RHSSearch();



    /**
     * Show result posts
     *
     * @return o resultado dos posts.
    */

    function exibir_resultado_post(){
        global $wp_query;
        $result = $wp_query;
        $total_result = $result->found_posts;
        $total = $result->found_posts;
        $paged = empty($result->query['paged']) ? 1 : $result->query['paged'];
        $per_page = $result->query_vars['posts_per_page'];
        $final = $per_page * $paged;
        $initial = $final - ($per_page-1);
        if ($final > $total) $final = $total;

        echo "Exibindo $initial a $final de $total resultados";
    }

    /**
     * Show result usuarios
     *
     * @return o resultado dos usuarios.
    */

    function exibir_resultado_user(){
        $RHSSearch = new RHSSearch();
        $users = $RHSSearch->search_users();
        $total = $users->total_users;
        $paged = empty($users->query_vars['paged']) ? 1 : $users->query_vars['paged'];
        $per_page = $users->query_vars['number'];

        $final = $per_page * $paged;

        $initial = $final - ($per_page-1);
        if ($final > $total) $final = $total;

        echo "Exibindo $initial a $final de $total resultados";
    }