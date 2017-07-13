<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends MY_Model {

    // id do estado
    public $CodLog;

    // entidade
    public $entidade;

    // planilha
    public $planilha;

    // mensagem
    public $mensagem;

    // status
    public $status;

    // data
    public $data;

    // entidade
    public $entity = 'Log';
    
    // tabela
    public $table = 'Logs';

    // chave primaria
    public $primaryKey = 'CodLog';

   /**
    * __construct
    *
    * metodo construtor
    *
    */
    public function __construct() {
        parent::__construct();
    }
    
    // seta o codigo
    public function setCod( $cod ) {
        $this->CodLog = $cod;
    }

    // entidade
    public function setEntidade( $entidade ) {
        $this->entidade = $entidade;
        return $this;
    }

    // entidade
    public function setPlanilha( $planilha ) {
        $this->planilha = $planilha;
        return $this;
    }

    // mensagem
    public function setMensagem( $mensagem ) {
        $this->mensagem = $mensagem;
        return $this;
    }

    // status
    public function setStatus( $status ) {
        $this->status = $status;
        return $this;
    }

    // data
    public function setData( $data ) {
        $this->data = $data;
        return $this;
    }
}

/* end of file */
