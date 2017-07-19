<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Venda extends MY_Model {

    // id do estado
    public $CodVenda;

    // funcionario
    public $funcionario;

    // produto
    public $produto;

    // quantidade
    public $quantidade;

    // data
    public $data;

    // pontos
    public $pontos;

    // loja
    public $loja;

    // entidade
    public $entity = 'Venda';
    
    // tabela
    public $table = 'Vendas';

    // chave primaria
    public $primaryKey = 'CodVenda';

   /**
    * __construct
    *
    * metodo construtor
    *
    */
    public function __construct() {
        parent::__construct();
    }
    
    public function setCod( $cod ) {
        $this->CodVenda = $cod;
    }

    // funcionario
    public function setFuncionario( $funcionario ) {
        $this->funcionario = $funcionario;
    }

    // quantidade
    public function setQuantidade( $quantidade ) {
        $this->quantidade = $quantidade;
    }

    // produto
    public function setProduto( $produto ) {
        $this->produto = $produto;
    }

    // data
    public function setData( $data ) {
        $this->data = $data;
    }

    // pontos
    public function setPontos( $pontos ) {
        $this->pontos = $pontos;
    }

    // loja
    public function setLoja( $loja ) {
        $this->loja = $loja;
    }
}

/* end of file */
