<?php $title_box = 'Seguindo'; ?>
<?php get_header('full'); ?>

<div class="row">
    <div class="col-xs-6">
        <h1 class="titulo-page"><?php _e('Quem eu Sigo') ?></h1>
        <div class="tab-content">                
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php $RHSFollow->show_follows(); ?>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>
</div>
    
</div>
<?php get_footer('full');
