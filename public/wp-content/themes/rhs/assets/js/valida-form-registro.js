jQuery( function( $ ) {

    $(function () {
        jQuery.validator.setDefaults({
            debug: true,
            focusCleanup: true
        });

        /*$("#register").validate({
            rules: {
                mail: {
                    required: true,
                    email: true,
                    check_email_exist: true
                },
                pass: {
                    required: true,
                    minlength: 5    
                },
                pass2: {
                    required: true,
                    equalTo: "input[name='pass1']"
                },
                first_name: {
                    required: true,
                },
                last_name: {
                    required: true,
                },
                estado: {
                    required: true
                },
                municipio: {
                    required: true
                }
            },
            messages: {
                mail: {
                    required: "Preencha com o seu email.",
                    email: "Preencha corretamente o seu email.",
                    check_email_exist: "Email já existente, escolha outro."
                },
                pass: {
                    required: "Preencha com a sua senha.",
                    minlength: "Sua senha deve ser acima de 5 caracteres!"
                },
                pass2: {
                    required: "Preencha com a sua senha.",
                    equalTo: "Senhas diferentes!"
                },
                first_name: {
                    required: "Preencha com o seu primeiro nome.",
                },
                last_name: {
                    required: "Preencha com o seu último nome.",
                },
                estado: {
                    required: "Preencha com o seu estado."
                },
                municipio: {
                    required: "Preencha com o seu municipio."
                }
            },
            errorContainer: ".block-email", 
            errorLabelContainer: ".block-email", 
            errorElement: "dt",
            errorPlacement: function ( error, element ) {
                // Add the `help-block` class to the error element
                //error.addClass( "help-block" );
                console.log(error);

                error.appendTo(element.closest('.col-sm-7').next().find('.help-block'));
            },
            success: function ( label, element ) {
                // Add the span element, if doesn't exists, and apply the icon classes to it.
                if ( !$( element ).next( "span" )[ 0 ] ) {
                    $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
                }
                
            },
            highlight: function ( element, errorClass, validClass ) {

                if ( !$( element ).next( "span" )[ 0 ] ) {
                    $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter($(element));
                }

                $( element ).parents( ".col-sm-12" ).addClass( "has-error" ).removeClass( "has-success" );
                $( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
            },
            unhighlight: function ( element, errorClass, validClass ) {

                if ( !$( element ).next( "span" )[ 0 ] ) {
                    $("<span class='glyphicon glyphicon-okay form-control-feedback'></span>").insertAfter($(element));
                }

                $( element ).parents( ".col-sm-12" ).removeClass( "has-error" );
                $( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
            }
        });*/

        $('#register').validate({
            errorElement: 'p',
            errorClass: 'block-error',
            focusInvalid: true,
            ignore: '',
            rules: {
                mail: {
                    required: true,
                    email: true,
                    check_email_exist: true
                },
                pass: {
                    required: true,
                    minlength: 5
                },
                pass2: {
                    required: true,
                    equalTo: "input[name='pass']"
                },
                first_name: {
                    required: true,
                },
                last_name: {
                    required: true,
                },
                estado: {
                    required: true
                },
                municipio: {
                    required: true
                }
            },
            messages: {
                mail: {
                    required: "Preencha com o seu email.",
                    email: "Preencha corretamente o seu email.",
                    check_email_exist: "Email já existente, escolha outro."
                },
                pass: {
                    required: "Preencha com a sua senha.",
                    minlength: "Sua senha deve ser acima de 5 caracteres!"
                },
                pass2: {
                    required: "Preencha com a sua senha.",
                    equalTo: "Senhas diferentes!"
                },
                first_name: {
                    required: "Preencha com o seu primeiro nome.",
                },
                last_name: {
                    required: "Preencha com o seu último nome.",
                },
                estado: {
                    required: "Preencha com o seu estado."
                },
                municipio: {
                    required: "Preencha com o seu municipio."
                }
            },
            invalidHandler: function (event, validator) {},
            errorPlacement: function (error, element) {

                if (element.parents(".col-sm-7").size() > 0) {
                    error.appendTo(element.parents(".form-group").find('.help-block'));
                } else if (element.parent(".input-group").size() > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').size() > 0) {
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').size() > 0) {
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').size() > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').size() > 0) {
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').size() > 0) {
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else if (element.parent().find('.help-block').size() > 0) {

                } else {
                    element.parent().append(error);
                }
            },
            highlight: function (element) {

                if ( !$( element ).next( "span" )[ 0 ] ) {
                    $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter($(element));
                }

                $(element).closest('.form-group').addClass('has-error');
                $( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
            },
            unhighlight: function (element) {

                if ( !$( element ).next( "span" )[ 0 ] ) {
                    $("<span class='glyphicon glyphicon-okay form-control-feedback'></span>").insertAfter($(element));
                }

                $(element).closest('.form-group').removeClass('has-error');
                $( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
            },
            submitHandler: function(form) {
                $(form).find('[type="submit"]').button('loading');
                form.submit();
            }
        });

        $('#login').validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: true,
            ignore: '',
            rules: {
                log: {
                    required: true,
                    email: true
                },
                pwd: {
                    required: true
                }
            },
            messages: {
                log: {
                    required: 'Preencha com seu email.',
                    email: 'Preencha com email no formato correto'
                },
                pwd: {
                    required: 'Preencha a sua senha.',
                    maxlength: 'Tamanho máximo de 20 caracteres'
                },
            },
            invalidHandler: function (event, validator) {},
            errorPlacement: function (error, element) {
                if (element.parent(".input-group").size() > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').size() > 0) {
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').size() > 0) {
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').size() > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').size() > 0) {
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').size() > 0) {
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else if (element.parent().find('.help-block').size() > 0) {

                } else {
                    element.parent().append(error);
                }
            },
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            submitHandler: function(form) {
                $(form).find('[type="submit"]').button('loading');
                form.submit();
            }
        });

        $('#lostpassword').validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: true,
            ignore: '',
            rules: {
                user_login: {
                    required: true,
                    email: true
                }
            },
            messages: {
                user_login: {
                    required: 'Preencha com seu email.',
                    email: 'Preencha com email no formato correto'
                }
            },
            invalidHandler: function (event, validator) {},
            errorPlacement: function (error, element) {
                if (element.parent(".input-group").size() > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').size() > 0) {
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').size() > 0) {
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').size() > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').size() > 0) {
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').size() > 0) {
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else if (element.parent().find('.help-block').size() > 0) {

                } else {
                    element.parent().append(error);
                }
            },
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            submitHandler: function(form) {
                $(form).find('[type="submit"]').button('loading');
                form.submit();
            }
        });

        $('#retrievepassword').validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: true,
            ignore: '',
            rules: {
                pass1: {
                    required: true
                },
                pass2: {
                    required: true,
                    equalTo: "input[name='pass1']"
                }
            },
            messages: {
                pass1: {
                    required: 'Preencha com sua nova senha.'
                },
                pass2: {
                    required: 'Preencha com sua nova senha.',
                    equalTo: 'Senhas diferentes.'
                }
            },
            invalidHandler: function (event, validator) {},
            errorPlacement: function (error, element) {
                if (element.parent(".input-group").size() > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').size() > 0) {
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').size() > 0) {
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').size() > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').size() > 0) {
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').size() > 0) {
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else if (element.parent().find('.help-block').size() > 0) {

                } else {
                    element.parent().append(error);
                }
            },
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            submitHandler: function(form) {
                $(form).find('[type="submit"]').button('loading');
                form.submit();
            }
        });

        $('#perfil').validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: true,
            ignore: '',
            rules: {
                pass_old: {
                    maxlength: 128
                },
                pass: {
                    maxlength: 128
                },
                pass2: {
                    equalTo: "input[name='pass']",
                    maxlength: 128
                },
                first_name: {
                    required: true,
                    maxlength: 254,
                },
                last_name: {
                    required: true,
                    maxlength: 254,
                },
                estado: {
                    required: true
                },
                municipio: {
                    required: true
                }
            },
            messages: {
                pass_old: {
                    required: 'Preencha com sua senha antiga.',
                    maxlength: 'Tamanho maximo de 128 caracteres.'
                },
                pass: {
                    required: 'Preencha a sua nova senha.',
                    maxlength: 'Tamanho maximo de 128 caracteres.'
                },
                pass2: {
                    required: 'Preencha a sua nova senha.',
                    equalTo: "Senhas diferentes",
                    maxlength: 'Tamanho maximo de 128 caracteres.'
                },
                first_name: {
                    required: 'Preencha com seu primeiro nome.',
                    maxlength: 'Tamanho maximo de 254 caracteres.'
                },
                last_name: {
                    required: 'Preencha com seu último nome.',
                    maxlength: 'Tamanho maximo de 254 caracteres.'
                },
                estado: {
                    required: 'Selecione seu estado.'
                },
                municipio: {
                    required: 'Selecione sua cidade.'
                },
            },
            invalidHandler: function (event, validator) {},
            errorPlacement: function (error, element) {
                if (element.parent(".input-group").size() > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').size() > 0) {
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').size() > 0) {
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').size() > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').size() > 0) {
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').size() > 0) {
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else if (element.parent().find('.help-block').size() > 0) {

                } else {
                    element.parent().append(error);
                }
            },
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            submitHandler: function(form) {
                $(form).find('[type="submit"]').button('loading');
                form.submit();
            }
        });

        $('#posting').validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: false,
            ignore: '',
            rules: {
                title: {
                    required: true,
                    minlenght: 35
                },
                category: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: 'Preencha o titulo.',
                    minlenght: 'Tamanho mínimo de 35 caracteres'
                },
                category: {
                    required: 'Selecione a categoria'
                }
            },
            invalidHandler: function (event, validator) {},
            errorPlacement: function (error, element) {
                if (element.parent(".input-group").size() > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').size() > 0) {
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').size() > 0) {
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').size() > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').size() > 0) {
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').size() > 0) {
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else if (element.parent().find('.help-block').size() > 0) {

                } else {
                    element.parent().append(error);
                }
            },
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            submitHandler: function(form) {
                $(form).find('[type="submit"]').button('loading');
                form.submit();
            }
        });

        $.validator.addMethod("check_email_exist", function (value, element, params) {
            var retorno = false;
            var email = $("input[name='mail']").val();
            $.ajax({
                async: false,
                type: "POST",
                dataType: "json",
                url: vars.ajaxurl,
                data: {action: 'check_email_exist','email': email},
                success: function (data) {
                    if (!data) {
                        retorno = true;
                    }
                },
                error: function (data) {
                    retorno = false;
                }
            });
            return retorno;
        });

        $("body").on('click','.show_pass i',function(){

            var input = $(this).closest('.form-group').find('input');

            if($(input).attr('type') == 'text'){
                $(input).attr('type', 'password');
                $(this).removeClass().addClass('fa fa-eye-slash');
            } else {
                $(input).attr('type', 'text');
                $(this).removeClass().addClass('fa fa-eye');
            }
        });

        $('.js-add-link').click(function() {
            var links = $(this).closest('.panel-body').find('.links').last().clone();
            $(links).find('input').attr('value','');

            console.log(links);
            links.insertAfter($(this).closest('.panel-body').find('.links').last());

        });

        $('.remove-link').click(function() {
            alert('ok');
           $(this).closest('.links').remove();
        });

        $('#ms-filter').magicSuggest({
            placeholder: 'Tags',
            data: [{
                id: 1,
                name: 'Tag1',
                nb: 34
            }, {
                id: 2,
                name: 'Tag2',
                nb: 106
            }],
            selectionPosition: 'inner',
            selectionStacked: true,
            mode: 'remote',
            selectionRenderer: function(data){
                return data.name + ' (<b>' + data.nb + '</b>)';
            }
        });
    });
});

function removerLink(link) {
    jQuery(link).closest('#Links').remove();
}