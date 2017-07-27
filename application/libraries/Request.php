<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Request {

    // instancia do codeigniter
    public $ci;

    // usuario atualmente logado
    public $userData;

   /**
    * __construct
    *
    * método construtor
    *
    */
    public function __construct() {

        // pega a instancia do ci
        $this->ci =& get_instance();

        // carrega o usuario da requisicao
        $this->_loadUser();
    }

    /**
     * _loadUser
     *
     * carrega o usuario
     *
     */
    private function _loadUser() {

        // pega o header
        $uid   = $this->header( 'AUTH_UID' );
        $email = $this->header( 'AUTH_EMAIL' );

        // carrega o finder
        $this->ci->load->finder( 'FuncionariosFinder' );

        // carrega o usuario
        $this->userData = $this->ci->FuncionariosFinder->clean()->uid( $uid )->get( true );

        // verifica se carregou o usuario
        if ( $this->userData ) {

            // seta o email
            $this->userData->email = $email;
            $this->userData->save();
        }
    }

   /**
    * header
    *
    * pega o header da requisicao
    *
    */
    public function header( $name ) {

        // prepara o nome
        $f_name = strtoupper( $name );

        // pega pelo http
        $val = isset( $_SERVER['HTTP_'.$f_name] ) ? $_SERVER['HTTP_'.$f_name] : null;

        // pega pelo ci
        return $this->ci->input->get_request_header( $name ) ? $this->ci->input->get_request_header( $name ) : $val;
    }

   /**
    * user
    *
    * pega os dados do usuario logado
    *
    */
    public function user( $force = true ) {

        // verifica se existe o usuario
        if ( !$this->userData && $force ) {

            // volta acesso negado
            $this->ci->load->library( 'Response' );
            $this->ci->response->denied();
            exit();
        } else return $this->userData;
    }

}

/* end of file */