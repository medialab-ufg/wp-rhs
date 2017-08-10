jQuery( function( $ ) {

    $( "body" ).on( "click", '.comunidade .contato .well.profile_view .bottom a', function () {

        var obj = '';
        var term_id = $(this).closest('.well-disp').attr('data-id');
        var user_id = $(this).closest('.well-disp').attr('data-userid');
        var type = $(this).attr('data-type');
        var href = $(this).attr('href');
        var box_user = $(this).closest('.well-disp');

        if(href != 'javascript:;'){
            return false;
        }

        if(type == 'leave'){
            swal({
                    title: "<i class='fa fa-exclamation-triangle'></i> Tem certeza que quer sair da comunidade?",
                    showCancelButton: true,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "Remover",
                    cancelButtonText: "Cancelar",
                    closeOnConfirm: true
                },
                function(){
                    if (!isConfirm) {
                        return false;
                    }
                });
        }

        $.ajax({
            async: false,
            type: "POST",
            dataType: "json",
            url: vars.ajaxurl,
            data: {
                action: 'comunity_action',
                type: type,
                term_id: term_id,
                user_id: user_id,
                user_out: true},
            success: function (data) {

                if(data) {
                    if (data['permissions']) {
                        if (type == 'leave' || type == 'reject_request') {
                            $(box_user).slideUp('slow');
                        }

                        if (type == 'not_moderate') {
                            $(box_user).find('.right h2 span .fa-address-card-o').slideUp('slow');
                        }

                        if (type == 'moderate') {
                            $(box_user).find('.right h2 span .fa-address-card-o').slideDown('slow');
                        }

                        if (type == 'accept_request') {
                            $(box_user).find('.right h2 span .fa-info-circle').slideUp('slow');
                        }

                        Object.keys(data['permissions']).forEach(function(key) {

                            obj = $('.comunidade .contato .well-disp[data-userid="'+user_id+'"] a[data-type="' + key + '"]');

                            if (data['permissions'][key]) {
                                $(obj).fadeIn();
                            } else {
                                $(obj).fadeOut();
                            }

                        });
                    }

                    if (data['messages']) {

                        var html = '';

                        Object.keys(data['messages']).forEach(function(key) {
                            html += data['messages'][key];
                        });

                        swal({title: html, html: true});
                    }

                }
            },
            error: function (data) {
                swal({title: data, html: true});
            }
        });

    });

    $( "body" ).on( "click", '.comunidade .card.hovercard .card-buttons a', function () {

        var obj = '';
        var term_id = $(this).closest('.comunidade').attr('data-id');
        var user_id = $(this).closest('.comunidade').attr('data-userid');
        var type = $(this).attr('data-type');
        var href = $(this).attr('href');

        if(href != 'javascript:;'){
            return true;
        }

        $.ajax({
            async: false,
            type: "POST",
            dataType: "json",
            url: vars.ajaxurl,
            data: {
                action: 'comunity_action',
                type: type,
                term_id: term_id,
                user_id: user_id},
            success: function (data) {

                if(data) {
                    if (data['permissions']) {

                        $('.comunidade[data-id="'+term_id+'"] .card.hovercard .card-buttons').slideUp('normal', function () {

                            Object.keys(data['permissions']).forEach(function(key) {

                                obj = $('.comunidade[data-id="'+term_id+'"] .card.hovercard .card-buttons a[data-type="' + key + '"]')

                                if (data['permissions'][key]) {
                                    $(obj).show();
                                } else {
                                    $(obj).hide();
                                }

                            });

                            $('.comunidade[data-id="'+term_id+'"] .card.hovercard .card-buttons').slideDown('normal');

                        });
                    }

                    /*if (data['messages']) {
                        $('.comunidade > .alert').remove();
                        Object.keys(data['messages']).forEach(function(key) {
                            $('.comunidade').prepend(data['messages'][key]);
                        });

                    }*/

                    if (data['messages']) {

                        var html = '';

                        Object.keys(data['messages']).forEach(function(key) {
                            html += data['messages'][key];
                        });

                        swal({title: html, html: true});
                    }

                    if (data['refresh']) {
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    }
                }
            },
            error: function (data) {
                /*var alert = '<div class="alert alert-danger">Sua requisição não foi, tente novamente.</div>';

                $('.comunidades .ibox-content.forum-container').prepend(alert);*/
            }
        });

    });

    $( "body" ).on( "click", '.comunidades .content-table ul li .btn-rhs', function () {
        var obj = '';
        var term_id = $(this).closest('tr').attr('data-id');
        var user_id = $(this).closest('tr').attr('data-userid');
        var type = $(this).attr('data-type');
        var href = $(this).attr('href');
        var number_members = $(this).closest('tr').find('ul li:first-child .views-number');
        var icon_member = $(this).closest('tr').find('i.fa-user');
        var number = 0;

        if(type == 'enter'){
            number = Number($(number_members).html()) + 1;
        }

        if(type == 'leave'){
            number = Number($(number_members).html()) - 1;

            /*swal({
                    title: "Tem certeza que quer sair da comunidade?",
                    showCancelButton: true,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "Remover",
                    cancelButtonText: "Cancelar",
                    closeOnConfirm: true
                },
                function(){
                    if (!isConfirm) {
                        return false;
                    }
                });*/
        }

        if(href != 'javascript:;'){
            return true;
        }

        $.ajax({
            async: false,
            type: "POST",
            dataType: "json",
            url: vars.ajaxurl,
            data: {
                action: 'comunity_action',
                type: type,
                term_id: term_id,
                user_id: user_id},
            success: function (data) {

                if(data) {
                    if (data['permissions']) {

                        if(type == 'enter'){
                            $(number_members).slideUp('slow').html(number).slideDown('slow');
                            $(icon_member).slideDown('slow');
                        }

                        if(type == 'leave'){
                            $(number_members).slideUp('slow').html(number).slideDown('slow');
                            $(icon_member).slideUp('slow');
                        }

                        $('.comunidades .content-table .table tr[data-id="'+term_id+'"] td:last-child ul').slideUp('slow', function () {

                            Object.keys(data['permissions']).forEach(function(key) {

                                obj = $('.comunidades .content-table .table tr[data-id="'+term_id+'"] td:last-child  ul li a[data-type="' + key + '"]')

                                if (data['permissions'][key]) {
                                    $(obj).show();
                                } else {
                                    $(obj).hide();
                                }

                            });

                            $('.comunidades .content-table .table tr[data-id="'+term_id+'"] td ul').slideDown('slow');

                        });

                    }

                    if (data['messages']) {

                        var html = '';

                        Object.keys(data['messages']).forEach(function(key) {
                            html += data['messages'][key];
                        });

                        swal({title: html, html: true});
                    }
                }
            },
            error: function (data) {
                swal({title: data, html: true});
            }
        });
    });

    $(".typeahead").autocomplete({
        serviceUrl: vars.ajaxurl,
        type: 'POST',
        dataType: 'json',
        paramName: 'string',
        params : {
            action: 'complete_comunity_members',
            comunity_id: $('.comunidade').attr('data-id')
        },
        minChars: 3,
        onSelect: function (suggestion) {

            $.ajax({
                async: false,
                type: "POST",
                dataType: "json",
                url: vars.ajaxurl,
                data: {
                    action: 'comunity_action_add_member',
                    user_id: suggestion.data,
                    comunity_id: $('.comunidade').attr('data-id')
                },
                success: function (data) {

                    if(data['user']){

                        var box = $('.comunidade .tab-content .tab-pane .contato .wrapper-content > .row:last-child > div:first-child').clone();
                        $(box).find('.well-disp').attr('data-userid', data['user']['user_id']);
                        $(box).find('.well-disp').attr('data-id', data['user']['comunity_id']);
                        $(box).find('.well.profile_view .comunity-avatar').html(data['user']['avatar']);
                        $(box).find('.well.profile_view .comunity-name').html(data['user']['name']);
                        $(box).find('.well.profile_view .comunity-moderate').hide();
                        $(box).find('.well.profile_view .comunity-request').hide();
                        $(box).find('.well.profile_view .comunity-location').html(data['user']['location']);
                        $(box).find('.well.profile_view .comunity-date').html(data['user']['date']);
                        $(box).find('.well.profile_view .comunity-buttons').html(data['user']['buttons']);
                        $(box).hide();

                        $('.comunidade .tab-content .tab-pane .contato .wrapper-content > .row:last-child').prepend(box);
                        $('.comunidade .tab-content .tab-pane .contato .wrapper-content > .row:last-child > div:first-child').slideDown('slow');
                        $('[data-toggle="tooltip"]').tooltip();
                    }

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