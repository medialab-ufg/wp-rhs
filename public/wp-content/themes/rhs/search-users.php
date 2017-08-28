<?php get_header('full'); ?>

<div class="container">
    <form >
        <div class="col-xs-6">
            <div class="form-inline">    
                <?php UFMunicipio::form( array(
                    'content_before' => '',
                    'content_after' => '',
                    'content_before_field' => '<div class="form-group">',
                    'content_after_field' => '</div>',
                    'select_before' => ' ',
                    'select_after' => ' ',
                    'state_field_name' => 'estado_user',
                    'state_field_id' => 'estado_user',
                    'city_field_id' => 'municipio_user',
                    'city_field_name' => 'municipio_user',
                    'state_label' => 'Estado &nbsp',
                    'city_label' => 'Cidade &nbsp',
                    'select_class' => 'form-control',
                    'show_label' => true
                ) ); ?>
            </div>
        </div>
    </form>
</div>

<?php
// Parametros de busca
$paged = $RHSSearch->get_param('paged') ? $RHSSearch->get_param('paged') : 1;
echo "<h5>parametros</h5>";
echo "uf: " . $RHSSearch->get_param('uf') . "<br/>";

echo "municipio: " . $RHSSearch->get_param('municipio') . "<br/>";
echo "order: " . $RHSSearch->get_param('rhs_order') . "<br/>";
echo "keyword: " . $RHSSearch->get_param('keyword') . "<br/>";
echo "<hr>";

$users = $RHSSearch->search_users(array(
    'uf' => $RHSSearch->get_param('uf'), 
    'keyword' => $RHSSearch->get_param('keyword'),
    'municipio' => $RHSSearch->get_param('municipio')
), $paged);


echo "<hr>";

// User Loop
if (!empty($users->results)) {
	foreach ($users->results as $user) {
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

<ul class="list-group" id="followContent">
    <li class="list-group-item">
        <div class="col-xs-12 col-sm-8">
            <div class="follow-user-thumb">
                <?php echo get_avatar($user->ID, 40); ?>
            </div>
            <div class="user-name"><a href="<?php echo get_author_posts_url($user->ID); ?> "><?php echo $user->display_name; ?></a></div><br/>
        </div>
        <div class="col-xs-12 col-sm-4 text-right">
            <?php $RHSFollow->show_header_follow_box($user->ID); ?>
        </div>
        <div class="clearfix"></div>
    </li>
</ul>

<?php
    }
    
} else {
	echo 'Usuário não encontrado.';
}
$RHSSearch->show_users_pagination($paged);

echo "<br/>total: " . $users->total_users;
?>

<?php get_footer('full');
