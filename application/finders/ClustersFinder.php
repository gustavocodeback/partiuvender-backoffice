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

    /**
    * filtro
    *
    * volta o array para formatar os filtros
    *
    */
    public function filtro() {

        // prepara os dados
        $this->db->from( $this->table )
        ->select( 'CodCluster as Valor, Nome as Label' );

        // faz a busca
        $busca = $this->db->get();

        // verifica se existe resultados
        if ( $busca->num_rows() > 0 ) {

            // seta o array de retorna
            $ret = [];

            // percorre todos os dados
            foreach( $busca->result_array() as $item ) {
                $ret[$item['Valor']] = $item['Label'];
            }

            // retorna os dados
            return $ret;

        } else return [];
    }
}

/* end of file */
