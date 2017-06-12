<?php get_header(); ?>

<?php global $RHSTicket; ?>

    <div class="row">
        <!-- Container -->
        <div class="col-xs-12 col-md-9">
            <div class="row">
                <!-- Button Publicar e Ver Fila de Votação -->
                <?php get_template_part('partes-templates/buttons-top' ); ?>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="titulo-page"><?php _e('Tickets') ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 tickets">
                    <div class="wrapper-content">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span>
                                    <?php
                                    $term_list = wp_get_post_terms($post->ID, 'tickets-category');
                                    echo $term_list[0]->name;
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar -->
        <div class="col-xs-12 col-md-3"><?php get_sidebar(); ?></div>
    </div>

<?php get_footer();
