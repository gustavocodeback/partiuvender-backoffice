<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends MY_Model {

    // id do usuario
    public $uid;

    // id do grupo
    public $gid;

    // email
    public $email;

    // senha
    public $senha;

    // entidade
    public $entity = 'Usuario';
    
    // tabela
    public $table = 'Usuarios';

    // chave primaria
    public $primaryKey = 'uid';

   /**
    * __construct
    *
    * metodo construtor
    *
    */
    public function __construct() {
        parent::__construct();
    }
    
    // seta o codigo
    public function setCod( $uid ) {
        $this->uid = $uid;
    }

    // gid
    public function setGid( $gid ) {
        $this->gid = $gid;
    }

    // email
    public function setEmail( $email ) {
        $this->email = $email;
    }

    // senha
    public function setSenha( $senha ) {
        $this->senha = $senha;
    }

    // adiciona uma key
    public function addKey() {

        // carrega a lib
        $this->load->library( 'Guard' );

        // gera a key
        $this->uid = $this->guard->_generateKey();
    }
}

/* end of file */
