jQuery( function( $ ) {

    $(function () {
                
                $("#form-cadastro").validate({
                    highlight: function(element) {
                        $(element).parent().addClass('has-error');
                    },
                    unhighlight: function(element) {
                        $(element).parent().removeClass('has-error');
                    },
                    success: function(element) {
                        $(element).parent().addClass('has-success');
                    },
                    debug: true,
                    focusCleanup: true,
                    errorLabelContainer: "input .label",
                    errorElement: "div",
                    rules: {
                        name: {
                            minlength: 9,
                            maxlength: 50
                        },
                        mail: {
                            required: true,
                            email: true
                        }
                    },
                    messages: {
                        name: {
                            required: "",
                            minlength: "Insira seu nome completo!"
                        },
                        mail: {
                            required: "",
                            email: "Preencha corretamento seu email!"
                        }
                    }
                });
            });

});