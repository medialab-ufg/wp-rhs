<div class="container">
    <form action="<?php echo home_url('/'); ?>busca/usuarios/" id="filter">
        <div class="col-xs-12 col-sm-7">
            <div class="form-inline col-xs-12">    
                <?php UFMunicipio::form( array(
                    'content_before' => '',
                    'content_after' => '',
                    'content_before_field' => '<div class="form-group">',
                    'content_after_field' => '</div>',
                    'select_before' => ' ',
                    'select_after' => ' ',
                    'state_label' => 'Estado &nbsp',
                    'state_field_name' => 'uf',
                    'city_label' => 'Cidade &nbsp',
                    'select_class' => 'form-control',
                    'label_class' => 'control-label',
                    'show_label' => true,
                    'selected_state' => RHSSearch::get_param('uf'),
                    'selected_municipio' => RHSSearch::get_param('municipio'),
                ) ); ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-5">
            <div class="form-inline col-xs-12">
                <div class="form-group">
                    <label for="keyword" class="control-label">Nome ou E-mail</label>
                    <input type="text" name="keyword" id="keyword" class="form-control" value="<?php echo RHSSearch::get_param('keyword'); ?>">
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-default filtro">Filtrar</button>
    </form>
</div>