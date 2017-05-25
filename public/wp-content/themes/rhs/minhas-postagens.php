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
                    <h1 class="titulo-page"><?php _e('Minhas Postagens') ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 postagens">
                    <div class="wrapper-content table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Post <i class="fa fa-long-arrow-down" aria-hidden="true"></i></th>
                                    <th>Data</th>
                                    <th>Visualizações</th>
                                    <th>Comentários</th>
                                    <th>Votos</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php RHSPost::minhasPostagens(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar -->
        <div class="col-xs-12 col-md-3"><?php get_sidebar(); ?></div>
    </div>

<?php get_footer();
