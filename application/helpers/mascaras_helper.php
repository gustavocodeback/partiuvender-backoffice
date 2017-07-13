<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * mascara_cnpj
 *
 * formata o cnpj
 *
 */
if ( ! function_exists('mascara_cnpj') ) {
    function mascara_cnpj( $valor ) {
        $cnpj = substr( $valor, 0, 2 ).'.'.substr( $valor, 2, 3 ).'.'
                        .substr( $valor, 5, 3 ).'/'.substr( $valor, 8, 4 ).'-'.substr( $valor, 12, 2 );
        return $cnpj;
    }
}

/**
 * mascara_cep
 *
 * formata o cep
 *
 */
if ( ! function_exists( 'mascara_cep' ) ) {
    function mascara_cep( $valor ) {
        $cep = substr( $valor, 0, 5 ).'-'.substr( $valor, 5, 3 );
        return $cep;       
    }
}

/**
 * mascara_cpf
 *
 * formata o cpf
 *
 */
if ( ! function_exists( 'mascara_cpf' ) ) {
    function mascara_cpf( $val ) {
        return substr( $val, 0, 3 ).'.'.substr( $val, 3, 3 ).'.'.substr( $val, 6, 3).'-'.substr( $val, 9, 2 );
    }
}

/**
 * mascara_telefone
 *
 * formata o telefone
 *
 */
if ( ! function_exists( 'mascara_telefone' ) ) {
    function mascara_telefone( $val ) {

        // seta o dd
        $dd = '('.$val[0].$val[1].')';

        // seta a primeira parte
        $prefix = ( strlen( $val ) == 8 ) ? substr( $val, 2, 4 ) : substr( $val, 2, 5 );

        // seta a segunda parte
        $pos = ( strlen( $val ) == 8 ) ? substr( $val, 5, 4 ) : substr( $val, 6, 4 );

        // volta os dados formatados
        return $dd.' '.$prefix.'-'.$pos;
    }
}

/**
 * in_cell
 *
 * verifica se existe
 *
 */
if ( ! function_exists( 'in_cell' ) ) {
    function in_cell( $val ) {

        // verifica se existe
        if ( !$val ) return false;

        // verificia o tamanho
        if ( strlen( $val ) == 0 ) return false;

        // verifica o conteudo
        if ( $val == '0' ) return false;

        // volta true por padrao
        return true;
    }
}