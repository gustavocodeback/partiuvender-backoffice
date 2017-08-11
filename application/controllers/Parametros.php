<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Parametros extends MY_Controller {

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
        $this->load->finder( [ 'ParametrosFinder' ] );
        
        // chama o modulo
        $this->view->module( 'navbar' )->module( 'aside' );
    }

   /**
    * _formularioParametros
    *
    * valida o formulario de parametros
    *
    */
    private function _formularioParametros() {

        // seta as regras
        $rules = [
            [
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'required|min_length[3]|trim'
            ],[
                'field' => 'valor',
                'label' => 'Valor',
                'rules' => 'required|min_length[1]|trim'
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
		$this->ParametrosFinder->grid()

		// seta os filtros
        ->addFilter( 'nome', 'text' )
		->filter()
		->order()
		->paginate( 0, 20 )

		// seta as funcoes nas colunas
		->onApply( 'Ações', function( $row, $key ) {
			echo '<a href="'.site_url( 'parametros/alterar/'.$row['Código'] ).'" class="margin btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil"></span></a>';
			echo '<a href="'.site_url( 'parametros/excluir/'.$row['Código'] ).'" class="margin btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';            
		})

		// renderiza o grid
		->render( site_url( 'parametros/index' ) );
		
        // seta a url para adiciona
        $this->view->set( 'add_url', site_url( 'parametros/adicionar' ) );

		// seta o titulo da pagina
		$this->view->setTitle( 'Parametros - listagem' )->render( 'grid' );
    }

   /**
    * adicionar
    *
    * mostra o formulario de adicao
    *
    */
    public function adicionar() {

        // carrega a view de adicionar
        $this->view->setTitle( 'Conta Ágil - Adicionar parametro' )->render( 'forms/parametro' );
    }

   /**
    * alterar
    *
    * mostra o formulario de edicao
    *
    */
    public function alterar( $key ) {

        // carrega o cargo
        $parametro = $this->ParametrosFinder->key( $key )->get( true );

        // verifica se o mesmo existe
        if ( !$parametro ) {
            redirect( 'parametros/index' );
            exit();
        }

        // salva na view
        $this->view->set( 'parametro', $parametro );

        // carrega a view de adicionar
        $this->view->setTitle( 'Conta Ágil - Alterar parametro' )->render( 'forms/parametro' );
    }

   /**
    * excluir
    *
    * exclui um item
    *
    */
    public function excluir( $key ) {
        $grupo = $this->ParametrosFinder->getParametro();
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
        $parametro = $this->ParametrosFinder->getParametro();
        $parametro->setNome( $this->input->post( 'nome' ) );
        $parametro->setValor( $this->input->post( 'valor' ) );
        $parametro->setCod( $this->input->post( 'cod' ) );

        // verifica se o formulario é valido
        if ( !$this->_formularioParametros() ) {

            // seta os erros de validacao            
            $this->view->set( 'parametro', $parametro );
            $this->view->set( 'errors', validation_errors() );
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Conta Ágil - Adicionar parametro' )->render( 'forms/parametro' );
            return;
        }

        // verifica se o dado foi salvo
        if ( $parametro->save() ) {
            redirect( site_url( 'parametros/index' ) );
        }
    }
}
