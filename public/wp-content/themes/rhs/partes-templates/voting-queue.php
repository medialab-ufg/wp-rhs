<div class="row display-row">
	<?php

	$args = array(
		'orderby'          => 'date',
		'order'            => 'DESC',
		'post_status'      => RHSVote::VOTING_QUEUE,
	);

	$posts = get_posts( $args );

	if($posts) :

	foreach ( $posts as $post ) : setup_postdata( $post );
		get_template_part( 'partes-templates/posts');
	endforeach;
	?>
</div><!--display-row-->
<div class="row">
	<div class="col-xs-12">
		<div class="text-center">
			<?php paginacao_personalizada(); ?>
		</div>
	</div>
	<?php
	else :
		get_template_part('partes-templates/content', 'none');
	endif;
	?>
</div>