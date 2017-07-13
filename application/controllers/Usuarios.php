<?php defined('BASEPATH') OR exit('No direct script access allowed');

// inclui a classe de usuario
include_once( 'application/classes/User.php' );

class Usuarios extends MY_Controller {

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
        $this->load->finder( [ 'UsuariosFinder', 'GruposFinder' ] );
        
        // chama o modulo
        $this->view->module( 'navbar' )->module( 'aside' );
    }

   /**
    * _formularioUsuarios
    *
    * valida o formulario de rotinas
    *
    */
    private function _formularioUsuarios() {

        // seta as regras
        $rules = [
            [
                'field' => 'email',
                'label' => 'E-mail',
                'rules' => 'valid_email|required'
            ], [
                'field' => 'senha',
                'label' => 'Senha',
                'rules' => 'required'
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
		$this->UsuariosFinder->grid()

		// seta os filtros
		->order()
		->paginate( 0, 20 )

		// seta as funcoes nas colunas
		->onApply( 'Ações', function( $row, $key ) {
			echo '<a href="'.site_url( 'usuarios/alterar/'.$row['Código'] ).'" class="margin btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil"></span></a>';
			echo '<a href="'.site_url( 'usuarios/excluir/'.$row['Código'] ).'" class="margin btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';            
		})

		// renderiza o grid
		->render( site_url( 'usuarios/index' ) );
		
        // seta a url para adiciona
        $this->view->set( 'add_url', site_url( 'usuarios/adicionar' ) );

		// seta o titulo da pagina
		$this->view->setTitle( 'Usuários - listagem' )->render( 'grid' );
    }

   /**
    * adicionar
    *
    * mostra o formulario de adicao
    *
    */
    public function adicionar() {

        // carrega as classificacoes
        $grupos = $this->GruposFinder->get();

        // seta a view
        $this->view->set( 'grupos', $grupos );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Adicionar usuario' )->render( 'forms/usuario' );
    }

   /**
    * alterar
    *
    * mostra o formulario de edicao
    *
    */
    public function alterar( $key ) {

        // carrega as classificacoes
        $grupos = $this->GruposFinder->get();

        // seta a view
        $this->view->set( 'grupos', $grupos );

        // carrega o cargo
        $usuario = $this->UsuariosFinder->key( $key )->get( true );

        // verifica se o mesmo existe
        if ( !$usuario ) {
            redirect( 'usuarios/index' );
            exit();
        }

        // salva na view
        $this->view->set( 'usuario', $usuario );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Adicionar rotina' )->render( 'forms/usuario' );
    }

   /**
    * excluir
    *
    * exclui um item
    *
    */
    public function excluir( $key ) {
        $usuario = $this->UsuariosFinder->getUsuario();
        $usuario->setCod( $key );
        $usuario->delete();
        $this->index();
    }

   /**
    * salvar
    *
    * salva os dados
    *
    */
    public function salvar() {

        // pega os dados
        $grupos = $this->GruposFinder->get();
        $this->view->set( 'grupos', $grupos );

        // instancia um novo objeto grupo
        $usuario = $this->UsuariosFinder->getUsuario();
        $usuario->setEmail( $this->input->post( 'email' ) );
        $usuario->setSenha( $this->input->post( 'senha' ) );
        $usuario->setGid( $this->input->post( 'gid' ) );
        $usuario->setCod( $this->input->post( 'cod' ) );

        // seta o usuario
        $this->view->set( 'usuario', $usuario );

        // verifica se o formulario é valido
        if ( !$this->_formularioUsuarios() ) {

            // seta os erros de validacao            
            $this->view->set( 'errors', validation_errors() );
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Adicionar usuario' )->render( 'forms/usuario' );
            return;
        }

        // verifica se existe um codigo
        if ( !$usuario->uid ) {

            // cria o usuario
            $user = $this->guard->createUserWithEmailAndPassword( $usuario->email, $usuario->senha, $usuario->gid  );

            // verifica se criou o usuario
            if ( !$user ) {

                // seta o erro
                $erro = $this->guard->cause()['message'];
                $this->view->set( 'errors', $erro );

                // carrega a view de adicionar
                $this->view->setTitle( 'Samsung - Adicionar usuario' )->render( 'forms/usuario' );
                return;
            } else {
                
                // redireciona para a index
                redirect( site_url( 'usuarios/index' ) );
                return;
            }
        }

        // cria um novo usuario
        $user = new User( $this->db, $this->config );

        // carrega pelo email
        if ( $user->loadByEmail( $usuario->email )->isLoaded() ) {

            // verifica se os uid sao iguais
            if ( $user->data['uid'] != $usuario->uid ) {

                // seta o erro
                $this->view->set( 'errors', 'O e-mail digitado já está em uso.' );

                // carrega a view de adicionar
                $this->view->setTitle( 'Samsung - Adicionar usuario' )->render( 'forms/usuario' );
                return;
            } else $user->updateUser( $usuario );
        } else if ( $user->loadByUid( $usuario->uid )->isLoaded() ) {
            $user->updateUser( $usuario );
        }

        // redireciona para a index
        redirect( site_url( 'usuarios/index' ) );
    }
}
