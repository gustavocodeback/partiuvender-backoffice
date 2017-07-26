<?php

require 'application/models/Funcionario.php';

class FuncionariosFinder extends MY_Model {

    // entidade
    public $entity = 'Funcionario';

    // tabela
    public $table = 'Funcionarios';

    // chave primaria
    public $primaryKey = 'CodFuncionario';

    // labels
    public $labels = [
        'CodLoja'   => 'Loja',
        'UID'       => 'UID',
        'Token'     => 'Token',
        'Cargo'     => 'Cargo',
        'Nome'      => 'Nome',
        'Email'     => 'Email',
        'Senha'     => 'Senha',
        'CPF'       => 'CPF',
        'Pontos'    => 'Pontos'
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
    public function getFuncionario() {
        return new $this->entity();
    }

   /**
    * grid
    *
    * funcao usada para gerar o grid
    *
    */
    public function grid() {
        $this->db->from( $this->table.' f' )
        ->select( 'f.UID, f.CPF, f.Nome, f.Cargo,
         l.Nome as Loja, CodFuncionario as Ações' )
        ->join( 'Lojas l', 'l.CodLoja = f.CodLoja' );
        return $this;
    }

    /**
    * exportar
    *
    * funcao usada para organizar os dados para exportacao
    *
    */
    public function exportar() {
        $this->db->from( $this->table.' f' )
        ->select( 'f.CodFuncionario as Codigo, l.Nome as Loja, f.UID, f.Token, f.Cargo, f.CPF, f.RG, f.Nome, f.Email, f.Pontos,
        f.Endereco, f.Numero, f.Complemento, f.Cep, f.Celular, c.Nome as Cidade, e.UF as Estado')
        ->join( 'Lojas l', 'l.CodLoja = f.CodLoja' )
        ->join( 'Cidades c', 'c.CodCidade = f.CodCidade', 'left' )
        ->join( 'Estados e', 'e.CodEstado = f.CodEstado', 'left' );
        return $this;
    }

   /**
    * cpf
    *
    * filtra pelo cpf
    *
    */
    public function cpf( $cpf ) {
        $this->where( " CPF = $cpf" );
        return $this;
    }

    /**
    * uid
    *
    * filtra pelo uid
    *
    */
    public function uid( $uid ) {
        $this->where( " UID = '$uid'" );
        return $this;
    }

   /**
    * cargo
    *
    * filtra pelo cargo
    *
    */
    public function cargo( $cargo ) {

        if( $cargo == 'Gerente' ) {
            $this->where( " Cargo = 'Gerente' OR Cargo = 'Sub-Gerente' " );
            return $this;
        }
        if( $cargo == 'Vendedor' ) {
            $this->where( " Cargo = 'Vendedor' " );
            return $this;
        }
    }

   /**
    * loja
    *
    * filtra pela loja
    *
    */
    public function loja( $loja ) {

        // seta a loja
        $this->where( " CodLoja = $loja " );
        return $this;
    }

   /**
    * orderByPontos
    *
    * ordena pelos pontos
    *
    */
    public function orderByPontos() {
        $this->db->order_by( 'Pontos', 'DESC' );
        return $this;
    }

   /**
    * rankingClusterPessoal
    *
    * pega o ranking
    *
    */
    public function rankingClusterPessoal( $cluster, $cod ) {

        // faz a busca
        $busca = $this->db->query( "SELECT * FROM 
            ( SELECT f.*, @i := @i+1 AS ranking
                FROM (SELECT @i:=0) AS foo, 
                ( SELECT f.* FROM Funcionarios f
            INNER JOIN Lojas l on f.CodLoja = l.CodLoja 
            INNER JOIN Clusters c on l.CodCluster = c.CodCluster 
            WHERE c.CodCluster = '$cluster'
            ORDER BY f.Pontos DESC ) as f ) as s
        WHERE CodFuncionario = $cod
        LiMIT 10" );

        // volta o array
        return $busca->result_array()[0];
    }

   /**
    * rankingCluster
    *
    * pega o ranking
    *
    */
    public function rankingCluster( $cluster ) {

        // faz a busca
        $busca = $this->db->query( "SELECT * FROM 
            ( SELECT f.*, @i := @i+1 AS ranking
                FROM (SELECT @i:=0) AS foo, 
                ( SELECT f.* FROM Funcionarios f
            INNER JOIN Lojas l on f.CodLoja = l.CodLoja 
            INNER JOIN Clusters c on l.CodCluster = c.CodCluster 
            WHERE c.CodCluster = '$cluster'
            ORDER BY f.Pontos DESC ) as f ) as s
        LiMIT 10" );

        // volta o array
        return $busca->result_array();
    }
    
}

/* end of file */
