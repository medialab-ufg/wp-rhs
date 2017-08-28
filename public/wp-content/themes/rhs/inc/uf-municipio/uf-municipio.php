<?php

/** Esta classe implementa a tabela de estados e municípios e cria uma série de funções para serem usadas em formulários
 *
 * Também lida com o salvamento dos metadados de estados e municípios para usuários e posts
 *
 * Para funcionar, depende que as tabelas sejam criadas no banco. Esses dados estão em db/brasil.sql
 *
 *
 * TODO: Podíamos criar uns métodos get_state e get_city que retorna os dados de uma cidade ou estado e grava num cache no atributo da instancia da classe
 * assim a gente pode ficar chamando isso várias vezes sem medo de ficar fazendo várias requisições no banco.
 *
 */

Class UFMunicipio {


    const UF_META = '_uf';
    const MUN_META = '_municipio';


    static function init() {

        add_action('wp_enqueue_scripts', array('UFMunicipio', 'addJS'));
        add_action('admin_enqueue_scripts', array('UFMunicipio', 'addJS'));
        add_action('wp_ajax_nopriv_get_cities_options', array('UFMunicipio', 'ajax_handle_get_cities'));
        add_action('wp_ajax_get_cities_options', array('UFMunicipio', 'ajax_handle_get_cities'));

    }


    static function addJS() {
        wp_enqueue_script('UFMunicipio', get_template_directory_uri() . '/inc/uf-municipio/uf-municipio.js', array('jquery'));
        wp_localize_script( 'UFMunicipio', 'vars', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    }

    /**
     * Retorna HTML com as cidades de um determinado estado.
     *
     * @param string|int $uf sigla ou id do estado
     * @param string $currentCity nome da cidade selecionada
     * @return string
     */
    static function get_cities_options($uf, $currentCity = '') {

        $cidades = self::get_cities($uf);

        $output = '';

        if (is_array($cidades) && count($cidades) > 0) {
            foreach ($cidades as $cidade) {
                $selected = selected($currentCity, $cidade->id, false);
                $output .= "<option value='{$cidade->id}' $selected>{$cidade->nome}</option>";
            }
        } else {
            return "<option value=''>Selecione a cidade...</option>";
        }

        return $output;
    }

    /**
     * Retorna HTML com os estados.
     *
     * @param string $currentState sigla do estado selecionado
     * @return string
     */
    static function get_states_options($currentState = '') {

        $states = self::get_states();

        $output = "<option value=''>Estado</option>";

        $output = "<option value=''>Selecione o estado...</option>";

        foreach ($states as $state) {
            $selected = selected($currentState, $state->id, false);
            $output .= "<option value='{$state->id}' $selected>{$state->nome}</option>";
        }

        return $output;

    }

    /**
     * Imprime um HTML com as cidades de um estado.
     *
     * @param string|int $uf sigla ou id do estado
     * @param string $currentCity nome da cidade selecionada
     *
     * @return null
     */
    static function print_cities_options($uf, $currentCity = '') {
        echo self::get_cities_options($uf, $currentCity);
    }

    /**
     * Imprime um HTML com os estados.
     *
     * @param string $currentState sigla do estado selecionado
     *
     * @return null
     */
    static function print_states_options($currentState = '') {
        echo self::get_states_options($currentState);
    }


    /**
     * Recebem um request ajax via POST e imprime um select com as cidades de um estado
     *
     * @return null
     */
    static function ajax_handle_get_cities() {
        self::print_cities_options($_POST['uf'], $_POST['selected']);
        die;
    }

    /**
     * Retorna os estados
     *
     * Os campos são:
     *
     * id -> código do IBGE do estado
     * nome -> nome do estado
     * sigla -> sigla do estado
     *
     * @return Array
     *
     */
    static function get_states() {
        global $wpdb;
        return $wpdb->get_results("SELECT * from uf ORDER BY sigla");
    }


    /**
     * Retorna as cidades de um estado
     *
     * Recebe o ID ou a sigla do estado
     *
     * Os campos são:
     *
     * id -> código do IBGE do município
     * ufid -> código do estado deste município
     * nome -> nome do município
     *
     * @param string|int $uf sigla ou id do estado
     *
     * @return Array
     *
     */
    static function get_cities($uf) {
        global $wpdb;

        if (!is_numeric($uf))
            $uf = $wpdb->get_var($wpdb->prepare("SELECT id FROM uf WHERE sigla LIKE %s", $uf));

        return $wpdb->get_results( $wpdb->prepare("SELECT * from municipio WHERE ufid = %d ORDER BY nome", $uf) );
    }

    /**
     * Imprime os campos do formulário para estado e cidade
     *
     * Recebe um array com os parâmetros para o formulário.
     *
     * Valores padrão:
     *
     * array(
     *      'state_label' => 'UF',
     *      'state_field_name' => 'estado',
     *      'city_label' => 'Cidade',
     *      'city_field_name' => 'municipio',
     *      'selected_state' => '',
     *      'selected_municipio' => '',
     *      'separator' => '',
     *
     *  );
     *
     * @param Array $params Parametros para o formulário
     *
     * @return null
     *
     */
    static function form($params = array()) {

        $defaults = array(
            'content_before' => '',
            'content_after' => '',
            'content_before_field' => '',
            'content_after_field' => '',
            'select_before' => '<div class="col-sm-12">',
            'select_after' => '</div>',
            'state_label' => 'UF',
            'state_field_name' => 'estado',
            'state_field_id' => 'estado',
            'city_label' => 'Cidade',
            'city_field_name' => 'municipio',
            'city_field_id' => 'municipio',
            'selected_state' => '',
            'selected_municipio' => '',
            'separator' => '',
            'select_class' => '',
            'label_class' => '',
            'show_label' => true,
            'tabindex_state' => '',
            'tabindex_city' => '',
            'label_before' => '',
            'label_after' => '',
        );

        $params = array_merge($defaults, $params);

        echo $params['content_before'];
        echo $params['content_before_field'];

        if($params['show_label']){
            echo $params['label_before'];
            ?>
            <label for="<?php echo $params['state_field_id']; ?>" class="<?php echo $params['label_class']; ?>">
                <?php echo $params['state_label']; ?>
            </label>
        <?php
            echo $params['label_after'];
        }
        echo $params['select_before']; ?>
            <select name="<?php echo $params['state_field_name']; ?>" tabindex="<?php echo $params['tabindex_state']; ?>" class="<?php echo $params['select_class']; ?>" id="<?php echo $params['state_field_id']; ?>">
                <?php self::print_states_options($params['selected_state']); ?>
            </select>
        <?php
        echo $params['select_after'];
        echo $params['content_after_field'];
        echo $params['separator'];
        echo $params['content_before_field'];

        if($params['show_label']){
            echo $params['label_before'];
            ?>
        <label for="<?php echo $params['city_field_id']; ?>" class="<?php echo $params['label_class']; ?>">
            <?php echo $params['city_label']; ?>
        </label>
        <?php
            echo $params['label_after'];
        }
        echo $params['select_before']; ?>
            <select name="<?php echo $params['city_field_name']; ?>" tabindex="<?php echo $params['tabindex_city']; ?>" class="<?php echo $params['select_class']; ?>" id="<?php echo $params['city_field_id']; ?>">
                <?php self::print_cities_options($params['selected_state'], $params['selected_municipio']); ?>
            </select>
        <?php
        echo $params['select_after'];
        echo $params['content_after_field'];
        echo $params['content_after'];
    }

    static function get_uf_link($uf_id, $type = 'post', $uf_data = false) {
        global $wpdb;

        if (false === $uf_data)
            $uf_data = $wpdb->get_row( $wpdb->prepare("SELECT * FROM uf WHERE id = %d", $uf_id), ARRAY_A );

        $base_url = $type == 'user' ? RHSSearch::BASE_USERS_URL : RHSSearch::BASE_URL;

        return home_url($base_url . '/' . $uf_data['sigla']);

    }

    static function get_mun_link($mun_id, $type = 'post', $mun_data = false) {
        global $wpdb;

        if (false === $mun_data)
            $mun_data = $wpdb->get_row( $wpdb->prepare("SELECT * FROM municipio WHERE id = %d", $mun_id), ARRAY_A );

        return self::get_uf_link($mun_data['ufid'], $type) . '/' . $mun_id . '-' . sanitize_title( $mun_data['nome'], '', 'save' );

    }

    static function get_uf_id_from_sigla($sigla) {
        global $wpdb;
        return $wpdb->get_var( $wpdb->prepare("SELECT id FROM uf WHERE sigla = %s", $sigla) );
    }

    static function get_municipio_id_from_nome($nome) {
        global $wpdb;
        return $wpdb->get_var( $wpdb->prepare("SELECT id FROM municipio WHERE nome = %s", $nome) );
    }



    static function add_post_meta($post_id, $cod_mun, $cod_uf = null) {

        if (is_null($cod_uf)) $cod_uf = substr($cod_mun, 1, 2);

        update_post_meta($post_id, self::UF_META, $cod_uf);
        update_post_meta($post_id, self::MUN_META, $cod_mun);

    }

    static  function add_user_meta($user_id, $cod_mun, $cod_uf = null) {

        if (is_null($cod_uf)) $cod_uf = substr($cod_mun, 1, 2);

        update_user_meta($user_id, self::UF_META, $cod_uf);
        update_user_meta($user_id, self::MUN_META, $cod_mun);

    }

    static function get_post_meta($post_id) {
        global $wpdb;
        $result = array();

        $result['uf'] = ['id' => get_post_meta($post_id, self::UF_META, true)];
        $result['mun'] = ['id' => get_post_meta($post_id, self::MUN_META, true)];

        if ($result['uf']['id']) {
            $result_uf = $wpdb->get_row( $wpdb->prepare("SELECT * FROM uf WHERE id = %d", $result['uf']), ARRAY_A);

            if($result_uf){
                $result['uf'] = array_merge($result['uf'], $result_uf);
            }
        }

        if ($result['mun']['id']) {
            $result_mun = $wpdb->get_row( $wpdb->prepare("SELECT * FROM municipio WHERE id = %d", $result['mun']), ARRAY_A);

            if($result_mun){
                $result['mun'] = array_merge($result['mun'], $result_mun);
            }
        }

        return $result;

    }


    static function get_user_meta($user_id) {
        global $wpdb;
        $result = array();

        $result['uf'] = ['id' => get_user_meta($user_id, self::UF_META, true)];
        $result['mun'] = ['id' => get_user_meta($user_id, self::MUN_META, true)];

        if ($result['uf']['id']) {
            $result['uf'] = array_merge($result['uf'], $wpdb->get_row( $wpdb->prepare("SELECT * FROM uf WHERE id = %d", $result['uf']), ARRAY_A));
        }

        if ($result['mun']['id']) {
            $result['mun'] = array_merge($result['mun'], $wpdb->get_row( $wpdb->prepare("SELECT * FROM municipio WHERE id = %d", $result['mun']), ARRAY_A));
        }

        return $result;

    }

    static function the_post($post = null) {

        $post = get_post($post); // ID, Object ou o post atual do Loop

        if (!isset($post->ID))
            return false;

        $meta = self::get_post_meta($post->ID);
        static $uf_html, $mun_html, $uf_link;

        if ($meta['uf']['id']) {

            $uf_link = self::get_uf_link($meta['uf']['id'], 'post', $meta['uf']);
            $uf_html = "<a href='$uf_link'>".$meta['uf']['sigla']."</a>";

            //$uf_html = $meta['uf']['sigla']; // REMOVER ESSA LINHA QUANDO FIZERMOS OS LINKS FUNCIONAREM

        }

        if ($meta['mun']['id']) {

            $mun_link = self::get_mun_link($meta['mun']['id'], 'post', $meta['mun']);
            $mun_html = "<a href='$mun_link'>".$meta['mun']['nome']."</a>";

            //$mun_html = $meta['mun']['nome']; // REMOVER ESSA LINHA QUANDO FIZERMOS OS LINKS FUNCIONAREM

        }

        if ($mun_html) {
            echo $mun_html;
            echo ', ';
        }

        if ($uf_html)
            echo $uf_html;

    }

    static function the_user($user_id) {

        $meta = self::get_user_meta($user_id);
        static $mun_html, $uf_html, $uf_link;

        if ($meta['uf']['id']) {

            $uf_link = self::get_uf_link($meta['uf']['id'], 'user', $meta['uf']);
            $uf_html = "<a href='$uf_link'>".$meta['uf']['sigla']."</a>";

            //$uf_html = $meta['uf']['sigla']; // REMOVER ESSA LINHA QUANDO FIZERMOS OS LINKS FUNCIONAREM

        }

        if ($meta['mun']['id']) {

            $mun_link = self::get_mun_link($meta['mun']['id'], 'user', $meta['mun']);
            $mun_html = "<a href='$mun_link'>".$meta['mun']['nome']."</a>";

            //$mun_html = $meta['mun']['nome']; // REMOVER ESSA LINHA QUANDO FIZERMOS OS LINKS FUNCIONAREM

        }

        echo $mun_html;
        echo ', ';
        echo $uf_html;

    }

}

add_action('init', array('UFMunicipio', 'init'));


/* Functions to handle metadata */
function add_post_ufmun_meta($post_id, $cod_mun, $cod_uf = null) {
    return UFMunicipio::add_post_meta($post_id, $cod_mun, $cod_uf);
}

function add_user_ufmun_meta($user_id, $cod_mun, $cod_uf = null) {
    return UFMunicipio::add_user_meta($user_id, $cod_mun, $cod_uf);
}

function get_post_ufmun($post_id) {
    return UFMunicipio::get_post_meta($post_id);
}

function get_user_ufmun($user_id) {
    return UFMunicipio::get_user_meta($user_id);
}

/* Template Tags */


function the_ufmun($post = null) {

    return UFMunicipio::the_post($post);

}

function the_user_ufmun($user_id) {

    return UFMunicipio::the_user($user_id);

}

function has_post_ufmun( $post_id ) {
    return get_post_meta( $post_id, '_uf', true );
}

function has_user_ufmun( $user_id ) {
    return get_user_meta( $user_id, '_uf', true );
}