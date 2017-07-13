

// mascaras para telefones
var SPMaskBehavior = function (val) {
  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
},
spOptions = {
    onKeyPress: function( val, e, field, options ) {
        field.mask(SPMaskBehavior.apply( {}, arguments ), options );
    }
};
$( '.telefone' ).mask( SPMaskBehavior, spOptions );

// mascara para o cpf
$( '.cpf' ).mask( '999.999.999-99' );

// mascara para o cnpj
$( '.cnpj' ).mask( '99.999.999/9999-99' );

// mascara para o cnpj
$( '.cep' ).mask( '999999-999' );