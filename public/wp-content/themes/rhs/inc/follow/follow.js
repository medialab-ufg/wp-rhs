jQuery( function( $ ) {

    var trigger = ".follow-btn";

    $(trigger).click(function() {
        var author_id = $(this).data('author_id');
        var link_class = $(this).attr('class');
        var button = $(this);

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
            $(button).html(response == 1 ? "Seguir" : "Parar de Seguir");
            $(button).removeClass('loading');
        };
    });


});
