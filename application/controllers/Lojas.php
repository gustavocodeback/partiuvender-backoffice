<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Lojas extends MY_Controller {

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
        $this->load->finder( [ 'LojasFinder', 'CidadesFinder', 'EstadosFinder', 'ClustersFinder' ] );
        
        // chama o modulo
        $this->view->module( 'navbar' )->module( 'aside' )->module( 'jquery-mask' );
    }

   /**
    * _formularioEstados
    *
    * valida o formulario de estados
    *
    */
    private function _formularioLoja() {

        // seta as regras
        $rules = [
            [
                'field' => 'cluster',
                'label' => 'Cluster',
                'rules' => 'required'
            ], [
                'field' => 'cnpj',
                'label' => 'CNPJ',
                'rules' => ''
            ], [
                'field' => 'razao',
                'label' => 'Razao',
                'rules' => 'required|min_length[3]|trim'
            ], [
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'required|min_length[3]|trim'
            ], [
                'field' => 'endereco',
                'label' => 'Endereco',
                'rules' => 'min_length[3]|trim'
            ], [
                'field' => 'numero',
                'label' => 'Numero',
                'rules' => 'min_length[1]|trim'
            ], [
                'field' => 'complemento',
                'label' => 'Complemento',
                'rules' => 'min_length[3]|trim'
            ], [
                'field' => 'bairro',
                'label' => 'Bairro',
                'rules' => 'min_length[3]|trim'
            ], [
                'field' => 'cidade',
                'label' => 'Cidade',
                'rules' => 'min_length[1]|trim'
            ], [
                'field' => 'estado',
                'label' => 'Estado',
                'rules' => 'min_length[1]|trim'
            ], [
                'field' => 'pontosiniciais',
                'label' => 'PontosIniciais',
                'rules' => 'required'
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
        
        // carrega os categorias
        $clusters = $this->ClustersFinder->filtro();

        // faz a paginacao
		$this->LojasFinder->grid()

		// seta os filtros
        ->addFilter( 'Razao', 'text' )
        ->addFilter( 'Nome', 'text', false, 'l' )
        ->addFilter( 'CodCluster', 'select', $clusters, 'l')
		->filter()
		->order()
		->paginate( 0, 20 )

		// seta as funcoes nas colunas
		->onApply( 'Ações', function( $row, $key ) {
			echo '<a href="'.site_url( 'lojas/alterar/'.$row[$key] ).'" class="margin btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil"></span></a>';
			echo '<a href="'.site_url( 'lojas/excluir/'.$row[$key] ).'" class="margin btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';            
		})

        // formata o Cnpj para exibicao
        ->onApply( 'CNPJ', function( $row, $key ) {
			echo mascara_cnpj( $row[$key] );        
        })
        ->onApply( 'Crescimento', function( $row, $key ) {
            $row['PontosIniciais']  = $row['PontosIniciais']  == 0 ? 1 : $row['PontosIniciais'] ;
            echo ( number_format( $row[$key], 2 ) * 100 ).' %';
        })

		// renderiza o grid
		->render( site_url( 'lojas/index' ) );
		
        // seta a url para adiciona
        $this->view->set( 'add_url', site_url( 'lojas/adicionar' ) )
        ->set( 'import_url', site_url( 'lojas/importar_planilha' ) )        
        ->set( 'export_url', site_url( 'lojas/exportar_planilha' ) );

		// seta o titulo da pagina
		$this->view->setTitle( 'Lojas - listagem' )->render( 'grid' );
    }

    public function exportar_planilha() {

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=LojasExportação".date( 'H:i d-m-Y', time() ).".xls" );

        // faz a paginacao
		$this->LojasFinder->clean()->exportar()
        ->paginate( 1, 0, false, false )

        ->onApply( '*', function( $row, $key ) {
            echo strtoupper( mb_convert_encoding( $row[$key], 'UTF-16LE', 'UTF-8' ) );
        })
        
		// renderiza o grid
		->render( site_url( 'lojas/index' ) );

		// seta o titulo da pagina
		$this->view->component( 'table' );
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

        // carrega os clusters
        $clusters = $this->ClustersFinder->get();
        $this->view->set( 'clusters', $clusters );

        // carrega os estados
        $estados = $this->EstadosFinder->get();
        $this->view->set( 'estados', $estados );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Adicionar loja' )->render( 'forms/loja' );
    }

   /**
    * alterar
    *
    * mostra o formulario de edicao
    *
    */
    public function alterar( $key ) {

         // carrega o jquery mask
        $this->view->module( 'jquery-mask' );

        // carrega o classificacao
        $loja = $this->LojasFinder->key( $key )->get( true );

        // carrega os clusters
        $clusters = $this->ClustersFinder->get();
        $this->view->set( 'clusters', $clusters );

        // carrega os estados
        $estados = $this->EstadosFinder->get();
        $this->view->set( 'estados', $estados );
        
        // carrega as cidades
        $cidades = $this->CidadesFinder->clean()->porEstado( $loja->estado )->get();
        $this->view->set( 'cidades', $cidades );

        // verifica se o mesmo existe
        if ( !$loja ) {
            redirect( 'lojas/index' );
            exit();
        }

        // salva na view
        $this->view->set( 'loja', $loja );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Alterar loja' )->render( 'forms/loja' );
    }

   /**
    * excluir
    *
    * exclui um item
    *
    */
    public function excluir( $key ) {
        $loja = $this->LojasFinder->getLoja();
        $loja->setCod( $key );
        $loja->delete();
        $this->index();
    }

   /**
    * salvar
    *
    * salva os dados
    *
    */
    public function salvar() {

        // carrega os clusters
        $clusters = $this->ClustersFinder->get();
        $this->view->set( 'clusters', $clusters );

        // carrega os cidades
        $cidades = $this->CidadesFinder->get();
        $this->view->set( 'cidades', $cidades );

        // carrega os estados
        $estados = $this->EstadosFinder->get();
        $this->view->set( 'estados', $estados );

        $search = array('.','/','-');
        $cnpj = str_replace ( $search , '' , $this->input->post( 'cnpj') );

        // instancia um novo objeto classificacao
        if( $this->input->post( 'cod' ) )
            $loja = $this->LojasFinder->clean()->key( $this->input->post( 'cod' ) )->get( true );
        else
            $loja = $this->LojasFinder->getLoja();

        // instancia um novo objeto classificacao
        $loja->setCluster( $this->input->post( 'cluster' ) );
        $loja->setCnpj( $cnpj );
        $loja->setRazao( $this->input->post( 'razao' ) );
        $loja->setNome( $this->input->post( 'nome' ) );
        $loja->setEndereco( $this->input->post( 'endereco' ) );
        $loja->setNumero( $this->input->post( 'numero' ) );
        $loja->setBairro( $this->input->post( 'bairro' ) );
        $loja->setComplemento( $this->input->post( 'complemento' ) );
        $loja->setCidade( $this->input->post( 'cidade' ) );
        $loja->setEstado( $this->input->post( 'estado' ) );
        $loja->setPontosIniciais( $this->input->post( 'pontosiniciais' ) );

        // verifica se o formulario é valido
        if ( !$this->_formularioLoja() ) {

            // seta os erros de validacao            
            $this->view->set( 'loja', $loja );
            $this->view->set( 'errors', validation_errors() );
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Adicionar loja' )->render( 'forms/loja' );
            return;
        }

        // verifica se o dado foi salvo
        if ( $loja->save() ) {
            redirect( site_url( 'lojas/index' ) );
        }
    }

   /**
    * verificaEntidade
    *
    * verifica se um entidade existe no banco
    *
    */
    public function verificaEntidade( $finder, $method, $dado, $nome, $planilha, $linha, $attr, $status ) {

        // carrega o finder de logs
        $this->load->finder( 'LogsFinder' );

        // verifica se nao esta vazio
        if ( in_cell( $dado ) ) {

            // carrega o finder
            $this->load->finder( $finder );

            // pega a entidade
            if ( $entidade = $this->$finder->clean()->$method( $dado )->get( true ) ) {
                return $entidade->$attr;
            } else {

                // grava o log
                $this->LogsFinder->getLog()
                ->setEntidade( $planilha )
                ->setPlanilha( $this->planilhas->filename )
                ->setMensagem( 'O campo '.$nome.' com valor '.$dado.' nao esta gravado no banco - linha '.$linha )
                ->setData( date( 'Y-m-d H:i:s', time() ) )
                ->setStatus( $status )
                ->save();

                // retorna falso
                return null;
            }
        } else {

            // grava o log
            $this->LogsFinder->getLog()
            ->setEntidade( $planilha )
            ->setPlanilha( $this->planilhas->filename )
            ->setMensagem( 'Nenhum '.$nome.' encontrado - linha '.$linha )
            ->setData( date( 'Y-m-d H:i:s', time() ) )
            ->setStatus( $status )            
            ->save();

            // retorna falso
            return null;
        }
    }

   /**
    * importar_linha
    *
    * importa a linha
    *
    */
    public function importar_linha( $linha, $num ) {

        // percorre todos os campos
        foreach( $linha as $chave => $coluna ) {
            $linha[$chave] = in_cell( $linha[$chave] ) ? $linha[$chave] : null;
        }

        // pega as entidades relacionaveis
        $linha['CodEstado'] = $this->verificaEntidade( 'EstadosFinder', 'uf', $linha['Estado'], 'Estados', 'Lojas', $num, 'CodEstado', 'I' );
        $linha['CodCidade'] = $this->verificaEntidade( 'CidadesFinder', 'nome', $linha['Cidade'], 'Cidades', 'Lojas', $num, 'CodCidade', 'I' );
        $linha['CodCluster'] = $this->verificaEntidade( 'ClustersFinder', 'nome', $linha['Cluster'], 'Clusters', 'Lojas', $num, 'CodCluster', 'I' );

        // verifica se existe um nome
        if ( !in_cell( $linha['NomeFantasia'] ) ) {

            // grava o log
            $this->LogsFinder->getLog()
            ->setEntidade( 'Lojas' )
            ->setPlanilha( $this->planilhas->filename )
            ->setMensagem( 'Não foi possivel inserir a loja pois nenhum nome foi informado - linha '.$num )
            ->setData( date( 'Y-m-d H:i:s', time() ) )
            ->setStatus( 'B' )            
            ->save();

        } else {

            // tenta carregar a loja pelo nome
            $loja = $this->LojasFinder->clean()->nome( $linha['NomeFantasia'] )->get( true );

            // verifica se carregou
            if ( !$loja ) $loja = $this->LojasFinder->getLoja();

            // preenche os dados
            $loja->setRazao( $linha['RazãoSocial'] );
            $loja->setCNPJ( null );
            $loja->setNome( $linha['NomeFantasia'] );
            $loja->setCluster( $linha['CodCluster'] );
            $loja->setCidade( $linha['CodCidade'] );
            $loja->setEstado( $linha['CodEstado'] );

            // tenta salvar a loja
            if ( $loja->save() ) {

                // grava o log
                $this->LogsFinder->getLog()
                ->setEntidade( 'Lojas' )
                ->setPlanilha( $this->planilhas->filename )
                ->setMensagem( 'Loja criada com sucesso - '.$num )
                ->setData( date( 'Y-m-d H:i:s', time() ) )
                ->setStatus( 'S' )            
                ->save();

            } else {

                // grava o log
                $this->LogsFinder->getLog()
                ->setEntidade( 'Lojas' )
                ->setPlanilha( $this->planilhas->filename )
                ->setMensagem( 'Não foi possivel inserir a loja - linha '.$num )
                ->setData( date( 'Y-m-d H:i:s', time() ) )
                ->setStatus( 'B' )            
                ->save();
            }
        }
    }

   /**
    * importar_planilha
    *
    * importa os dados de uma planilha
    *
    */
    public function importar_planilha() {

        // importa a planilha
        $this->load->library( 'Planilhas' );

        // faz o upload da planilha
        $planilha = $this->planilhas->upload();

        // tenta fazer o upload
        if ( !$planilha ) {
            // seta os erros
            $this->view->set( 'errors', $this->planilhas->errors );
        } else {
            $planilha->apply( function( $linha, $num ) {
                $this->importar_linha( $linha, $num );
            });
            $planilha->excluir();
        }

        // carrega a view
        $this->index();
    }
}
