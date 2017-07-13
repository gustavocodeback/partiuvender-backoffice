<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Perguntas extends MY_Controller {

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
        $this->load->finder( [ 'PerguntasFinder', 'QuestionariosFinder' ] );
        
        // chama o modulo
        $this->view->module( 'navbar' )->module( 'aside' );
    }

   /**
    * _formularioPergunta
    *
    * valida o formulario de pergunta
    *
    */
    private function _formularioPergunta() {

        // seta as regras
        $rules = [
            [
                'field' => 'texto',
                'label' => 'Texto',
                'rules' => 'required|min_length[3]|trim'
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
		$this->PerguntasFinder->clean()->grid()

		// seta os filtros
		->order()
		->paginate( 0, 20 )

		// seta as funcoes nas colunas
		->onApply( 'Ações', function( $row, $key ) {
			echo '<a href="'.site_url( 'perguntas/alterar/'.$row['Código'] ).'" class="margin btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil"></span></a>';
			echo '<a href="'.site_url( 'perguntas/excluir/'.$row['Código'] ).'" class="margin btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';            
		})

		// renderiza o grid
		->render( site_url( 'perguntas/index' ) );
		
        // seta a url para adiciona
        $this->view->set( 'add_url', site_url( 'perguntas/adicionar' ) );

		// seta o titulo da pagina
		$this->view->setTitle( 'Perguntas - listagem' )->render( 'grid' );
    }

   /**
    * adicionar
    *
    * mostra o formulario de adicao
    *
    */
    public function adicionar() {

        // carrega os estados
        $questionario = $this->QuestionariosFinder->get();
        $this->view->set( 'questionarios', $questionario );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Adicionar pergunta' )->render( 'forms/pergunta' );
    }

   /**
    * alterar
    *
    * mostra o formulario de edicao
    *
    */
    public function alterar( $key ) {

        // carrega os estados
        $questionario = $this->QuestionariosFinder->get();
        $this->view->set( 'questionarios', $questionario );

        // carrega o cargo
        $pergunta = $this->PerguntasFinder->key( $key )->get( true );

        // verifica se o mesmo existe
        if ( !$pergunta ) {
            redirect( 'perguntas/index' );
            exit();
        }

        // salva na view
        $this->view->set( 'pergunta', $pergunta );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Alterar pergunta' )->render( 'forms/pergunta' );
    }

   /**
    * excluir
    *
    * exclui um item
    *
    */
    public function excluir( $key ) {
        $pergunta = $this->PerguntasFinder->getPergunta();
        $pergunta->setCod( $key );
        $pergunta->delete();
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
        $pergunta = $this->PerguntasFinder->getPergunta();
        $pergunta->setCod( $this->input->post( 'cod' ) )
        ->setTexto( $this->input->post( 'texto' ) )
        ->setPontos( $this->input->post( 'pontos' ) )
        ->setResposta( $this->input->post( 'resposta' ) )
        ->setAlternativa1( $this->input->post( 'alternativa1' ) )
        ->setAlternativa2( $this->input->post( 'alternativa2' ) )
        ->setAlternativa3( $this->input->post( 'alternativa3' ) )
        ->setAlternativa4( $this->input->post( 'alternativa4' ) )
        ->setQuestionario( $this->input->post( 'questionario' ) );
        
        // verifica se o formulario é valido
        if ( !$this->_formularioPergunta() ) {

            // seta os erros de validacao            
            $this->view->set( 'pergunta', $pergunta );
            $this->view->set( 'errors', validation_errors() );
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Adicionar pergunta' )->render( 'forms/pergunta' );
            return;
        }

        // verifica se o dado foi salvo
        if ( $pergunta->save() ) {
            redirect( site_url( 'perguntas/index' ) );
        }
    }
}
