jQuery( function( $ ) {

    var postID = $('.post-containe').attr('ID');

    $.ajax({
        async: false,
        type: "POST",
        dataType: "json",
        url: vars.ajaxurl,
        data: {action: 'add_data_view', 'postID': postID, 'json' : true, type: 'rhs_data_view'},
        success: function (data) {

        },
        error: function (data) {

        }
    });

    $('.facebook_share').click(function () {
        jQuery( function( $ ) {
            $.ajax({
                async: false,
                type: "POST",
                dataType: "json",
                url: vars.ajaxurl,
                data: {action: 'add_data_facebook', 'postID': postID, 'json' : true, type: 'rhs_data_facebook'},
                success: function (data) {

                },
                error: function (data) {

                }
            });
        });
    });

    $('.twitter_share').click(function () {
        jQuery( function( $ ) {
            $.ajax({
                async: false,
                type: "POST",
                dataType: "json",
                url: vars.ajaxurl,
                data: {action: 'add_data_twitter', 'postID': postID, 'json' : true, type: 'rhs_data_twitter'},
                success: function (data) {

                },
                error: function (data) {

                }
            });
        });
    });

    $('.whatsapp_share').click(function () {
        jQuery( function( $ ) {
            $.ajax({
                async: false,
                type: "POST",
                dataType: "json",
                url: vars.ajaxurl,
                data: {action: 'add_data_whatsapp', 'postID': postID, 'json' : true, type: 'rhs_data_whatsapp'},
                success: function (data) {

                },
                error: function (data) {

                }
            });
        });
    });

});