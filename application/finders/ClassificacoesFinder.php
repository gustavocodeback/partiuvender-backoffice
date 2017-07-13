<?php

require 'application/models/Classificacao.php';

class ClassificacoesFinder extends MY_Model {

    // entidade
    public $entity = 'Classificacao';

    // tabela
    public $table = 'Classificacoes';

    // chave primaria
    public $primaryKey = 'CodClassificacao';

    // labels
    public $labels = [
        'Nome'  => 'Nome',
        'Icone' => 'Icone',
        'Ordem' => 'Ordem',
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
    * getClassificacao
    *
    * pega a instancia da classificacao
    *
    */
    public function getClassificacao() {
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
        ->select( 'CodClassificacao as Código, Nome, Icone, Ordem, CodClassificacao as Ações' );
        return $this;
    }
}

/* end of file */
