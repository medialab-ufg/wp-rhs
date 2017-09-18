<?php
    class RHSOptions extends RHSMessage{
  
        const BUTTON_HEADER_PUB = 'button_header_publica_post';
        const BUTTON_HEADER_VOTE = 'button_header_fila_vote';


        public function __construct()
        {
            add_action('admin_menu', array($this, 'Menu'));
            add_action( "admin_init", array( &$this, "show_buttons_header" ) );
        }

        /*
        * Menu do painel admin Opções da RHS com os Submenus
        */
        public function Menu()
        {
            global $RHSVote;
            add_menu_page('RHS', 'Opções da RHS', '', 'rhs_options', array( $RHSVote, 'rhs_admin_page_voting_queue' ), 'dashicons-screenoptions', 30);

            add_submenu_page( 'rhs_options', 'Fila de votação', 'Fila de votação', 'manage_options', 'rhs_votes', array( $RHSVote, 'rhs_admin_page_voting_queue' ) );
            add_submenu_page( 'rhs_options', 'Redirecionamento dos Botões Publicar e Fila de Votação', 'Redirecionamentos Botões Topo', 'manage_options', 'rhs_buttons_header', array( &$this, 'buttons_header_options_page' ) );
        }

        /**
        * Formulario pegando os selects
        *
        */
        function buttons_header_options_page() { ?>
            <div class="wrap">
                <h1>Redirecionamento dos Botões Publicar e Fila de Votação</h1>
                <form autocomplete="off" id="form" method="post" action="options.php">
                    <?php
                    settings_fields( "header_section" );
                    do_settings_sections( "buttons_header-options" );
                    submit_button();
                    ?>
                </form>
            </div>
        <?php }

        /**
        * Fields pegando as functions show_buttons_header_content | show_buttons_header_publicar_element | show_buttons_header_fila_element
        *
        */
        function show_buttons_header() {
            add_settings_section( "header_section", "", array( &$this, "show_buttons_header_content" ),
                "buttons_header-options" );
            add_settings_field( 'buttons_header_publica_post', __( "Botão Publicar Post" ), array( &$this, "show_buttons_header_publicar_element" ),
                "buttons_header-options", "header_section" );
            add_settings_field( "buttons_header_fila_vote", __( "Botão Fila de Votação" ),
                array( &$this, "show_buttons_header_fila_element" ), "buttons_header-options", "header_section" );
            register_setting( "header_section", self::BUTTON_HEADER_PUB );
            register_setting( "header_section", self::BUTTON_HEADER_VOTE );
        }

        function show_buttons_header_content() {
            echo __( '<p>Selecione a página para onde os botões Publicar Post e Fila de Votação irá ser re-direcionado para quando o usuario não estiver logado.</p>' );
        }

        function show_buttons_header_publicar_element() {
            wp_dropdown_pages(array('name' => self::BUTTON_HEADER_PUB, 'selected' => get_option(self::BUTTON_HEADER_PUB)));
        }

        function show_buttons_header_fila_element() {
            wp_dropdown_pages(array('name' => self::BUTTON_HEADER_VOTE, 'selected' => get_option(self::BUTTON_HEADER_VOTE)));
        }

        /**
        * Retorna a ID da Página Publicar
        * @return ID
        */
        static function get_buttons_header_Pub() {
            return get_permalink( get_option( self::BUTTON_HEADER_PUB ));
        }

        /**
        * Retorna a ID da Página Fila de Votação
        * @return ID
        */
        static function get_buttons_header_Vote() {
            return get_permalink( get_option( self::BUTTON_HEADER_VOTE ));
        }
  
    }

$RHSOptions = new RHSOptions();


//Funções para retornar os ids das páginas

function show_buttons_header_Pub() {
    return RHSOptions::get_buttons_header_Pub();
}

function show_buttons_header_Vote() {
    return RHSOptions::get_buttons_header_Vote();
}

//fim funções de retornar ids das páginas