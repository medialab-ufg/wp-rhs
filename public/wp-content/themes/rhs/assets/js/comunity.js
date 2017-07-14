jQuery( function( $ ) {

    $(function () {

        $('.comunidades .forum-info ul li a.hide').hide();
        $('.comunidades .forum-info ul li a').removeClass('hide');

        $('.comunidades .forum-info ul li a#comunity-follow').click(function () {

            var comunidade_id = $(this).closest('.forum-info').attr('data-id');

            $('.comunidades .forum-info ul li a#comunity-follow').fadeOut();

            $.ajax({
                type: "POST",
                dataType: "json",
                url: vars.ajaxurl,
                data: {action: 'follow_comunity', comunidade_id: comunidade_id},
                success: function (data) {
                    if (data) {
                        $('.comunidades .forum-info ul li a#comunity-not-follow').fadeIn();
                    }
                },
                error: function (data) {

                }
            });
        });

        $('.comunidades .forum-info ul li a#comunity-not-follow').click(function () {
            var comunidade_id = $(this).closest('.forum-info').attr('data-id');

            $('.comunidades .forum-info ul li a#comunity-not-follow').fadeOut();

            $.ajax({
                type: "POST",
                dataType: "json",
                url: vars.ajaxurl,
                data: {action: 'not_follow_comunity', comunidade_id: comunidade_id},
                success: function (data) {
                    if (data) {

                        $('.comunidades .forum-info ul li a#comunity-follow').fadeIn();
                    }
                },
                error: function (data) {

                }
            });
        });

        $('.comunidades .forum-info ul li a#comunity-enter').click(function () {

            var comunidade_id = $(this).closest('.forum-info').attr('data-id');

            $('.comunidades .forum-info ul li a#comunity-enter').fadeOut();

            $.ajax({
                type: "POST",
                dataType: "json",
                url: vars.ajaxurl,
                data: {action: 'enter_comunity', comunidade_id: comunidade_id},
                success: function (data) {
                    if (data) {
                        $('.comunidades .forum-info ul li a#comunity-leave').fadeIn();
                    }
                },
                error: function (data) {

                }
            });
        });

        $('.comunidades .forum-info ul li a#comunity-leave').click(function () {
            var confimation = confirm('Você tem certeza que quer sair dessa comunidade?');

            if (!confimation) {
                return;
            }

            var comunidade_id = $(this).closest('.forum-info').attr('data-id');

            $('.comunidades .forum-info ul li a#comunity-leave').fadeOut();

            $.ajax({
                type: "POST",
                dataType: "json",
                url: vars.ajaxurl,
                data: {action: 'leave_comunity', comunidade_id: comunidade_id},
                success: function (data) {
                    if (data) {
                        $('.comunidades .forum-info ul li a#comunity-enter').fadeIn();
                    }
                },
                error: function (data) {

                }
            });
        });

    });
});