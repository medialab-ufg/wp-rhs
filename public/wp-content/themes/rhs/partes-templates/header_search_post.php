<div class="container no-padding">
    <form action="<?php echo home_url('/'); ?>busca/" id="filter" class="col-md-12 no-padding">

        <div class="col-md-6 no-padding">

            <div class="uf-city col-md-12 no-padding"> <?php RHSSearch::render_uf_city_select(); ?> </div>

            <div class="col-md-12 no-padding tags-categories">
                <div class="form-group col-md-6">
                    <label for="tag" class="control-label">Tags</label>
                    <input type="text" value="" class="form-control" id="input-tag" placeholder="Filtre por tags do post" name="tag">
                </div>

                <div class="form-group col-md-6">
                    <label for="categoria" class="control-label">Categoria</label>
                    <?php
                    $dropdown_options = [
                        'show_option_none' => 'Filtre por categoria',
                        'selected' => RHSSearch::get_param('cat'),
                        'option_none_value' => '',
                        'orderby' => 'name',
                        'class' => 'form-control'
                    ];
                    wp_dropdown_categories($dropdown_options); ?>
                    </div>
                </div>

        </div>

            <div class="col-md-6">
                <div class="col-md-12">
                    <label for="date" class="control-label">Data</label>
                    <div class="form-group date-range-container">
                        <div class="input-group input-daterange" style="width: 100%">
                            <div class="input-group-addon">a partir de</div>
                            <input type="text" class="form-control" value="<?php echo RHSSearch::get_param('date_from'); ?>" name="date_from">
                            <div class="input-group-addon">até</div>
                            <input type="text" class="form-control" value="<?php echo RHSSearch::get_param('date_to'); ?>" name="date_to">
                        </div>
                    </div>
                </div>

                <div class="col-md-12" style="margin-top: 10px;">
                    <label for="keyword" class="control-label palavra-chave">Palavra Chave</label>
                    <div class="keyword-container">
                        <input type="text" name="keyword" id="keyword" class="form-control" value="<?php echo RHSSearch::get_param('keyword'); ?>">
                    </div>

                    <div class="form-inline" style="text-align: right">
                        <label for="full-term" style="font-size: x-small; color: grey"> <?php _e('Buscar termo completo', 'rhs'); ?> </label>
                        <input type="checkbox" value="true" <?php echo (RHSSearch::get_param('full-term')) ? 'checked' : ''; ?> name="full-term">
                    </div>

                </div>

            </div>

        <div class="row"> <div class="col-xs-12" id="custom-ctn"></div> </div>

        <?php echo RHSSearch::getSearchButtons(); ?>

    </form>
</div>