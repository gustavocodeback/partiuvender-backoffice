<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Grupo extends MY_Model {

    // id do grupo
    public $gid;

    // grupo
    public $grupo;

    // entidade
    public $entity = 'Grupo';
    
    // tabela
    public $table = 'Grupos';

    // chave primaria
    public $primaryKey = 'gid';

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
    * setGid
    *
    * seta o gid
    *
    */
    public function setGid( $gid ) {
        $this->gid = $gid;
    }

   /**
    * setGrupo
    *
    * seta o grupo
    *
    */
    public function setGrupo( $grupo ) {
        $this->grupo = $grupo;
    }
}

/* end of file */
