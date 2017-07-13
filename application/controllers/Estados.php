<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Estados extends MY_Controller {

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
        $this->load->finder( [ 'EstadosFinder', 'GruposFinder' ] );
        
        // chama o modulo
        $this->view->module( 'navbar' )->module( 'aside' );
    }

   /**
    * _formularioEstados
    *
    * valida o formulario de estados
    *
    */
    private function _formularioEstados() {

        // seta as regras
        $rules = [
            [
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'required|min_length[3]|max_length[32]|trim'
            ],[
                'field' => 'uf',
                'label' => 'UF',
                'rules' => 'required|min_length[2]|max_length[2]|trim'
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
		$this->EstadosFinder->grid()

		// seta os filtros
        ->addFilter( 'nome', 'text' )
		->filter()
		->order()
		->paginate( 0, 20 )

		// seta as funcoes nas colunas
		->onApply( 'Ações', function( $row, $key ) {
			echo '<a href="'.site_url( 'estados/alterar/'.$row['Código'] ).'" class="margin btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil"></span></a>';
			echo '<a href="'.site_url( 'estados/excluir/'.$row['Código'] ).'" class="margin btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';            
		})

		// renderiza o grid
		->render( site_url( 'estados/index' ) );
		
        // seta a url para adiciona
        $this->view->set( 'add_url', site_url( 'estados/adicionar' ) );

		// seta o titulo da pagina
		$this->view->setTitle( 'Estados - listagem' )->render( 'grid' );
    }

   /**
    * adicionar
    *
    * mostra o formulario de adicao
    *
    */
    public function adicionar() {

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Adicionar estado' )->render( 'forms/estado' );
    }

   /**
    * alterar
    *
    * mostra o formulario de edicao
    *
    */
    public function alterar( $key ) {

        // carrega o cargo
        $estado = $this->EstadosFinder->key( $key )->get( true );

        // verifica se o mesmo existe
        if ( !$estado ) {
            redirect( 'estados/index' );
            exit();
        }

        // salva na view
        $this->view->set( 'estado', $estado );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Alterar estado' )->render( 'forms/estado' );
    }

   /**
    * excluir
    *
    * exclui um item
    *
    */
    public function excluir( $key ) {
        $grupo = $this->EstadosFinder->getEstado();
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
        $estado = $this->EstadosFinder->getEstado();
        $estado->setNome( $this->input->post( 'nome' ) );
        $estado->setUf( $this->input->post( 'uf' ) );
        $estado->setCod( $this->input->post( 'cod' ) );

        // verifica se o formulario é valido
        if ( !$this->_formularioEstados() ) {

            // seta os erros de validacao            
            $this->view->set( 'estado', $estado );
            $this->view->set( 'errors', validation_errors() );
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Adicionar estado' )->render( 'forms/estado' );
            return;
        }

        // verifica se o dado foi salvo
        if ( $estado->save() ) {
            redirect( site_url( 'estados/index' ) );
        }
    }
}
