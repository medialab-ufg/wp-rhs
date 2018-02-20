jQuery( function( $ ) {
    
    var trigger_modal = ".modal-delete-account";
    $(trigger_modal).on("click", function(e) {
        var html_content = "<hr>"+
        "<i class='fa fa-spinner fa-spin' id='spinner-content-download'></i>"+
        "<a class='btn btn-primary download-my-content'>Realizar o download de conteúdo do meu perfil</a>"+
        "<hr>"+
        "<div>"+
        "<label for='send-to-legacy-user'>"+
        "<input type='checkbox' value='true' name='send-to-legacy-user' id='send-to-legacy-user' checked='checked'> Manter meu conteúdo como acervo da RHS"+
        "</label>"+
        "<p class='extra-small-type'>as publicações ficarão em nome da RHS e sua identidade será preservada.</p>"+
        "</div>"+
        "<hr>"+
        "<div>"+
        "<a class='btn btn-danger delete-my-account send-to-legacy' data-send-to-legacy-user='true''>Excuir conta definitivamente</a>"+
        "<a class='btn btn-danger delete-my-account dont-send-to-legacy' data-send-to-legacy-user='false''>Excuir conta definitivamente</a>"+
        "</div>"+
        "<hr>"
        ;
        
        e.preventDefault();
        swal({
            title: "Excluir Conta?",
            text: html_content,
            html: true,
            type: "warning",
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: "Cancelar",
        });
        
        $('.dont-send-to-legacy, #spinner-content-download').hide();

        $('#send-to-legacy-user').bind('change', function() {
            if (this.checked) {
                $('.dont-send-to-legacy').hide();
                $('.send-to-legacy').show();
            } else { 
                $('.send-to-legacy').hide();
                $('.dont-send-to-legacy').show();
            }
        });
    });

    $(document).on('click', '.download-my-content', function() {
        var d = new Date();
        var filename = "RHS_meu_backup_de_posts_" + d.getDate() + "" + (d.getMonth() + 1) + ""+ d.getFullYear() + ".xls";
        $('.download-my-content').hide();
        $('#spinner-content-download').show();
        $.ajax({
            type: "POST",
            url: user_vars.ajaxurl,
            cache: false,
            data: {
                action: 'generate_backup_file',
                vars_to_generate: user_vars
            },
            success: function(output) {
                var blob = new Blob(["\ufeff", output]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;
                link.click();
                $('#spinner-content-download').hide();
                $('.download-my-content').show();
            }
        });

    });
    $(document).on('click', '.delete-my-account', function() {
        var send_to_legacy_user = $(this).data('send-to-legacy-user');

        $.ajax({
            type: "POST",
            url: user_vars.ajaxurl,
            cache: false,
            data: {
                action: 'delete_my_account',
                send_to_legacy_user: send_to_legacy_user
            },
            success: function(output) {
                swal({
                    title: "Excluída!", 
                    text: "Conta excluída com sucesso.", 
                    type: "success",
                  }, function() {
                    window.location.href = window.location.origin;
                  });
            }
        });

    });
});