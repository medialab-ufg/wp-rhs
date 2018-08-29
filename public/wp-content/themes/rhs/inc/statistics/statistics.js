jQuery(function () {
    jQuery("#parametros").submit(function (event) {
        jQuery.post(ajax_vars.ajaxurl, {
            action: 'rhs_gen_graphic',
            type: jQuery("#type").val()
        }).success(function (r) {
            r = JSON.parse(r);
        });

        event.preventDefault();
    });
});