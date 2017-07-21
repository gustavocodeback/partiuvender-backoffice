<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Response {

    // instancia do ci
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
    * show
    *
    * exibne os dados
    *
    */
    public function show( $data ) {

        // seta o atributo
        $data['notificacoes'] = 0;

        // carrega a library de request
        $this->ci->load->library( 'Request' );

        // pega o header
        $uid   = $this->ci->request->header( 'AUTH_UID' );
        $email = $this->ci->request->header( 'AUTH_EMAIL' );

        // verifica se existe uid
        if ( $uid ) {

            // carrega o finder
            $this->ci->load->finder( 'FuncionariosFinder' );

            // carrega o usuario
            $func = $this->ci->FuncionariosFinder->clean()->uid( $uid )->get( true );
        
            // pega as notificacoes
            if ( $func )
                $data['notificacoes'] = $func->naoLidas();
            else
                $data['notificacoes'] = 0;
        }
        
        // envia os dados
        echo json_encode( $data );
        return;
    }

   /**
    * denied
    *
    * volta acesso negado
    *
    */
    public function denied() {
        
        // prepara os dados
        $data = [
            'code'    => '403',
            'message' => 'Acesso negado'
        ];

        // envia a resposta
        return $this->show( $data );
    }

   /**
    * reject
    *
    * volta um erro
    *
    */
    public function reject( $msg ) {
        
        // prepara os dados
        $data = [
            'code' => '400',
            'message' => $msg
        ];

        // envia a resposta
        return $this->show( $data );
    }

   /**
    * resolve
    *
    * volta sucesso
    *
    */
    public function resolve( $data ) {

        // prepara os dados
        $data = [
            'code' => '200',
            'data' => $data
        ];

        // envia a resposta
        return $this->show( $data );
    }
}

/* end of file */