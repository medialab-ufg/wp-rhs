jQuery(function () {
    jQuery("#parametros").submit(function (event) {
        event.preventDefault();
        jQuery.post(ajax.ajaxurl, {
            action: 'gen_graph',
            type: jQuery("#type").val()
        }).success(function (r) {
            console.log("enviado");
        });
    });
});