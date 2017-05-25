jQuery(document).ready(function() {
  
	jQuery('.carrossel_button').click(function() {
		var action;
		var update_destaque;
		var post_id = jQuery(this).attr('id').replace('carrossel_', '');
		if (jQuery(this).attr('checked')) {
			action = 'destaque_add';
		} else {
			action = 'destaque_remove';
		}
		
		update_destaque = jQuery.ajax({
            type: 'POST',
            url: hacklab.ajaxurl,
            dataType: 'html',
            data: {
				post_id: post_id,
				action: action
            },
            
            complete: function() {
                if (update_destaque.responseText != 'ok')
                	alert('Falha ao setar destaque');
            }
        });
		
	});
	
});
