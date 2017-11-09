jQuery(function() {
    
    jQuery.fn.datepicker.defaults.templates = {
        leftArrow: "<i class='glyphicon glyphicon-chevron-left'></i>",
        rightArrow: "<i class='glyphicon glyphicon-chevron-right'></i>"
    };
    jQuery.fn.datepicker.dates["pt-BR"]={days:["Domingo","Segunda","Terça","Quarta","Quinta","Sexta","Sábado"],daysShort:["Dom","Seg","Ter","Qua","Qui","Sex","Sáb"],daysMin:["Do","Se","Te","Qu","Qu","Se","Sa"],months:["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],monthsShort:["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"],today:"Hoje",monthsTitle:"Meses",clear:"Limpar",format:"dd-mm-yyyy"};
    jQuery.fn.datepicker.defaults.language = "pt-BR";
    jQuery.fn.datepicker.defaults.orientation = "bottom";
    jQuery('.input-daterange input').each(function() {
        jQuery(this).datepicker();
    });
    
    var ms = jQuery('#input-tag').magicSuggest({
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
        selectionContainer: jQuery('#custom-ctn'),
    });

    // ver https://github.com/nicolasbize/magicsuggest/issues/21
    jQuery(ms).on('load', function(){
        if(this._dataSet === undefined){
            // Roda apenas da primeira vez e depois remove o parametro term_slugs dos parametros da URL
            this._dataSet = true;
            ms.setValue(search_vars.selectedTags);
            ms.setDataUrlParams({action: 'get_tags'});
        }
    });

    
    jQuery(document).ready(function() {
       jQuery(".export-csv").click(function () {
        var d = new Date();
        var filename = "RHS_pesquisa_" + d.getDate() + "" + (d.getMonth() + 1) + ""+ d.getFullYear() + ".csv";
        
        jQuery.ajax({
            type: "POST",
            url: search_vars.ajaxurl,
            cache: false,
            data: {
                action: 'generate_csv'
            },
            success: function(output) {
                var blob=new Blob(["\ufeff", output]);
                var link=document.createElement('a');
                link.href=window.URL.createObjectURL(blob);
                link.download=filename;
                link.click();
            }
        });
    
      });
    })
    
});