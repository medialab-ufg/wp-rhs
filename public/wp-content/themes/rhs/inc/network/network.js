jQuery( function( $ ) {

    var postID = $('.post-container').attr('id');
    postID = postID.replace("post-", "");

    $.ajax({
        async: false,
        type: "POST",
        dataType: "json",
        url: vars.ajaxurl,
        data: {
            action: 'rhs_add_stats_data',
            postID: postID,
            json : true,
            type: RHSNetworkJS.META_KEY_VIEW
        },
        success: function (data) {

        },
        error: function (data) {

        }
    });

    $('.facebook_share').click(function () {
            $.ajax({
                async: false,
                type: "POST",
                dataType: "json",
                url: vars.ajaxurl,
                data: {action: 'rhs_add_stats_data', postID: postID, 'json' : true, type: RHSNetworkJS.META_KEY_FACEBOOK},
                success: function (data) {

                },
                error: function (data) {

                }
            });
    });

    $('.twitter_share').click(function () {
            $.ajax({
                async: false,
                type: "POST",
                dataType: "json",
                url: vars.ajaxurl,
                data: {action: 'rhs_add_stats_data', postID: postID, 'json' : true, type: RHSNetworkJS.META_KEY_TWITTER},
                success: function (data) {

                },
                error: function (data) {

                }
            });
    });

    $('.whatsapp_share').click(function () {
            $.ajax({
                async: false,
                type: "POST",
                dataType: "json",
                url: vars.ajaxurl,
                data: {action: 'rhs_add_stats_data', postID: postID, 'json' : true, type: RHSNetworkJS.META_KEY_WHATSAPP},
                success: function (data) {

                },
                error: function (data) {

                }
            });
    });
    
    $('.share_print').click(function () {
            $.ajax({
                async: false,
                type: "POST",
                dataType: "json",
                url: vars.ajaxurl,
                data: {action: 'rhs_add_stats_data', postID: postID, 'json' : true, type: RHSNetworkJS.META_KEY_PRINT},
                success: function (data) {

                },
                error: function (data) {

                }
            });
    });

});
