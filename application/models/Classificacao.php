<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Classificacao extends MY_Model {

    // id da classificacao
    public $CodClassificacao;

    // nome
    public $nome;

    // icone
    public $icone;

    // ordem
    public $ordem;

    // entidade
    public $entity = 'Classificacao';
    
    // tabela
    public $table = 'Classificacoes';

    // chave primaria
    public $primaryKey = 'CodClassificacao';

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
    * setCod
    *
    * seta o cod
    *
    */
    public function setCod( $cod ) {
        $this->CodClassificacao = $cod;
    }

   /**
    * nome
    *
    * seta o nome
    *
    */
    public function setNome( $nome ) {
        $this->nome = $nome;
    }

    /**
    * setIcone
    *
    * seta o icone
    *
    */
    public function setIcone( $icone ) {
        $this->icone = $icone;
    }

   /**
    * setOrdem
    *
    * seta a ordem
    *
    */
    public function setOrdem( $ordem ) {
        $this->ordem = $ordem;
    }
}

/* end of file */
