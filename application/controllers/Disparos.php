<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Disparos extends MY_Controller {

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
        $this->load->finder( [ 'DisparosFinder', 'NotificacoesFinder', 'FuncionariosFinder' ] );
        
        // chama o modulo
        $this->view->module( 'navbar' )->module( 'aside' );
    }

   /**
    * _formularioNotificacoes
    *
    * valida o formulario de notificacoes
    *
    */
    private function _formularioDisparo() {

        // seta as regras
        $rules = [
            [
                'field' => 'grupo',
                'label' => 'Grupo',
                'rules' => 'required|min_length[3]|trim'
            ],[
                'field' => 'notificacao',
                'label' => 'Notificacao',
                'rules' => 'required|min_length[1]'
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
		$this->DisparosFinder->grid()

		// seta os filtros
		->order()
		->paginate( 0, 20 )

		// seta as funcoes nas colunas
		->onApply( 'Ações', function( $row, $key ) {
			echo '<a href="'.site_url( 'disparos/excluir/'.$row['Código'] ).'" class="margin btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';            
		})
        ->onApply( 'Data', function( $row, $key ) {
            echo '<small>'.date( 'd/m/Y \à\s H:i', strtotime( $row[$key] ) ).'</small>';
        })

		// renderiza o grid
		->render( site_url( 'disparos/index' ) );
		
        // seta a url para adiciona
        $this->view->set( 'add_url', site_url( 'disparos/adicionar' ) );

		// seta o titulo da pagina
		$this->view->setTitle( 'Disparos - listagem' )->render( 'grid' );
    }

   /**
    * adicionar
    *
    * mostra o formulario de adicao
    *
    */
    public function adicionar() {

        // carrega os notificacoes
        $notificacoes = $this->NotificacoesFinder->get();
        $this->view->set( 'notificacoes', $notificacoes );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Adicionar disparo' )->render( 'forms/disparo' );
    }

   /**
    * alterar
    *
    * mostra o formulario de edicao
    *
    */
    public function alterar( $key ) {

        // carrega os notificacoes
        $notificacoes = $this->NotificacoesFinder->get();
        $this->view->set( 'notificacoes', $notificacoes );

        // carrega o cargo
        $disparo = $this->DisparosFinder->key( $key )->get( true );

        // verifica se o mesmo existe
        if ( !$disparo ) {
            redirect( 'notificacoes/index' );
            exit();
        }

        // salva na view
        $this->view->set( 'disparo', $disparo );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Alterar disparo' )->render( 'forms/disparo' );
    }

   /**
    * excluir
    *
    * exclui um item
    *
    */
    public function excluir( $key ) {
        $grupo = $this->DisparosFinder->getDisparo();
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

        // verifica se o formulario é valido
        if ( !$this->_formularioDisparo() ) {

            // seta os erros de validacao            
            $this->view->set( 'disparo', $disparo );
            $this->view->set( 'errors', validation_errors() );
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Adicionar disparo' )->render( 'forms/disparo' );
            return;
        }        
        
        $funcionarios = $this->input->post( 'grupo' ) != 'Todos' ? 
            $this->FuncionariosFinder->cargo( $this->input->post( 'grupo' ) )->get() :
            $this->FuncionariosFinder->get();

        // pega a notificacao
        $notificacao = $this->NotificacoesFinder->key( $this->input->post( 'notificacao' ) )->get( true );
        $notificacao->setDisparos( $notificacao->disparos + 1 );
        $notificacao->save();

        // // salva os disparos
        foreach ( $funcionarios as $key => $funcionario ) {         
            
            // instancia um novo objeto grupo
            $disparo = $this->DisparosFinder->getDisparo();
            $disparo->setData( date( 'Y-m-d H:i:s', time() ) );
            $disparo->setNotificacao( $this->input->post( 'notificacao' ) );
            $disparo->setFunc( $funcionario->CodFuncionario );
            $disparo->setStatus( 'N' );
            $disparo->setCod( $this->input->post( 'cod' ) );
            $disparo->save();
        }

        // carrega a library de push
        $this->load->library( 'Push' );
        $this->push->setTitle( $notificacao->nome )
        ->setBody( $notificacao->texto )
        ->setImage( base_url( 'uploads/'.$notificacao->notificacao ) )
        ->fire();

        // redireciona
        redirect( site_url( 'disparos/index' ) );
    }
}
