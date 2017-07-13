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
        'Nome'  => 'Nome',
        'Categoria'  => 'Categoria',
        'Foto'  => 'Foto',
        'Pontos'  => 'Pontos'
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
        ->select( 'CodProduto as Código, p.Nome, c.Nome as Categoria, p.Foto, p.Pontos, CodProduto as Ações' )
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
}

/* end of file */
