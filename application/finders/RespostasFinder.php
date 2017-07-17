<?php

require 'application/models/Resposta.php';

class RespostasFinder extends MY_Model {

    // entidade
    public $entity = 'Resposta';

    // tabela
    public $table = 'Respostas';

    // chave primaria
    public $primaryKey = 'CodResposta';

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
    * getEstado
    *
    * pega a instancia do estado
    *
    */
    public function getResposta() {
        return new $this->entity();
    }

    // filtra por pergunta
    public function pergunta( $pergunta ) {
        $this->where( " CodPergunta = '$pergunta' " );
        return $this;
    }

    // filtra por funcionario
    public function func( $func ) {
        $this->where( " CodUsuario = '$func' " );
        return $this;
    }
}

/* end of file */
