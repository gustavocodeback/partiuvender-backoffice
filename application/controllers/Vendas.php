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
        $this->load->finder( [ 'FuncionariosFinder', 'LogsFinder', 'CategoriasFinder', 'ProdutosFinder', 'VendasFinder', 'LojasFinder' ] );
        
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
                'rules' => 'required|min_length[10]|trim'
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
        ->order()
        ->addFilter( 'CodLoja', 'select', $lojas, 'v' )
        ->addFilter( 'CodFuncionario', 'text', false, 'f' )
        ->addFilter( 'NeoCode', 'text', false, 'f' )
		->filter()
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

        $export_url = site_url( 'vendas/exportar_planilha?' );

        foreach ($_GET as $key => $value) {
            if ( $key != 'page' ) {
                $export_url .= $key .'=' .$value .'&';
            }
        }

        $export_url = trim( $export_url, '&' );

        // seta a url para adiciona
        $this->view->set( 'add_url', site_url( 'vendas/adicionar' ) )
        ->set( 'import_url', site_url( 'vendas/importar_planilha' ) )             
        ->set( 'export_url', $export_url );

		// seta o titulo da pagina
		$this->view->setTitle( 'Vendas - listagem' )->render( 'grid' );
    }

    public function exportar_planilha() {

        // carrega os categorias
        $lojas = $this->LojasFinder->filtro();

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=LojasExportação".date( 'H:i d-m-Y', time() ).".xls" );

        // faz a paginacao
		$this->VendasFinder->clean()->exportar()
        ->addFilter( 'CodLoja', 'select', $lojas, 'v' )
        ->addFilter( 'CodFuncionario', 'text', false, 'f' )
        ->addFilter( 'NeoCode', 'text', false, 'f' )
		->filter()
        ->paginate( 1, 0, false, false )
        

        ->onApply( '*', function( $row, $key ) {
            echo strtoupper( mb_convert_encoding( $row[$key], 'UTF-16LE', 'UTF-8' ) );
        })
        
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

                // carrega o finder da loja
                $this->load->finder( 'LojasFinder' );
                $loja = $this->LojasFinder->clean()->key( $linha['CodLoja'] )->get( true );

                $loja->setPontosAtuais( $loja->pontosatuais += $l['tvalor'] );
                $loja->save();

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

        // $l = $linha;
        foreach( $linha as $chave => $coluna ) {
           $a = utf8_encode($chave);
           $t = utf8_encode( $linha[$chave] );
           $l[$a] = in_cell( $linha[$chave] ) ? $t : null;
        }
        
        // pega as entidades relacionaveis
        $neoCode = str_replace( [ '(', ')', ' ', '-', '.', '_' ], '', $l['CODNEOTASS']);

        // Funcionario
        $l['CodFuncionario'] = $this->verificaEntidade( 'FuncionariosFinder', 'neoCode', $neoCode, 'Funcionarios', 'Vendas', $num, 'CodFuncionario', 'I' );

        // busca o funcionario pelo neoCode
        $func = $this->FuncionariosFinder->clean()->neoCode( $neoCode )->get( true );
        
        $l['CodLoja'] = false;
        if( $func && isset( $func->loja ) ){

            // Loja
            $l['CodLoja'] = $this->verificaEntidade( 'LojasFinder', 'key', $func->loja, 'Lojas', 'Vendas', $num, 'CodLoja', 'I' );
        } else {
            $l['CodLoja'] = false;
        }
        
        // pega os 7 primeiros digitos do codigo do produto
        $refProduto = substr( $l['Referência'], 0, 7 );
        
        // Produto
        $l['CodProduto'] = $this->verificaEntidade( 'ProdutosFinder', 'basicCode', $refProduto, 'Produtos', 'Vendas', $num, 'CodProduto', 'I' );

        // carrega o produto da venda
        $produto = $this->ProdutosFinder->clean()->key( $l['CodProduto'] )->get( true );

        // ve a quantidade pelos pontos gerados pela venda
        $qtd = $l['tponto'] / $produto->pontos;

        // verifica se existe um nome
        if ( !in_cell( $l['CodProduto'] )  
        || !in_cell( $l['tponto'] )
        || !in_cell( $l['CodFuncionario'] )
        || !in_cell( $l['DataDocumento'] ) 
        || ( $qtd != $l['Quantidade'] && $produto->pontos != $l['ponto'] ) ) {

            // grava o log
            $this->LogsFinder->getLog()
            ->setEntidade( 'Vendas' )
            ->setPlanilha( $this->planilhas->filename )
            ->setMensagem( 'Não foi possivel inserir a Vendasenda pois os campos obrigatórios não foram informados, ou não estão corretos - linha '.$num )
            ->setData( date( 'Y-m-d H:i:s', time() ) )
            ->setStatus( 'B' )            
            ->save();

        } else {

            // formata a data
            if( strlen( $l['DataDocumento'] ) == 9 ) $data = substr( $l['DataDocumento'], 5, 4) .'-' .substr( $l['DataDocumento'], 3, 1) .'-' .substr( $l['DataDocumento'], 0, 2);
            if( strlen( $l['DataDocumento'] ) == 8 ) $data = substr( $l['DataDocumento'], 4, 4) .'-' .substr( $l['DataDocumento'], 2, 1) .'-' .substr( $l['DataDocumento'], 0, 1);

            // verifica se carregou
            $venda = $this->VendasFinder->clean()->getVenda();

            // preenche os dados
            $venda->setFuncionario( $l['CodFuncionario'] );
            $venda->setQuantidade( $l['Quantidade'] );
            $venda->setProduto( $l['CodProduto'] );            
            $venda->setPontos( $l['tponto'] );
            $venda->setData( $data );

            if( $l['CodLoja'] ) $venda->setLoja( $l['CodLoja'] );

            // tenta salvar a venda
            if ( $venda->save() ) {
               
                if( $l['CodLoja'] ) {

                    // carrega o finder da loja
                    $this->load->finder( 'LojasFinder' );
                    $loja = $this->LojasFinder->clean()->key( $l['CodLoja'] )->get( true );
                    $l['tvalor'] = str_replace( [ ',' ], '.', $l['tvalor']);
                    $loja->setPontosAtuais( $l['tvalor'] );
                    $loja->save();
                }

                // adiciona os pontos
                $func->addPontos( $l['tponto'] );

                // salva
                $func->save();

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
    * importar_linha_nova
    *
    * importa a linha
    *
    */
    public function importar_linha_pontos( $linha, $num ) {
        
        $l = $linha;
        // percorre todos os campos
        //foreach( $linha as $chave => $coluna ) {
          //  $a = utf8_encode($chave);
          //  $t = utf8_encode( $linha[$chave] );
          //  $l[$a] = in_cell( $linha[$chave] ) ? $t : null;
        // }

        $loja = $this->LojasFinder->clean()->nome( trim( $l['PDV'] ) )->get( true );

        if( !$loja ) {

            // grava o log
            $this->LogsFinder->getLog()
            ->setEntidade( 'Lojas' )
            ->setPlanilha( $this->planilhas->filename )
            ->setMensagem( 'Não foi possivel inserir os pontos iniciais pois o PDV esta incorreto - linha '.$num )
            ->setData( date( 'Y-m-d H:i:s', time() ) )
            ->setStatus( 'B' )            
            ->save();
            return;
        }
        
        // pega os 7 primeiros digitos do codigo do produto
        $refProduto = substr( $l['Referência'], 0, 7 );
        
        // Produto
        $l['CodProduto'] = $this->verificaEntidade( 'ProdutosFinder', 'basicCode', $refProduto, 'Produtos', 'Vendas', $num, 'CodProduto', 'I' );

        // carrega o produto da venda
        $produto = $this->ProdutosFinder->clean()->key( $l['CodProduto'] )->get( true );

        // ve a quantidade pelos pontos gerados pela venda
        $pontos = $l['Quantidade'] * $produto->pontos;

        // verifica se existe um nome
        if ( !in_cell( $l['CodProduto'] ) ) {

            // grava o log
            $this->LogsFinder->getLog()
            ->setEntidade( 'Lojas' )
            ->setPlanilha( $this->planilhas->filename )
            ->setMensagem( 'Não foi possivel inserir os pontos iniciais pois os campos obrigatórios não foram informados, ou não estão corretos - linha '.$num )
            ->setData( date( 'Y-m-d H:i:s', time() ) )
            ->setStatus( 'B' )            
            ->save();

        } else {

            // preenche os dados
            $loja->setPontosIniciais( $loja->pontosiniciais + $l['ValorTotal'] );

            // tenta salvar a venda
            if ( $loja->save() ) {

                // grava o log
                $this->LogsFinder->getLog()
                ->setEntidade( 'Lojas' )
                ->setPlanilha( $this->planilhas->filename )
                ->setMensagem( 'Pontos iniciais alterado com sucesso - '.$num )
                ->setData( date( 'Y-m-d H:i:s', time() ) )
                ->setStatus( 'S' )            
                ->save();

            } else {

                // grava o log
                $this->LogsFinder->getLog()
                ->setEntidade( 'Lojas' )
                ->setPlanilha( $this->planilhas->filename )
                ->setMensagem( 'Não foi possivel inserir os pontos iniciais - linha '.$num )
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