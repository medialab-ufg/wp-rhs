jQuery(document).ready(function() {

    wp.media.featuredImage.select = function() {
        wp.media.view.settings.post.featuredImageId = this.get('selection').single().id;
        var imgurl = this.get('selection').single().attributes.sizes.thumbnail.url;

        //set image id to hidden input
        document.getElementById("img_destacada").value = this.get('selection').single().id;
        jQuery("#img_destacada_preview").html('<img src="'+imgurl+'">');
    }
    
    jQuery('.set_img_destacada').click(function() {
        
            wp.media.featuredImage.frame().open();
    
    });
    
    jQuery('.publish_post_sidebox_city_state #estado').attr('title', 'Se este post está relacionado a um Estado, indique aqui').tooltip({placement: "left"}).tooltip('show');
        
    jQuery('.publish_post_sidebox_city_state #estado').change(function(){
        if (jQuery(this).val() != '') {
            jQuery(this).tooltip('hide');
            jQuery('.publish_post_sidebox_city_state #municipio').attr('title', 'Se este post está relacionado a uma Cidade, indique aqui').tooltip({placement: "left"}).tooltip('show');
        }
    });

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
    });
    
    // ver https://github.com/nicolasbize/magicsuggest/issues/21
    jQuery(ms).on('load', function(){
        if(this._dataSet === undefined){
            // Roda apenas da primeira vez e depois remove o parametro term_ids dos parametros da URL
            this._dataSet = true;
            ms.setValue(post_vars.selectedTags);
            ms.setDataUrlParams({action: 'get_tags'});
        }
    });

});