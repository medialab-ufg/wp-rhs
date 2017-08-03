jQuery( function( $ ) {

    $('#estado').change(function() {
        if ($(this).val() != '') {
            var selected = $('#municipio').val();
            $('#municipio').html('<option value="">Carregando...</option>');

            
            $.ajax({
                url: vars.ajaxurl, 
                type: 'post',
                data: {action: 'get_cities_options', uf: $('#estado').val(), selected: selected},
                success: function(data) {
                    $('#estado').tooltip('hide');
                    $('#municipio').html(data).attr('title', 'Defina o municipio relacionado a este post aqui').tooltip({placement: "left"}).tooltip('show');
                } 
            });
        }
    }).change();

});
