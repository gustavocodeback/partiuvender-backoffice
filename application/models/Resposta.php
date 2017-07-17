<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Resposta extends MY_Model {

    // id do estado
    public $CodResposta;

    // usuario
    public $usuario;

    // pergunta
    public $pergunta;

    // alternativa
    public $alternativa;

    // entidade
    public $entity = 'Resposta';
    
    // tabela
    public $table = 'Respostas';

    // chave primaria
    public $primaryKey = 'CodResposta';

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
        $this->CodResposta = $cod;
        return $this;
    }

    // usuario
    public  function setUsuario( $usuario ) {
        $this->usuario = $usuario;
        return $this;
    }

    // pergunta
    public function setPergunta( $pergunta ) {
        $this->pergunta = $pergunta;
        return $this;
    }

    // alternativa
    public function setAlternativa( $alternativa ) {
        $this->alternativa = $alternativa;
        return $this;
    }
}

/* end of file */
