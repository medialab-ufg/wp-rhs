<?php 
/**
* Template name: Editar-Comunidade
*/
?>
<?php get_header('full'); ?>
<div class="row">
    <div class="col-xs-12 comunidade">
        <h1 class="titulo-page">Editar Comunidade</h1>
        <div class="panel panel-default">
            <form class="form-horizontal">
                <fieldset class="panel-body" style="padding: 20px">
                        <div class="form-group float-label-control">
                            <label for="nome">Nome <span class="required">*</span></label>
                            <input id="nome" type="text" tabindex="1" name="nome" class="form-control" value="" >
                        </div>
                        <div class="form-group float-label-control">
                            <label for="descri">Descrição <span class="required">*</span></label>
                            <textarea id="descri" tabindex="2" class="form-control" rows="5" name="descri"></textarea>
                        </div><div class="form-group float-label-control">
                            <label for="image">Imagem <span class="required">*</span></label>
                            <input id="image" type="file" tabindex="3" name="image" class="form-control" value="" >
                        </div>
                        
                        <div class="pull-right">
                            <button class="btn btn-default btn-editar" tabindex="4" type="submit" >Enviar</button>
                        </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
<?php get_footer('full');