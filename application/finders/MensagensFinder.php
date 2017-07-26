<?php

require 'application/models/Mensagem.php';

class MensagensFinder extends MY_Model {

    // entidade
    public $entity = 'Mensagem';

    // tabela
    public $table = 'Mensagens';

    // chave primaria
    public $primaryKey = 'CodMensagem';

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
    * grid
    *
    * funcao usada para gerar o grid
    *
    */
    public function grid() {
        $this->db->from( $this->table.' m' )
        ->select( 'f.Nome, count( m.CodFuncionario ) as Mensagens, m.CodFuncionario as Ações' )
       ->join( 'Funcionarios f', 'f.CodFuncionario = m.CodFuncionario' )
       ->group_by( 'm.CodFuncionario' );
        return $this;
    }

    /**
    * grid
    *
    * funcao usada para gerar o grid
    *
    */
    public function gridFunc( $CodFuncionario ) {
        
        $this->where( " m.CodFuncionario = '$CodFuncionario' " );
        $this->db->from( $this->table.' m' )
        ->select( 'f.Nome, m.Texto, m.Data' )
       ->join( 'Funcionarios f', 'f.CodFuncionario = m.CodFuncionario' );
        return $this;
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
