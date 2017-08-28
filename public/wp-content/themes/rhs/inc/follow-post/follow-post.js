jQuery( function( $ ) {
    
        var trigger = ".follow-post-btn";
            
        $(trigger).click(function() {
            var post_id = $(this).data('post_id');
            var link_class = $(this).attr('class');
            var button = $(this);
            var button_value = $(this).html();
    
            $.ajax({
                url: follow_post.ajaxurl,
                method: 'POST',
                data:  {
                    action: 'rhs_follow_post',
                    post_id: post_id
                },
                beforeSend: function () {
                    $(button).addClass('loading');
                    $(button).html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
                },
                success: function(response){
                    response == 1 || response == 2 ? changeButton(response) : error_handler()
                },
                error: error_handler,
            });
    
            function changeButton(response){
                $(button).html(response == 1 ? "Seguir Post" : "Deixar de Seguir Post");
                $(button).removeClass('loading');
            };
            
            var error_handler = function(xhr, textStatus, error){
                swal({title: "Erro, tente novamente por favor.", html: true});
                $(button).removeClass('loading');
                $(button).html(button_value);
            };
        });
    
    
    });
    