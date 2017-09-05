<?php $title_box = 'Fila de Votação'; ?>
<?php get_header(); ?>
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="titulo-page"><?php _e('Fila de Votação') ?></h1>
                </div>
            </div>
            <?php if(get_option( 'vq_description' )){ ?>
            <div class="row">
                <div class="col-xs-12">
                    <p class="box-descricao-page">
	                    <?php echo get_option( 'vq_description' ); ?>
                    </p>
                </div>
            </div>
            <?php } ?>
            
            <?php get_template_part( 'partes-templates/loop-posts'); ?>
<?php get_footer();
