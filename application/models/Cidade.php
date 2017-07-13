<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cidade extends MY_Model {

    // id da cidade
    public $CodCidade;

    // estado
    public $estado;

    // nome
    public $nome;

    // entidade
    public $entity = 'Cidade';
    
    // tabela
    public $table = 'Cidades';

    // chave primaria
    public $primaryKey = 'CodCidade';

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
        $this->CodCidade = $cod;
    }

    // nome
    public function setNome( $nome ) {
        $this->nome = $nome;
    }

    // uf
    public function setEstado( $estado ) {
        $this->estado = $estado;
    }
}

/* end of file */
