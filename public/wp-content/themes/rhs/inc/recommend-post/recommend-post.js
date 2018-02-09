jQuery( function( $ ) {
    var input_recommend_post = '#input-recommend-post';

    $(input_recommend_post).autocomplete({
        serviceUrl: vars.ajaxurl,
        type: 'POST',
        dataType: 'json',
        paramName: 'string',
        params : {
            action: 'show_people_to_recommend',
        },
        minChars: 3,
        onSelect: function (suggestion) {
            $.ajax({
                async: false,
                type: "POST",
                dataType: "json",
                url: vars.ajaxurl,
                data: {
                    action: 'recommend_the_post',
                    user_id: suggestion.data,
                    post_id: $(input_recommend_post).data('post-id')
                },
                success: function (data) {
                    if (data['messages']) {
                        var html = '';
                        Object.keys(data['messages']).forEach(function(key) {
                            html += data['messages'][key];
                        });
                        swal({title: html, html: true});
                    }
                },
                error: function (data) {
                    swal({title: data, html: true});
                }
            });
        }
    });
});