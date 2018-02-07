<?php 
/**
* Template name: Contato
*/
global $RHSCaptcha;
global $RHSTicket;
global $RHSUsers;
?>
<?php get_header(); ?>

            <div class="row">
                <div class="col-xs-12">
                    <h1 class="titulo-page">Contato</h1>
                    <div class="pull-right edit-page">
                        <?php edit_post_link( __( 'Editar Página', 'rhs' ), '<span class="text-uppercase">', '</span>', null, 'btn btn-default' ); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 contato">
                    <div class="wrapper-content">
                        <div class="row">
                            <?php
                                if(have_posts()){
                                    while(  have_posts()) : the_post();
                                    ?>
                                    <?php
                                        the_content();
                                    endwhile;
                                    wp_reset_query();
                                }
                            ?>
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
                                <form autocomplete="off" id="contato" class="form-horizontal" role="form" action="" method="post">
                                    <?php if(is_user_logged_in()) : $dis = 'none';?>
                                        <h4>Logado como <a href="<?php echo get_author_posts_url(get_current_user_id(), $RHSUsers->get_user_data('name')); ?>"><?php echo $RHSUsers->get_user_data('display_name');?></a></h4>
                                    <?php
                                        else :
                                            $dis = 'block';
                                    endif; ?>
                                    <div class="form-group float-label-control" style="display: <?php echo $dis; ?>">
                                        <label for="name">Nome <span class="required">*</span></label>
                                        <input type="text" tabindex="1" name="name" id="input-name" class="form-control" value="<?php echo $RHSUsers->get_user_data('display_name');?>" >
                                        <input class="form-control" type="hidden" value="<?php echo $RHSTicket->getKey(); ?>" name="ticket_user_wp" />
                                    </div>

                                    <?php if( rand(0,2) == 0 ): ?>
                                        <div id="contact_phone" class="form-group">
                                            <label for="surname">Sobrenome <span class="required">*</span></label>
                                            <input type="text" name="surname" class="form-control" />
                                        </div>
                                    <?php endif; ?>

                                    <div class="form-group float-label-control" style="display: <?php echo $dis; ?>">
                                        <label for="email">Email <span class="required">*</span></label>
                                        <input type="email" tabindex="2" name="email" id="input-email" class="form-control" value="<?php echo $RHSUsers->get_user_data('email');?>" >
                                    </div>

                                    <?php if( rand(10,12) == 11 ): ?>
                                        <div id="contact_phone" class="form-group">
                                            <label for="phone">Telefone <span class="required">*</span></label>
                                            <input type="text" name="phone" class="form-control" />
                                        </div>
                                    <?php endif; ?>

                                    <div class="form-group float-label-control" style="display: <?php echo $dis; ?>">
                                        <div class="row">
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
                                    <div class="form-group float-label-control">
                                        <label for="category">Categoria <span class="required">*</span></label>
                                        <?php $categories = $RHSTicket->category_parent(); ?>
                                        <select tabindex="5"  class="form-control" name="category" id="select-category">
                                            <option value="">-- Selecione --</option>
                                            <?php foreach ($categories as $category){ ?>
                                                <option value="<?php echo $category->term_id ?>"><?php echo $category->name ?></option>
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

                                    <?php if( !is_user_logged_in() ): ?>
                                        <div class="panel-captcha pull-left">
                                            <?php echo $RHSCaptcha->display_contact_captcha(); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="panel-button form-actions pull-right">
                                        <button class="btn btn-default btn-contato" tabindex="8" type="submit" id="send_contact">Enviar</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>  
                        </fieldset>
                    </div>  
                    <?php if(is_user_logged_in()) :
                        $ticketArgs = array('author' => get_current_user_id(),'post_type' => 'tickets', 'posts_per_page' => 5);
                        $ticketLoop = new WP_Query( $ticketArgs ); ?>
                        <div class="wrapper-content">
                            <fieldset class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title"> Contatos Realizados </div>
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
                                            <?php
                                                while ($ticketLoop->have_posts()): $ticketLoop->the_post();
                                                    echo $RHSTicket->renderTicketInfo(get_the_ID());
                                                endwhile;
                                            ?>
                                            </tbody>
                                        </table>
                                    <?php else : ?>
                                        <span>Você ainda não realizou nenhum contato, não seja tímido.</span>
                                    <?php endif; ?>
                                </div>
                            </fieldset>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
<?php get_footer();
