<?php

/** Esta classe implementa a tabela de estados e municípios e cria uma série de funções para serem usadas em formulários
 * 
 * Também lida com o salvamento dos metadados de estados e municípios para usuários e posts
 * 
 * Para funcionar, depende que as tabelas sejam criadas no banco. Esses dados estão em db/brasil.sql
 * 
 */ 

Class UFMunicipio {

    
    static function init() {
    
        add_action('wp_enqueue_scripts', array('UFMunicipio', 'addJS'));
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
                $selected = selected($currentCity, $cidade->nome);
                $output .= "<option value='{$cidade->nome}' $selected>{$cidade->nome}</option>";
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
            $selected = selected($currentState, $state->sigla);
            $output .= "<option value='{$state->sigla}' $selected>{$state->nome}</option>";
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
            'city_label' => 'Cidade',
            'city_field_name' => 'municipio',
            'selected_state' => '',
            'selected_municipio' => '',
            'separator' => '',
            'select_class' => '',
            'label_class' => '',
            'show_label' => true,
            
        );

        $params = array_merge($defaults, $params);

        echo $params['content_before'];
        echo $params['content_before_field'];

        if($params['show_label']){ ?>
            <label for="estado" class="<?php echo $params['label_class']; ?>">
                <?php echo $params['state_label']; ?>
            </label>
        <?php }
        echo $params['select_before']; ?>
            <select name="<?php echo $params['state_field_name']; ?>" class="<?php echo $params['select_class']; ?>" id="estado">
                <?php self::print_states_options($params['selected_state']); ?>
            </select>
        <?php
        echo $params['select_after'];
        echo $params['content_after_field'];
        echo $params['separator'];
        echo $params['content_before_field'];

        if($params['show_label']){?>
        <label for="municipio" class="<?php echo $params['label_class']; ?>">
            <?php echo $params['city_label']; ?>
        </label>
        <?php }
        echo $params['select_before']; ?>
            <select name="<?php echo $params['city_field_name']; ?>" class="form-control <?php echo $params['select_class']; ?>" id="municipio">
                <?php self::print_cities_options($params['selected_state'], $params['selected_municipio']); ?>
            </select>
        <?php
        echo $params['select_after'];
        echo $params['content_after_field'];
        echo $params['content_after'];
    }

}

add_action('init', array('UFMunicipio', 'init'));
