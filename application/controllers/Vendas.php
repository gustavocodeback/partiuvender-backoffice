<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Vendas extends MY_Controller {

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
        $this->load->finder( [ 'FuncionariosFinder', 'CategoriasFinder', 'ProdutosFinder', 'VendasFinder', 'LojasFinder' ] );
        
        // chama o modulo
        $this->view->module( 'navbar' )->module( 'aside' )->module( 'jquery-mask' );
    }

   /**
    * _formularioEstados
    *
    * valida o formulario de estados
    *
    */
    private function _formularioVenda() {

        // seta as regras
        $rules = [
            [
                'field' => 'cpf',
                'label' => 'CPF',
                'rules' => 'required|min_length[14]|max_length[14]|trim'
            ], [
                'field' => 'quantidade',
                'label' => 'Quantidade',
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
    * mostra o grid de contadores
    *
    */
	public function index() {

        // carrega os categorias
        $lojas = $this->LojasFinder->filtro();

        // faz a paginacao
		$this->VendasFinder->clean()->grid()

		// seta os filtros
        ->addFilter( 'CodLoja', 'select', $lojas, 'v' )
		->filter()
		->order()
		->paginate( 0, 20 )

		// seta as funcoes nas colunas
		->onApply( 'Ações', function( $row, $key ) {
			echo '<a href="'.site_url( 'vendas/excluir/'.$row[$key] ).'" class="margin btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';            
		})
        ->onApply( 'Data', function( $row, $key ) {
            echo '<small>'.date( 'd/m/Y', strtotime( $row[$key] ) ).'</small>';
        })

        // formata o Cnpj para exibicao
        ->onApply( 'Funcionario', function( $row, $key ) {
			echo mascara_cpf( $row[$key] );        
		})

		// renderiza o grid
		->render( site_url( 'vendas/index' ) );
		
        // seta a url para adiciona
        $this->view->set( 'add_url', site_url( 'vendas/adicionar' ) )
        ->set( 'import_url', site_url( 'vendas/importar_planilha' ) )             
        ->set( 'export_url', site_url( 'vendas/exportar_planilha' ) );

		// seta o titulo da pagina
		$this->view->setTitle( 'Vendas - listagem' )->render( 'grid' );
    }

    public function exportar_planilha() {

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=LojasExportação".date( 'H:i d-m-Y', time() ).".xls" );

        // faz a paginacao
		$this->VendasFinder->clean()->exportar()
        ->paginate( 1, 0, false, false )

		// renderiza o grid
		->render( site_url( 'vendas/index' ) );

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

        // carrega os lojas
        $categorias = $this->CategoriasFinder->get();
        $this->view->set( 'categorias', $categorias );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Adicionar venda' )->render( 'forms/venda' );
    }

   /**
    * excluir
    *
    * exclui um item
    *
    */
    public function excluir( $key ) {

        // carrega a venda
        $venda = $this->VendasFinder->key( $key )->get( true );

        // carrega o funcionario
        $funcionario = $this->FuncionariosFinder->key( $venda->funcionario )->get( true );

        $funcionario->removePontos( $venda->pontos );

        $venda->delete();
        $this->index();
    }

   /**
    * salvar
    *
    * salva os dados
    *
    */
    public function salvar() {

        //carrega o produto da venda
        $produto = $this->ProdutosFinder->key( $this->input->post( 'produto' ) )->get( true );
        
        // carrega as categorias
        $categorias = $this->CategoriasFinder->get();
        $this->view->set( 'categorias', $categorias );        
        
        // carrega produtos da categoria selecionada
        $produtos = $this->ProdutosFinder->clean()->porCategoria( $this->input->post( 'categoria' ) )->get();
        $this->view->set( 'produtos', $produtos );

        $search = array('.','/','-');
        $cpf = str_replace ( $search , '' , $this->input->post( 'cpf') );

        // pega o funcionario da venda
        $funcionario = $this->FuncionariosFinder->cpf( $cpf )->get(true);

        $data = date( 'Y-m-d', strtotime( $this->input->post( 'data' ) ) );
        
        // instancia um novo objeto classificacao
        $venda = $this->VendasFinder->getVenda();  
        
        $venda->cpf = $cpf;

        $venda->categoria = $this->input->post( 'categoria' );
      
        $venda->setData( $data ); 
        $venda->setQuantidade( $this->input->post( 'quantidade' ) );  
        $venda->setCod( $this->input->post( 'cod' ) );

        // verifica se existe produto
        if( !$produto ) {

            $this->view->set( 'errors', $this->view->item( 'errors' ) ? 
                            $this->view->item( 'errors' ).'Selecione um produto!<br>' : 'Selecione um produto!<br>' );
            
        } else {

            $pontos = $produto->pontos * $this->input->post( 'quantidade' );
            $venda->setProduto( $this->input->post( 'produto' ) );
            $venda->setPontos( $pontos );  

        }
        
        // retorna erro caso o funcionario não exista no sistema
        if( !$funcionario ) {

            $this->view->set( 'errors', $this->view->item( 'errors' ) ? 
                            $this->view->item( 'errors' ).'Funcionário inexistente no sistema!<br>' : $view->item( 'errors' ).'Funcionário inexistente no sistema!<br>' );
        }  else {            

            $funcionario->addPontos( $pontos );
            
            $venda->setLoja( $funcionario->loja );
            $venda->setFuncionario( $funcionario->CodFuncionario );
        }


        // verifica se o formulario é valido
        if ( !$this->_formularioVenda() || $this->view->item( 'errors' ) ) {

            // seta os erros de validacao            
            $this->view->set( 'venda', $venda );
            $this->view->set( 'errors', $this->view->item( 'errors' ) ? 
                            $this->view->item( 'errors' ).validation_errors() : validation_errors() );
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Adicionar venda' )->render( 'forms/venda' );
            return;
        }

        // verifica se o dado foi salvo
        if ( $venda->save() ) {
            redirect( site_url( 'vendas/index' ) );
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
        // Loja
        $linha['CodLoja'] = $this->verificaEntidade( 'LojasFinder', 'nome', $linha['NOMELOJA'], 'Lojas', 'Vendas', $num, 'CodLoja', 'I' ); 
        
        // Funcionario
        $linha['CodFuncionario'] = $this->verificaEntidade( 'FuncionariosFinder', 'cpf', $linha['CPFFUNCIONARIO'], 'Lojas', 'Vendas', $num, 'CodFuncionario', 'I' );

        // Produto
        $linha['CodProduto'] = $this->verificaEntidade( 'ProdutosFinder', 'basicCode', $linha['BASICCODE'], 'Produtos', 'Vendas', $num, 'CodProduto', 'I' );


        // verifica se existe um nome
        if ( !in_cell( $linha['CodProduto'] )  
        || !in_cell( $linha['QUANTIDADE'] )
        || !in_cell( $linha['CodLoja'] )
        || !in_cell( $linha['CodFuncionario'] )
        || !in_cell( $linha['DATA'] ) ) {

            // grava o log
            $this->LogsFinder->getLog()
            ->setEntidade( 'Vendas' )
            ->setPlanilha( $this->planilhas->filename )
            ->setMensagem( 'Não foi possivel inserir a Vendasenda pois os campos obrigatórios não foram informados, ou não estão corretos - linha '.$num )
            ->setData( date( 'Y-m-d H:i:s', time() ) )
            ->setStatus( 'B' )            
            ->save();

        } else {

            // carrega o produto da venda
            $produto = $this->ProdutosFinder->clean()->key( $linha['CodProduto'] )->get( true );
            $pontos = $produto->pontos * $linha['QUANTIDADE'];

            // formata a data
            
            $data = substr( $linha['DATA'], 6, 4) .'-' .substr( $linha['DATA'], 3, 2) .'-' .substr( $linha['DATA'], 0, 2);        

            // verifica se carregou
            $venda = $this->VendasFinder->clean()->getVenda();

            // preenche os dados
            $venda->setFuncionario( $linha['CodFuncionario'] );
            $venda->setQuantidade( $linha['QUANTIDADE'] );
            $venda->setProduto( $linha['CodProduto'] );            
            $venda->setPontos( $pontos );
            $venda->setData( $data );
            $venda->setLoja( $linha['CodLoja'] );

            // tenta salvar a venda
            if ( $venda->save() ) {

                // grava o log
                $this->LogsFinder->getLog()
                ->setEntidade( 'Vendas' )
                ->setPlanilha( $this->planilhas->filename )
                ->setMensagem( 'Venda criada com sucesso - '.$num )
                ->setData( date( 'Y-m-d H:i:s', time() ) )
                ->setStatus( 'S' )            
                ->save();

            } else {

                // grava o log
                $this->LogsFinder->getLog()
                ->setEntidade( 'Vendas' )
                ->setPlanilha( $this->planilhas->filename )
                ->setMensagem( 'Não foi possivel inserir a Venda - linha '.$num )
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
            echo 'aki';
            die;
            // seta os erros
            $this->view->set( 'errors', $this->planilhas->errors );
        } else {
            $planilha->apply( function( $linha, $num ) {
                $this->importar_linha_nova( $linha, $num );
            });
            $planilha->excluir();
        }

        // carrega a view
        $this->index();
    }

    
   /**
    * importar_linha_nova
    *
    * importa a linha
    *
    */
    public function importar_linha_nova( $linha, $num ) {

        // percorre todos os campos
        foreach( $linha as $chave => $coluna ) {
            $linha[$chave] = in_cell( $linha[$chave] ) ? $linha[$chave] : null;
        }

        // pega as entidades relacionaveis

        $neoCode = str_replace( [ '(', ')', ' ', '-', '.', '_' ], '', $linha['CODNEOTASS']);

        // busca o funcionario pelo neoCode
        $func = $this->FuncionariosFinder->clean()->neoCode( $neoCode )->get( true );

        // verifica se o funcionario nao existe
        if( !$func ){

            // busca os funcionarios com o nome
            $func = $this->FuncionariosFinder->clean()->nome( $linha['NOME'] )->get();

            // verifica se existe mais de 1
            if( count( $func ) > 1 ) {

                // percorre todos funcionarios com o nome informado
                foreach ($func as $key => $value) {
                    
                    // pega a loja do funcionario
                    $loja = $this->LojasFinder->clean()->key( $value->loja )->get( true );

                    // verifica se o nome da loja se encontra na linha da planilha
                    if( strpos( $linha['PDV'], $loja->nome  ) != -1 ){

                        // Loja
                        $linha['CodLoja'] = $this->verificaEntidade( 'LojasFinder', 'key', $loja->CodLoja, 'Lojas', 'Vendas', $num, 'CodLoja', 'I' );
                        
                        // seta o neoCode
                        $value->setNeoCode( $neoCode );

                        // Funcionario
                        $linha['CodFuncionario'] = $this->verificaEntidade( 'FuncionariosFinder', 'key', $value->CodFuncionario, 'Funcionarios', 'Vendas', $num, 'CodFuncionario', 'I' );  
                        
                        // salva a alteracao do neoCode
                        $value->save();
                        break;
                    }
                }
            } else if ( count( $func ) == 1 ) {

                // carrega a loja do funcionario
                $loja = $this->LojasFinder->clean()->key( $func[0]->loja )->get( true );

                // seta o neocode
                $func[0]->setNeoCode( $neoCode );

                // Funcionario
                $linha['CodFuncionario'] = $this->verificaEntidade( 'FuncionariosFinder', 'key', $func[0]->CodFuncionario, 'Funcionarios', 'Vendas', $num, 'CodFuncionario', 'I' );

                // salva
                $func[0]->save();

                // Loja
                $linha['CodLoja'] = $this->verificaEntidade( 'LojasFinder', 'key', $loja->CodLoja, 'Lojas', 'Vendas', $num, 'CodLoja', 'I' );
            } else {

                // log de erro
                $linha['CodFuncionario'] = $this->verificaEntidade( 'FuncionariosFinder', 'nome', $linha['NOME'], 'Funcionarios', 'Vendas', $num, 'CodFuncionario', 'I' );
                return;
            }
        } else {

            // Funcionario
            $linha['CodFuncionario'] = $this->verificaEntidade( 'FuncionariosFinder', 'key', $func->CodFuncionario, 'Funcionarios', 'Vendas', $num, 'CodFuncionario', 'I' );
            
            // Loja
            $linha['CodLoja'] = $this->verificaEntidade( 'LojasFinder', 'key', $func->loja, 'Lojas', 'Vendas', $num, 'CodLoja', 'I' );
        }

        // pega os 7 primeiros digitos do codigo do produto
        $refProduto = substr( $linha['CODPRODUTO'], 0, 7 );

        // Produto
        $linha['CodProduto'] = $this->verificaEntidade( 'ProdutosFinder', 'basicCode', $refProduto, 'Produtos', 'Vendas', $num, 'CodProduto', 'I' );

        // verifica se existe um nome
        if ( !in_cell( $linha['CodProduto'] )  
        || !in_cell( $linha['PONTO'] )
        || !in_cell( $linha['CodLoja'] )
        || !in_cell( $linha['CodFuncionario'] )
        || !in_cell( $linha['DATA'] ) ) {

            // grava o log
            $this->LogsFinder->getLog()
            ->setEntidade( 'Vendas' )
            ->setPlanilha( $this->planilhas->filename )
            ->setMensagem( 'Não foi possivel inserir a Vendasenda pois os campos obrigatórios não foram informados, ou não estão corretos - linha '.$num )
            ->setData( date( 'Y-m-d H:i:s', time() ) )
            ->setStatus( 'B' )            
            ->save();

        } else {

            // carrega o produto da venda
            $produto = $this->ProdutosFinder->clean()->key( $linha['CodProduto'] )->get( true );

            // ve a quantidade pelos pontos gerados pela venda
            $linha['QUANTIDADE'] = $linha['PONTO'] / $produto->pontos;

            // carrega o funcionario
            $func = $this->FuncionariosFinder->clean()->key( $linha['CodFuncionario'] )->get( true );

            // adiciona os pontos
            $func->addPontos( $linha['PONTO'] );

            // salva
            $func->save();

            // formata a data
            $data = substr( $linha['DATA'], 6, 4) .'-' .substr( $linha['DATA'], 0, 1) .'-' .substr( $linha['DATA'], 2, 2);        

            // verifica se carregou
            $venda = $this->VendasFinder->clean()->getVenda();

            // preenche os dados
            $venda->setFuncionario( $linha['CodFuncionario'] );
            $venda->setQuantidade( $linha['QUANTIDADE'] );
            $venda->setProduto( $linha['CodProduto'] );            
            $venda->setPontos( $linha['PONTO'] );
            $venda->setData( $data );
            $venda->setLoja( $linha['CodLoja'] );

            // tenta salvar a venda
            if ( $venda->save() ) {

                // grava o log
                $this->LogsFinder->getLog()
                ->setEntidade( 'Vendas' )
                ->setPlanilha( $this->planilhas->filename )
                ->setMensagem( 'Venda criada com sucesso - '.$num )
                ->setData( date( 'Y-m-d H:i:s', time() ) )
                ->setStatus( 'S' )            
                ->save();

            } else {

                // grava o log
                $this->LogsFinder->getLog()
                ->setEntidade( 'Vendas' )
                ->setPlanilha( $this->planilhas->filename )
                ->setMensagem( 'Não foi possivel inserir a Venda - linha '.$num )
                ->setData( date( 'Y-m-d H:i:s', time() ) )
                ->setStatus( 'B' )            
                ->save();
            }
        }
    }
    
    // public function calcular_pontos_iniciais() {

    //     // faz a busca
    //     $query = $this->db->query( " select SUM( Pontos ) as total, CodLoja from Vendas GROUP BY CodLoja " );

    //     // percorre os dados
    //     foreach( $query->result_array() as $item ) {

    //         // carrega o finder de lojas
    //         $this->load->finder( [ 'LojasFinder' ] );

    //         // carrega a loja
    //         $loja = $this->LojasFinder->clean()->key( $item['CodLoja'] )->get( true );
    //         $loja->setPontosIniciais( $item['total'] );
    //         $loja->save();
    //     }
    // }
}