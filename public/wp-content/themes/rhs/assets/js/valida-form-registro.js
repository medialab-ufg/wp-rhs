jQuery( function( $ ) {

    $(function () {
                
                $("#form-cadastro").validate({
                    rules: {
                        name: {
                            required: true,
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
                            required: "O nome é necessário!",
                            minlength: "Insira seu nome completo!"
                        },
                        mail: "Preencha corretamento seu email!"
                    },
                    errorContainer: ".block-email", 
                    errorLabelContainer: ".block-email", 
                    errorElement: "dt",
                    errorPlacement: function ( error, element ) {
                        // Add the `help-block` class to the error element
                        error.addClass( "help-block" );

                        // Add `has-feedback` class to the parent div.form-group
                        // in order to add icons to inputs
                        element.parents( ".col-sm-12" ).addClass( "has-feedback" );

                        if ( element.prop( "type" ) === "checkbox" ) {
                            error.insertIn( element.parent( "label" ) );
                        } else {
                            error.appendTo( element );
                        }

                        // Add the span element, if doesn't exists, and apply the icon classes to it.
                        if ( !element.next( "span" )[ 0 ] ) {
                            $( "<span class='glyphicon glyphicon-remove form-control-feedback'></span>" ).insertAfter( element );
                        }
                    },
                    success: function ( label, element ) {
                        // Add the span element, if doesn't exists, and apply the icon classes to it.
                        if ( !$( element ).next( "span" )[ 0 ] ) {
                            $( "<span class='glyphicon glyphicon-ok form-control-feedback'></span>" ).insertAfter( $( element ) );
                        }
                        
                    },
                    highlight: function ( element, errorClass, validClass ) {
                        $( element ).parents( ".col-sm-12" ).addClass( "has-error" ).removeClass( "has-success" );
                        $( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
                    },
                    unhighlight: function ( element, errorClass, validClass ) {
                        $( element ).parents( ".col-sm-12" ).addClass( "has-success" ).removeClass( "has-error" );
                        $( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
                    }
                });
            });

});


       