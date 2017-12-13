<div class="container">
    <form action="<?php echo home_url('/'); ?>busca/usuarios/" id="filter">
        <div class="col-xs-12 col-sm-7">
            <div class="form-inline col-xs-12">    
                <?php RHSSearch::render_uf_city_select(); ?>
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
        <button type="submit" class="btn btn-default filtro btn-rhs">Filtrar</button>
    </form>
</div>