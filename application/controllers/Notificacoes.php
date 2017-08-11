<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notificacoes extends MY_Controller {

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
        $this->load->finder( [ 'NotificacoesFinder' ] );
        
        // carrega a librarie de fotos
		$this->load->library( 'Picture' );
        
        // chama o modulo
        $this->view->module( 'navbar' )->module( 'aside' );
    }

   /**
    * _formularioNotificacoes
    *
    * valida o formulario de notificacoes
    *
    */
    private function _formularioNotificacoes() {

        // seta as regras
        $rules = [
            [
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'required|min_length[3]|trim'
            ], [
                'field' => 'texto',
                'label' => 'Texto',
                'rules' => 'required|min_length[10]trim'
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
		$this->NotificacoesFinder->grid()

		// seta os filtros
        ->addFilter( 'nome', 'text' )
		->filter()
		->order()
		->paginate( 0, 20 )

		// seta as funcoes nas colunas
		->onApply( 'Ações', function( $row, $key ) {
			echo '<a href="'.site_url( 'notificacoes/alterar/'.$row['Código'] ).'" class="margin btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil"></span></a>';
			echo '<a href="'.site_url( 'notificacoes/excluir/'.$row['Código'] ).'" class="margin btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';            
		})

         // seta as funcoes nas colunas
		->onApply( 'Notificacao', function( $row, $key ) {
            if( $row[$key] )
			    echo '<img src="'.base_url( 'uploads/'.$row[$key] ).'" style="width: 50px; height: 50px;">';
            else echo 'Sem Foto';
		})

		// renderiza o grid
		->render( site_url( 'notificacoes/index' ) );
		
        // seta a url para adiciona
        $this->view->set( 'add_url', site_url( 'notificacoes/adicionar' ) );

		// seta o titulo da pagina
		$this->view->setTitle( 'Notificações - listagem' )->render( 'grid' );
    }

   /**
    * adicionar
    *
    * mostra o formulario de adicao
    *
    */
    public function adicionar() {

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Adicionar notificação' )->render( 'forms/notificacao' );
    }

   /**
    * alterar
    *
    * mostra o formulario de edicao
    *
    */
    public function alterar( $key ) {

        // carrega o cargo
        $notificacao = $this->NotificacoesFinder->key( $key )->get( true );

        // verifica se o mesmo existe
        if ( !$notificacao ) {
            redirect( 'notificacoes/index' );
            exit();
        }

        // salva na view
        $this->view->set( 'notificacao', $notificacao );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Alterar notificação' )->render( 'forms/notificacao' );
    }

   /**
    * excluir
    *
    * exclui um item
    *
    */
    public function excluir( $key ) {
        $grupo = $this->NotificacoesFinder->getNotificacao();
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

        // faz o upload da imagem
        $file_name = $this->picture->upload( 'foto' );

        if ( $this->input->post( 'cod' ) ) {
            $notificacao = $this->NotificacoesFinder->key( $this->input->post( 'cod' ) )->get( true );
        } else {

            // instancia um novo objeto grpo
            $notificacao = $this->NotificacoesFinder->getNotificacao();            
            $notificacao->setNotificacao( 'sem-foto.jpg' );
        }

        // instancia um novo objeto grupo
        $notificacao->setDisparos( 0 );        
        $notificacao->setNome( $this->input->post( 'nome' ) );
        $notificacao->setTexto( $this->input->post( 'texto' ) );
        $notificacao->setCod( $this->input->post( 'cod' ) );
        
        if ( $file_name ) {
            $this->picture->delete( $notificacao->notificacao );
            $notificacao->setNotificacao( $file_name );
        }

        // verifica se o formulario é valido
        if ( !$this->_formularioNotificacoes() ) {

            // seta os erros de validacao            
            $this->view->set( 'notificacao', $notificacao );
            $this->view->set( 'errors', validation_errors() );
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Adicionar notificação' )->render( 'forms/notificacao' );
            return;
        }

        // verifica se o dado foi salvo
        if ( $notificacao->save() ) {
            redirect( site_url( 'notificacoes/index' ) );
        }
    }
}
