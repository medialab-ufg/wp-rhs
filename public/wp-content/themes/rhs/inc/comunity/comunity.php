<?php

class RHSComunity extends RHSMenssage {

    const TAXONOMY = 'comunity-category';

    public function __construct() {

        add_action('init', array( &$this, "register_taxonomy" ));

        add_action( self::TAXONOMY.'_edit_form_fields', array( &$this, 'edit_category_field') );
        add_action( self::TAXONOMY.'_add_form_fields',array( &$this, 'new_category_field') );
        add_action( 'edited_'.self::TAXONOMY, array( &$this,'save_tax_meta'), 10, 2 );
        add_action( 'create_'.self::TAXONOMY, array( &$this,'save_tax_meta'), 10, 2 );
    }

    function register_taxonomy()
    {
        $labels = array(
            'name' =>'Comunidades',
            'singular_name' => 'Comunidade',
            'search_items' => 'Buscar Comunidade',
            'all_items' => 'Todas as Comunidades',
            'parent_item' => 'Comunidades Acima',
            'parent_item_colon' => 'Comunidades Acima:',
            'edit_item' => 'Editar Comunidade',
            'update_item' => 'Atualizar Comunidades',
            'add_new_item' => 'Adicionar Nova Comunidade',
            'new_item_name' => 'Novo nome da Comunidade',
        );
        register_taxonomy(
            self::TAXONOMY,
            array('post'),
            array(
                'hierarchical' => true,
                'labels' => $labels,
                'show_ui' => true,
                'query_var' => true,
                'rewrite' => false,
                'hierarchical' => false,
                'parent_item'  => null,
                'parent_item_colon' => null,
            )
        );
    }

    function new_category_field( $term ){
        ?>

        <div class="form-field term-parent-wrap">
            <label for="term_meta[rhs-comunity-type]">Tipo</label>
            <br />
            <fieldset>
                <label>
                    <input checked type="radio" name="term_meta[rhs-comunity-type]" value="open" />
                    <span>Aberto</span>
                </label>
                <label>
                    <input type="radio" name="term_meta[rhs-comunity-type]" value="private" />
                    <span>Privado</span>
                </label>
                <label>
                    <input type="radio" name="term_meta[rhs-comunity-type]" value="hide" />
                    <span>Oculto</span>
                </label>
            </fieldset>
        </div>
        <?php
    }

    function edit_category_field( $term ){
        $term_meta = '';
        if($term instanceof WP_Term){
            $term_id = $term->term_id;
            $term_meta = get_term_meta($term_id, 'rhs-comunity-type', true );
        }



        ?>
        <tr class="form-field term-parent-wrap">
            <th scope="row">
                <label>Tipo</label>
            </th>
            <td>
                <fieldset>
                    <label>
                        <input <?php echo ($term_meta == 'open' || !$term_meta) ? 'checked' : ''; ?> type="radio" name="term_meta[rhs-comunity-type]" value="open" />
                        <span>Aberto</span>
                    </label>
                    <br />
                    <label>
                        <input <?php echo ($term_meta == 'private') ? 'checked' : ''; ?> type="radio" name="term_meta[rhs-comunity-type]" value="private" />
                        <span>Privado</span>
                    </label>
                    <br />
                    <label>
                        <input <?php echo ($term_meta == 'hide') ? 'checked' : ''; ?> type="radio" name="term_meta[rhs-comunity-type]" value="hide" />
                        <span>Oculto</span>
                    </label>
                </fieldset>
            </td>
        </tr>

        <?php
    }

    function save_tax_meta( $term_id , $taxonomy ){

        if(isset( $_POST['term_meta']['rhs-comunity-type'])){
            $term_meta = array();

            if ( ! add_term_meta($term_id, 'rhs-comunity-type', $_POST['term_meta']['rhs-comunity-type'], true) ) {

                update_term_meta($term_id, 'rhs-comunity-type', $_POST['term_meta']['rhs-comunity-type']);
            }
        }
    }
}

global $RHSComunity;
$RHSComunity = new RHSComunity();