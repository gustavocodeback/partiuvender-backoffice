/**
 * toggleSideBar
 * 
 * esconde/mostra o sidebar
 * 
 */
function toggleSideBar() {

    // mostrar o aside
    $('#aside').toggleClass( 'hide' );

    // mostra o wrapper
    $('#wrapper').toggleClass( 'hide' );

    // retorna false
    return false;
}

/**
 * importarPlanilha
 * 
 * importa uma planilha
 * 
 */
function importarPlanilha( input ) {

    // verifica se existe algum conteudo
    if ( input.val() ) $( '#import-form' ).submit();
}

function fileToBlob( input, callback ) {
    var file = input[0].files[0];
    var fileReader = new FileReader();
    fileReader.readAsDataURL( file );
    fileReader.onload = function( e ) {
        callback( e.target.result );
    }
}


/**
 * preencherSelect
 * 
 * preenche um select com os dados passados
 * 
 * @param {string} seletor o seletor usado no elemento
 * @param {Array<{label: string, value: string }>} dados os dados que preencherao o select
 */
function preencherSelect( seletor, dados ) {

    // pega o elemento
    var elem = $( seletor );

    // esvazia o mesmo
    elem.html( '' );

    // cria uma option
    var option = $( '<option></option>' );

    // habilita o select
    elem.removeAttr( 'disabled' )

    // adiciona a opcao padrao
    option.val( '' ).html( '-- Selecione --' );
    elem.append( option );
    // verifica se existem dados
    if ( dados.length == 0 ) {

        // desabilita o input
        elem.attr( 'disabled', 'disabled' );
        return;
    }

    // percorre os dados
    for ( var d in dados ) {
        var opt = option.clone();
        // seta a opcao
        opt.val( dados[d].value ).html( dados[d].label );
        elem.append( opt );
    }
}

/**
 * atualizarSelect
 * 
 * atualiza um select relacional
 * 
 * @param {string} seletor o seletor do elemento
 * @param {string} url a url onde obter os dados
 * @param {Element} elem o elemento atual
 */
function atualizarSelect( seletor, url, elem ) {

    // pega o valor no elemento
    var value = elem.val();
    
    // verifica se o mesmo eh numerico
    if ( !Number.isInteger( parseInt( value ) ) ) {
        preencherSelect( seletor, [] );
        return;
    }

    // monta a url de requisicao
    var ajaxUrl = Site.url+url+'/'+value;

    // faz a requisicao
    $.get( ajaxUrl, function( data ) {

        try {

            // transforma em json
            data = JSON.parse( data );
            preencherSelect( seletor, data );

        } catch (error) {

            // preenche o select com o campo vazio
            preencherSelect( seletor, [] );
        }
    });
}

// mostra as importacoes
$( document ).ready( function() {
    $('.fade-in').animate( { opacity: 1 }, 1000 );
});
