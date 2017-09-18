<?php $title_box = 'Fila de Votação'; ?>
<?php if(is_user_logged_in()) { ?>
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
            <?php }
                get_template_part( 'partes-templates/loop-posts'); }            
                else {
                    wp_redirect(home_url());
                }
            ?>
            
<?php get_footer();