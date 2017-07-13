<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rotina extends MY_Model {

    // id da rotina
    public $rid;

    // rotina
    public $rotina;

    // classificacao
    public $classificacao;

    // link
    public $link;

    // entidade
    public $entity = 'Rotina';
    
    // tabela
    public $table = 'Rotinas';

    // chave primaria
    public $primaryKey = 'rid';

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
    * setRid
    *
    * seta o rid
    *
    */
    public function setRid( $rid ) {
        $this->rid = $rid;
    }

   /**
    * setRotina
    *
    * seta a rotina
    *
    */
    public function setRotina( $rotina ) {
        $this->rotina = $rotina;
    }

   /**
    * setClassificacao
    *
    * seta a classificacao
    *
    */
    public function setClassificacao( $clas ) {
        $this->classificacao = $clas;
    }

   /**
    * setLink
    *
    * seta o link
    *
    */
    public function setLink( $link ) {
        $this->link = $link;
    }
}

/* end of file */
