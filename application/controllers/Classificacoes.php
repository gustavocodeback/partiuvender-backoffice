<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Classificacoes extends MY_Controller {

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
        $this->load->finder( 'ClassificacoesFinder' );
        
        // chama o modulo
        $this->view->module( 'navbar' )->module( 'aside' );
    }

   /**
    * _formularioClassificacoes
    *
    * valida o formulario de classificacoes
    *
    */
    private function _formularioClassificacoes() {

        // seta as regras
        $rules = [
            [
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'required|min_length[2]|max_length[30]'
            ], [
                'field' => 'icone',
                'label' => 'Icone',
                'rules' => 'required|min_length[2]|max_length[30]'
            ], [
                'field' => 'ordem',
                'label' => 'Ordem',
                'rules' => 'required|numeric|max_length[30]'
            ]
        ];

        // valida o formulário
        $this->form_validation->set_rules( $rules );
        return $this->form_validation->run();
    }

   /**
    * index
    *
    * mostra o grid de classificacoes
    *
    */
	public function index() {

        // faz a paginacao
		$this->ClassificacoesFinder->grid()

		// seta os filtros
		->order()
		->paginate( 0, 20 )

		// seta as funcoes nas colunas
		->onApply( 'Ações', function( $row, $key ) {
			echo '<a href="'.site_url( 'classificacoes/alterar/'.$row['Código'] ).'" class="margin btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil"></span></a>';
			echo '<a href="'.site_url( 'classificacoes/excluir/'.$row['Código'] ).'" class="margin btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';            
		})
        ->onApply( 'Icone', function( $row, $key ) {
            echo '<span class="glyphicon glyphicon-'.$row['Icone'].'"></span>';
        })

		// renderiza o grid
		->render( site_url( 'classificacoes/index' ) );
		
        // seta a url para adiciona
        $this->view->set( 'add_url', site_url( 'classificacoes/adicionar' ) );

		// seta o titulo da pagina
		$this->view->setTitle( 'classificacoes - listagem' )->render( 'grid' );
    }

   /**
    * adicionar
    *
    * mostra o formulario de adicao
    *
    */
    public function adicionar() {

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Adicionar classificacao' )->render( 'forms/classificacao' );
    }

   /**
    * alterar
    *
    * mostra o formulario de edicao
    *
    */
    public function alterar( $key ) {

        // carrega o classificacao
        $classificacao = $this->ClassificacoesFinder->key( $key )->get( true );

        // verifica se o mesmo existe
        if ( !$classificacao ) {
            redirect( 'classificacoes/index' );
            exit();
        }

        // salva na view
        $this->view->set( 'classificacao', $classificacao );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Adicionar classificacao' )->render( 'forms/classificacao' );
    }

   /**
    * excluir
    *
    * exclui um item
    *
    */
    public function excluir( $key ) {
        $classificacao = $this->ClassificacoesFinder->getClassificacao();
        $classificacao->setCod( $key );
        $classificacao->delete();

        $this->index();
    }

   /**
    * salvar
    *
    * salva os dados
    *
    */
    public function salvar() {

        // instancia um novo objeto classificacao
        $classificacao = $this->ClassificacoesFinder->getClassificacao();
        $classificacao->setNome( $this->input->post( 'nome' ) );
        $classificacao->setIcone( $this->input->post( 'icone' ) );
        $classificacao->setOrdem( $this->input->post( 'ordem' ) );
        $classificacao->setCod( $this->input->post( 'cod' ) );

        // verifica se o formulario é valido
        if ( !$this->_formularioClassificacoes() ) {

            // seta os erros de validacao            
            $this->view->set( 'classificacao', $classificacao );
            $this->view->set( 'errors', validation_errors() );
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Adicionar classificacao' )->render( 'forms/classificacao' );
            return;
        }

        // verifica se o dado foi salvo
        if ( $classificacao->save() ) {
            redirect( site_url( 'classificacoes/index' ) );
        }
    }
}
