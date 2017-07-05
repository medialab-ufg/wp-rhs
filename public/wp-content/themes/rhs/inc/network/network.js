jQuery( function( $ ) {
    $.ajax({
        async: false,
        type: "POST",
        dataType: "json",
        url: vars.ajaxurl,
        data: {action: 'add_data_view', 'postID': postID, 'json' : true},
        success: function (data) {

        },
        error: function (data) {

        }
    });

    twttr.events.bind(
        'tweet',
        function (event) {
            jQuery( function( $ ) {
                $.ajax({
                    async: false,
                    type: "POST",
                    dataType: "json",
                    url: vars.ajaxurl,
                    data: {action: 'add_data_twitter', 'postID': postID, 'json' : true},
                    success: function (data) {

                    },
                    error: function (data) {

                    }
                });
            });
        }
    );

    FB.ui({
            method: 'feed',
            name: 'Facebook Dialogs',
            link: 'https://developers.facebook.com/docs/dialogs/',
            picture: 'http://fbrell.com/f8.jpg',
            caption: 'Reference Documentation',
            description: 'Dialogs provide a simple, consistent interface for applications to interface with users.'
        },
        function(response) {
            if (response && response.post_id) {
                jQuery( function( $ ) {
                    $.ajax({
                        async: false,
                        type: "POST",
                        dataType: "json",
                        url: vars.ajaxurl,
                        data: {action: 'add_data_facebook', 'postID': postID, 'json' : true},
                        success: function (data) {

                        },
                        error: function (data) {

                        }
                    });
                });
            }
        }
    );
});