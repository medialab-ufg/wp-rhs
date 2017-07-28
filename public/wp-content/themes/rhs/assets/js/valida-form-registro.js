jQuery( function( $ ) {

    $(function () {

        jQuery.validator.setDefaults({
            debug: true,
            focusCleanup: true
        });

        $('#register').validate({
            ignore: ".ignore",
            errorElement: 'p',
            errorClass: 'block-error',
            focusInvalid: true,
            focusCleanup: false,
            onkeyup: false,
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
                },
                hiddenRecaptcha: {
                    required: function () {
                        if (grecaptcha.getResponse() == '') {
                            return true;
                        } else {
                            return false;
                        }
                    }
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
                },
                hiddenRecaptcha: {
                    required: "Valide o Captcha primeiro."
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
                if($(element).parent().find('.capt')){
                    $("span").hide();
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
                $(form).find('[type="submit"]').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>');
                form.submit();
            }
        });

        function recaptchaCallback() {
          $('#hiddenRecaptcha').valid();
        };

        $('#login').validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: true,
            focusCleanup: false,
            onkeyup: false,
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
                $(form).find('[type="submit"]').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>');
                form.submit();
            }
        });

        $('#lostpassword').validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: true,
            focusCleanup: false,
            onkeyup: false,
            ignore: '',
            rules: {
                user_login: {
                    required: true,
                    email: true
                },
                hiddenRecaptcha: {
                    required: function () {
                        if (grecaptcha.getResponse() == '') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                }
            },
            messages: {
                user_login: {
                    required: 'Preencha com seu email.',
                    email: 'Preencha com email no formato correto'
                },
                hiddenRecaptcha: {
                    required: "Valide o Captcha primeiro."
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
                $(form).find('[type="submit"]').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>');
                form.submit();
            }
        });

        $('#retrievepassword').validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: true,
            focusCleanup: false,
            onkeyup: false,
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
                $(form).find('[type="submit"]').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>');
                form.submit();
            }
        });

        $('#perfil').validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: true,
            focusCleanup: false,
            onkeyup: false,
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
                $(form).find('[type="submit"]').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>');
                form.submit();
            }
        });

        $('#input-tags').find('input').attr('name','tags');
        $('#input-category').find('input').attr('name','category');

        $('#posting').validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: true,
            focusCleanup: false,
            onkeyup: false,
            ignore: '[type="button"]',
            rules: {
                title: {
                    required: true
                },
                'comunity-status[]': {
                    required: true
                }
            },
            messages: {
                title: {
                    required: 'Preencha o titulo.'
                },
                'comunity-status[]': {
                    required: 'Selecione onde será publicado.'
                }
            },
            invalidHandler: function (event, validator) {},
            errorPlacement: function (error, element) {
                if (element.parents(".form-checkbox").size() > 0) {
                    error.appendTo(element.parents(".form-checkbox"));
                }else if (element.parents(".ms-ctn").size() > 0) {
                    error.insertAfter(element.parents(".ms-ctn"));
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
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            submitHandler: function(form) {
                $(form).find('[type="submit"]').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>');
                form.submit();
            }
        });

        $('#contato').validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: true,
            focusCleanup: false,
            onkeyup: false,
            ignore: '',
            rules: {
                name: {
                    maxlength: 128,
                    required: true
                },
                email: {
                    email: true,
                    required: true
                },
                category: {
                    required: true
                },
                subject: {
                    maxlength: 200,
                    required: true
                },
                estado: {
                    required: true
                },
                city: {
                    required: true
                },
                message: {
                    maxlength: 500,
                    required: true
                }
            },
            messages: {
                name: {
                    maxlength: 'Tamanho maximo de 128 caracteres.',
                    required: 'Preencha com seu nome.',
                },
                email: {
                    email: 'Formato de email inválido.',
                    required: 'Preencha a seu email.',
                },
                category: {
                    required: 'Selecione a categoria sobre o contato.'
                },
                subject: {
                    maxlength: 'Tamanho maximo de 200 caracteres.',
                    required: 'Preencha com o assunto do contato.',
                },
                estado: {
                    required: 'Selecione com seu estado.'
                },
                municipio: {
                    required: 'Selecione com sua cidade.'
                },
                message: {
                    maxlength: 'Tamanho maximo de 500 caracteres.',
                    required: 'Preencha com a sua mensagem.',
                }
            },
            invalidHandler: function (event, validator) {},
            errorPlacement: function (error, element) {

                if(!element.parent(".form-group").is(':visible')){
                    $(element).parents("form").prepend(error);
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
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            submitHandler: function(form) {
                $(form).find('[type="submit"]').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>');
                console.log('foi');
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
           $(this).closest('.links').remove();
        });
    });
});

function removerLink(link) {
    jQuery(link).closest('#Links').remove();
}