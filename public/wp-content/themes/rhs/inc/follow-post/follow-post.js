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
                $(button).html(response == 1 ? "<span class='follow-post'>Seguir Post <i class='fa fa-rss'></i></span>" : "<span class='unfollow' title='Deixar de Seguir'>Deixar de Seguir <i class='fa fa-remove'></i></span>");
                $(button).removeClass('loading');
            };
            
            var error_handler = function(xhr, textStatus, error){
                swal({title: "Erro, tente novamente por favor.", html: true});
                $(button).removeClass('loading');
                $(button).html(button_value);
            };
        });

        /* 
        //Propor a Mari para exibir quando o usuario abrir o post, o mesmo pode ser usado com um localStorage para ser exibido apenas uma vez.

        $('.share-wrap').attr('title', 'Use estes botões para Seguir o post, compartilhar e imprimir').addClass('focus').tooltip({placement: "auto"}).tooltip('show');
        $('.share-wrap .follow-post-btn .follow-post').attr('title', 'Clique aqui para seguir este post e receber notificações relacionadas a ele.');
        $('.share-wrap .facebook_share').attr('title', 'Clique aqui para Compartilhar no Facebook este Post.');
        $('.share-wrap .twitter_share').attr('title', 'Clique aqui para Compartilhar no Twitter este Post.');
        $('.share-wrap .share_print').attr('title', 'Clique aqui para Imprimir este Post.');

            setTimeout(function(){ $('.share-wrap').tooltip('hide').removeClass('focus'); }, 4000);
            setTimeout(function(){ $('.share-wrap .follow-post-btn .follow-post').tooltip('show'); }, 16001);
            setTimeout(function(){ $('.share-wrap .facebook_share').tooltip('show'); }, 12001);
            setTimeout(function(){ $('.share-wrap .facebook_share').tooltip('hide'); }, 16000);
            setTimeout(function(){ $('.share-wrap .twitter_share').tooltip('show'); }, 8001);
            setTimeout(function(){ $('.share-wrap .twitter_share').tooltip('hide'); }, 12000);
            setTimeout(function(){ $('.share-wrap .share_print').tooltip('show'); }, 4001);
            setTimeout(function(){ $('.share-wrap .share_print').tooltip('hide'); }, 8000);

        */
    
    
    });
    