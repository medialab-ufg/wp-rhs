jQuery(document).ready(function() {
    
    
    
    wp.media.featuredImage.select = function() {
            
            wp.media.view.settings.post.featuredImageId = this.get('selection').single().id;

            //set image id to hidden input
            document.getElementById("img_destacada").value = this.get('selection').single().id;
    }
    
    jQuery('.set_img_destacada').click(function() {
        
            wp.media.featuredImage.frame().open();
    
    });

});