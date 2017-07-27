<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cartao extends MY_Model {

    // id da cidade
    public $CodCartao;

    // codigo
    public $codigo;

    // funcionario
    public $funcionario;

    // data
    public $data;

    // status
    // A - Aberto
    // U - Usado
    // D - Debitado
    // C - Cancelado
    public $status;

    public $valor;

    // entidade
    public $entity = 'Cartao';
    
    // tabela
    public $table = 'Cartoes';

    // chave primaria
    public $primaryKey = 'CodCartao';

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
        $this->CodCartao = $cod;
    }

    // funcionario
    public function setFunc( $funcionario ) {
        $this->funcionario = $funcionario;
    }

    // data
    public function setData( $data ) {
        $this->data = $data;
    }

    // status
    public function setStatus( $status ) {
        $this->status = $status;
    }

    // codigo
    public function setCodigo( $codigo ) {
        $this->codigo = $codigo;
    }

    // valor
    public function setValor( $valor ) {
        $this->valor = $valor;
    }
}

/* end of file */
