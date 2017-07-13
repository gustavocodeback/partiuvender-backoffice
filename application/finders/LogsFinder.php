<?php

require 'application/models/Log.php';

class LogsFinder extends MY_Model {

    // entidade
    public $entity = 'Log';

    // tabela
    public $table = 'Logs';

    // chave primaria
    public $primaryKey = 'CodLog';

    // labels
    public $labels = [
        'CodLog' => 'Código'
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
    * getLog
    *
    * pega a instancia do estado
    *
    */
    public function getLog() {
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
        ->select( 'CodLog, Entidade, Planilha, Mensagem, Data, Status, CodLog as Ações' );
        return $this;
    }
}

/* end of file */
