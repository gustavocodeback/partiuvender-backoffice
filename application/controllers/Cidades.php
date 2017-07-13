<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cidades extends MY_Controller {

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
        $this->load->finder( [ 'CidadesFinder', 'EstadosFinder' ] );
        
        // chama o modulo
        $this->view->module( 'navbar' )->module( 'aside' );
    }

   /**
    * _formularioEstados
    *
    * valida o formulario de estados
    *
    */
    private function _formularioCidade() {

        // seta as regras
        $rules = [
            [
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'required|min_length[3]|max_length[32]|trim'
            ],[
                'field' => 'estado',
                'label' => 'Estado',
                'rules' => 'required|min_length[1]|max_length[2]|trim'
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
		$this->CidadesFinder->grid()

		// seta os filtros
        ->addFilter( 'nome', 'text' )
		->filter()
		->order()
		->paginate( 0, 20 )

		// seta as funcoes nas colunas
		->onApply( 'Ações', function( $row, $key ) {
			echo '<a href="'.site_url( 'cidades/alterar/'.$row['Código'] ).'" class="margin btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil"></span></a>';
			echo '<a href="'.site_url( 'cidades/excluir/'.$row['Código'] ).'" class="margin btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';            
		})

		// renderiza o grid
		->render( site_url( 'cidades/index' ) );
		
        // seta a url para adiciona
        $this->view->set( 'add_url', site_url( 'cidades/adicionar' ) );

		// seta o titulo da pagina
		$this->view->setTitle( 'Cidades - listagem' )->render( 'grid' );
    }

   /**
    * adicionar
    *
    * mostra o formulario de adicao
    *
    */
    public function adicionar() {

        // carrega os estados
        $estados = $this->EstadosFinder->get();
        $this->view->set( 'estados', $estados );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Adicionar cidade' )->render( 'forms/cidade' );
    }

   /**
    * alterar
    *
    * mostra o formulario de edicao
    *
    */
    public function alterar( $key ) {

        // carrega os estados
        $estados = $this->EstadosFinder->get();
        $this->view->set( 'estados', $estados );

        // carrega o cargo
        $cidade = $this->CidadesFinder->key( $key )->get( true );

        // verifica se o mesmo existe
        if ( !$cidade ) {
            redirect( 'estados/index' );
            exit();
        }

        // salva na view
        $this->view->set( 'cidade', $cidade );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Alterar cidade' )->render( 'forms/cidade' );
    }

   /**
    * excluir
    *
    * exclui um item
    *
    */
    public function excluir( $key ) {
        $grupo = $this->CidadesFinder->getCidade();
        $grupo->setCod( $key );
        $grupo->delete();
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
        $cidade = $this->CidadesFinder->getCidade();
        $cidade->setNome( $this->input->post( 'nome' ) );
        $cidade->setEstado( $this->input->post( 'estado' ) );
        $cidade->setCod( $this->input->post( 'cod' ) );

        // verifica se o formulario é valido
        if ( !$this->_formularioCidade() ) {

            // seta os erros de validacao            
            $this->view->set( 'cidade', $cidade );
            $this->view->set( 'errors', validation_errors() );
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Adicionar cidade' )->render( 'forms/cidade' );
            return;
        }

        // verifica se o dado foi salvo
        if ( $cidade->save() ) {
            redirect( site_url( 'cidades/index' ) );
        }
    }
}
