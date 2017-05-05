<div id="carousel-example-generic" class="carousel slide carousel-fade" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
    <li data-target="#carousel-example-generic" data-slide-to="3"></li>
    <li data-target="#carousel-example-generic" data-slide-to="4"></li>
  </ol>
  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox" style="min-height: 320px; background-color: #fff">
  	<?php if(have_posts()) :
  				$first = true;
				while (have_posts()): 
					the_post(); ?>	
	    <div class="item <?php if($first) echo 'active'; ?>">
			<div class="row">
				<div class="col-xs-6">
					<?php if( has_post_thumbnail() ) :  ?>
						<a href="<?php the_permalink(); ?>">
							<img src="<?php get_the_post_thumbnail_url() ?>" alt="">
						</a>
					<?php endif; ?>

				</div>
				<div class="col-xs-6">
					<div class="carousel-caption">
						<a href="<?php the_permalink(); ?>"><?php the_title( '<h3>', '</h3>' ); ?></a>
						<p>
							<?php the_excerpt(); ?>
						</p>
					</div>
				</div>
			</div>
	    </div>
	    <?php $first = false; ?>
	    <?php endwhile; endif; ?>
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
<?php wp_reset_query(); ?>