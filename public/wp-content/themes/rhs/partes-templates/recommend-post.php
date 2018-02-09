<div class="panel panel-default hidden-print">
    <div class="panel-heading">
        <div class="row post-titulo">
            <div class="col-xs-12">
                <label for="input-recommend-post">Indicar Post</label>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-12">
                <form class="form form-inline recommend-post-container">
                    <div class="form-group">                
                        <input id="input-recommend-post" name="recommend-post" placeholder="Busque por e-mail ou nome de usuário" class="form-control" value="" data-post-id="<?php echo get_the_ID(); ?>"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>