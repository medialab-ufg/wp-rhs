jQuery( function( $ ) {

    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.form-image').removeClass('hide');
                $('.form-image img').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#edit-avatar").change(function () {
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

});