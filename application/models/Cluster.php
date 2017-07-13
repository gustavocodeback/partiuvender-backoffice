<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cluster extends MY_Model {

    // id do cluster
    public $CodCluster;

    // nome
    public $nome;

    // entidade
    public $entity = 'Cluster';
    
    // tabela
    public $table = 'Clusters';

    // chave primaria
    public $primaryKey = 'CodCluster';

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
        $this->CodCluster = $cod;
    }

    // nome
    public function setNome( $nome ) {
        $this->nome = $nome;
    }
}

/* end of file */
