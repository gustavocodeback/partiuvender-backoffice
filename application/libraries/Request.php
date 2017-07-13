<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Request {

    // instancia do codeigniter
    public $ci;

   /**
    * __construct
    *
    * método construtor
    *
    */
    public function __construct() {

        // pega a instancia do ci
        $this->ci =& get_instance();
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
}

/* end of file */