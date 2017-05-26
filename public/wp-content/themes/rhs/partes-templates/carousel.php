<?php
global $Carrossel;
$Carrossel       = new Carrossel();
$carrossel_posts = $Carrossel::get_posts();
?>
<?php if ( $carrossel_posts->have_posts() ) : ?>
    <div id="carousel-example-generic" class="carousel slide carousel-fade" data-ride="carousel">
        <?php if ( $carrossel_posts->found_posts > 1 ) { ?>
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <?php for ( $i = 0; $i < $carrossel_posts->found_posts; $i ++ ) { ?>
                    <li data-target="#carousel-example-generic"
                        data-slide-to="<?php echo $i; ?>" <?php echo $i == 0 ? 'class="active"' : ''; ?> ></li>
                <?php } ?>
            </ol>
            <!-- Wrapper for slides -->
        <?php } ?>
        <div class="carousel-inner" role="listbox">
            <?php
            $first = true;
            while ( $carrossel_posts->have_posts() ):
                $carrossel_posts->the_post(); ?>
                <div class="item <?php if ( $first ) {
                    echo 'active';
                } ?>">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'carrossel' ); ?>"
                                         alt="" class="img-responsive">
                                </a>
                            <?php endif; ?>

                        </div>
                        <div class="col-xs-12 col-md-6">
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
            <?php endwhile; ?>
        </div>
        <?php if ( $carrossel_posts->found_posts > 1 ) { ?>
            <!-- Controls -->
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        <?php } ?>
    </div>
<?php endif; ?>
<?php wp_reset_query(); ?>
