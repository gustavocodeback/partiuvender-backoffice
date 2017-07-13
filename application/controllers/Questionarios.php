<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Questionarios extends MY_Controller {

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
        $this->load->finder( [ 'QuestionariosFinder' ] );

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
    private function _formularioQuestionario() {

        // seta as regras
        $rules = [
            [
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'required|min_length[3]|max_length[32]|trim'
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
		$this->QuestionariosFinder->clean()->grid()

		// seta os filtros
		->order()
		->paginate( 0, 20 )

		// seta as funcoes nas colunas
		->onApply( 'Ações', function( $row, $key ) {
			echo '<a href="'.site_url( 'questionarios/alterar/'.$row['Código'] ).'" class="margin btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil"></span></a>';
			echo '<a href="'.site_url( 'questionarios/excluir/'.$row['Código'] ).'" class="margin btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';            
		})

        // seta as funcoes nas colunas
		->onApply( 'Foto', function( $row, $key ) {
			echo '<img src="'.base_url( 'uploads/'.$row[$key] ).'" style="width: 50px; height: 50px;">';
		})

		// renderiza o grid
		->render( site_url( 'questionarios/index' ) );
		
        // seta a url para adiciona
        $this->view->set( 'add_url', site_url( 'questionarios/adicionar' ) );

		// seta o titulo da pagina
		$this->view->setTitle( 'Questionarios - listagem' )->render( 'grid' );
    }

   /**
    * adicionar
    *
    * mostra o formulario de adicao
    *
    */
    public function adicionar() {

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Adicionar questionario' )->render( 'forms/questionario' );
    }

   /**
    * alterar
    *
    * mostra o formulario de edicao
    *
    */
    public function alterar( $key ) {

        // carrega o cargo
        $questionario = $this->QuestionariosFinder->key( $key )->get( true );

        // verifica se o mesmo existe
        if ( !$questionario ) {
            redirect( 'questionarios/index' );
            exit();
        }

        // salva na view
        $this->view->set( 'questionario', $questionario );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Alterar questionario' )->render( 'forms/questionario' );
    }

   /**
    * excluir
    *
    * exclui um item
    *
    */
    public function excluir( $key ) {
        $questionario = $this->QuestionariosFinder->key( $key )->get( true );
        $this->picture->delete( $questionario->foto );
        $questionario->delete();
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
            $questionario = $this->QuestionariosFinder->key( $this->input->post( 'cod' ) )->get( true );
        } else {

            // instancia um novo objeto grpo
            $questionario = $this->QuestionariosFinder->getQuestionario();
        }

        $questionario->setNome( $this->input->post( 'nome' ) );
        $questionario->setDescricao( $this->input->post( 'descricao' ) );
        $questionario->setCod( $this->input->post( 'cod' ) );

        if( !$file_name && !$questionario->foto ) {
            $this->view->set( 'questionario', $questionario );
            $this->view->set( 'errors', 'Escolha uma foto!' );

            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Adicionar questionario' )->render( 'forms/questionario' );
            return;
        }

        if ( $file_name ) {
            $this->picture->delete( $questionario->foto );
            $questionario->setFoto( $file_name );
        }

        // verifica se o formulario é valido
        if ( !$this->_formularioQuestionario() ) {

            // seta os erros de validacao            
            $this->view->set( 'questionario', $questionario );
            $this->view->set( 'errors', validation_errors() );
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Adicionar questionario' )->render( 'forms/questionario' );
            return;
        }

        // verifica se o dado foi salvo
        if ( $questionario->save() ) {
            redirect( site_url( 'questionarios/index' ) );
        }
    }
}
