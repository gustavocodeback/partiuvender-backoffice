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

   /**
    * obterPrimeirosColocados
    *
    * Obtem os primeiros colocados
    *
    */
    public function obterPrimeirosColocados() {

        // prepara a query
        $query = $this->db->query( "SELECT Rankeado.*, @i := @i+1 AS ranking
            FROM (SELECT @i:=0) AS foo,
            ( SELECT 	Lojas.CodLoja,
                Lojas.Nome,
                ( CASE WHEN Total IS NULL THEN 0 ELSE Total END ) as Total,
                ( Total / PontosIniciais ) as Cociente from Lojas 
            LEFT JOIN
                ( SELECT CodLoja, 
                SUM( Pontos ) as Total 
            FROM Vendas
            GROUP BY CodLoja ) as Pontuacao
            ON Lojas.CodLoja = Pontuacao.CodLoja
            WHERE CodCluster = $this->CodCluster
            ORDER BY Cociente DESC ) as Rankeado
        LIMIT 10" );

        // faz a busca
        return $query->result_array();
    }

   /**
    * obterLojaPosicao
    *
    * Obtem a posicao da loja
    *
    */
    public function obterLojaPosicao( $loja ) {

        // prepara a query
        $query = $this->db->query( "SELECT * FROM ( SELECT Rankeado.*, @i := @i+1 AS ranking
            FROM (SELECT @i:=0) AS foo,
            ( SELECT 	Lojas.CodLoja,
                Lojas.Nome,
                ( CASE WHEN Total IS NULL THEN 0 ELSE Total END ) as Total,
                ( Total / PontosIniciais ) as Cociente from Lojas 
            LEFT JOIN
                ( SELECT CodLoja, 
                SUM( Pontos ) as Total 
            FROM Vendas
            GROUP BY CodLoja ) as Pontuacao
            ON Lojas.CodLoja = Pontuacao.CodLoja
            WHERE CodCluster = $this->CodCluster
            ORDER BY Cociente DESC ) as Rankeado ) Posicao
        WHERE CodLoja = $loja" );

        // volta o resultado
        return ( $query->num_rows() > 0 ) ? $query->result_array()[0] : false;
    }
}

/* end of file */
