<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
    <head profile="http://gmpg.org/xfn/11">
        <script type="text/javascript">
        function dialog_validate(){
            return true;
        }
        </script>
        <link rel="stylesheet" href="<?php get_template_directory_uri() . '/inc/comments/comments.css'; ?>" />
    </head>
    <body>
        <div id="dialog_content">

            <h1>Editar Comentário</h1>

            <form action="<?php echo get_permalink().'#comment-'.$editable_comment->comment_ID; ?>" method="post" id="dialog_commentform" onsubmit="return dialog_validate()">

                <input type="hidden" name="editable_comments_form" id="editable_comments_form" value="1" />

                <input type="hidden" name="comment_ID" id="dialog_comment_ID" value="<?php echo $editable_comment->comment_ID; ?>" />

                <p id="dialog_loader"><img src="<?php echo get_template_directory_uri() . '/inc/comments/images/loadingAnimation.gif'; ?>" alt="carregando..." /></p>

                <p> <textarea name="comment" id="dialog_comment" cols="100%"
                              rows="10" tabindex="1" class="form-control"><?php echo $editable_comment->comment_content; ?></textarea></p>

                <p id="editable_comment_buttons">
                <input type="button" tabindex="3" value="Cancelar" onclick="jQuery('#dialog').dialog('close');" class="btn btn-default pull-left"/>

                    <input name="submit" type="submit" id="submit" tabindex="2" value="Atualizar" class="btn btn-primary"/>
                </p>
            </form>
        </div>
    </body>
</html>
