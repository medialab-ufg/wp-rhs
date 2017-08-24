<?php get_header('full'); ?>

<?php
// Parametros de busca
echo $RHSSearch->get_param('keyword');
echo $RHSSearch->get_param('uf');
echo $RHSSearch->get_param('municipio');
echo $RHSSearch->get_param('date_from');
echo $RHSSearch->get_param('date_to');
echo $RHSSearch->get_param('rhs_order'); // comments, views, shares, votes ou date (padrão)
echo get_query_var('cat');
echo get_query_var('tag');

//var_dump($wp_query);

?>

<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default busca-page" style="padding: 10px;">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#posts" aria-controls="posts" role="tab" data-toggle="tab">Posts</a></li>
                <li role="presentation"><a href="#user" aria-controls="user" role="tab" data-toggle="tab">Usuários</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="posts">
                    <div class="jumbotron formulario">
                        <div class="container">
                            <form class="form-horizontal">
                                <div class="col-xs-12 col-sm-7">
                                    <div class="form-inline">    
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
                                    </div>

                                    <div class="form-inline">
                                        <div class="col-xs-12 col-sm-6 tags-cats">
                                            <div class="form-group">
                                                <label for="tag" class="control-label">Tags</label>
                                                <input type="text" value="" class="form-control" id="input-tag" placeholder="Tags" name="tag">
                                                <script>
                                                    var ms = jQuery('#input-tag').magicSuggest({
                                                        placeholder: 'Select...',
                                                        allowFreeEntries: true,
                                                        selectionPosition: 'bottom',
                                                        selectionStacked: true,
                                                        selectionRenderer: function(data){
                                                            return data.id;
                                                        },
                                                        data: vars.ajaxurl,
                                                        dataUrlParams: { action: 'get_tags' },
                                                        minChars: 3,
                                                        name: 'tag',
                                                        maxSelection: 1
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 tags-cats">
                                            <div class="form-group">
                                                <label for="categoria" class="control-label">Categoria</label>
                                                <?php wp_dropdown_categories( 'show_option_none=Selecione uma Categorias&option_none_value=&hierarchical=1&orderby=name&class=form-control tags' ); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-5">
                                    <div class="form-inline">
                                        <label for="date" class="control-label">Data</label>
                                        <div class="form-group">
                                            <div class="input-group input-daterange">
                                                <input type="text" class="form-control" value="" name="date_from">
                                                <div class="input-group-addon">até</div>
                                                <input type="text" class="form-control" value="" name="date_to">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-default filtro">Filtrar</button>
                            </form>
                        </div>
                    </div>
                    <script>
                        jQuery(function() {
                            jQuery.fn.datepicker.defaults.templates = {
                                leftArrow: "<i class='fa fa-long-arrow-left'></i>",
                                rightArrow: "<i class='fa fa-long-arrow-right'></i>"
                            };
                            jQuery.fn.datepicker.dates["pt-BR"]={days:["Domingo","Segunda","Terça","Quarta","Quinta","Sexta","Sábado"],daysShort:["Dom","Seg","Ter","Qua","Qui","Sex","Sáb"],daysMin:["Do","Se","Te","Qu","Qu","Se","Sa"],months:["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],monthsShort:["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"],today:"Hoje",monthsTitle:"Meses",clear:"Limpar",format:"yyyy-mm-dd"};
                            jQuery.fn.datepicker.defaults.language = "pt-BR";
                            jQuery('.input-daterange input').each(function() {
                                jQuery(this).datepicker('clearDates');
                            });
                        });
                    </script> 
                    <div class="row resultado">
                        <?php get_template_part( 'partes-templates/loop-posts'); ?>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="user">...</div>
            </div>
        </div>
    </div>
</div>

<?php get_footer('full');
