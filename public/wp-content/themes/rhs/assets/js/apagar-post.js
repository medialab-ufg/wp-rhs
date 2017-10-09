jQuery(document).ready(function(){

  jQuery('[id^=apagar-meu-post-]').on('click', function(event){
    event.preventDefault();

    var buttonDelete = jQuery(this);
    
    var text = buttonDelete.text();
    var id = +buttonDelete.attr('id').match(/\d+/);

    var postStatusLabel = jQuery('#post-status-label-'+id);
    var buttonEdit = jQuery('#editar-meu-post-'+id);
    
    var acoesMeuPost = jQuery('#acoes-meu-post-'+id);
    acoesMeuPost.children().hide();

    if(text.includes('(Tirar da Lixeira)')){
      text = '(Apagar)';
    }
    else if(text.includes('(Apagar)')){
      text = '(Tirar da Lixeira)';
    }

    apagarPostToggle(id, postStatusLabel, buttonDelete, text, acoesMeuPost);
  });

  function apagarPostToggle(id, postStatusLabel, buttonDelete, text, acoesMeuPost){
    showRefreshAnimation(acoesMeuPost, true);

    jQuery.ajax({
      url: post_vars.ajaxurl,
      method: 'POST',
      dataType: 'json',
      data: {
        'action': 'apagar_post_toggle',
        'id': id
      },
    }).done(function(response){
      setTimeout(function(){
        showRefreshAnimation(acoesMeuPost, false);
        
        var post_status = response.post_status;
       
        buttonDelete.text(text);
        postStatusLabel.text(post_status);  
        buttonEdit = jQuery('#editar-meu-post-'+id);

        if(post_status == 'Lixeira'){
          buttonEdit.hide();
        }
        else{
          buttonEdit.show();
        }

      }, 350);
    });
  }

  function showRefreshAnimation(acoesMeuPost, show){
    if(show){
      acoesMeuPost.find('#apagar-refresh').show();
    }
    else{
      acoesMeuPost.children().show();
      acoesMeuPost.find('#apagar-refresh').hide();
    }
  }
});