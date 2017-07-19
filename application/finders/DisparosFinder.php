<?php

require 'application/models/Disparo.php';

class DisparosFinder extends MY_Model {

    // entidade
    public $entity = 'Disparo';

    // tabela
    public $table = 'Disparos';

    // chave primaria
    public $primaryKey = 'CodDisparo';

    // labels
    public $labels = [
        'Data'        => 'Data',
        'Notificacao' => 'Notificacao',
        'Nome'        => 'Nome'
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
    * getCidade
    *
    * pega a instancia do cidade
    *
    */
    public function getDisparo() {
        return new $this->entity();
    }

   /**
    * grid
    *
    * funcao usada para gerar o grid
    *
    */
    public function grid() {
        $this->db->from( $this->table.' d' )
        ->select( 'CodDisparo as Código, n.Nome as Notificacao, f.Nome as Nome, d.Data as Data, CodDisparo as Ações' )
        ->join( 'Notificacoes n', 'n.CodNotificacao = d.CodNotificacao' )
        ->join( 'Funcionarios f', 'f.CodFuncionario = d.CodFuncionario' );
        return $this;
    }

    /**
    * porFunc
    *
    * funcao usada para buscar os disparos relacionados ao funcionario
    *
    */
    public function porFunc( $CodFuncionario ) {        
        $this->where( " CodFuncionario = $CodFuncionario" );
        $this->db->order_by( 'Data', 'DESC' );
        return $this;
    }
}

/* end of file */
