<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Categorias extends MY_Controller {

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
        $this->load->finder( [ 'CategoriasFinder' ] );

        // carrega a librarie de fotos
		$this->load->library( 'Picture' );
        
        // chama o modulo
        $this->view->module( 'navbar' )->module( 'aside' );
    }

   /**
    * _formularioEstados
    *
    * valida o formulario de estados
    *
    */
    private function _formularioCategorias() {

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
		$this->CategoriasFinder->grid()

		// seta os filtros
        ->addFilter( 'nome', 'text' )
		->filter()
		->order()
		->paginate( 0, 20 )

		// seta as funcoes nas colunas
		->onApply( 'Ações', function( $row, $key ) {
			echo '<a href="'.site_url( 'categorias/alterar/'.$row['Código'] ).'" class="margin btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil"></span></a>';
			echo '<a href="'.site_url( 'categorias/excluir/'.$row['Código'] ).'" class="margin btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';            
		})

        // seta as funcoes nas colunas
		->onApply( 'Foto', function( $row, $key ) {
			echo '<img src="'.base_url( 'uploads/'.$row[$key] ).'" style="width: 50px; height: 50px;">';
		})

		// renderiza o grid
		->render( site_url( 'categorias/index' ) );
		
        // seta a url para adiciona
        $this->view->set( 'add_url', site_url( 'categorias/adicionar' ) );

		// seta o titulo da pagina
		$this->view->setTitle( 'Categorias - listagem' )->render( 'grid' );
    }

   /**
    * adicionar
    *
    * mostra o formulario de adicao
    *
    */
    public function adicionar() {

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Adicionar categoria' )->render( 'forms/categoria' );
    }

   /**
    * alterar
    *
    * mostra o formulario de edicao
    *
    */
    public function alterar( $key ) {

        // carrega o cargo
        $categoria = $this->CategoriasFinder->key( $key )->get( true );

        // verifica se o mesmo existe
        if ( !$categoria ) {
            redirect( 'categorias/index' );
            exit();
        }

        // salva na view
        $this->view->set( 'categoria', $categoria );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Alterar categoria' )->render( 'forms/categoria' );
    }

   /**
    * excluir
    *
    * exclui um item
    *
    */
    public function excluir( $key ) {
        $categoria = $this->CategoriasFinder->key( $key )->get( true );
        $this->picture->delete( $categoria->foto );
        $categoria->delete();
        $this->index();
    }

   /**
    * salvar
    *
    * salva os dados
    *
    */
    public function salvar() {

        // faz o upload da imagem
        $file_name = $this->picture->upload( 'foto', [ 'square' => 200 ] );

        if ( $this->input->post( 'cod' ) ) {
            $categoria = $this->CategoriasFinder->key( $this->input->post( 'cod' ) )->get( true );
        } else {

            // instancia um novo objeto grpo
            $categoria = $this->CategoriasFinder->getCategoria();
        }

        $categoria->setNome( $this->input->post( 'nome' ) );
        $categoria->setCod( $this->input->post( 'cod' ) );

        if( !$file_name && !$categoria->foto ) {
            $this->view->set( 'categoria', $categoria );
            $this->view->set( 'errors', 'Escolha uma foto!' );
            return;
        }

        if ( $file_name ) {
            $this->picture->delete( $categoria->foto );
            $categoria->setFoto( $file_name );
        }

        // verifica se o formulario é valido
        if ( !$this->_formularioCategorias() ) {

            // seta os erros de validacao            
            $this->view->set( 'categoria', $categoria );
            $this->view->set( 'errors', validation_errors() );
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Adicionar categoria' )->render( 'forms/categoria' );
            return;
        }

        // verifica se o dado foi salvo
        if ( $categoria->save() ) {
            redirect( site_url( 'categorias/index' ) );
        }
    }
}
