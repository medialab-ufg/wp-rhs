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

});