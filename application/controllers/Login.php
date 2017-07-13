<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

    // indica se o controller é publico
	protected $public = true;

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
    * index
    *
    * mostra o formulario de login
    *
    */
	public function index() {
        
        // renderiza a view de login
        $this->view->setTitle( 'Samsung - Login' )->render( 'login' );
    }

   /**
    * logar
    *
    * faz o login
    *
    */
    public function logar() {

        // pega o email e a senha
        $email = $this->input->post( 'email' );
        $senha = $this->input->post( 'senha' );

        // tenta fazer o login
        if( !$this->guard->loginWithEmailAndPassword( $email, $senha ) ) {

            // seta o erro
            $erro = $this->guard->cause()['message'];
            $this->view->set( 'error', $erro );

            // chama o index
            $this->index();
            return false;
        } else redirect( site_url( 'dashboard' ) );
    }
}
