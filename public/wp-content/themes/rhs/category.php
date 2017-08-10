<?php get_header(); ?>
    <div class="row">
        <div class="col-xs-12">
            <h1 class="titulo-page">
                <?php
                     _e('Categoria ');
                     echo wp_title( );
                ?>
            </h1>
        </div>
    </div>
    <?php get_template_part( 'partes-templates/loop-posts'); ?>
<?php get_footer();