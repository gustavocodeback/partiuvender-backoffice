<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Funcionario extends MY_Model {

    // id do cluster
    public $CodFuncionario;

    // loja
    public $loja;

    // uid
    public $uid;

    // token
    public $token;

    // cargo
    public $cargo;

    // nome
    public $nome;

    // email
    public $email;

    // cpf
    public $cpf;

    // pontos
    public $pontos;

    // entidade
    public $entity = 'Funcionario';
    
    // tabela
    public $table = 'Funcionarios';

    // chave primaria
    public $primaryKey = 'CodFuncionario';

   /**
    * __construct
    *
    * metodo construtor
    *
    */
    public function __construct() {
        parent::__construct();
    }
    
    // seta o codigo
    public function setCod( $cod ) {
        $this->CodFuncionario = $cod;
    }

    // loja
    public function setLoja( $loja ) {
        $this->loja = $loja;
    }

    // uid
    public function setUid( $uid ) {
        $this->uid = $uid;
    }

    // token
    public function setToken( $token ) {
        $this->token = $token;
    }

    // cargo
    public function setCargo( $cargo ) {
        $this->cargo = $cargo;
    }    

    // nome
    public function setNome( $nome ) {
        $this->nome = $nome;
    }

    // cpf
    public function setCpf( $cpf ) {
        $this->cpf = $cpf;
    }

    // pontos
    public function setPontos( $pontos ) {
        $this->pontos = $pontos;
    }
}

/* end of file */
