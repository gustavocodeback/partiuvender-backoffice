<?php

require 'application/models/Venda.php';

class VendasFinder extends MY_Model {

    // entidade
    public $entity = 'Venda';

    // tabela
    public $table = 'Vendas';

    // chave primaria
    public $primaryKey = 'CodVenda';

    // labels
    public $labels = [
        'f.CodFuncionario' => 'f.CodFuncionario',
        'CodProduto'     => 'CodProduto',
        'Funcionario'    => 'CPF'
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
    * getVenda
    *
    * pega a instancia do cidade
    *
    */
    public function getVenda() {
        return new $this->entity();
    }

   /**
    * grid
    *
    * funcao usada para gerar o grid
    *
    */
    public function grid() {
        $this->db->from( $this->table.' v' )
        ->select( 'CodVenda as Código, f.CodFuncionario as Vendedor, f.NeoCode as NeoCode, f.CPF, p.Nome as Produto, v.Quantidade, v.Pontos as Pontos,
         v.Data as Data, l.Nome as Loja, CodVenda as Ações' )
        ->join( 'Funcionarios f', 'f.CodFuncionario = v.CodFuncionario' )
        ->join( 'Produtos p', 'p.CodProduto = v.CodProduto' )
        ->join( 'Lojas l', 'l.CodLoja = v.CodLoja' );
        return $this;
    }
    
   /**
    * grid
    *
    * funcao usada para gerar o grid
    *
    */
    public function exportar() {
        $this->db->from( $this->table.' v' )
        ->select( 'CodVenda as Código, f.CodFuncionario as Vendedor, f.NeoCode as NeoCode, f.CPF, p.Nome as Produto, v.Quantidade, v.Pontos as Pontos,
         v.Data as Data, l.Nome as Loja' )
        ->join( 'Funcionarios f', 'f.CodFuncionario = v.CodFuncionario' )
        ->join( 'Produtos p', 'p.CodProduto = v.CodProduto' )
        ->join( 'Lojas l', 'l.CodLoja = v.CodLoja' );
        return $this;
    }

}

/* end of file */
