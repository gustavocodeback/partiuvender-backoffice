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
        'Loja' => 'Loja',
        'f.UID' => 'UID',
        'f.Token' => 'Token',
        'f.Cargo' => 'Cargo',
        'f.Nome' => 'Nome',
        'f.Email' => 'Email',
        'Senha' => 'Senha',
        'f.CPF' => 'CPF',
        'f.Pontos' => 'Pontos'
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

    /**
    * uid
    *
    * filtra pelo uid
    *
    */
    public function uid( $uid ) {
        $this->where( " UID = '$uid'" );
        return $this;
    }

   /**
    * cargo
    *
    * filtra pelo cpf
    *
    */
    public function cargo( $cargo ) {

        if( $cargo == 'Gerente' ) {
            $this->where( " Cargo = 'Gerente' OR Cargo = 'Sub-Gerente' " );
            return $this;
        }
        if( $cargo == 'Vendedor' ) {
            $this->where( " Cargo = 'Vendedor' " );
            return $this;
        }
    }
}

/* end of file */
