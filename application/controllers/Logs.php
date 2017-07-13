<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends MY_Controller {

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
        $this->load->finder( [ 'LogsFinder' ] );
        
        // chama o modulo
        $this->view->module( 'navbar' )->module( 'aside' );
    }

   /**
    * index
    *
    * mostra o grid de contadores
    *
    */
	public function index() {

        // faz a paginacao
		$this->LogsFinder->clean()->grid()

		// seta os filtros
        ->addFilter( 'Status', 'select', [ 'I' => 'Informativo', 'B' => 'Erro', 'S' => 'Sucesso' ] )
        ->filter()
		->order()
		->paginate( 0, 20 )

		// seta as funcoes nas colunas
		->onApply( 'Ações', function( $row, $key ) {
			echo '<a href="'.site_url( 'logs/excluir/'.$row[$key] ).'" class="margin btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';            
		})
        ->onApply( [ 'Entidade', 'Planilha', 'Mensagem', 'Data' ], function( $row, $key ) {
            if ( $row['Status'] == 'B' )
                echo '<span class="text-danger">'.$row[$key].'</span>';
            if ( $row['Status'] == 'S' )
                echo '<span class="text-success">'.$row[$key].'</span>';
            if ( $row['Status'] == 'I' )
                echo '<span class="text-info">'.$row[$key].'</span>';
		})
        ->onApply( 'Status', function( $row, $key ) {
			if ( $row['Status'] == 'B' )
                echo '<span class="text-danger">Erro</span>';
            if ( $row['Status'] == 'S' )
                echo '<span class="text-success">Sucesso</span>';
            if ( $row['Status'] == 'I' )
                echo '<span class="text-info">Informativo</span>';
        })
        

		// renderiza o grid
		->render( site_url( 'logs/index' ) );

		// seta o titulo da pagina
		$this->view->setTitle( 'Logs - listagem' )->render( 'grid' );
    }

   /**
    * excluir
    *
    * exclui um item
    *
    */
    public function excluir( $key ) {
        $log = $this->LogsFinder->key( $key )->get( true );
        $log->delete();
        $this->index();
    }
}
