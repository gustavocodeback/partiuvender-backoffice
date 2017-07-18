<?php

require 'application/models/Notificacao.php';

class NotificacoesFinder extends MY_Model {

    // entidade
    public $entity = 'Notificacao';

    // tabela
    public $table = 'Notificacoes';

    // chave primaria
    public $primaryKey = 'CodNotificacao';

    // labels
    public $labels = [
        'Nome'  => 'Nome',
        'Notificacao' => 'Notificacao',
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
    * getNotificacao
    *
    * pega a instancia do estado
    *
    */
    public function getNotificacao() {
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
        ->select( 'CodNotificacao as Código, Nome, Notificacao, CodNotificacao as Ações' );
        return $this;
    }

}

/* end of file */
