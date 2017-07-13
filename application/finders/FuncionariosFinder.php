<?php

require 'application/models/Funcionario.php';

class FuncionariosFinder extends MY_Model {

    // entidade
    public $entity = 'Funcionario';

    // tabela
    public $table = 'Funcionarios';

    // chave primaria
    public $primaryKey = 'CodFuncionario';

    // labels
    public $labels = [
        'CodLoja' => 'Loja',
        'UID' => 'UID',
        'Token' => 'Token',
        'Cargo' => 'Cargo',
        'Nome' => 'Nome',
        'Email' => 'Email',
        'Senha' => 'Senha',
        'CPF' => 'CPF',
        'Pontos' => 'Pontos'
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
    * getLoja
    *
    * pega a instancia do loja
    *
    */
    public function getFuncionario() {
        return new $this->entity();
    }

   /**
    * grid
    *
    * funcao usada para gerar o grid
    *
    */
    public function grid() {
        $this->db->from( $this->table.' f' )
        ->select( 'f.UID, f.CPF, f.Nome, f.Cargo,
         l.Nome as Loja, CodFuncionario as Ações' )
        ->join( 'Lojas l', 'l.CodLoja = f.CodLoja' );
        return $this;
    }

   /**
    * cpf
    *
    * filtra pelo cpf
    *
    */
    public function cpf( $cpf ) {
        $this->where( " CPF = $cpf" );
        return $this;
    }
}

/* end of file */
