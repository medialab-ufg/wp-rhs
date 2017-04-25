<?php
if ( post_password_required() ) {
	return;
}
?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'rhs' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>


	<?php if ( have_comments() ) : ?>

		<ul class="list-unstyled">
		    <?php
		        // Register Custom Comment Walker
		        require_once('inc/class-wp-bootstrap-comment-walker.php');

		        wp_list_comments( array(
		            'style'         => 'ul',
		            'short_ping'    => true,
		            'avatar_size'   => '64',
		            'walker'        => new Bootstrap_Comment_Walker(),
		        ) );
		    ?>
		</ul><!-- .comment-list -->

	<?php endif; // have_comments() ?>