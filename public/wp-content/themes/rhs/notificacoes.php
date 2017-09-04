<?php 
$current_user = wp_get_current_user();
$paged = !empty(get_query_var('rhs_paged')) ? get_query_var('rhs_paged') : 1;
?>
<?php get_header(); ?>
            <div class="row">
                <div class="col-xs-12 notificacoes">
                    <?php global $RHSNotifications;?>
                    <div class="wrapper-content">
                        <!-- Inicio do Loop -->
                        <?php foreach ($RHSNotifications->get_notifications(get_current_user_id()) as $notification){ ?>
                            <div class="panel panel-default panel-horizontal">
                                <div class="panel-heading">
                                    <?php echo $notification->getImage(); ?>
                                </div>
                                <div class="panel-body">
                                   <span><?php echo $notification->getText(); ?></span>
                                   <small><?php echo $notification->getTextDate(); ?></small>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- Fim do Loop -->
                        <?php $RHSNotifications->show_notification_pagination($current_user->ID, $paged); ?>
                    </div>
                </div>
            </div>
<?php get_footer();