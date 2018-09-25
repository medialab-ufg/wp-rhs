jQuery( function( $ ) {

    $('.js-vote-button').click(function() {
        var post_id = $(this).data('post_id');
        var panel = $(this).closest('.panel-heading');
        var button = $(this);
        var button_text = $(this).html();

        if($(this).hasClass('loading')){
            return false;
        }

        $.ajax({
            url: vote.ajaxurl,
            dataType: 'json',
            method: 'POST',
            data:  {
                action: 'rhs_vote',
                post_id: post_id
            },
            beforeSend: function () {
                $(button).addClass('loading');
                $(button).html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>');
            },
            success: function (data) {

                if(data){

                    if(data['error']) {
                        swal({'title':data['error']['text'], html: true});
                    }

                    if(data['success']) {
                        swal({'title':data['success']['text'], html: true});
                        $('#votebox-'+post_id).parent('.votebox').html(data['success']['html']);
                    } else {
                        $(button).html(button_text);
                    }
                }

                $(button).removeClass('loading');
            }
        });
    });

    $(".who-votted").click(function () {
        var post_id = $(this).data('postid');
        if($(".dropdown"+post_id).length == 0)
        {
            $.ajax({
                url: vote.ajaxurl,
                method: 'POST',
                data:  {
                    action: 'rhs_get_posts_vote',
                    post_id: post_id
                }
            }).success(function (html) {
                $(".who-votted[data-postid='"+post_id+"']").after(html);
            });
        }
    });

});
