<?php

require 'application/models/Loja.php';

class LojasFinder extends MY_Model {

    // entidade
    public $entity = 'Loja';

    // tabela
    public $table = 'Lojas';

    // chave primaria
    public $primaryKey = 'CodLoja';

    // labels
    public $labels = [
        'Cluster' => 'Cluster',
        'CNPJ' => 'CNPJ',
        'Razao' => 'Razao',
        'Nome' => 'Nome',
        'Endereco' => 'Endereco',
        'Numero' => 'Numero',
        'Complemento' => 'Complemento',
        'Bairro' => 'Bairro',
        'Cidade' => 'Cidade',
        'Estado' => 'Estado'
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
    * getLoja
    *
    * pega a instancia do loja
    *
    */
    public function getLoja() {
        return new $this->entity();
    }

   /**
    * grid
    *
    * funcao usada para gerar o grid
    *
    */
    public function grid() {
        $this->db->from( $this->table.' l' )
        ->select( 'CodLoja as Código, l.CNPJ, l.Razao, l.Nome, c.Nome as Cidade, 
        e.Nome as Estado, r.Nome as Cluster, CodLoja as Ações' )
        ->join( 'Cidades c', 'c.CodCidade = l.CodCidade' )
        ->join( 'Estados e', 'e.CodEstado = l.CodEstado' )
        ->join( 'Clusters r', 'r.CodCluster = l.CodCluster' );
        return $this;
    }

    public function nome( $nome ) {
        $this->where( " Nome = '$nome' " );
        return $this;
    }
}

/* end of file */
