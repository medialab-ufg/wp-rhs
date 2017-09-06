<?php $members = $comunity->get_members_saparete_by_capability(); ?>
<div class="row contato">
    <div class="wrapper-content">
        <?php if($comunity->is_moderate()){ ?>
        <div class="row">
            <div class="col-xs-12">
                <form class="form form-inline">
                    <div class="form-group">
                        <label for="input-add-member">Adicionar membro</label>
                        <input id="input-add-member" name="add-member" placeholder="Nome" class="form-control typeahead" value="" />
                    </div>
                </form>
            </div>
        </div>
        <?php } ?>
        <div class="row">
        <?php if($members){ ?>
                <?php foreach ( $members as $member ) { ?>
                <?php $comun = $member->get_comunity( $comunity->get_id() ) ?>
                    <div class="col-md-4 col-xs-12 well-disp" data-userid="<?php echo $member->get_id(); ?>"
                         data-id="<?php echo $comunity->get_id(); ?>">
                        <div class="well profile_view">
                            <a href="<?php echo $member->get_link(); ?>" class="membros">
                                <div class="left">
                                    <span class="comunity-avatar">
                                        <?php echo $member->get_avatar(); ?>
                                    </span>
                                </div>
                                <div class="right">
                                    <h2>
                                        <span class="comunity-name"><?php echo $member->get_name() ?></span>
                                        <span class="comunity-moderate"><i <?php echo !$comun->is_moderate() ? 'style="display:none;"' : '' ; ?> data-toggle="tooltip" data-placement="top" title="<?php echo __('Moderador') ?>" class="fa fa-address-card-o"></i> </span>
                                        <?php if($comun->is_request()){ ?>
                                            <span class="comunity-request"><i data-toggle="tooltip" data-placement="top" title="<?php echo __('Solicitação para entrar') ?>" class="fa fa-info-circle"></i> </span>
                                        <?php } ?>
                                    </h2>
                                    <div class="info">
                                        <p><strong>Localidade: </strong> <span class="comunity-location"><?php echo $member->get_city(); ?>
                                            <?php if ( $member->get_state_uf() ) { ?>
                                                / <?php echo $member->get_state_uf(); ?>
                                            <?php } ?> </span></p>
                                        <p><strong>Membro da RHS desde: </strong> <span class="comunity-date"><?php echo $member->get_date_registered( 'Y' ) ?></span> </p>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </a>
                            <?php if($comunity->is_moderate() || $comunity->is_admin()){ ?>
                            <div class="bottom-mask col-xs-12"></div>
                            <div class="bottom comunity-buttons col-xs-12">
                                    <?php echo $comun->get_button_moderate('Adicionar como moderador'); ?>
                                    <?php echo $comun->get_button_not_moderate('Remover como moderador'); ?>
                                    <?php echo $comun->get_button_leave('Remover da comunidade'); ?>
                                    <?php echo $comun->get_button_accept_request(); ?>
                                    <?php echo $comun->get_button_reject_request(); ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
        <?php } else { ?>
                <h3 class="text-center"><?php echo __('Nenhum membro nesta comunidade'); ?></h3>
        <?php } ?>
        </div>
    </div>
</div>
