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
                var not_sent_title = "Não enviado!";
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
                            if(data.msgErr) {
                                swal(not_sent_title, data.msgErr, "error");
                            } else if(data.messages.success && data.user.sent_name) {
                                swal("Enviado!", "Indicação enviada com sucesso para " + data.user.sent_name, "success");
                                $(input_recommend_post).val('');
                            } else {
                                swal(not_sent_title, "Tente novamente mais tarde!", "error");
                            }                                                    
                        },
                        error: function (data) {
                            swal(not_sent_title, "Sua indicação não foi enviada.", "error");
                        }
                    });
                } else {
                    $(input_recommend_post).val('');
                    swal("Cancelado", not_sent_title, "error");
                }
            });
            
            return false;
        }
    });
});