jQuery(document).ready(function(){

  jQuery('[id^=apagar-meu-post-]').on('click', function(event){
    event.preventDefault();

    var buttonDelete = jQuery(this);
    
    var text = buttonDelete.text();
    var id = +buttonDelete.attr('id').match(/\d+/);

    var postStatusLabel = jQuery('#post-status-label-'+id);

    if(text.includes('(Tirar da Lixeira)')){
      text = '(Apagar)';
    }
    else if(text.includes('(Apagar)')){
      text = '(Tirar da Lixeira)';
    }

    apagarPostToggle(id, postStatusLabel, buttonDelete, text);
  });

  function apagarPostToggle(id, postStatusLabel, buttonDelete, text){
    jQuery.ajax({
      url: '',
      method: 'POST',
      dataType: 'json',
      data: {
        'is_delete_post': true,
        'id': id
      },
    }).done(function(response){
      setTimeout(function(){
        var post_status = response.post_status;
       
        buttonDelete.text(text);
        postStatusLabel.text(post_status);  
        buttonEdit = jQuery('#editar-meu-post-'+id);

        if(post_status == 'Lixeira'){
          buttonEdit.hide('fast', 'linear');
        }
        else{
          buttonEdit.show('fast', 'linear');
        }
      }, 100);
    });
  }

});