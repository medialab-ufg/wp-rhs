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
                        <div class="panel panel-default panel-horizontal">
                            <div class="panel-heading">
                                <img src="http://www.knowmuhammad.org/img/default_avatar.gif" class="img-responsive img-circle" width="80">
                            </div>
                            <div class="panel-body">
                                <span><b>Fabiano Alencar</b> comentou no post <b>"Teste de notificação."</b></span>
                                <small>Há 5 dias e 18 horas atrás.</small>
                                    <button class="btn btn-default text-uppercase hidden-md hidden-lg hidden-sm">Marcar como Lida</button>
                            </div>
                            <div class="panel-footer hidden-xs">
                                <button class="btn btn-default text-uppercase">Marcar como Lida</button>
                            </div>
                        </div>
                        <!-- Fim do Loop -->
                    </div>
                </div>
            </div>
<?php get_footer();