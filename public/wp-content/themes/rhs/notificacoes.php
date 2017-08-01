<?php 
/**
* Template name: Notificações
*/
?>
<?php RHSHtml::setTitulo('Notificações'); ?>
<?php get_header(); ?>
            <div class="row">
                <div class="col-xs-12 notificacoes">
                    <div class="wrapper-content">
                        <!-- Inicio do Loop -->

                            <!-- Loop #1 -->
                            <div class="panel panel-default panel-horizontal">
                                <div class="panel-heading">
                                    <img src="http://www.knowmuhammad.org/img/default_avatar.gif" class="img-responsive img-circle" width="80">
                                </div>
                                <div class="panel-body">
                                    <span><b><a href="#author">Fabiano Alencar</a></b> comentou no post <b>"<a href="#post">Teste de notificação usúario</a>."</b></span>
                                    <small>Há 5 dias e 18 horas atrás.</small>
                                </div>
                            </div>
                            <!-- Fim Loop #1 -->

                            <!-- Loop #2 -->
                            <div class="panel panel-default panel-horizontal">
                                <div class="panel-heading">
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/post-promovido.png" class="img-responsive" width="80">
                                </div>
                                <div class="panel-body">
                                    <span>Seu post <b>"<a href="#post">Teste de notificação post</a>."</b> foi promovido a página principal por ser votado 5 vezes.</span>
                                    <small>Há 10 segundos atrás.</small>
                                </div>
                            </div>
                            <!-- Fim Loop #2 -->

                        <!-- Fim do Loop -->
                    </div>
                </div>
            </div>
<?php get_footer();