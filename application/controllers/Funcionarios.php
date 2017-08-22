<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Funcionarios extends MY_Controller {

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
        $this->load->finder( [ 'LojasFinder', 'FuncionariosFinder', 'EstadosFinder', 'CidadesFinder' ] );
        
        // chama o modulo
        $this->view->module( 'navbar' )->module( 'aside' )->module( 'jquery-mask' );
    }

   /**
    * _formularioEstados
    *
    * valida o formulario de estados
    *
    */
    private function _formularioFuncionario() {

        // seta as regras
        $rules = [
            [
                'field' => 'loja',
                'label' => 'Loja',
                'rules' => 'required'
            ], [
                'field' => 'cpf',
                'label' => 'CPF',
                'rules' => 'required|trim'
            ], [
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'required|min_length[3]|trim'
            ], [
                'field' => 'cargo',
                'label' => 'Cargo',
                'rules' => 'required'
            ], [
                'field' => 'pontos',
                'label' => 'Pontos',
                'rules' => 'required'
            ],  [
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
                'field' => 'cep',
                'label' => 'Bairro',
                'rules' => 'min_length[9]|trim'
            ], [
                'field' => 'cidade',
                'label' => 'Cidade',
                'rules' => 'min_length[1]|trim'
            ], [
                'field' => 'estado',
                'label' => 'Estado',
                'rules' => 'min_length[1]|trim'
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
		$this->FuncionariosFinder->clean()->grid()

		// seta os filtros
        ->addFilter( 'CPF', 'text' )
        ->addFilter( 'NeoCode', 'text' )
        ->addFilter( 'Nome', 'text', false, 'f' )
        ->addFilter( 'CodLoja', 'select', $lojas, 'f' )
		->filter()
		->order()
		->paginate( 0, 20 )

		// seta as funcoes nas colunas
		->onApply( 'Ações', function( $row, $key ) {
			echo '<a href="'.site_url( 'funcionarios/alterar/'.$row[$key] ).'" class="margin btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil"></span></a>';
			echo '<a href="'.site_url( 'funcionarios/excluir/'.$row[$key] ).'" class="margin btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';            
		})

        // formata o Cnpj para exibicao
        ->onApply( 'CPF', function( $row, $key ) {
			echo mascara_cpf( $row[$key] );        
		})

		// renderiza o grid
		->render( site_url( 'funcionarios/index' ) );
		
        // seta a url para adiciona
        $this->view->set( 'add_url', site_url( 'funcionarios/adicionar' ) )
        ->set( 'import_url', site_url( 'funcionarios/importar_planilha' ) )
        ->set( 'export_url', site_url( 'funcionarios/exportar_planilha' ) );

		// seta o titulo da pagina
		$this->view->setTitle( 'Funcionários - listagem' )->render( 'grid' );
    }

   /**
    * exportar_planilha
    *
    * exportar a planilha
    *
    */
    public function exportar_planilha() {

        header("Content-type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=FuncionariosExportação".date( 'H:i d-m-Y', time() ).".xls" );

        // faz a paginacao
		$this->FuncionariosFinder->clean()->exportar()
        ->paginate( 1, 0, false, false )

        ->onApply( '*', function( $row, $key ) {
            echo strtoupper( mb_convert_encoding( $row[$key], 'UTF-16LE', 'UTF-8' ) );
        })

		// renderiza o grid
		->render( site_url( 'funcionarios/index' ) );

		// seta o titulo da pagina
		$html = $this->view->component( 'table' );
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
        
        // carrega os estados
        $estados = $this->EstadosFinder->get();
        $this->view->set( 'estados', $estados );

        // carrega os lojas
        $lojas = $this->LojasFinder->get();
        $this->view->set( 'lojas', $lojas );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Adicionar funcionário' )->render( 'forms/funcionario' );
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
        $funcionario = $this->FuncionariosFinder->key( $key )->get( true );
        
        // carrega os estados
        $estados = $this->EstadosFinder->get();
        $this->view->set( 'estados', $estados );

        // carrega os lojas
        $lojas = $this->LojasFinder->get();
        $this->view->set( 'lojas', $lojas );

        // verifica se o mesmo existe
        if ( !$funcionario ) {
            redirect( 'funcionarios/index' );
            exit();
        }

        if( $funcionario->estado ) {
        
            // carrega as cidades
            $cidades = $this->CidadesFinder->clean()->porEstado( $funcionario->estado )->get();
            $this->view->set( 'cidades', $cidades );
        }

        // salva na view
        $this->view->set( 'funcionario', $funcionario );

        // carrega a view de adicionar
        $this->view->setTitle( 'Samsung - Alterar funcionário' )->render( 'forms/funcionario' );
    }

   /**
    * excluir
    *
    * exclui um item
    *
    */
    public function excluir( $key ) {
        $funcionario = $this->FuncionariosFinder->getFuncionario();
        $funcionario->setCod( $key );
        $funcionario->delete();
        $this->index();
    }

   /**
    * salvar
    *
    * salva os dados
    *
    */
    public function salvar() {
        
        // carrega os lojas
        $lojas = $this->LojasFinder->get();
        $this->view->set( 'lojas', $lojas );

        $search = array('.','/','-','(',')',' ');
        $cpf = str_replace ( $search , '' , $this->input->post( 'cpf') );
        $cep = str_replace ( $search , '' , $this->input->post( 'cep') );
        $celular = str_replace ( $search , '' , $this->input->post( 'celular') );
        $rg = str_replace ( $search , '' , $this->input->post( 'rg') );

        // instancia um novo objeto classificacao
        $funcionario = $this->FuncionariosFinder->getFuncionario();
        $funcionario->setLoja( $this->input->post( 'loja' ) );
        $funcionario->setCpf( $cpf );
        $funcionario->setNome( $this->input->post( 'nome' ) );
        $funcionario->setCargo( $this->input->post( 'cargo' ) );
        $funcionario->setPontos( $this->input->post( 'pontos' ) );
        $funcionario->setEndereco( $this->input->post( 'endereco' ) );
        $funcionario->setNumero( $this->input->post( 'numero' ) );
        $funcionario->setComplemento( $this->input->post( 'complemento' ) );
        $funcionario->setCep( $cep );
        $funcionario->setCidade( $this->input->post( 'cidade' ) );
        $funcionario->setEstado( $this->input->post( 'estado' ) );
        $funcionario->setCelular( $celular );
        $funcionario->setRg( $rg );
        $funcionario->setCod( $this->input->post( 'cod' ) );

        // verifica se o formulario é valido
        if ( !$this->_formularioFuncionario() ) {

            // seta os erros de validacao            
            $this->view->set( 'funcionario', $funcionario );
            $this->view->set( 'errors', validation_errors() );
            
            // carrega a view de adicionar
            $this->view->setTitle( 'Samsung - Adicionar funcionário' )->render( 'forms/funcionario' );
            return;
        }

        // verifica se o dado foi salvo
        if ( $funcionario->save() ) {
            redirect( site_url( 'funcionarios/index' ) );
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
        $linha['CodLoja'] = $this->verificaEntidade( 'LojasFinder', 'nome', $linha['CNPJ(Loja)'], 'Lojas', 'Funcionarios', $num, 'CodLoja', 'I' );

        // verifica se existe um nome
        if ( !in_cell( $linha['CPF'] ) ) {

            // grava o log
            $this->LogsFinder->getLog()
            ->setEntidade( 'Funcionarios' )
            ->setPlanilha( $this->planilhas->filename )
            ->setMensagem( 'Não foi possivel inserir o funcionario pois nenhum CPF foi informado - linha '.$num )
            ->setData( date( 'Y-m-d H:i:s', time() ) )
            ->setStatus( 'B' )            
            ->save();

        } else {

            // tenta carregar a loja pelo nome
            $func = $this->FuncionariosFinder->clean()->cpf( $linha['CPF'] )->get( true );

            // verifica se carregou
            if ( !$func ) $func = $this->FuncionariosFinder->getFuncionario();

            // preenche os dados
            $func->setCargo( $linha['Cargo'] );
            $func->setNome( $linha['Nome'] );
            $func->setCpf( $linha['CPF'] );
            $func->setLoja( $linha['CodLoja'] );

            // tenta salvar a loja
            if ( $func->save() ) {

                // grava o log
                $this->LogsFinder->getLog()
                ->setEntidade( 'Funcionarios' )
                ->setPlanilha( $this->planilhas->filename )
                ->setMensagem( 'Funcionario criada com sucesso - '.$num )
                ->setData( date( 'Y-m-d H:i:s', time() ) )
                ->setStatus( 'S' )            
                ->save();

            } else {

                // grava o log
                $this->LogsFinder->getLog()
                ->setEntidade( 'Funcionarios' )
                ->setPlanilha( $this->planilhas->filename )
                ->setMensagem( 'Não foi possivel inserir o funcionario - linha '.$num )
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

    /**
    * similarity
    *
    * calcula a similaridade entre duas strings
    *
    */
    public function similarity($str1, $str2) {
        $len1 = strlen($str1);
        $len2 = strlen($str2);
        
        $max = max($len1, $len2);
        $similarity = $i = $j = 0;
        
        while (($i < $len1) && isset($str2[$j])) {
            if ($str1[$i] == $str2[$j]) {
                $similarity++;
                $i++;
                $j++;
            } elseif ($len1 < $len2) {
                $len1++;
                $j++;
            } elseif ($len1 > $len2) {
                $i++;
                $len1--;
            } else {
                $i++;
                $j++;
            }
        }

        return round($similarity / $max, 2);
    }

   /**
    * importar_linha_neocode
    *
    * importa a linha adicionando o neocode na planilha
    *
    */
    public function importar_linha_neocode( $linha, $num ) {

        // verifica se tem cluster
        if ( !in_cell( $linha['CLUSTER'] ) ) return;

        // carrega o finder do funcionario
        $this->load->finder( [ 'FuncionariosFinder', 'LojasFinder' ] );

        // tenta carregar o funcionario pelo nome
        $funcs = $this->FuncionariosFinder->clean()->nome( strtoupper( $linha['NOMENEO'] ) )->get();

        // verifica quantos funcionarios foram encontrados
        $cont = count( $funcs );

        // faz o switch
        switch( $cont ) {
            case 0:
            break;
            case 1:

                // prepara os dados
                $dados = [
                    'neo'     => str_replace(  '-', '', $linha['COD_NEO'] ),
                    'func_id' => $funcs[0]->CodFuncionario,
                ];

                // insere
                $this->db->insert( 'temp_table', $dados );

            break;
            default:
                foreach( $funcs as $func ) {
                    
                    // pega a loja
                    $loja = $this->LojasFinder->clean()->key( $func->loja )->get( true );

                    // verifica a similiraridade no nome da loja
                    $percent = $this->similarity( $linha['PDV'], $loja->nome );

                    if ( $percent > 0.3 ) {
                        
                        // prepara os dados
                        $dados = [
                            'neo'           => str_replace( '-', '', $linha['COD_NEO'] ),
                            'func_id'       => $func->CodFuncionario,
                        ];

                        // insere
                        $this->db->insert( 'temp_table', $dados );
                    };
                }
            break;
        };
    }
}
