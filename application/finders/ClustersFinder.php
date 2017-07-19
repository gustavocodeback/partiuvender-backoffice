<?php

require 'application/models/Cluster.php';

class ClustersFinder extends MY_Model {

    // entidade
    public $entity = 'Cluster';

    // tabela
    public $table = 'Clusters';

    // chave primaria
    public $primaryKey = 'CodCluster';

    // labels
    public $labels = [
        'Nome'  => 'Nome',
    ];

   /**
    * __construct
    *
    * metodo construtor
    *
    */
    public function __construct() {
        parent::__construct();
    }

   /**
    * getCluster
    *
    * pega a instancia do cluster
    *
    */
    public function getCluster() {
        return new $this->entity();
    }

   /**
    * grid
    *
    * funcao usada para gerar o grid
    *
    */
    public function grid() {
        $this->db->from( $this->table )
        ->select( 'CodCluster as Código, Nome, CodCluster as Ações' );
        return $this;
    }

   /**
    * nome
    *
    * filtra por nome
    *
    */
    public function nome( $nome ) {
        $this->where( " Nome = '$nome'" );
        return $this;
    }
}

/* end of file */
