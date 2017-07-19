<?php

require 'application/models/Treinamento.php';

class TreinamentosFinder extends MY_Model {

    // entidade
    public $entity = 'Treinamento';

    // tabela
    public $table = 'Treinamentos';

    // chave primaria
    public $primaryKey = 'CodTreinamento';

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
    public function getTreinamento() {
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
        ->select( 'CodTreinamento as Código, p.Nome, p.Foto, CodTreinamento as Ações' );
        return $this;
    }

    public function treinamentos() {
        
        $this->db->order_by( 'CodTreinamento', 'DESC' );

        return $this;
    }

}

/* end of file */
