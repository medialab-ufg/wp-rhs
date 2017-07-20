<?php $members = $comunity->get_members_saparete_by_capability(); ?>
<div class="row contato">
    <div class="wrapper-content">
        <?php if($members){ ?>
        <?php foreach ( $members as $type_member => $members_sub ) { ?>
            <div class="row">
                <div class="col-md-12">
                    <h3><?php echo ( $type_member == 'modarates' ) ? 'Moderadores' : 'Membros'; ?></h3>
                </div>
            </div>
            <div class="row">
                <?php foreach ( $members_sub as $member ) { ?>
                    <div class="col-md-4 col-xs-12 well-disp" data-userid="<?php echo $member->get_id(); ?>"
                         data-id="<?php echo $comunity->get_id(); ?>">
                        <div class="well profile_view">
                            <a href="<?php echo $member->get_link(); ?>" class="membros">
                                <div class="left">
                                    <img src="<?php echo $member->get_avatar(); ?>" alt=""
                                         class="img-circle img-responsive">
                                </div>
                                <div class="right">
                                    <h2><?php echo $member->get_name() ?></h2>
                                    <div class="info">
                                        <p><strong>Membro
                                                desde: </strong> <?php echo $member->get_date_registered( 'Y' ) ?> </p>
                                        <?php if ( $member->get_city() && $member->get_state_uf() ) { ?>
                                            <p><strong>Localidade: </strong> <?php echo $member->get_city(); ?>
                                                <?php if ( $member->get_state_uf() ) { ?>
                                                    / <?php echo $member->get_state_uf(); ?>
                                                <?php } ?> </p>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </a>
                            <?php if ( $comunity->is_admin() ) { ?>
                                <div class="bottom-mask col-xs-12"></div>
                                <div class="bottom col-xs-12">
                                    <?php $comun = $member->get_comunity( $comunity->get_id() ) ?>
                                    <a data-type="leave" <?php echo !$comun->can_leave() ? 'style="display: none;"' : ''; ?> href="javascript:;" class="btn btn-danger btn-xs">
                                        <i class="fa fa-remove"></i> Remover da cominidade
                                    </a>
                                    <a data-type="moderate" <?php echo !$comun->can_modarate() ? 'style="display: none;"' : ''; ?> href="javascript:;" class="btn btn-primary btn-xs">
                                        <i class="fa fa-arrow-up"></i> Tornar Moderador
                                    </a>
                                    <a data-type="not_moderate" <?php echo !$comun->can_not_modarate() ? 'style="display: none;"' : ''; ?> href="javascript:;" class="btn btn-primary btn-xs">
                                        <i class="fa fa-arrow-down"></i> Retirar Moderador
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php }
        } else { ?>
            <div class="row">
                <h3 class="text-center">Nenhum moderador</h3>
            </div>
        <?php } ?>
    </div>
</div>
