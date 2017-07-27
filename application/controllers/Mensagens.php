<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mensagens extends MY_Controller {

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
        $this->load->finder( [ 'MensagensFinder', 'FuncionariosFinder' ] );
        
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
		$this->MensagensFinder->clean()->grid()

		// seta os filtros
		->order()
		->paginate( 0, 20 )

		// seta as funcoes nas colunas
		->onApply( 'Ações', function( $row, $key ) {
			echo '<a href="'.site_url( 'mensagens/ver/'.$row[$key] ).'" class="margin btn btn-xs btn-info"><span class="glyphicon glyphicon-envelope"></span></a>';   
		})
        ->onApply( 'Data', function( $row, $key ) {
			echo date( 'H:i:s d/m/Y', strtotime( $row[$key] ) );   
		})

		// renderiza o grid
		->render( site_url( 'mensagens/index' ) );

        // faz a contagem
        $this->MensagensFinder->count = $this->MensagensFinder->count();

		// seta o titulo da pagina
		$this->view->setTitle( 'Mensagens - listagem' )->render( 'grid' );
    }

   /**
    * index
    *
    * mostra o grid de contadores
    *
    */
	public function ver( $key ) {

        // faz a paginacao
		$this->MensagensFinder->gridFunc( $key )

		// seta os filtros
		->order()
		->paginate( 0, 20 )

        ->onApply( 'Data', function( $row, $key ) {
            echo '<small>'.date( 'd/m/Y', strtotime( $row[$key] ) ).'</small>';
        })

		// renderiza o grid
		->render( site_url( 'mensagens/index' ) );
        
        // seta a url para adiciona
        $this->view->set( 'export_url', site_url( 'mensagens/exportar_planilha/'.$key ) );

		// seta o titulo da pagina
		$this->view->setTitle( 'Mensagens - listagem' )->render( 'grid' );
    }
    
    public function exportar_planilha( $key ) {

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=MensagensExportação".date( 'H:i d-m-Y', time() ).".xls" );

        // faz a paginacao
		$this->MensagensFinder->gridFunc( $key )
        ->paginate( 1, 0, false, false )

		// renderiza o grid
		->render( site_url( 'produtos/index' ) );

		// seta o titulo da pagina
		$this->view->component( 'table' );
    }


}
