<?php get_header(); ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel categoria">
                <h1 class="titulo">
                    <?php
                        _e('Categoria ');
                        echo wp_title( );
                    ?>
                </h1>
                <div class="descricao text-left">
                    <?php
                        echo category_description();
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php get_template_part( 'partes-templates/loop-posts'); ?>
<?php get_footer();