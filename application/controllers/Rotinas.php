<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rotinas extends MY_Controller {

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
        $this->load->finder( [ 'RotinasFinder', 'ClassificacoesFinder' ] );
        
        // chama o modulo
        $this->view->module( 'navbar' )->module( 'aside' );
    }

   /**
    * _formulariorotinas
    *
    * valida o formulario de rotinas
    *
    */
    private function _formularioRotinas() {

        // seta as regras
        $rules = [
            [
                'field' => 'rotina',
                'label' => 'Rotinas',
                'rules' => 'required|min_length[2]|max_length[30]'
            ]
        ];

        // valida o formulário
        $this->form_validation->set_rules( $rules );
        return $this->form_validation->run();
    }

   /**
    * index
    *
    * mostra o grid de rotinas
    *
    */
	public function index() {

        // faz a paginacao
		$this->RotinasFinder->grid()

		// seta os filtros
		->order()
		->paginate( 0, 20 )

		// seta as funcoes nas colunas
		->onApply( 'Ações', function( $row, $key ) {
			echo '<a href="'.site_url( 'rotinas/alterar/'.$row['Código'] ).'" class="margin btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil"></span></a>';
			echo '<a href="'.site_url( 'rotinas/excluir/'.$row['Código'] ).'" class="margin btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';            
		})

		// renderiza o grid
		->render( site_url( 'rotinas/index' ) );
		
        // seta a url para adiciona
        $this->view->set( 'add_url', site_url( 'rotinas/adicionar' ) );

		// seta o titulo da pagina
		$this->view->setTitle( 'rotinas - listagem' )->render( 'grid' );
    }

   /**
    * adicionar
    *
    * mostra o formulario de adicao
    *
    */
    public function adicionar() {

        // carrega as classificacoes
        $class = $this->ClassificacoesFinder->get();

        // seta a view
        $this->view->set( 'class', $class );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Adicionar rotina' )->render( 'forms/rotina' );
    }

   /**
    * alterar
    *
    * mostra o formulario de edicao
    *
    */
    public function alterar( $key ) {

        // carrega as classificacoes
        $class = $this->ClassificacoesFinder->get();

        // seta a view
        $this->view->set( 'class', $class );

        // carrega o cargo
        $rotina = $this->RotinasFinder->key( $key )->get( true );

        // verifica se o mesmo existe
        if ( !$rotina ) {
            redirect( 'rotinas/index' );
            exit();
        }

        // salva na view
        $this->view->set( 'rotina', $rotina );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Adicionar rotina' )->render( 'forms/rotina' );
    }

   /**
    * excluir
    *
    * exclui um item
    *
    */
    public function excluir( $key ) {
        $rotina = $this->RotinasFinder->getRotina();
        $rotina->setRid( $key );
        $rotina->delete();
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
        $grupo = $this->RotinasFinder->getRotina();
        $grupo->setRotina( $this->input->post( 'rotina' ) );
        $grupo->setClassificacao( $this->input->post( 'classificacao' ) );
        $grupo->setLink( $this->input->post( 'link' ) );
        $grupo->setRid( $this->input->post( 'cod' ) );

        // verifica se o formulario é valido
        if ( !$this->_formularioRotinas() ) {

            // seta os erros de validacao            
            $this->view->set( 'cargo', $grupo );
            $this->view->set( 'errors', validation_errors() );
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Adicionar cargo' )->render( 'forms/rotina' );
            return;
        }

        // verifica se o dado foi salvo
        if ( $grupo->save() ) {
            redirect( site_url( 'rotinas/index' ) );
        }
    }
}
