<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Clusters extends MY_Controller {

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
        $this->load->finder( [ 'ClustersFinder' ] );
        
        // chama o modulo
        $this->view->module( 'navbar' )->module( 'aside' );
    }

   /**
    * _formularioCluster
    *
    * valida o formulario de estados
    *
    */
    private function _formularioCluster() {

        // seta as regras
        $rules = [
            [
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'required|min_length[3]|max_length[32]|trim'
            ]
        ];

        // valida o formulário
        $this->form_validation->set_rules( $rules );
        return $this->form_validation->run();
    }

   /**
    * index
    *
    * mostra o grid de contadores
    *
    */
	public function index() {

        // faz a paginacao
		$this->ClustersFinder->grid()

		// seta os filtros
        ->addFilter( 'nome', 'text' )
		->filter()
		->order()
		->paginate( 0, 20 )

		// seta as funcoes nas colunas
		->onApply( 'Ações', function( $row, $key ) {
			echo '<a href="'.site_url( 'clusters/alterar/'.$row['Código'] ).'" class="margin btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil"></span></a>';
			echo '<a href="'.site_url( 'clusters/excluir/'.$row['Código'] ).'" class="margin btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';            
		})

		// renderiza o grid
		->render( site_url( 'clusters/index' ) );
		
        // seta a url para adiciona
        $this->view
        ->set( 'add_url', site_url( 'clusters/adicionar' ) )
        ->set( 'import_url', site_url( 'clusters/importar_planilha' ) );

		// seta o titulo da pagina
		$this->view->setTitle( 'Clusters - Listagem' )->render( 'grid' );
    }

   /**
    * adicionar
    *
    * mostra o formulario de adicao
    *
    */
    public function adicionar() {

        // carrega a view de adicionar
        $this->view->setTitle( 'Adicionar cluster' )->render( 'forms/cluster' );
    }

   /**
    * alterar
    *
    * mostra o formulario de edicao
    *
    */
    public function alterar( $key ) {

        // carrega o cargo
        $cluster = $this->ClustersFinder->key( $key )->get( true );

        // verifica se o mesmo existe
        if ( !$cluster ) {
            redirect( 'clusters/index' );
            exit();
        }

        // salva na view
        $this->view->set( 'cluster', $cluster );

        // carrega a view de adicionar
        $this->view->setTitle( 'Alterar cluster' )->render( 'forms/cluster' );
    }

   /**
    * excluir
    *
    * exclui um item
    *
    */
    public function excluir( $key ) {
        $cluster = $this->ClustersFinder->getCluster();
        $cluster->setCod( $key );
        $cluster->delete();
        $this->index();
    }

   /**
    * salvar
    *
    * salva os dados
    *
    */
    public function salvar() {

        // instancia um novo objeto grupo
        $cluster = $this->ClustersFinder->getCluster();
        $cluster->setNome( $this->input->post( 'nome' ) );
        $cluster->setCod( $this->input->post( 'cod' ) );

        // verifica se o formulario é valido
        if ( !$this->_formularioCluster() ) {

            // seta os erros de validacao            
            $this->view->set( 'cluster', $cluster );
            $this->view->set( 'errors', validation_errors() );
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Adicionar cluster' )->render( 'forms/cluster' );
            return;
        }

        // verifica se o dado foi salvo
        if ( $cluster->save() ) {
            redirect( site_url( 'clusters' ) );
        }
    }

    public function importar_planilha() {

        // importa a planilha
        $this->load->library( 'Planilhas' );

        // faz o upload da planilha
        $planilha = $this->planilhas->upload();

        // tenta fazer o upload
        if ( !$planilha ) {

            // seta os erros
            $this->view->set( 'errors', $this->planilhas->errors );
        }

        $planilha->excluir();
    }
}
