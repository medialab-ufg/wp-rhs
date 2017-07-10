<?php 
/**
* Template name: Comunidade
*/
?>
<?php get_header(); ?>
    <div class="col-xs-12 comunidade">
        <div class="card hovercard">
            <div class="card-background">
                <img class="card-bkimg" alt="" src="http://www.blog.saude.gov.br/images/cmigration/humanizasus.jpg">
            </div>
            <div class="useravatar">
                <div class="row">
                    <div class="col-xs-12">
                        <img alt="" src="http://www.blog.saude.gov.br/images/cmigration/humanizasus.jpg">
                    </div>
                </div>
            </div>
            <div class="card-info">
                <div class="row">
                    <div class="col-sm-7 col-xs-12 col-xs-pull-3 col-sm-pull-0">
                        <div class="pull-right">
                            <div class="card-title">Media Lab / UFG</div>
                            <small class="card-subtitle">Descrição</small>
                        </div>
                    </div>
                    <div class="col-sm-5 col-xs-12 col-xs-pull-1 col-sm-pull-0">
                        <div class="pull-right espace">
                            <div class="col-xs-4">
                                <p class="text-center">0</p>
                                <span>Mensagens</span>
                            </div>
                            <div class="col-xs-4">
                                <p class="text-center">0</p>
                                <span>Comentários</span>
                            </div>
                            <div class="col-xs-4">
                                <p class="text-center">0</p>
                                <span>Participantes</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
            <div class="btn-group" role="group">
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
            <div class="btn-group" role="group">
                <a class="btn btn-default" href="#">
                    <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                    <div class="hidden-xs">Seguir</div>
                </a>
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

<?php get_footer();