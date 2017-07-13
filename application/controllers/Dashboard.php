<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    // indica se o controller é publico
	protected $public = false;

    /**
    * __construct
    *
    * metodo construtor
    *
    */
    public function __construct() {
        parent::__construct();

         // chama o modulo
        $this->view->module( 'navbar' )->module( 'aside' );
    }
    
   /**
    * index
    *
    * mostra o formulario de login
    *
    */
	public function index() {

        // renderiza a view de login
        $this->view->setTitle( 'Samsung - Painel de controle' )->render( 'dashboard' );
    }

    /**
    * logout
    *
    * faz o logout
    *
    */
    public function logout() {

        // faz o logout
        $this->guard->logout();

        // carrega a pagina de login
        redirect( site_url( 'login' ) );
    }
}
