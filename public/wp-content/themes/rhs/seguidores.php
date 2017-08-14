<?php $title_box = 'Seguidores'; ?>
<?php get_header('full'); ?>

<div class="row">
    <div class="col-xs-6">
        <h1 class="titulo-page"><?php _e('Quem me segue') ?></h1>
        <div class="tab-content">                
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php $RHSFollow->show_followers(); ?>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>
</div>
    
</div>
<?php get_footer('full');
