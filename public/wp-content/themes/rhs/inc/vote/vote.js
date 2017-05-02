jQuery( function( $ ) {

    $('.js-vote-button').click(function() {
        var post_id = $(this).data('post_id');
        $('#votebox-'+post_id).html('...').load(
            vote.ajaxurl, 
            {
                action: 'rhs_vote', 
                post_id: post_id
            }
        );
    });

});
