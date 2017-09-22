jQuery( function( $ ) {

    $('[data-toggle="tooltip"]').tooltip();
    $('.uniform').uniform();
    
    $('.list-members li .member').popover({html : true, container: 'body'});

    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.form-image').removeClass('hide');
                $('.form-image img').attr('src', e.target.result);
                $('.form-image .save').removeClass('hide');
                $('.form-image .button-end .btn').addClass('hide');

            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $('#carousel-example-generic').on('slid.bs.carousel', function(event) {
        //console.log(event);
        var item = $('#' + event.currentTarget.id).find('div.item.active');
        if (item) {
            var itemNumber = item.data('carousel-item');
            $.ajax({
                async: false,
                type: "POST",
                dataType: "json",
                url: FuncoesForm.ajaxurl,
                data: {action: 'rhs_test_carousel', item: itemNumber, 'json' : true, type: 'carousel-view'},
                success: function (data) {

                },
                error: function (data) {

                }
            });
        }
        
    });
    
    $("#edit-avatar").change(function () {
        readURL(this);
    });

    $('#file-avatar_comunity').change(function () {
        readURL(this);
    });
    
    $('#pre-visualizar').click(function () {

        var title = $('#verDados input#title').val();
        var html = document.getElementById('public_post_ifr').contentWindow.document.body.innerHTML;

        $("#pre-view").show(100, function () {
            $('html,body').animate({scrollTop: $("#pre-view").offset().top}, 'slow');
        });

        if($('#pre-view .panel').is(':visible')){
            $('#pre-view .panel').fadeOut('slow', function () {
                $("#pre-view .panel-icon").fadeIn('slow');
            });
        } else {
            $("#pre-view .panel-icon").fadeIn('slow');
        }

        $("#pre-view .panel-icon").fadeIn('slow');

        setTimeout(function () {
            $("#pre-view .panel-icon").fadeOut('slow', function () {
                $('#pre-view .panel .post-titulo h3').html(title);
                $('#pre-view .panel .content').html(html);
                $('#pre-view .panel').fadeIn('slow');
            });
        }, 2000);


    });



    //Share
    // upon clicking a share button
    jQuery('.share-wrap a').click(function(event){

        // don't go the the href yet
        event.preventDefault();

        // if it's facebook mobile
        if(jQuery(this).data('facebook') == 'mobile') {
            FB.ui({
                method: 'share',
                mobile_iframe: true,
                href: jQuery(this).data('href')
            }, function(response){});
        } else {
            // these share options don't need to have a popup
            if (jQuery(this).data('site') == 'email' || jQuery(this).data('site') == 'print') {

                // just redirect
                window.location.href = jQuery(this).attr("href");
            } else {

                // prepare popup window
                var width  = 575,
                    height = 520,
                    left   = (jQuery(window).width()  - width)  / 2,
                    top    = (jQuery(window).height() - height) / 2,
                    opts   = 'status=1' +
                        ',width='  + width  +
                        ',height=' + height +
                        ',top='    + top    +
                        ',left='   + left;

                // open the share url in a smaller window
                window.open(jQuery(this).attr("href"), 'share', opts);
            }
        }
    });

    //SDK Facebook
    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v2.10";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    //Pagina Membros
    $('.roles').editable({
        value: 1,    
        source: [
            {value: 1, text: 'Selecione'},
            {value: 1, text: 'Gerenciador'},
            {value: 2, text: 'Super Gerenciador'}
           ]
    });
    
    $(document).ready(function() {
        $(".btn-pref .btn").click(function () {
            $(".btn-pref .btn").removeClass("btn-primary").addClass("btn-default");
            // $(".tab").addClass("active"); // instead of this do the below 
            $(this).removeClass("btn-default").addClass("btn-primary");   
        });


        $('.masonry').masonry({
            percentPosition: true,
            itemSelector: '.grid-item',
            columnWidth: '.grid-sizer',
            gutter: '.gutter-sizer',
            horizontalOrder: true
        });
    });

    $('#button-notifications').click(function () {

        var button = $(this);

        $.ajax({
            async: false,
            type: "POST",
            dataType: "json",
            url: vars.ajaxurl,
            data: {action: 'rhs_clear_notification'},
            success: function (data) {
                if (data) {
                    $(button).find('i').removeClass('notification-count');
                }
            },
            error: function (data) {

            }
        });
    })

});

