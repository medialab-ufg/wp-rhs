<div class="container col-md-12 no-padding">
    <form action="<?php echo home_url('/'); ?>busca/usuarios/" id="filter">

        <div class="col-md-6 no-padding">
            <?php RHSSearch::render_uf_city_select(); ?>
        </div>

        <div class="col-md-6 form-group ">
            <label for="keyword" class="control-label">Nome ou E-mail</label>
            <input type="text" name="keyword" id="keyword" class="form-control" value="<?php echo RHSSearch::get_param('keyword'); ?>">
        </div>

        <?php echo RHSSearch::getSearchButtons(); ?>

    </form>
</div>