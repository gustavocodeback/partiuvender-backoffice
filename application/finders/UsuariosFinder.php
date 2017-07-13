<?php

require 'application/models/Usuario.php';

class UsuariosFinder extends MY_Model {

    // entidade
    public $entity = 'Usuario';

    // tabela
    public $table = 'Usuarios';

    // chave primaria
    public $primaryKey = 'uid';

    // labels
    public $labels = [
        'email'  => 'E-mail',
        'gid' => 'Cargo',
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
    * getUsuario
    *
    * pega a instancia do usuario
    *
    */
    public function getUsuario() {
        return new $this->entity();
    }

   /**
    * grid
    *
    * funcao usada para gerar o grid
    *
    */
    public function grid() {
        $this->db->from( $this->table.' u' )
        ->select( 'u.uid as Código, u.email as Email, g.grupo as Cargo, u.uid as Ações' )
        ->join( 'Grupos g', 'g.gid = u.gid', 'left' );
        return $this;
    }
}

/* end of file */
