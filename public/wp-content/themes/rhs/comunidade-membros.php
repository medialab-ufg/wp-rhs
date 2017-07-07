<?php 
/**
* Template name: Comunidade Membros
*/
?>
<?php get_header(); ?>
<?php
    $array = array(
        'João' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Maria' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Beatriz' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Camila' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Laura' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Ana' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Carolina' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Yasmin' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Guilherme' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Vinícius' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Henrique' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Leonardo' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Vitor' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Gustavo' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Enzo' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Rodrigo' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Eduardo' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Diego' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Rafael' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Maria' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Amanda' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Júlia' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Larissa' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Letícia' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
        'Luana' => 'http://2.gravatar.com/avatar/b7a0216719034222834c37c60e03daf7?s=96&d=mm&r=g',
    );

?>
    <div class="row comunidade">
        <div class="col-xs-12">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="verDados">
                    <div class="jumbotron perfil">
                        <h3 class="perfil-title"> Membros: Raio de Sol (452)</h3>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="panel panel-default">
                                    <div class="panel-body painel-members">
                                        <h4>Administrador</h4>
                                        <ul class="list-members">
                                            <?php foreach ($array as $name => $img){ ?>
                                            <li>
                                                <div class="member"
                                                     data-toggle="popover"
                                                     title="<b><?php echo $name ?></b>"
                                                     data-placement="top"
                                                     data-content="<b>Nome completo:</b> João Frascisco<br />
                                                     <b>Cidade / Estado:</b> Goiânia(GO)<br />
                                                     <b>Idade:</b> 22 anos<br />
                                                     <b>Data de cadastro:</b> 22/07/2017 ás 18:40<br />
                                                     <b>Quantidade de Posts:</b> 42<br />
                                                     <b>Quantidade de Votos:</b> 152<br />"
                                                     >
                                                    <div class="img-member">
                                                        <img src="<?php echo $img ?>" />
                                                    </div>
                                                    <div class="name-member"><?php echo $name ?></div>
                                                </div>
                                            </li>
                                            <?php break; } ?>
                                        </ul>
                                        <h4>Moderadores</h4>
                                        <ul class="list-members">
                                            <?php foreach ($array as $name => $img){ ?>
                                                <li>
                                                    <div class="member"
                                                         data-toggle="popover"
                                                         title="<b><?php echo $name ?></b>"
                                                         data-placement="top"
                                                         data-content="<b>Nome completo:</b> João Frascisco<br />
                                                     <b>Cidade / Estado:</b> Goiânia(GO)<br />
                                                     <b>Idade:</b> 22 anos<br />
                                                     <b>Data de cadastro:</b> 22/07/2017 ás 18:40<br />
                                                     <b>Quantidade de Posts:</b> 42<br />
                                                     <b>Quantidade de Votos:</b> 152<br />"
                                                    >
                                                        <div class="img-member">
                                                            <img src="<?php echo $img ?>" />
                                                        </div>
                                                        <div class="name-member"><?php echo $name ?></div>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                        <h4>Membros</h4>
                                        <ul class="list-members">
                                            <?php foreach ($array as $name => $img){ ?>
                                                <li>
                                                    <div class="member"
                                                         data-toggle="popover"
                                                         title="<b><?php echo $name ?></b>"
                                                         data-placement="top"
                                                         data-content="<b>Nome completo:</b> João Frascisco<br />
                                                     <b>Cidade / Estado:</b> Goiânia(GO)<br />
                                                     <b>Idade:</b> 22 anos<br />
                                                     <b>Data de cadastro:</b> 22/07/2017 ás 18:40<br />
                                                     <b>Quantidade de Posts:</b> 42<br />
                                                     <b>Quantidade de Votos:</b> 152<br />
                                                     <a class='btn btn-success' href=''>Promover</a>
                                                     <a class='btn btn-danger' href=''>Banir</a>"
                                                    >
                                                        <div class="img-member">
                                                            <img src="<?php echo $img ?>" />
                                                        </div>
                                                        <div class="name-member"><?php echo $name ?></div>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer();