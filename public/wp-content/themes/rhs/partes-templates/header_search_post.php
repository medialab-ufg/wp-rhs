<div class="container">
    <div class="row">
        <form action="<?php echo home_url('/'); ?>busca/" id="filter">

            <div class="col-xs-12 col-sm-7">

                <div class="form-inline uf-city">
                    <?php RHSSearch::render_uf_city_select(); ?>
                </div>

                <div class="form-inline">
                    <div class="form-group col-md-5">
                        <label for="tag" class="control-label">Tags</label>
                        <input type="text" value="" class="form-control" id="input-tag" placeholder="Tags" name="tag">
                    </div>
                    <div class="form-group col-md-7">
                        <label for="categoria" class="control-label">Categoria</label>
                        <?php wp_dropdown_categories( [
                            'show_option_none' => 'Selecione uma Categoria',
                            'selected' => RHSSearch::get_param('cat'),
                            'option_none_value' => '',
                            'orderby' => 'name',
                            'class' => 'form-control '
                        ] ); ?>
                    </div>
                </div>

            </div>

            <div class="col-xs-12 col-sm-5">

                <div class="form-inline col-md-12">
                    <label for="date" class="control-label" style="padding-top: 8px">Data</label>
                    <div class="form-group date-range-container">
                        <div class="input-group input-daterange" style="width: 100%">
                            <input type="text" class="form-control" value="<?php echo RHSSearch::get_param('date_from'); ?>" name="date_from">
                            <div class="input-group-addon">até</div>
                            <input type="text" class="form-control" value="<?php echo RHSSearch::get_param('date_to'); ?>" name="date_to">
                        </div>
                    </div>
                </div>

                <div class="form-inline col-md-12" style="margin-top: 10px;">
                    <label for="keyword" class="control-label palavra-chave">Palavra Chave</label>
                    <div class="keyword-container">
                        <input type="text" name="keyword" id="keyword" class="form-control" value="<?php echo RHSSearch::get_param('keyword'); ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div id="custom-ctn"></div>
                </div>
            </div>
            <button type="submit" class="btn btn-default filtro">Filtrar</button>
        </form>
    </div>
</div>