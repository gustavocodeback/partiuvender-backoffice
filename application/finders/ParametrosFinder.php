<?php

require 'application/models/Parametro.php';

class ParametrosFinder extends MY_Model {

    // entidade
    public $entity = 'Parametro';

    // tabela
    public $table = 'Parametros';

    // chave primaria
    public $primaryKey = 'CodParametro';

    // labels
    public $labels = [
        'nome'  => 'Nome',
        'valor' => 'Valor',
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
    * getParametro
    *
    * pega a instancia do estado
    *
    */
    public function getParametro() {
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
        ->select( 'CodParametro as Código, Nome, Valor, CodParametro as Ações' );
        return $this;
    }

    public function parametro( $nome ) {
        $this->where( " Nome = '$nome'" );
        return $this;
    }
}

/* end of file */
