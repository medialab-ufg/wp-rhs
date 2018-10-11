<?php
class RHSComments {

    function __construct() {
		global $wp;	
		$wp->add_query_var('rhs-comments');
		
        add_action('wp_enqueue_scripts', array(&$this, 'addJS'));
		add_action('wp_ajax_rhs_comment', array(&$this, 'ajax_callback'));
		add_action('wp_ajax_rhs_delete_comment', array(&$this, 'delete_comment'));
		add_filter('comment_text',array(&$this,'comment_notification'));
    }

    function addJS() {
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('rhs_comments', get_template_directory_uri() . '/inc/comments/comments.js', array('jquery'));
		wp_enqueue_style( 'dialog', get_template_directory_uri() .'/inc/comments/comments.css');
		wp_localize_script('rhs_comments', 'comment', array('ajaxurl' => admin_url('admin-ajax.php')));
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
			
			$this->show_comment_form_to_edit();
		}
	}

	function show_comment_form_to_edit(){
		global $wp,$post;
		
		if(isset($wp->query_vars['rhs-comments'])){
			$editable_comment = get_comment($wp->query_vars['rhs-comments']);
			if($editable_comment){
				include('comment-form.php');
				exit;
			}
		}		
	}
	
	function ajax_callback() {
        if (is_user_logged_in()) {
			$comment_id = $_POST['comment_ID'];
			$comment_content = $_POST['comment_content'];
			return $this->save_edited_comment($comment_id, $comment_content);
        }
        exit;
	}

	function delete_comment(){
        $result = wp_delete_comment($_POST['comment_ID']);
        echo $result;
        wp_die();
    }
	
	function save_edited_comment($comment_id, $comment_content) {
		$comment_array = array('comment_ID' => $comment_id, 'comment_content' => $comment_content);
		wp_update_comment($comment_array);
	}

	function comment_notification($comment_text){	
		global $comment;
		if(isset($_POST['editable_comments_form'])){
				if(in_array($comment->comment_ID, array($_POST['comment_ID'])) )
					$comment_text .= '<div class="alert alert-success auto-hide-3-sec">Comentário atualizado</p>';
		}
		return $comment_text;
	}
}


add_action('init', function() {
    global $RHSComments;
    $RHSComments = new RHSComments();
});

