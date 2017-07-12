<?php 
/**
* Template name: Comunidade
*/
?>
<?php get_header('full'); ?>
<?php global $RHSComunity;?>
<?php $RHSComunity->check_comunity();?>
    <div class="col-xs-12 comunidade">
        <div class="card hovercard">
            <div class="card-background">
                <img class="card-bkimg" alt="" src="http://www.teleios.com.br/wp-content/uploads/2006/08/indios1.jpg">
            </div>
            <div class="card-buttons">
                <a href="#">Seguir Comunidade <i class="fa fa-rss"></i></a>
                <a href="#">Deixar de Seguir Comunidade <i class="fa fa-rss"></i></a>
                <a href="#">Sair na Comunidade <i class="fa fa-remove"></i></a>
                <a href="#">Entrar na Comunidade <i class="fa fa fa-sign-in"></i></a>
            </div>
            <div class="useravatar">
                <div class="row">
                    <div class="col-xs-12">
                        <img alt="" src="http://www.teleios.com.br/wp-content/uploads/2006/08/indios1.jpg">
                    </div>
                </div>
            </div>
            <div class="card-info">
                <div class="row">
                    <div class="col-md-12 col-sm-7 col-xs-12 col-xs-pull-3 col-sm-pull-0">
                        <div class="card-title">
                            Indígena
                            <i title="Esse grupo é privado" class="fa fa-lock"></i>
                            <i title="Você faz parte desta comunidade" class="fa fa-check"></i>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-5 col-xs-12 col-xs-pull-1 col-sm-pull-0">
                        <div class="espace">
                            <ul>
                                <li>
                                    <span class="views-number">0</span>
                                    <small>Membros</small>
                                </li>
                                <li>
                                    <span class="views-number">0</span>
                                    <small>Posts</small>
                                </li>
                                <li>
                                    <span class="views-number">0</span>
                                    <small>Seguidores</small>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
            <div class="btn-group active" role="group">
                <button type="button" id="stars" class="btn btn-primary" href="#tab1" data-toggle="tab">
                    <span class="glyphicon glyphicon-th" aria-hidden="true"></span>
                    <div class="hidden-xs">Posts</div>
                </button>
            </div>
            <div class="btn-group" role="group">
                <button type="button" id="favorites" class="btn btn-default" href="#tab2" data-toggle="tab">
                    <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                    <div class="hidden-xs">Membros</div>
                </button>
            </div>
        </div>

        <div class="well">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab1">
                    <?php get_template_part( 'partes-templates/loop-posts'); ?>
                </div>
                <div class="tab-pane fade in" id="tab2">
                    <?php get_template_part('membro'); ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    jQuery(function($){
        $(document).ready(function() {
            $(".btn-pref .btn").click(function () {
                $(".btn-pref .btn").removeClass("btn-primary").addClass("btn-default");
                // $(".tab").addClass("active"); // instead of this do the below 
                $(this).removeClass("btn-default").addClass("btn-primary");   
            });
        });
    });
    </script>

<?php get_footer('full');