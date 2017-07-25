<?php

require 'application/models/Cartao.php';

class CartoesFinder extends MY_Model {

    // entidade
    public $entity = 'Cartao';

    // tabela
    public $table = 'Cartoes';

    // chave primaria
    public $primaryKey = 'CodCartao';

    // labels
    public $labels = [
        'Codigo'         => 'Cartao',
        'CodFuncionario' => 'Funcionario',
        'Status'         => 'Status',
        'Data'           => 'Data'
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
    public function getCartao() {
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
        ->select( 'CodCartao as Código, d.Codigo, d.Valor, f.Nome as Funcionario, d.Data as Data, CodCartao as Ações' )
        ->join( 'Funcionarios f', 'f.CodFuncionario = d.CodFuncionario', 'left' );
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

    /**
    * codigo
    *
    * funcao usada para buscar os cartoes com determinado codigo
    *
    */
    public function codigo( $Codigo ) {        
        $this->where( " Codigo = '$Codigo'" );
        return $this;
    }
}

/* end of file */
