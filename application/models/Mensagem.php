<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mensagem extends MY_Model {

    // id do cluster
    public $CodMensagem;

    // funcionario
    public $funcionario;

    // texto
    public $texto;

    // data
    public $data;

    // entidade
    public $entity = 'Mensagem';
    
    // tabela
    public $table = 'Mensagens';

    // chave primaria
    public $primaryKey = 'CodMensagem';

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
        $this->CodMensagem = $cod;
    }

    // funcionario
    public function setFuncionario( $funcionario ) {
        $this->funcionario = $funcionario;
        return $this;
    }

    // texto
    public function setTexto( $texto ) {
        $this->texto = $texto;
        return $this;
    }

    // data
    public function setData( $data ) {
        $this->data = $data;
        return $this;        
    }
}

/* end of file */
