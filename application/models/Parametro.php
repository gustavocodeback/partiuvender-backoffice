<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Parametro extends MY_Model {

    // id do Parametro
    public $CodParametro;

    // nome
    public $nome;

    // valor
    public $valor;

    // entidade
    public $entity = 'Parametro';
    
    // tabela
    public $table = 'Parametros';

    // chave primaria
    public $primaryKey = 'CodParametro';

   /**
    * __construct
    *
    * metodo construtor
    *
    */
    public function __construct() {
        parent::__construct();
    }
    
    public function setCod( $cod ) {
        $this->CodParametro = $cod;
    }

    // nome
    public function setNome( $nome ) {
        $this->nome = $nome;
    }

    // valor
    public function setValor( $valor ) {
        $this->valor = $valor;
    }
}

/* end of file */
