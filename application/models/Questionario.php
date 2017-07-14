<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Questionario extends MY_Model {

    // id do estado
    public $CodQuestionario;

    // nome
    public $nome;

    // foto
    public $foto;

    // descricao
    public $descricao;

    // entidade
    public $entity = 'Questionario';
    
    // tabela
    public $table = 'Questionarios';

    // chave primaria
    public $primaryKey = 'CodQuestionario';

   /**
    * __construct
    *
    * metodo construtor
    *
    */
    public function __construct() {
        parent::__construct();
    }
    
    // codigo
    public function setCod( $cod ) {
        $this->CodQuestionario = $cod;
        return $this;        
    }

    // nome
    public function setNome( $nome ) {
        $this->nome = $nome;
        return $this;        
    }

    // foto
    public function setFoto( $foto ) {
        $this->foto = $foto;
        return $this;        
    }
    
    // descricao
    public function setDescricao( $desc ) {
        $this->descricao = $desc;
        return $this;
    }
}

/* end of file */
