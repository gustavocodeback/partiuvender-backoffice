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
        'CodCluster' => 'Cluster',
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
        ->select( ' CodLoja as Código, 
                    l.CNPJ, 
                    l.Razao, 
                    l.Nome, 
                    l.PontosIniciais, 
                    l.PontosAtuais, 
                    ( l.PontosAtuais / l.PontosIniciais ) as Crescimento, 
                    c.Nome as Cidade, 
        e.Nome as Estado, CodLoja as Ações' )
        ->join( 'Cidades c', 'c.CodCidade = l.CodCidade' )
        ->join( 'Estados e', 'e.CodEstado = l.CodEstado' )
        ->join( 'Clusters r', 'r.CodCluster = l.CodCluster' );
        return $this;
    }
    
    /**
    * exportar
    *
    * funcao usada para organizar os dados para exportacao
    *
    */
    public function exportar() {
        $this->db->from( $this->table.' l' )
        ->select( ' l.CodLoja as Codigo, 
                    r.Nome as Cluster, 
                    l.CNPJ, 
                    l.Razao, 
                    l.Nome, 
                    l.PontosIniciais, 
                    ( l.PontosAtuais / l.PontosIniciais ) as Crescimento, 
                    l.Endereco, 
                    l.Numero, 
                    l.Complemento,
                    l.Bairro, 
                    c.Nome as Cidade, 
                    e.UF as Estado')
        ->join( 'Cidades c', 'c.CodCidade = l.CodCidade', 'left' )
        ->join( 'Estados e', 'e.CodEstado = l.CodEstado', 'left' )
        ->join( 'Clusters r', 'r.CodCluster = l.CodCluster' );
        return $this;
    }

   /**
    * nome
    *
    * filtra pelo nome
    *
    */
    public function nome( $nome ) {
        $this->where( " Nome = '$nome' " );
        return $this;
    }

   /**
    * cnpj
    *
    * filtra pelo cnpj
    *
    */
    public function cnpj( $cnpj ) {
        $this->where( " CNPJ = '$cnpj' " );
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
        ->select( 'CodLoja as Valor, Nome as Label' );

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

   /**
    * count
    *
    * faz a contagem de lojas no sistema
    *
    */
    public function count() {

        // monta a query
        $this->db->select( 'count( * ) as Total' )
        ->from( 'Lojas' );

        // faz a busca
        $busca = $this->db->get();

        // volta o resultado
        return ( $busca->num_rows() ) ? $busca->result_array()[0]['Total'] : 0;
    }
}

/* end of file */
