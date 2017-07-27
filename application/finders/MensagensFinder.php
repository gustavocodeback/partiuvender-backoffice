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
        ->select( ' m.CodFuncionario as `Código do funcionário`,
                    f.Nome as Nome,
                    f.Cpf,
                    m.Data,
                    count( m.CodMensagem ) as Total,
                    f.CodFuncionario as Ações ' )
        ->join( 'Funcionarios f', 'f.CodFuncionario = m.CodFuncionario' )
        ->order_by( 'Data', 'DESC' )
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
        ->select( 'f.Email, f.Cpf, f.Nome, m.Texto, m.Data' )
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

   /**
    * count
    *
    * conta quantos funcionarios possui o sistema
    *
    */
    public function count() {

        // monta a query
        $this->db->select( 'count( distinct( CodFuncionario ) ) as Total' )
        ->from( 'Mensagens' );

        // faz a busca
        $busca = $this->db->get();

        // volta o resultado
        return ( $busca->num_rows() ) ? $busca->result_array()[0]['Total'] - 1 : 0;
    }
}

/* end of file */
