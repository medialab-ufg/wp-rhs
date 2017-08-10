jQuery( function( $ ) {

    var triggers = ".follow-btn, .unfollow-btn";

    $(triggers).click(function() {
        var author_id = $(this).data('author_id');
        var link_class = $(this).attr('class');
        var button = $(this);

        if(button.hasClass('follow-btn')) {
            $(this).removeClass("follow-btn");
            $(this).addClass("unfollow-btn");
            var button_text = "Parar de Seguir";
        } else {
            $(this).removeClass("unfollow-btn");
            $(this).addClass("follow-btn");
            var button_text = "Seguir";
        }

        $.ajax({
            url: follow.ajaxurl,
            method: 'POST',
            data:  {
                action: 'rhs_follow',
                author_id: author_id
            },
            beforeSend: function () {
                $(button).addClass('loading');
                $(button).html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
            },
            success: changeButton
        });

        function changeButton(response){
            console.log(response);
            $(button).removeClass('loading');
            $(button).html(button_text);
        };
    });


});
