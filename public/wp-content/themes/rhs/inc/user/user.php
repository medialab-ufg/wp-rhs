<?php

/**
 * Created by PhpStorm.
 * User: MediaLab01
 * Date: 05/05/2017
 * Time: 11:49
 */
Class RHSUser {

	static $instance;

	function __construct() {

		if ( empty( self::$instance ) ) {

			add_action('show_user_profile', array( &$this, 'extra_profile_fields' ) );
			add_action('edit_user_profile', array( &$this, 'extra_profile_fields' ) );
			add_action('admin_enqueue_scripts', array( &$this, 'enqueue_admin') );
			add_action('admin_head',array( &$this,'script_user_options'));
			add_action( 'personal_options_update', array( &$this,'save_extra_profile_fields') );
			add_action( 'edit_user_profile_update', array( &$this,'save_extra_profile_fields') );

			self::$instance = true;
		}
	}

	function extra_profile_fields( $user )
	{
		if(function_exists( 'wp_enqueue_media' )){
			wp_enqueue_media();
		}else{
			wp_enqueue_style('thickbox');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
		}

		?>
		<table class="form-table field-add">
			<tbody>
			<tr class="user-url-wrap">
				<th><label for="formation"><?php _e('Formação') ?></label></th>
				<td><input type="text" name="formation" id="formation" value="" class="regular-text code"></td>
			</tr>
			<tr class="user-url-wrap">
				<th><label for="url">Interesses</label></th>
				<td><textarea name="interest" id="interest" rows="5" cols="30"></textarea></td>
			</tr>
			<tr>
				<th><label for="pass1-text"><?php _e('Foto do Perfil'); ?></label></th>
				<td>
					<a class="header_logo_upload" style="display: none; line-height: 0;" href="#" >
						<img  class="header_logo" src="<?php echo get_option('header_logo'); ?>" height="100" width="100"/>
					</a>
					<div>
						<button type="button" class="header_logo_upload button wp-generate-pw hide-if-no-js"><?php _e('Selecionar imagem') ?></button>
					</div>
				</td>
			</tr>
			</tbody>
		</table>
		<script>
            jQuery(document).ready(function($) {
                $('.header_logo_upload').click(function(e) {
                    e.preventDefault();

                    var custom_uploader = wp.media({
                        title: 'Custom Image',
                        button: {
                            text: 'Upload Image'
                        },
                        multiple: false
                    }).on('select', function() {
                        var attachment = custom_uploader.state().get('selection').first().toJSON();
                        $('.header_logo').attr('src', attachment.url);
                        $('.header_logo_url').val(attachment.url);
                        $('.header_logo').closest('a').show();

                        $('button.header_logo_upload').closest('div').hide();
                    }).open();

                });
            });
		</script>
		<?php
	}

	function enqueue_admin()
	{
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style('thickbox');

		wp_enqueue_script('media-upload');
	}

	function script_user_options(){
		?>
		<script type="text/javascript">
            jQuery(document).ready(function($) {
                $('form#your-profile tr.user-profile-picture').remove();
                $('form#your-profile table.field-add').insertAfter($('form#your-profile tr.user-description-wrap').closest('.form-table'));
            });
		</script>';
		<?php
	}


	function save_extra_profile_fields( $user_id ) {

		if ( !current_user_can( 'edit_user', $user_id ) )
		{
			return false;
		}

		echo '<pre>';
		print_r($_POST);
		exit;

		update_user_meta( $user_id, 'image', $_POST[ 'image' ] );
		update_user_meta( $user_id, 'sidebarimage', $_POST[ 'sidebarimage' ] );
	}

}

global $RHSUser;
$RHSUser = new RHSUser();