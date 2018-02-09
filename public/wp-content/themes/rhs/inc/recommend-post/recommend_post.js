jQuery( function( $ ) {
    var input_recommend_post = '#input-recommend-post';
    
    $(input_recommend_post).autocomplete({
        serviceUrl: vars.ajaxurl,
        type: 'POST',
        dataType: 'json',
        paramName: 'string',
        params : {
            action: 'show_people_to_recommend',
        },
        minChars: 3,
        onSelect: function (suggestion) {
            swal({
                title: "Deseja indicar esse post?",
                text: "A indicação será enviada para " + suggestion.value,
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Sim, enviar!",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        async: false,
                        type: "POST",
                        dataType: "json",
                        url: vars.ajaxurl,
                        data: {
                            action: 'recommend_the_post',
                            user_id: suggestion.data,
                            post_id: $(input_recommend_post).data('post-id')
                        },
                        success: function (data) {
                            swal("Enviado!", "Indicação enviada com sucesso.", "success");
                        },
                        error: function (data) {
                            swal("Não enviado", "Sua indicação não foi enviada.", "error");
                        }
                    });
                } else {
                    swal("Cancelado", "Não enviado", "error");
                }
            });
            
            return false;
        }
    });
});