<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Estado extends MY_Model {

    // id do estado
    public $CodEstado;

    // nome
    public $nome;

    // uf
    public $uf;

    // entidade
    public $entity = 'Estado';
    
    // tabela
    public $table = 'Estados';

    // chave primaria
    public $primaryKey = 'CodEstado';

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
        $this->CodEstado = $cod;
    }

    // nome
    public function setNome( $nome ) {
        $this->nome = $nome;
    }

    // uf
    public function setUf( $uf ) {
        $this->uf = $uf;
    }
}

/* end of file */
