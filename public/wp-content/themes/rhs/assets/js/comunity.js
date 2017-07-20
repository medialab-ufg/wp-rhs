jQuery( function( $ ) {

    $('.comunidade .contato .well.profile_view .bottom a').click(function () {

        var obj = '';
        var term_id = $(this).closest('.well-disp').attr('data-id');
        var user_id = $(this).closest('.well-disp').attr('data-userid');
        var type = $(this).attr('data-type');
        var href = $(this).attr('href');
        var box_user = $(this).closest('.profile_view');

        if(href != 'javascript:;'){
            return false;
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
                        if (type == 'leave' && data['permissions']['enter']) {
                            $(box_user).find('.bottom').html('');
                            $(box_user).css('opacity','0.5');
                            $(box_user).find('a').css('cursor','no-drop');
                        }

                        cObject.keys(data['permissions']).forEach(function(key) {

                            obj = $('.comunidade .contato .well-disp[data-userid="'+user_id+'"] a[data-type="' + key + '"]');

                            if (data['permissions'][key]) {
                                $(obj).fadeIn();
                            } else {
                                $(obj).fadeOut();
                            }

                        });
                    }

                    if (data['messages']) {
                        $('.comunidade > .alert').remove();
                        Object.keys(data['messages']).forEach(function(key) {
                            $('.wrapper-content').prepend(data['messages'][key]);
                        });

                    }

                }
            },
            error: function (data) {
                var alert = '<div class="alert alert-danger">Sua requisição não foi, tente novamente.</div>';

                $('.comunidades .ibox-content.forum-container').prepend(alert);
            }
        });

    });

    $('.comunidade .card.hovercard .card-buttons a').click(function () {

        var obj = '';
        var term_id = $(this).closest('.comunidade').attr('data-id');
        var user_id = $(this).closest('.comunidade').attr('data-userid');
        var type = $(this).attr('data-type');
        var href = $(this).attr('href');

        if(href != 'javascript:;'){
            return false;
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
                        Object.keys(data['permissions']).forEach(function(key) {

                            obj = $('.comunidade[data-id="'+term_id+'"] .card.hovercard .card-buttons a[data-type="' + key + '"]')

                            if (data['permissions'][key]) {
                                $(obj).fadeIn();
                            } else {
                                $(obj).fadeOut();
                            }
                        });
                    }

                    if (data['messages']) {
                        $('.comunidade > .alert').remove();
                        Object.keys(data['messages']).forEach(function(key) {
                            $('.comunidade').prepend(data['messages'][key]);
                        });

                    }

                    if (data['refresh']) {
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    }
                }
            },
            error: function (data) {
                var alert = '<div class="alert alert-danger">Sua requisição não foi, tente novamente.</div>';

                $('.comunidades .ibox-content.forum-container').prepend(alert);
            }
        });

    });

    $('.comunidades .ibox-content.forum-container ul li a').click(function () {
        var obj = '';
        var term_id = $(this).closest('.forum-info').attr('data-id');
        var user_id = $(this).closest('.forum-info').attr('data-userid');
        var type = $(this).attr('data-type');
        var href = $(this).attr('href');

        if(href != 'javascript:;'){
            return false;
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

                        Object.keys(data['permissions']).forEach(function(key) {

                            obj = $('.comunidades .ibox-content.forum-container .forum-item > .row > div .forum-info[data-id="'+term_id+'"] ul li a[data-type="' + key + '"]')

                            if (data['permissions'][key]) {
                                $(obj).fadeIn();
                            } else {
                                $(obj).fadeOut();
                            }

                        });
                    }

                    if (data['messages']) {
                        $('.comunidades .ibox-content.forum-container > .alert').remove();
                        Object.keys(data['messages']).forEach(function(key) {
                            $('.comunidades .ibox-content.forum-container').prepend(data['messages'][key]);
                        });

                    }
                }
            },
            error: function (data) {
                var alert = '<div class="alert alert-danger">Sua requisição não foi, tente novamente.</div>';

                $('.comunidades .ibox-content.forum-container').prepend(alert);
            }
        });
    });

});