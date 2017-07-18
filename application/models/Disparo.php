<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Disparo extends MY_Model {

    // id da cidade
    public $CodDisparo;

    // notificacao
    public $notificacao;

    // funcionario
    public $funcionario;

    // data
    public $data;

    // status
    public $status;

    // entidade
    public $entity = 'Disparo';
    
    // tabela
    public $table = 'Disparos';

    // chave primaria
    public $primaryKey = 'CodDisparo';

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
        $this->CodDisparo = $cod;
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

    // uf
    public function setNotificacao( $notificacao ) {
        $this->notificacao = $notificacao;
    }
}

/* end of file */
