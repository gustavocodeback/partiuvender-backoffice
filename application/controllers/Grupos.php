<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Grupos extends MY_Controller {

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
        $this->load->finder( 'GruposFinder' );
        
        // chama o modulo
        $this->view->module( 'navbar' )->module( 'aside' );
    }

   /**
    * _formularioGrupos
    *
    * valida o formulario de grupos
    *
    */
    private function _formularioGrupos() {

        // seta as regras
        $rules = [
            [
                'field' => 'grupo',
                'label' => 'Grupo',
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
    * mostra o grid de grupos
    *
    */
	public function index() {

        // faz a paginacao
		$this->GruposFinder->grid()

		// seta os filtros
		->order()
		->paginate( 0, 20 )

		// seta as funcoes nas colunas
		->onApply( 'Ações', function( $row, $key ) {
			echo '<a href="'.site_url( 'grupos/alterar/'.$row['Código'] ).'" class="margin btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil"></span></a>';
			echo '<a href="'.site_url( 'grupos/excluir/'.$row['Código'] ).'" class="margin btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';            
		})

		// renderiza o grid
		->render( site_url( 'grupos/index' ) );
		
        // seta a url para adiciona
        $this->view->set( 'add_url', site_url( 'grupos/adicionar' ) );

		// seta o titulo da pagina
		$this->view->setTitle( 'Grupos - listagem' )->render( 'grid' );
    }

   /**
    * adicionar
    *
    * mostra o formulario de adicao
    *
    */
    public function adicionar() {

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Adicionar grupo' )->render( 'forms/grupo' );
    }

   /**
    * alterar
    *
    * mostra o formulario de edicao
    *
    */
    public function alterar( $key ) {

        // carrega o cargo
        $grupo = $this->GruposFinder->key( $key )->get( true );

        // verifica se o mesmo existe
        if ( !$grupo ) {
            redirect( 'grupos/index' );
            exit();
        }

        // salva na view
        $this->view->set( 'grupo', $grupo );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Adicionar grupo' )->render( 'forms/grupo' );
    }

   /**
    * excluir
    *
    * exclui um item
    *
    */
    public function excluir( $key ) {
        $grupo = $this->GruposFinder->getGrupo();
        $grupo->setGid( $key );
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
        $grupo = $this->GruposFinder->getGrupo();
        $grupo->setGrupo( $this->input->post( 'grupo' ) );
        $grupo->setGid( $this->input->post( 'cod' ) );

        // verifica se o formulario é valido
        if ( !$this->_formularioGrupos() ) {

            // seta os erros de validacao            
            $this->view->set( 'grupo', $grupo );
            $this->view->set( 'errors', validation_errors() );
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Adicionar grupo' )->render( 'forms/grupo' );
            return;
        }

        // verifica se o dado foi salvo
        if ( $grupo->save() ) {
            redirect( site_url( 'grupos/index' ) );
        }
    }
}
