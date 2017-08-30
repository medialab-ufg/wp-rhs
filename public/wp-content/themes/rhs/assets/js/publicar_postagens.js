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
    
    
    var ms = jQuery('#input-tags').magicSuggest({
        placeholder: 'Select...',
        allowFreeEntries: true,
        selectionPosition: 'bottom',
        selectionStacked: true,
        selectionRenderer: function(data){
            return data.name;
        },
        data: post_vars.ajaxurl,
        dataUrlParams: { 
            action: 'get_tags', 
            term_ids: post_vars.selectedTags
        },
        minChars: 3,
        name: 'tags',
        valueField: 'term_id'
        //value: [17497]
    });
    
    // ver https://github.com/nicolasbize/magicsuggest/issues/21
    jQuery(ms).on('load', function(){
        if(this._dataSet === undefined){
            // we get here the first time the combo has loaded and gone through
            // the first if case in tag-autocomplete-service.php: the combo has been
            // populated with the default values only.
            this._dataSet = true;
            ms.setValue(post_vars.selectedTags);
            ms.setDataUrlParams({action: 'get_tags'});
        }
    });

});