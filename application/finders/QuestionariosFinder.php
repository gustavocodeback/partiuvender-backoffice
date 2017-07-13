<?php

require 'application/models/Questionario.php';

class QuestionariosFinder extends MY_Model {

    // entidade
    public $entity = 'Questionario';

    // tabela
    public $table = 'Questionarios';

    // chave primaria
    public $primaryKey = 'CodQuestionario';

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
    public function getQuestionario() {
        return new $this->entity();
    }

   /**
    * grid
    *
    * funcao usada para gerar o grid
    *
    */
    public function grid() {
        $this->db->from( $this->table )
        ->select( 'CodQuestionario as Código, Nome, Foto, CodQuestionario as Ações' );
        return $this;
    }
}

/* end of file */
