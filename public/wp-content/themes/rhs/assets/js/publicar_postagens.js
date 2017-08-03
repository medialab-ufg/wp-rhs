jQuery(document).ready(function() {

    wp.media.featuredImage.select = function() {
        console.log(this.get('selection').single().id);
        wp.media.view.settings.post.featuredImageId = this.get('selection').single().id;
        var imgurl = this.get('selection').single().attributes.sizes.thumbnail.url;

        //set image id to hidden input
        document.getElementById("img_destacada").value = this.get('selection').single().id;
        jQuery("#img_destacada_preview").html('<img src="'+imgurl+'">');
    }
    
    jQuery('.set_img_destacada').click(function() {
        
            wp.media.featuredImage.frame().open();
    
    });
    
    jQuery('.style #estado').attr('title', 'Se este post está relacionado a um Estado, indique aqui').tooltip({placement: "left"}).tooltip('show');

    wp.media.view.settings.post = {
        id: jQuery('#post_ID').val(),
        featuredImageId: jQuery('#img_destacada').val()
    }
});