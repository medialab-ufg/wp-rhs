<?php get_header(); ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel archive">
                <h1 class="titulo">
                    <?php
                        if(is_category()) {
                            _e('Categoria ');
                            echo wp_title( );
                        }elseif (is_tag()) {
                            _e('Tag ');
                            echo wp_title( );
                        }
                    ?>
                </h1>
                <div class="descricao text-left">
                    <?php
                        if(is_category()) {
                            echo category_description();
                        }elseif (is_tag()) {
                            echo tag_description();
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php get_template_part( 'partes-templates/loop-posts'); ?>
<?php get_footer();