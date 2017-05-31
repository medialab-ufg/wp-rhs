jQuery( function( $ ) {

    $('.js-vote-button').click(function() {
        var post_id = $(this).data('post_id');

        var panel = $(this).closest('.panel-heading');

        $.ajax({
            url: vote.ajaxurl,
            dataType: 'json',
            method: 'POST',
            data:  {
                action: 'rhs_vote',
                post_id: post_id
            },
            beforeSend: function () {
                $('#votebox-'+post_id).html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
            },
            success: function (data) {

                $('#votebox-'+post_id+' i').remove();

                if(data){

                    if(data['error']) {
                        var alrt = '<div class="alert alert-danger"><p><i class="fa fa-exclamation-triangle"></i> '+data['error']+'</p></div>';
                        $('#votebox-'+post_id).html('<i class="fa fa-remove"></i>');
                    }

                    if(data['success']) {
                        $('#votebox-'+post_id).parent('.votebox').html(data['success']);
                    }

                }

                $(alrt).hide();

                $(panel).children('.alert').fadeOut();
                $(panel).prepend(alrt).fadeIn();

            }
        });

    });

});
