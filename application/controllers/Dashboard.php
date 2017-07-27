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
    * parseChart
    *
    * metodo construtor
    *
    */
    private function parseChart( $data, $key = 'Pontos' ) {

        // seta o array de response
        $r = [];

        // percorre os dados
        foreach( $data as $item ) {
            $d = [
                'label' => $item['Nome'],
                'value' => $item[$key] ? $item[$key] : 0
            ];
            $r[] = $d;
        }

        // volta os dados formatados
        return $r;
    }


   /**
    * index
    *
    * mostra o formulario de login
    *
    */
	public function index() {

        // carrega os finders
        $this->load->finder( [ 'FuncionariosFinder', 'LojasFinder', 'ClustersFinder' ] );

        // pega o total de funcionarios
        $total = $this->FuncionariosFinder->count();
        $this->view->set( 'num_func', $total );

        // pega o total de funcionarios
        $total = $this->FuncionariosFinder->countLogged();
        $this->view->set( 'num_func_logado', $total );

        // pega o total de funcionarios
        $total = $this->LojasFinder->count();
        $this->view->set( 'num_lojas', $total );

        // pega os cluster
        $clusters = $this->ClustersFinder->get();
        $data = [];
        $itens = [];
        foreach( $clusters as $cluster ) {

            // pega o ranking
            $ranking = $cluster->obterPrimeirosColocados();
            $dados   = $this->parseChart( $ranking, 'Cociente' );
            $itens[$cluster->nome] = $dados;

            // monta dos funcionarios
            $dados = $this->FuncionariosFinder->rankingCluster( $cluster->CodCluster );
            $dados = $this->parseChart( $dados );
            $data[$cluster->nome] = $dados;
        }
        $this->view->set( 'clusters', $data );
        $this->view->set( 'lojas', $itens );

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
