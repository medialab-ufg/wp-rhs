<div class="panel panel-default hidden-print">
    <div class="panel-body panel-comentarios">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="titulo-quantidade text-uppercase"><i class="fa fa-share" aria-hidden="true"></i> Indique este post </h2>
                <form>
                    <div class="form-group">                
                        <input id="input-recommend-post" name="recommend-post" placeholder="Informe o nome de usuário para o qual você deseja recomendar este post" class="form-control" value="" data-post-id="<?php echo get_the_ID(); ?>"/>
                    </div>
                </form>
            </div>
        </div>

        <?php
        $_post_id = get_the_ID();
        $who_recommended = get_post_meta($_post_id, 'rhs_who_recommended');
        if (is_array($who_recommended) && !empty($who_recommended)): ?>
            <div class="col-xs-12 col-md-12 no-padding">
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title"> <a data-toggle="collapse" href="#collapse1">Histórico de recomendações deste post</a> </h4>
                        </div>
                        <div id="collapse1" class="panel-collapse collapse">
                            <ul class="list-group">
                                <?php
                                $who_recommended = array_reverse($who_recommended);
                                foreach ($who_recommended as $line)
                                {
                                    $from = get_user_by("id", $line['from']);
                                    $from_user_link = get_author_posts_url($line['from']);

                                    $to = get_user_by("id", $line['to']);
                                    $to_user_link = get_author_posts_url($line['to']);

                                    $from = $from->data->display_name;
                                    $to = $to->data->display_name;

                                    ?>
                                    <li class="list-group-item">
                                        <u><a class="keep_grey" href="<?php echo $from_user_link; ?>"> <?php echo $from; ?></a></u> recomendou para <u><a class="keep_grey" href="<?php echo $to_user_link;?>"><?php echo $to?></a></u>
                                        <span class="pull-right small"><strong><?php echo $line['date']; ?></strong></span>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php
            endif;
        ?>
    </div>
</div>