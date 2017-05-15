<?php get_header(); ?>

    <div class="row">
        <!-- Container -->
        <div class="col-xs-12 col-md-9">
            <div class="row">
                <!-- Button Publicar e Ver Fila de Votação -->
                <?php get_template_part('partes-templates/buttons-top' ); ?>
            </div>
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
        </div>
        <!-- Sidebar -->
        <div class="col-xs-12 col-md-3"><?php get_sidebar(); ?></div>
    </div>

<?php get_footer();
