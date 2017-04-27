<?php
/**
 * Tema para exibir Comments.
 *
 * A área da página que contém os comentários atuais
 * E o formulário de comentário. A exibição real dos comentários é
 * Manipulado por um callback em RHS_comment () que é
 * Localizado no arquivo functions.php.
 *
 * @package WordPress
 * @subpackage RHS
 */

if (post_password_required()) {
    return;
} ?>
<div class="row">
	<!-- Container -->
	<div class="col-xs-12 col-md-12">
		<!--show the form-->
		<?php if('open' == $post->comment_status) : ?>
			<div id="respond" class="clearfix">        
			    <?php if(get_option('comment_registration') && !$user_ID) : ?>
					<h5><?php _e('Deixe seu Comentário', 'rhs'); ?></h5>
					<p>
					<?php printf( __( 'Você precisa está %sloggedin%s para postar um comentário.', 'rhs'), "<a href='" . get_option('siteurl') . "/wp-login.php?redirect_to=" . urlencode(get_permalink()) ."'>", "</a>" ); ?>
					</p>        
			    <?php else : ?>
			    <h4 class="clearfix"><?php _e('Deixe um Comentário', 'rhs'); ?></h4>
			    <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="form-comentario" class="clearfix">
			        <?php comment_id_fields(); ?>             
			        <div class="form-group">         
						<textarea name="comment" id="comment" tabindex="1" onfocus="if (this.value == '<?php _e('Digite seu comentário aqui.', 'rhs'); ?>') this.value = '';" onblur="if (this.value == '') {this.value = '<?php _e('Digite seu comentário aqui.', 'rhs'); ?>';}" class="form-control" rows="4"><?php _e('Digite seu comentário aqui.', 'rhs'); ?></textarea>
					</div>
					<?php if($user_ID) : ?>
						<p><?php _e('Logado em', 'rhs'); ?> <a href="<?php print get_option('siteurl'); ?>/wp-admin/profile.php"><?php print $user_identity; ?></a>. <a href="<?php print get_option('siteurl'); ?>/wp-login.php?action=logout" title="Sair">Sair &raquo;</a></p>
					<?php endif; ?>           
					<input id="submit" class="btn btn-default" type="submit" value="<?php _e('Enviar', 'rhs'); ?>" name="submit" />
					 <?php cancel_comment_reply_link('<button class="btn btn-danger">Cancelar</button>'); ?>
			        <?php if(get_option("comment_moderation") == "1") : ?>
			        <?php _e('Todos os comentarios precisam ser aprovados.', 'rhs'); ?>
			        <?php endif; ?>
			        <?php do_action('comment_form', $post->ID); ?>
			    </form>
			    <?php endif; ?>
			</div>
		<?php endif; ?>

	    <?php if (have_comments()) : ?>

            <?php wp_list_comments(array('callback' => 'RHS_Comentarios')); ?>

	        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
	            <nav id="comment-nav-below" class="navigation" role="navigation">
	                <div class="nav-previous">
	                    <?php previous_comments_link( _e('&larr; Anterior', 'rhs')); ?>
	                </div>
	                <div class="nav-next">
	                    <?php next_comments_link(_e('Próximo &rarr;', 'rhs')); ?>
	                </div>
	            </nav>
	        <?php endif; // check for comment navigation ?>

	        <?php elseif (!comments_open() && '0' != get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
	            <p class="nocomments"><?php _e('Comentarios está fechado.', 'rhs'); ?></p>
	    <?php endif; ?>
	</div>
</div>