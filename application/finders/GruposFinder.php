<?php

require 'application/models/Grupo.php';

class GruposFinder extends MY_Model {

    // entidade
    public $entity = 'Grupo';

    // tabela
    public $table = 'Grupos';

    // chave primaria
    public $primaryKey = 'gid';

    // labels
    public $labels = [
        'grupo'   => 'Grupo',
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
    * getGrupo
    *
    * pega a instancia do grupo
    *
    */
    public function getGrupo() {
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
        ->select( 'gid as Código, grupo, gid as Ações' );
        return $this;
    }
}

/* end of file */
