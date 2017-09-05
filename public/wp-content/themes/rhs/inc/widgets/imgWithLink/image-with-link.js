jQuery(document).ready(function ($) {
      $(document).on("click", ".upload-image-button", function (e) {
            e.preventDefault();
            var $button = $(this);
      
            var file_frame = wp.media.frames.file_frame = wp.media({
                  title: 'Selecionar Imagem',
                  library: {
                        type: 'image'
                  },
                  button: {
                        text: 'Selecionar'
                  },
                  multiple: false
            });
      
            file_frame.on('select', function () {
                  var attachment = file_frame.state().get('selection').first().toJSON();
                  $button.siblings('input').val(attachment.url);
                  refresh_image(attachment.url);
                  show_buttons(attachment.url);
            });
            file_frame.open();
      });

      function refresh_image(url){
            $('.thumb-preview').attr('src', url);
      }

      function show_buttons(url){
            $('.alert-image').hide();
            $('.upload-image-button').hide();
            $(".remove-image").show();
            $('.thumb-label').show();
      }

      $(".remove-image").click(function() {
            var image_id = $(this).data('id');
            $("#"+image_id).val("");
            $('.thumb-label').hide();
            $('.thumb-preview').attr('src','');
            $('.alert-image').show();
            $('.upload-image-button').show();
            $(this).hide();
      });
});

