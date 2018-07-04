var $ = jQuery;
$(function() {
    
    $.fn.datepicker.defaults.templates = {
        leftArrow: "<i class='glyphicon glyphicon-chevron-left'></i>",
        rightArrow: "<i class='glyphicon glyphicon-chevron-right'></i>"
    };
    $.fn.datepicker.dates["pt-BR"]={days:["Domingo","Segunda","Terça","Quarta","Quinta","Sexta","Sábado"],daysShort:["Dom","Seg","Ter","Qua","Qui","Sex","Sáb"],daysMin:["Do","Se","Te","Qu","Qu","Se","Sa"],months:["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],monthsShort:["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"],today:"Hoje",monthsTitle:"Meses",clear:"Limpar",format:"dd-mm-yyyy"};
    $.fn.datepicker.defaults.language = "pt-BR";
    $.fn.datepicker.defaults.orientation = "bottom";
    $('.input-daterange input').each(function() {
        $(this).datepicker();
    });
    
    var ms = $('#input-tag').magicSuggest({
        data: search_vars.ajaxurl,
        dataUrlParams: { 
            action: 'get_tags',
            term_slugs: search_vars.selectedTags
        },
        minChars: 3,
        name: 'tag',
        valueField: 'slug',
        //maxSelection: 1
        selectionPosition: 'bottom',
        selectionStacked: true,
        selectionRenderer: function(data){
            return data.name;
        },
        selectionContainer: $('#custom-ctn'),
    });

    // ver https://github.com/nicolasbize/magicsuggest/issues/21
    $(ms).on('load', function(){
        if(this._dataSet === undefined){
            // Roda apenas da primeira vez e depois remove o parametro term_slugs dos parametros da URL
            this._dataSet = true;
            ms.setValue(search_vars.selectedTags);
            ms.setDataUrlParams({action: 'get_tags'});
        }
    });

    $(document).on("click", '#reset_filters', function () {
        var current_url = window.location.href,
            tabUser = $('a[aria-controls="user"]').parent(),
            get_url = current_url.split("?");

        if(get_url.length > 1)
        {
            if($(tabUser).attr("class") === "active")
            {
                current_url = current_url.split("busca/")[0] + 'busca/usuarios/';
            }else
            {
                current_url = current_url.split("busca/")[0] + 'busca/';
            }

            window.location = current_url;
        }
    });

    $(document).on("click", ".open-modal", function () {
        var format_to_export = $(this).data('format');
        $(".modal-title #format_file").text(format_to_export.toUpperCase());
        if (format_to_export == 'csv') {
            $(".modal-body #result_type_xls").hide();
            $(".modal-body #result_type_csv").show();
        } else {
            $(".modal-body #result_type_csv").hide();
            $(".modal-body #result_type_xls").show();
        }
    });
   
    $(document).ready(function() {
        $(".export-file").click(function () {
            var d = new Date();
            var paged = $(this).attr('data-page');
            var search_page = $(this).attr('data-page-title');
            var format_to_export = $(this).attr('data-page-format');
            var filename = "RHS_pesquisa_" + d.getDate() + "" + (d.getMonth() + 1) + ""+ d.getFullYear() + "_" + search_page + "_pagina_" + paged + "." + format_to_export;
            
            $(this).find('.export-loader').removeClass('hide');
            $.ajax({
                type: "POST",
                url: search_vars.ajaxurl,
                cache: false,
                data: {
                    action: 'generate_file',
                    vars_to_generate: search_vars,
                    paged: paged,
                    format_to_export: format_to_export
                },
                success: function(output) {
                    var blob = new Blob(["\ufeff", output]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;
                    link.click();
                    $('.export-loader').addClass('hide');
                }
            });
        });
    });
});


