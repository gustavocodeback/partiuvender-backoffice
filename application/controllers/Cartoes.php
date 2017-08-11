<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cartoes extends MY_Controller {

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
        $this->load->finder( [ 'CartoesFinder', 'FuncionariosFinder' ] );
        
        // chama o modulo
        $this->view->module( 'navbar' )->module( 'aside' );
    }

   /**
    * _formularioNotificacoes
    *
    * valida o formulario de notificacoes
    *
    */
    private function _formularioCartao() {

        // seta as regras
        $rules = [
            [
                'field' => 'cpf',
                'label' => 'CPF',
                'rules' => 'min_length[14]|trim'
            ], [
                'field' => 'valor',
                'label' => 'Valor',
                'rules' => 'required'
            ]
        ];

        if( $this->input->post( 'cod' ) ) {

            $cartao = $this->CartoesFinder->key( $this->input->post( 'cod' ) )->get( true );

            if( $cartao->codigo != $this->input->post( 'codigo' ) ){
                $rules[] = [
                    'field' => 'codigo',
                    'label' => 'Codigo',
                    'rules' => 'required|min_length[3]|trim|is_unique[Cartoes.Codigo]'
                ];
            } else {
                $rules[] = [
                    'field' => 'codigo',
                    'label' => 'Codigo',
                    'rules' => 'required|min_length[3]|trim'
                ];
            }

        } else {
            $rules[] = [
                'field' => 'codigo',
                'label' => 'Codigo',
                'rules' => 'required|min_length[3]|trim|is_unique[Cartoes.Codigo]'
            ];
        }

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
		$this->CartoesFinder->grid()

		// seta os filtros
		->order()
		->paginate( 0, 20 )

		// seta as funcoes nas colunas
		->onApply( 'Ações', function( $row, $key ) {
			echo '<a href="'.site_url( 'cartoes/alterar/'.$row[$key] ).'" class="margin btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil"></span></a>';
			echo '<a href="'.site_url( 'cartoes/excluir/'.$row[$key] ).'" class="margin btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';                
		})
        ->onApply( 'Data', function( $row, $key ) {
            if( !$row[$key] ) echo 'Não utilizado';
            else echo '<small>'.date( 'd/m/Y', strtotime( $row[$key] ) ).'</small>';
        })
        ->onApply( 'Funcionario', function( $row, $key ) {
            if( !$row[$key] ) echo 'Não utilizado';
            else echo $row[$key];
        })

		// renderiza o grid
		->render( site_url( 'cartoes/index' ) );
		
        // seta a url para adiciona
        $this->view->set( 'add_url', site_url( 'cartoes/adicionar' ) );

		// seta o titulo da pagina
		$this->view->setTitle( 'Cartoes - listagem' )->render( 'grid' );
    }

   /**
    * adicionar
    *
    * mostra o formulario de adicao
    *
    */
    public function adicionar() {
        
        // carrega o jquery mask
        $this->view->module( 'jquery-mask' );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Adicionar cartao' )->render( 'forms/cartao' );
    }

   /**
    * alterar
    *
    * mostra o formulario de edicao
    *
    */
    public function alterar( $key ) {

        // carrega o cargo
        $cartao = $this->CartoesFinder->key( $key )->get( true );

        $cartao->data = date( 'Y-m-d', strtotime($cartao->data) );

        // verifica se o cartao pertence a algum funcionario
        if( $cartao->funcionario ){

            // carrega o funcionario
            $funcionario = $this->FuncionariosFinder->key( $cartao->funcionario )->get( true );

            // pega o cpf
            $cartao->cpf = $funcionario->cpf;
        }

        // verifica se o mesmo existe
        if ( !$cartao ) {
            redirect( 'cartoes/index' );
            exit();
        }

        // salva na view
        $this->view->set( 'cartao', $cartao );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Alterar cartao' )->render( 'forms/cartao' );
    }

   /**
    * excluir
    *
    * exclui um item
    *
    */
    public function excluir( $key ) {
        $cartao = $this->CartoesFinder->getCartao();
        $cartao->setCod( $key );
        $cartao->delete();
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
        $cartao = $this->CartoesFinder->getCartao();        
        
        $cartao->setCod( $this->input->post( 'cod' ) );

        $cartao->setStatus( $this->input->post( 'status' ) );

        if( $this->input->post( 'data' ) && $cartao->status != 'A' ) $cartao->setData( date( 'Y-m-d H:i:s',  strtotime( $this->input->post( 'data' ) ) ) );

        $cartao->setCodigo(  $this->input->post( 'codigo' ) );
        
        $search = array('.','/','-');
        $cpf = str_replace ( $search , '' , $this->input->post( 'cpf') );
        $cpf = $cpf ? $cpf : '';

        $cartao->cpf = $cpf;

        if( $this->input->post( 'valor' ) <= 0 ) {

            // seta os erros de validacao            
            $this->view->set( 'cartao', $cartao );
            $this->view->set( 'errors', 'Valor inválido.' );
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Adicionar cartao' )->render( 'forms/cartao' );
            return;
        } else $cartao->setValor( $this->input->post( 'valor' ) );

        if( $cpf == '' && $cartao->status != 'A' ){
            
            // seta os erros de validacao            
            $this->view->set( 'cartao', $cartao );
            $this->view->set( 'errors', 'Informe um CPF');
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Adicionar cartao' )->render( 'forms/cartao' );
            return;
        }

        // verifica se o formulario é valido
        if ( !$this->_formularioCartao() ) {

            // seta os erros de validacao            
            $this->view->set( 'cartao', $cartao );
            $this->view->set( 'errors', validation_errors() );
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Adicionar cartao' )->render( 'forms/cartao' );
            return;
        }

        if( $cpf != '' ) {

            // pega o funcionario
            $funcionario = $this->FuncionariosFinder->cpf( $cpf )->get( true );
        
            if( !$funcionario ) {

                $this->view->set( 'cartao', $cartao );
                $this->view->set( 'errors', 'CPF não consta no sistema.' );            
                
                // carrega a view de adicionar
                $this->view->setTitle( 'Samsung - Adicionar cartao' )->render( 'forms/cartao' );
                return;
            } else $cartao->setFunc( $funcionario->CodFuncionario );
        }

        if( $cartao->status != 'A' && !$this->input->post( 'data' ) )  {
            $cartao->data = time();

            // seta os erros de validacao            
            $this->view->set( 'cartao', $cartao );
            $this->view->set( 'errors', 'Insira a data que o cartão foi usado' );
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Alterar cartao' )->render( 'forms/cartao' );
            return;
        }

        $cartao->save();

        // redireciona
        redirect( site_url( 'cartoes/index' ) );
    }
}
