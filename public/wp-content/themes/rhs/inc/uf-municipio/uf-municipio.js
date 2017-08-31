jQuery( function( $ ) {
    $('#estado, #estado_user').click(function() {
        var $state_id = "#" +  $(this).attr('id');
        var $city_element =  $(this).closest('div').nextAll('div').first().find('select');
        var $city_id = "#" + $city_element.attr('id');

        $($state_id).change(function() {
            if ($(this).val() != '') {
                var selected = $($city_id).val();
                $($city_id).html('<option value="">Carregando...</option>');
                
                $.ajax({
                    url: vars.ajaxurl, 
                    type: 'post',
                    data: {action: 'get_cities_options', uf: $($state_id).val(), selected: selected},
                    success: function(data) {
                        $('.style ' + $state_id).tooltip('hide');
                        $($city_id).html(data);
                        $('.style ' + $city_id).attr('title', 'Defina o municipio relacionado a este post aqui').tooltip({placement: "left"}).tooltip('show');
                    } 
                });
            }
        }).change();
    });
    
});
