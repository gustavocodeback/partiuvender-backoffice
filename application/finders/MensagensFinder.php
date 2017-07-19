<?php

require 'application/models/Mensagem.php';

class MensagensFinder extends MY_Model {

    // entidade
    public $entity = 'Mensagem';

    // tabela
    public $table = 'Mensagens';

    // chave primaria
    public $primaryKey = 'CodCluster';

    // labels
    public $labels = [];

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
    * getMensagem
    *
    * pega a instancia da mensagem
    *
    */
    public function getMensagem() {
        return new $this->entity();
    }

   /**
    * func
    *
    * filtra por funcionario
    *
    */
    public function func( $cod ) {
        $this->where( " CodFuncionario = '$cod' " );
        $this->db->order_by( 'Data', 'DESC' );
        return $this;
    }
}

/* end of file */
