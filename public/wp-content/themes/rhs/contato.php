<?php 
/**
* Template name: Contato
*/
?>
<?php get_header(); ?>
<?php global $RHSTicket; ?>
<?php global $RHSUser; ?>
    <div class="row">
        <!-- Container -->
        <div class="col-xs-12 col-md-9">
            <div class="row">
                <!-- Button Publicar e Ver Fila de Votação -->
                <?php get_template_part('partes-templates/buttons-top' ); ?>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="titulo-page"><?php _e('Contato') ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 contato">
                    <div class="wrapper-content">
                        <div class="row">
                            <div class="col-md-6">
                                <p>Utilize o formulário abaixo para se comunicar com o Coletivo de Editores/Cuidadores da Rede HumanizaSUS.</p>
                                <p>A Rede HumanizaSUS (RHS) é uma rede social das pessoas interessadas na humanização da gestão e do cuidado no SUS. Constitui-se como um espaço de discussão das políticas de saúde do SUS, onde são compartilhadas narrativas, ideias, críticas, sugestões e práticas que promovam o fortalecimento da humanização da saúde pública.</p>
                                <p>Tire dúvidas, peça esclarecimentos, peça ajuda para postar ou faça perguntas sobre uso da rede. Não funcionamos oficialmente como um espaço de denúncias relacionadas à área da saúde. Para esse fim, sugerimos que você busque o Conselho de Saúde de sua cidade e/ou a Ouvidoria do SUS, <a href="#">clicando aqui</a>.&nbsp;</p>
                            </div>
                            <div class="col-md-6">
                                <p>Todas as mensagens enviadas pelo formulário abaixo, são respondidas por nosso grupo de editores/cuidadores, e, caso haja necessidade, sua questão pode ser também encaminhada ao coletivo ampliado da Política Nacional de Humanização (PNH). Nenhuma mensagem enviada por este formulário será tornada pública em nosso site sem a explícita autorização de quem a enviou.</p>
                                <p>Caso queira entrar em contato com um membro específico da RHS, utilize o chat disponível na página do perfil de cada participante.</p>
                                <p>Acolheremos com atenção sua mensagem e a responderemos tão logo quanto possíve<span style="line-height: 1.1em;">!</span></p>
                            </div>
                        </div>
                        <?php foreach ($RHSTicket->messages() as $type => $messages){ ?>
                            <div class="alert alert-<?php echo $type == 'error' ? 'danger' : 'success' ; ?>">
                                <?php foreach ($messages as $message){ ?>
                                    <p><?php echo $message ?></p>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <fieldset class="panel panel-default">

                            <div class="panel-heading">
                                <div class="panel-title">
                                    Envie sua Mensagem
                                </div>
                            </div>
                            <div class="panel-body">
                                <?php $RHSTicket->clear_messages(); ?>
                                <form id="contato" class="form-horizontal" role="form" action="<?php the_permalink(); ?>" method="post">
                                    <?php if(is_user_logged_in()) : $dis = 'none';?>
                                        <h4>Logado como <a href="<?php echo get_author_posts_url(get_current_user_id(), $RHSUser->get_user_data('name')); ?>"><?php echo $RHSUser->get_user_data('display_name');?></a></h4>
                                    <?php endif; ?>
                                    <div class="form-group float-label-control" style="display: <?php echo $dis; ?>">
                                        <label for="name">Nome <span class="required">*</span></label>
                                        <input type="text" tabindex="1" name="name" id="input-name" class="form-control" value="<?php echo $RHSUser->get_user_data('display_name');?>" >
                                        <input class="form-control" type="hidden" value="<?php echo $RHSTicket->getKey(); ?>" name="ticket_user_wp" />
                                    </div>
                                    <div class="form-group float-label-control" style="display: <?php echo $dis; ?>">
                                        <label for="email">Email <span class="required">*</span></label>
                                        <input type="email" tabindex="2" name="email" id="input-email" class="form-control" value="<?php echo $RHSUser->get_user_data('email');?>" >
                                    </div>
                                    <div class="form-group float-label-control" style="display: <?php echo $dis; ?>">
                                        <div class="row">
                                            <div class="col-sm-7">
                                            <?php $location = get_user_ufmun($RHSPerfil->getUserId()); ?>
                                            <?php UFMunicipio::form( array(
                                                'content_before' => '<div class="row">',
                                                'content_after' => '</div>',
                                                'content_before_field' => '<div class="col-md-6"><div class="form-group float-label-control">',
                                                'content_after_field' => '<div class="clearfix"></div></div></div>',
                                                'state_label'  => 'Estado &nbsp <span class="required">*</span>',
                                                'city_label'   => 'Cidade &nbsp <span class="required">*</span>',
                                                'select_class' => 'form-control',
                                                'label_class'  => 'control-label col-sm-5',
                                                'selected_state' => $location['uf']['id'],
                                                'selected_municipio' => $location['mun']['id'],
                                                'tabindex_state' => 3,
                                                'tabindex_city' => 4
                                            ) ); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group float-label-control">
                                        <label for="category">Categoria <span class="required">*</span></label>
                                        <?php $categories = $RHSTicket->category_tree_option(); ?>
                                        <select tabindex="5"  class="form-control" name="category" id="select-category">
                                            <option value="">-- Selecione --</option>
                                            <?php foreach ($categories as $key => $category){ ?>
                                                <option value="<?php echo $key ?>"><?php echo $category ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group float-label-control">
                                        <label for="subject">Assunto <span class="required">*</span></label>
                                        <input type="text" tabindex="6" name="subject" id="input-subject" class="form-control" value="" >
                                    </div>
                                    <div class="form-group float-label-control">
                                        <label for="message">Mensagem <span class="required">*</span></label>
                                        <textarea id="textarea-message" tabindex="7" class="form-control" rows="5" name="message"></textarea>
                                    </div>
                                    <div class="panel-button form-actions pull-right">
                                        <button class="btn btn-default btn-contato" tabindex="8" type="submit" >Enviar</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>  
                        </fieldset>
                    </div>
                    <?php $ticketArgs = array( 'post_type' => 'tickets', 'posts_per_page' => 5);
                          $ticketLoop = new WP_Query( $ticketArgs ); ?>
                        <div class="wrapper-content">
                            <fieldset class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title">
                                        Tickets Criados
                                    </div>
                                </div>
                                <div class="panel-body table-responsive">
                                    <?php if($ticketLoop->have_posts()) : ?>
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>*</th>
                                                    <th>Assunto</th>
                                                    <th>Categoria</th>
                                                    <th>Data</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ( $ticketLoop->have_posts() ) : $ticketLoop->the_post(); ?>
                                                    <tr>
                                                        <th><a href="<?php the_permalink(); ?>" title="Responder"><span class="fa fa-reply"></span></th>
                                                        <th><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></th>
                                                        <th><a href="<?php the_permalink(); ?>"><strong>Categoria</strong></a></th>
                                                        <th><a href="<?php the_permalink(); ?>"><?php the_time('F jS, Y'); ?></a></th>
                                                        <?php if(get_post_status() == 'open')
                                                                $status = 'Em Aberto'; 
                                                            elseif(get_post_status() == 'close')
                                                                $status = 'Fechado'; 
                                                            elseif(get_post_status() == 'not_response')
                                                                $status = 'Não Repondido'; ?>
                                                        <th><a href="<?php the_permalink(); ?>"><?php echo $status ?></a></th>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    <?php else : ?>
                                        <span>Você não tem tickets criados.</span>
                                    <?php endif; ?>
                                </div>
                            </fieldset>
                        </div>
                </div>
            </div>
        </div>
        <!-- Sidebar -->
        <div class="col-xs-12 col-md-3"><?php get_sidebar(); ?></div>
    </div>

<?php get_footer();