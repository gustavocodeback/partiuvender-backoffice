<?php

require 'application/models/Produto.php';

class ProdutosFinder extends MY_Model {

    // entidade
    public $entity = 'Produto';

    // tabela
    public $table = 'Produtos';

    // chave primaria
    public $primaryKey = 'CodProduto';

    // labels
    public $labels = [
        'CodCategoria'  => 'Categoria',
        'Pontos'        => 'Pontos',
        'BasicCode'     => 'Basic Code'
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
    * getEstado
    *
    * pega a instancia do estado
    *
    */
    public function getProduto() {
        return new $this->entity();
    }

   /**
    * grid
    *
    * funcao usada para gerar o grid
    *
    */
    public function grid() {
        $this->db->from( $this->table .' p' )
        ->select( 'p.BasicCode, p.Nome as Nome, c.Nome as Categoria, p.Foto, p.Pontos, CodProduto as Ações' )
        ->join('Categorias c', 'c.CodCategoria = p.CodCategoria');
        return $this;
    }
    
    /**
    * exportar
    *
    * funcao usada para organizar os dados para exportacao
    *
    */
    public function exportar() {
        $this->db->from( $this->table .' p' )
        ->select( 'p.CodProduto as Codigo, p.BasicCode, c.Nome as Categoria, p.Nome as Nome, p.Pontos, p.Descricao, p.Foto, p.Video' )
        ->join('Categorias c', 'c.CodCategoria = p.CodCategoria');
        return $this;
    }

    /**
    * porCategoria
    *
    * obtem os produtos por categoria
    *
    */
    public function porCategoria( $CodCategoria ) {

        // seta o where
        $this->where( " CodCategoria = $CodCategoria " );
        return $this;
    }    
    
    public function basicCode( $basiccode ) {
        $this->where( " BasicCode = '$basiccode' " );
        return $this;
    }
}

/* end of file */
