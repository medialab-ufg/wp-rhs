<?php get_header('full'); ?>

<?php
// Parametros de busca
echo $RHSSearch->get_param('uf');
echo $RHSSearch->get_param('keyword');
echo $RHSSearch->get_param('municipio');
echo $RHSSearch->get_param('rhs_order');
echo get_query_var('paged');

//var_dump($wp_query);

$users = $RHSSearch->searchUsers();

// loop de usuários e paginação

?>
<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default busca-page" style="padding: 10px;">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation"><a href="<?php echo home_url('/'); ?>busca/" >Posts</a></li>
                <li role="presentation" class="active"><a href="#user" aria-controls="user" role="tab" data-toggle="tab">Usuários</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="user">
                <div class="jumbotron formulario">
                        <div class="container">
                            <form class="form-horizontal form-inline" action="<?php echo home_url('/'); ?>busca/usuarios/" id="filter">
                                <div class="col-xs-12">   
                                        <?php UFMunicipio::form( array(
                                            'content_before' => '',
                                            'content_after' => '',
                                            'content_before_field' => '<div class="form-group">',
                                            'content_after_field' => '</div>',
                                            'select_before' => ' ',
                                            'select_after' => ' ',
                                            'state_label' => 'Estado &nbsp',
                                            'state_field_name' => 'uf',
                                            'city_label' => 'Cidade &nbsp',
                                            'select_class' => 'form-control',
                                            'label_class' => 'control-label',
                                            'show_label' => true
                                        ) ); ?>
                                        <label for="date" class="control-label">Data de nascimento</label>
                                        <div class="form-group">
                                            <div class="input-group input-daterange">
                                                <input type="text" class="form-control" value="" name="date_from">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="keyword" class="control-label">Palavra Chave</label>
                                            <input type="text" name="keyword" id="keyword" class="form-control" value="<?php echo $s; ?>">
                                        </div>
                                    <button type="submit" class="btn btn-default filtro">Filtrar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row resultado">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="pull-right">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        Classificar por
                                        <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            <li><a href="?rhs_order=date">Data</a></li>
                                            <li><a href="?rhs_order=comments">Comentários</a></li>
                                            <li><a href="?rhs_order=votes">Votos</a></li>
                                            <li><a href="?rhs_order=views">Visualizações</a></li>
                                            <li><a href="?rhs_order=shares">Compartilhamentos</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(function() {
        jQuery.fn.datepicker.defaults.templates = {
            leftArrow: "<i class='glyphicon glyphicon-chevron-left'></i>",
            rightArrow: "<i class='glyphicon glyphicon-chevron-right'></i>"
        };
        jQuery.fn.datepicker.dates["pt-BR"]={days:["Domingo","Segunda","Terça","Quarta","Quinta","Sexta","Sábado"],daysShort:["Dom","Seg","Ter","Qua","Qui","Sex","Sáb"],daysMin:["Do","Se","Te","Qu","Qu","Se","Sa"],months:["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],monthsShort:["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"],today:"Hoje",monthsTitle:"Meses",clear:"Limpar",format:"yyyy-mm-dd"};
        jQuery.fn.datepicker.defaults.language = "pt-BR";
        jQuery.fn.datepicker.defaults.orientation = "bottom";
        jQuery('.input-daterange input').each(function() {
            jQuery(this).datepicker('clearDates');
        });
        jQuery('#filter .tags').val('');
    });
</script> 

<?php get_footer('full');
