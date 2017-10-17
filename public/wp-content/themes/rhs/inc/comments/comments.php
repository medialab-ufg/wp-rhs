<?php
class RHSComments {

    function __construct() {
        add_action('wp_enqueue_scripts', array(&$this, 'addJS'));
		add_action('wp', array(&$this, 'wp'));
		add_filter('comment_text',array(&$this,'comment_notification'));	
    }

    function addJS() {
        wp_enqueue_script('rhs_comments', get_template_directory_uri() . '/inc/comments/comments.js', array('jquery'));
        wp_enqueue_script('jquery-ui-dialog');
        wp_localize_script('rhs_comments', 'comments', array('ajaxurl' => admin_url('admin-ajax.php')));
    }

    function check_permissions($comment, $type){   
        if((int)$comment->user_id === get_current_user_id()) {
            return true;
        } else {
            return false;	
        }
	}
	
	function show_button_edit_comment(){
        global $comment, $post;

		if(self::check_permissions($comment, 'edit')){
            
			$perma_struct = get_option('permalink_structure');
            if(strlen($perma_struct)==0){ 
                $get = '&';
            } else {
                $get = '?';
            }

            echo '<a class="rhs-comment dialog" href="'.get_permalink().$get.'rhs-comments='.$comment->comment_ID.'" rel="nofollow"><i class="fa fa-pencil"></i></a>';
		}
	}
	
	function wp(){
		global $wp,$post;
		// Formulário
		if(isset($wp->query_vars['rhs-comments'])){
			$editable_comment = get_comment($wp->query_vars['rhs-comments']);
			if($editable_comment){
				if($this->check_permissions($editable_comment,'edit')){
                    $options = get_option('rhs-comments');
					include('comment-form.php');
					exit;
				}
                else{
                    echo 'Erro'; 
                    exit;
                }
			}
		}
        
        // Edição
		if(isset($_POST['editable_comments_form'])){
			$editable_comment = get_comment($_POST['comment_ID']);
			if($editable_comment){
				if($this->check_permissions($editable_comment,'edit')){
					if(isset($_POST['editable_comments_form'])){
						$comment_array = array('comment_ID' => $_POST['comment_ID'], 'comment_content' => $_POST['comment']);
						wp_update_comment( $comment_array);
						if($_POST['ajax'] == 'true'){
							echo 1;
							exit;
						}
					}
				}
				else{ 
                    echo 'Erro'; 
                    exit;
                }
			}
		}		
	}
	
	
	function comment_notification($comment_text){	
		global $comment;
		if(isset($_POST['editable_comments_form'])){
				if(in_array($comment->comment_ID, array($_POST['comment_ID'])) )
					$comment_text .= '<div class="alert alert-success">Comentário atualizado</p>';
		}
		return $comment_text;
	}
}


add_action('init', function() {
    global $wp;	
    
    $wp->add_query_var('rhs-comments');
    if(!is_admin() ){
        $rhs_comments = get_option('rhs-comments');
        if($rhs_comments['dialog'] == 1){
            wp_enqueue_script('jquery-ui-dialog');
            wp_enqueue_script('rhs_comments','comments.js', array('jquery'));
        }
        wp_enqueue_style( 'dialog', get_template_directory_uri() .'/inc/comments/comments.css');
    }

    global $RHSComments;
    $RHSComments = new RHSComments();
});

