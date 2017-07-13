<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Permissoes extends MY_Controller {

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
        
        // carrega o finder
        $this->load->finder( [ 'GruposFinder', 'RotinasFinder' ] );
        
        // chama o modulo
        $this->view->module( 'navbar' )->module( 'aside' );
    }

    private function _salvarPermissao( $rid, $gid ) {

        // prepara os dados
        $dados = [
            'rid'    => $rid,
            'gid'    => $gid,
            'access' => 'S'
        ];

        // salva no banco
        return $this->db->insert( 'Permissoes', $dados );
    }

   /**
    * index
    *
    * mostra o grid de rotinas
    *
    */
	public function index() {

        // pega os cargos e as rotinas
        $cargos  = $this->GruposFinder->get();
        $rotinas = $this->RotinasFinder->get(); 

        // seta os dados
        $this->view->set( 'cargos', $cargos );
        $this->view->set( 'rotinas', $rotinas );

        // renderiza a pagina
        $this->view->setTitle( 'Níveis de acesso' )->render( 'forms/permissao' );
    }

   /**
    * deleteAll
    *
    * remove todos os dados da tabela
    *
    */
    private function deleteAll() {
        $this->db->empty_table( 'Permissoes' );
    }

   /**
    * salvar
    *
    * salva os dados
    *
    */
    public function salvar() {

        // pega os checkbox
        $check = $this->input->post( 'permissoes' );
        
        // deleta as permissoes atuais
        $this->deleteAll();

        // percorre todas as permissoes
        foreach( $check as $item ) {

            // faz o explode
            $parts = explode( '_', $item );

            // salva a permissao
            $this->_salvarPermissao( $parts[0], $parts[1] );
        }

        // mostra o index
        $this->index();
    }
}
