<div class="panel panel-default hidden-print">
    <div class="panel-body panel-comentarios">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="titulo-quantidade text-uppercase"><i class="fa fa-share" aria-hidden="true"></i> Indicar Post</h2>
                <form>
                    <div class="form-group">                
                        <input id="input-recommend-post" name="recommend-post" placeholder="Informe o nome do usuário" class="form-control" value="" data-post-id="<?php echo get_the_ID(); ?>"/>
                    </div>
                </form>
            </div>
        </div>

        <?php
        $_post_author_id = get_the_author_meta( 'ID' );
        $current_user = wp_get_current_user();

        if($_post_author_id == $current_user->data->ID)
        {
        ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#collapse1">Histórico de recomendações</a>
                            </h4>
                        </div>
                        <div id="collapse1" class="panel-collapse collapse">
                            <ul class="list-group">
                                <?php
                                $_post_id = get_the_ID();
                                $who_recommended = get_post_meta($_post_id, 'rhs_who_recommended');
                                $who_recommended = array_reverse($who_recommended);

                                foreach ($who_recommended as $line)
                                {
                                    $from = get_user_by("id", $line['from']);
                                    $to = get_user_by("id", $line['to']);

                                    $from = $from->data->display_name;
                                    $to = $to->data->display_name;

                                    ?>
                                    <li class="list-group-item">
                                        <u><?php echo $from; ?></u> recomendou esta publicação para <u><?php echo $to?></u>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>