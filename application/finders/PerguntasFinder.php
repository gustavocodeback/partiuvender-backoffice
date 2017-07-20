<?php

require 'application/models/Pergunta.php';

class PerguntasFinder extends MY_Model {

    // entidade
    public $entity = 'Pergunta';

    // tabela
    public $table = 'Perguntas';

    // chave primaria
    public $primaryKey = 'CodPergunta';

    // labels
    public $labels = [
        'CodQuestionario' => 'Questionario'
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
    * getEstado
    *
    * pega a instancia do estado
    *
    */
    public function getPergunta() {
        return new $this->entity();
    }

    public function grid() {
        $this->db->from( $this->table.' p' )
        ->select( 'p.CodPergunta as Código, p.Texto, q.Nome, p.CodPergunta as Ações' )
        ->join( 'Questionarios q', 'p.CodQuestionario = q.CodQuestionario', 'left' );
        return $this;
    }

    public function quiz( $id ) {
        $this->where( " CodQuestionario = $id " );
        return $this;
    }
}

/* end of file */
