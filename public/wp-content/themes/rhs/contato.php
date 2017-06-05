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
                    <h1 class="titulo-page"><?php _e('Contato') ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 contato">
                    <div class="wrapper-content">
                        <form id="contato" class="form-horizontal" role="form" action="" method="post">
                            <div class="form-group float-label-control">
                                <label for="nome">Nome</label>
                                <input type="text" tabindex="1" name="nome" id="nome" class="form-control" value="" >
                            </div>
                            <div class="form-group float-label-control">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="" >
                            </div>
                            <div class="form-group float-label-control">
                                <label for="assunto">Assunto</label>
                                <input type="text" name="assunto" id="assunto" class="form-control" value="" >
                            </div>
                            <div class="form-group float-label-control">
                                <div class="row">
                                    <div class="col-sm-7">
                                    <?php UFMunicipio::form( array(
                                        'content_before' => '<div class="row">',
                                        'content_after' => '</div>',
                                        'content_before_field' => '<div class="col-md-6"><div class="form-group float-label-control">',
                                        'content_after_field' => '<div class="clearfix"></div></div></div>',
                                        'state_label'  => 'Estado &nbsp',
                                        'city_label'   => 'Cidade &nbsp',
                                        'select_class' => 'form-control',
                                        'label_class'  => 'control-label col-sm-4'
                                    ) ); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group float-label-control">
                                <label for="mensagem">Mensagem</label>
                                <textarea id="mensagem" class="form-control" rows="5" name="msg"></textarea>
                            </div>
                            <div class="panel-button form-actions pull-right">
                                <button class="btn btn-default btn-contato" type="submit" >Enviar</button>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar -->
        <div class="col-xs-12 col-md-3"><?php get_sidebar(); ?></div>
    </div>

<?php get_footer();