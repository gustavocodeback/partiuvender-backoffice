<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Produtos extends MY_Controller {

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
        $this->load->finder( [ 'ProdutosFinder', 'CategoriasFinder' ] );

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
    private function _formularioProdutos() {

        // seta as regras
        $rules = [
            [
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'required|min_length[3]|max_length[32]|trim'
            ], [
                'field' => 'categoria',
                'label' => 'Categoria',
                'rules' => 'required'
            ], [
                'field' => 'descricao',
                'label' => 'Descricao',
                'rules' => 'required|min_length[20]|max_length[255]|trim'
            ],
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
		$this->ProdutosFinder->grid()

		// seta os filtros
        ->addFilter( 'nome', 'text' )
		->filter()
		->order()
		->paginate( 0, 20 )

		// seta as funcoes nas colunas
		->onApply( 'Ações', function( $row, $key ) {
			echo '<a href="'.site_url( 'produtos/alterar/'.$row['Código'] ).'" class="margin btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil"></span></a>';
			echo '<a href="'.site_url( 'produtos/excluir/'.$row['Código'] ).'" class="margin btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';            
		})

        // seta as funcoes nas colunas
		->onApply( 'Foto', function( $row, $key ) {
			echo '<img src="'.base_url( 'uploads/'.$row[$key] ).'" style="width: 50px; height: 50px;">';
		})

		// renderiza o grid
		->render( site_url( 'produtos/index' ) );
		
        // seta a url para adiciona
        $this->view->set( 'add_url', site_url( 'produtos/adicionar' ) );

		// seta o titulo da pagina
		$this->view->setTitle( 'Produtos - listagem' )->render( 'grid' );
    }

   /**
    * adicionar
    *
    * mostra o formulario de adicao
    *
    */
    public function adicionar() {

        // carrega os categorias
        $categorias = $this->CategoriasFinder->get();
        $this->view->set( 'categorias', $categorias );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Adicionar produto' )->render( 'forms/produto' );
    }

   /**
    * alterar
    *
    * mostra o formulario de edicao
    *
    */
    public function alterar( $key ) {

        // carrega os categorias
        $categorias = $this->CategoriasFinder->get();
        $this->view->set( 'categorias', $categorias );

        // carrega o cargo
        $produto = $this->ProdutosFinder->key( $key )->get( true );

        // verifica se o mesmo existe
        if ( !$produto ) {
            redirect( 'produtos/index' );
            exit();
        }

        // salva na view
        $this->view->set( 'produto', $produto );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Alterar produto' )->render( 'forms/produto' );
    }

   /**
    * excluir
    *
    * exclui um item
    *
    */
    public function excluir( $key ) {
        $produto = $this->ProdutosFinder->key( $key )->get( true );
        $this->picture->delete( $produto->foto );
        $produto->delete();
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
            $produto = $this->ProdutosFinder->key( $this->input->post( 'cod' ) )->get( true );
        } else {

            // instancia um novo objeto grpo
            $produto = $this->ProdutosFinder->getProduto();
        }

        $produto->setNome( $this->input->post( 'nome' ) );
        $produto->setCategoria( $this->input->post( 'categoria' ) );
        $produto->setDescricao( $this->input->post( 'descricao' ) );
        $produto->setPontos( $this->input->post( 'pontos' ) );
        $produto->setVideo( $this->input->post( 'video' ) );
        $produto->setCod( $this->input->post( 'cod' ) );

        if( !$file_name && !$produto->foto ) {
            $this->view->set( 'produto', $produto );
            $this->view->set( 'errors', 'Escolha uma foto!' );

            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Adicionar produto' )->render( 'forms/produto' );
            return;
        }

        if ( $file_name ) {
            $this->picture->delete( $produto->foto );
            $produto->setFoto( $file_name );
        }

        // verifica se o formulario é valido
        if ( !$this->_formularioProdutos() ) {

            // carrega os categorias
            $categorias = $this->CategoriasFinder->get();
            $this->view->set( 'categorias', $categorias );

            // seta os erros de validacao            
            $this->view->set( 'produto', $produto );
            $this->view->set( 'errors', validation_errors() );
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Adicionar produto' )->render( 'forms/produto' );
            return;
        }

        // verifica se o dado foi salvo
        if ( $produto->save() ) {
            redirect( site_url( 'produtos/index' ) );
        }
    }
}
