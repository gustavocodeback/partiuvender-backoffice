<?php

require 'application/models/Categoria.php';

class CategoriasFinder extends MY_Model {

    // entidade
    public $entity = 'Categoria';

    // tabela
    public $table = 'Categorias';

    // chave primaria
    public $primaryKey = 'CodCategoria';

    // labels
    public $labels = [
        'nome'  => 'Nome',
        'foto'  => 'Foto'
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
    public function getCategoria() {
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
        ->select( 'CodCategoria as Código, Nome, Foto, CodCategoria as Ações' );
        return $this;
    }
}

/* end of file */
