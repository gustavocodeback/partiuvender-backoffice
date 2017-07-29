<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

    // indica se o controller é publico
	protected $public = true;

   /**
     * __construct
     *
     * metodo construtor
     *
     */
    public function __construct() {
        parent::__construct();
        // adiciona o json ao post
        $data = json_decode(file_get_contents('php://input'), true);
        if ( $data ) $_POST = $data; 
        
        // carrega o finder
        $this->load->library( [ 'Response', 'Request' ] );
    }

    // lista as lojas cadastradas no sistema
    public function obter_lojas() {

        // carrega o finder
        $this->load->finder( [ 'LojasFinder' ] );

        // carrega as lojas
        $lojas = $this->LojasFinder->get();

        // faz o mapeamento do array
        $lojas = array_map( function( $value ) {
            return [
                'cod'   => $value->CodLoja,
                'razao' => $value->razao,
                'nome'  => $value->nome
            ];
        }, $lojas );

        // envia as lojas
        $this->response->resolve( $lojas );
    }

    /**  -----------------------------------------------------------
     * 
     * METODOS DE LOGIN
     *
     * --------------------------------------------------------------- */

    /**
     * verificar_cpf
     *
     * verifica se existe um cpf para o usuario digitado
     *
     */
    public function verificar_cpf( $cpf ) {

        // carrega os finders
        $this->load->finder( [ 'FuncionariosFinder' ] );

        // verifica se o cpf eh valido
        if ( !$this->valid_cpf( $cpf ) ) return $this->response->reject( 'O CPF informado é inválido.' );

        // carrega pelo cpf
        $func = $this->FuncionariosFinder->clean()->cpf( $cpf )->get( true );
        if ( !$func ) return $this->response->reject( 'Nenhum funcionário encontrado para esse CPF.' );

        // devolve o funcionario
        $data = [
            'nome'  => $func->nome,
            'cpf'   => $func->cpf,
            'cargo' => $func->cargo,
            'uid'   => $func->uid,       
        ];
        return $this->response->resolve( $data );
    }

    /**
     * salvar_cpf
     *
     * salva o cpf
     *
     */
    public function salvar_cpf() {

        // carrega o finder
        $this->load->finder( [ 'FuncionariosFinder' ] );

        // pega os dados
        $cpf   = $this->input->post( 'cpf' );
        $nome  = $this->input->post( 'nome' );
        $loja  = $this->input->post( 'loja' );
        $cargo = $this->input->post( 'cargo' );

        // verifica se o cpf eh valido
        if ( !$this->valid_cpf( $cpf ) ) return $this->response->reject( 'O CPF informado é inválido.' );

        // carrega uma instancia do funcionario
        $func = $this->FuncionariosFinder->getFuncionario();

        // seta as propriedades
        $func->setCpf( $cpf );
        $func->setNome( $nome );
        $func->setLoja( $loja );
        $func->setCargo( $cargo );
        $func->setPlataforma( 'A' );

        // tenta salvar o funcionario
        if ( $func->save() ) {
            return $this->response->resolve( $func->serialize() );
        } else return $this->response->reject( 'Nao foi possivel criar o funcionário' );
    }

    /**
     * salvar_uid
     *
     * salva um uid para um cpf
     *
     */
    public function salvar_uid( $cpf ) {

        // carrega os finders
        $this->load->finder( [ 'FuncionariosFinder', 'LojasFinder', 'ClustersFinder' ] );

        // verifica se o cpf eh valido
        if ( !$this->valid_cpf( $cpf ) ) return $this->response->reject( 'O CPF informado é inválido.' );

        // carrega pelo cpf
        $func = $this->FuncionariosFinder->clean()->cpf( $cpf )->get( true );
        if ( !$func ) return $this->response->reject( 'Nenhum funcionário encontrado para esse CPF.' );

        // carrega a loja do funcionario
        $loja = $this->LojasFinder->clean()->key( $func->loja )->get( true );

        // carrega o cluster da loja
        $cluster = $this->ClustersFinder->clean()->key( $loja->cluster )->get( true );

        // pega o uid
        $uid = $this->input->post( 'uid' );
        if ( !$uid ) return $this->response->reject( 'Nenhum UID informado.' );
        $func->setUid( $uid );

        $modal = false;
        if( !$func->endereco || !$func->numero || !$func->cep 
            || !$func->estado || !$func->cidade || !$func->celular || !$func->rg ) $modal = true;

        // faz o update
        if ( $func->save() ) {

            // devolve o funcionario
            $data = [
                'nome'      => $func->nome,
                'cpf'       => $func->cpf,
                'cargo'     => $func->cargo,
                'uid'       => $func->uid,
                'loja'      => $loja->nome,
                'cluster'   => $cluster->nome,
                'modal'     => $modal     
            ];
            return $this->response->resolve( $data );

        } else return $this->response->reject( 'Houve um erro ao tentar salvar o UID desse funcionário.' );
    }
    
    /**
     * salvar_dados
     *
     * salva o endereco, telefone e rg do funcionario
     *
     */
    public function salvar_dados() {

        // carrega os finders
        $this->load->finder( [ 'FuncionariosFinder'] );
        
        // seta o funcionario
        if( $this->input->post( 'cpf' ) ) {
            $funcionario = $this->FuncionariosFinder->clean()->cpf( $this->input->post( 'cpf' ) )->get( true );
        }
        if( $this->input->post( 'uid' ) ) {
            $funcionario = $this->FuncionariosFinder->clean()->uid( $this->input->post( 'uid' ) )->get( true );
        }
                
        // verifica se o funcionario existe
        if( !$funcionario ) return $this->request->reject( 'Erro, tente mais tarde' );

        $funcionario->setCelular( $this->input->post( 'celular' ) );
        $funcionario->setRg( $this->input->post( 'rg' )  );        
        $funcionario->setEndereco( $this->input->post( 'endereco' )  );
        $funcionario->setNumero( $this->input->post( 'numero' )  );
        $funcionario->setComplemento( $this->input->post( 'complemento' )  );
        $funcionario->setCep( $this->input->post( 'cep' )  );
        $funcionario->setCidade( $this->input->post( 'cidade' )  );
        $funcionario->setEstado( $this->input->post( 'estado' )  );

        // faz o update
        if ( $funcionario->save() ) {

            return $this->response->resolve( $funcionario );

        } else return $this->response->reject( 'Houve um erro ao tentar salvar o endereço desse funcionário.' );
    }

    /**
     * obter_funcionario
     *
     * busca um funcionario
     *
     */
     public function obter_funcionario() {         

        $CodFuncionario = $this->request->user()->CodFuncionario;

        // carrega o funcionario
        $funcionario = $this->FuncionariosFinder->clean()->key( $CodFuncionario )->get( true );

        if( !$funcionario ) $this->response->reject( 'Funcionario inexistente' );
        return $this->response->resolve( $funcionario );
     }

    /**  -----------------------------------------------------------
     * 
     * METODOS DE PRODUTOS
     *
     * --------------------------------------------------------------- */

    /**
     * obter_produtos_categoria
     *
     * busca os produtos de uma determinada categoria
     *
     */
    public function obter_produtos_categoria( $CodCategoria, $indice ) {

        // carrega o finder
        $this->load->finder( [ 'ProdutosFinder', 'CategoriasFinder' ] );

        // carrega o categoria
        $categoria = $this->CategoriasFinder->key( $CodCategoria )->get( true );
        
        if ( !$categoria ) return $this->response->reject( 'A Categoria informada é inválida.' );
        
        // carrega os produtos da categoria
        $produtos = $this->ProdutosFinder
        ->porCategoria( $categoria->CodCategoria )
		->paginate( $indice, 5, true );
        if ( count( $produtos ) == 0 ) {
            return $this->response->resolve( [] );
        }

        // faz o mapeamento das cidades
        $produtos = array_map( function( $produto ) {
            return  [ 
                        'CodProduto'    => $produto->CodProduto, 
                        'CodCategoria'  => $produto->categoria,
                        'BasicCode'     => $produto->basiccode,
                        'Nome'          => $produto->nome,
                        'Pontos'        => $produto->pontos,
                        'Foto'          => $produto->foto ? base_url('uploads/' .$produto->foto) : $produto->foto,
                        'Descricao'     => $produto->descricao,
                        'Video'         => $produto->video
                    ];
        }, $produtos );

        return $this->response->resolve( $produtos );
    }

    /**
     * obter_categorias
     *
     * busca todas categorias
     *
     */
    public function obter_categorias() {

        // carrega o finder
        $this->load->finder( [ 'CategoriasFinder' ] );

        // carrega a library de request
        $this->load->library( [ 'Request', 'Response' ] );

        // carrega as categorias
        $categorias = $this->CategoriasFinder->get();
        
        if ( !$categorias ) return $this->response->reject( 'Não tem categorias.' );

        if ( count( $categorias ) == 0 ) {
            return $this->response->resolve( [] );
        }

        // faz o mapeamento das cidades
        $categorias = array_map( function( $categoria ) {
            return  [ 
                        'CodCategoria' => $categoria->CodCategoria,
                        'Nome' => $categoria->nome,
                        'Foto' => base_url('uploads/' .$categoria->foto)
                    ];
        }, $categorias );

        return $this->response->resolve( $categorias );
    }

    /**  -----------------------------------------------------------
     * 
     * METODOS DE QUIZ
     *
     * --------------------------------------------------------------- */

    /**
     * obter_questionarios
     *
     * lista os questionarios
     *
     */
    public function obter_questionarios( $indice ) {

        // carrega o finder
        $this->load->finder( [ 'QuestionariosFinder', 'PerguntasFinder' ] );

        // carrega os produtos da categoria
        $questionarios = $this->QuestionariosFinder
		->paginate( $indice, 5, true );

        // verifica se existem questionarios
        if ( count( $questionarios ) == 0 ) {
            return $this->response->resolve( [] );
        }

        // faz o mapeamento das cidades
        $questionarios = array_map( function( $questionario ) {

            // obtem as perguntas
            $perguntas = $this->PerguntasFinder->clean()->quiz( $questionario->CodQuestionario )->get();
            $sum = 0;
            $tot = 0;

            // percorre as perguntas
            if ( is_array( $perguntas ) ) {
                foreach( $perguntas as $pergunta ) {
                    $sum += $pergunta->pontos;
                    $tot++;
                }
            }

            // verifica se esta encerrado
            $encerrado = $questionario->encerrado( $this->request->user()->CodFuncionario );

            // volta os dados formatados
            return  [ 
                        'CodQuestionario' => $questionario->CodQuestionario, 
                        'Nome'            => $questionario->nome,
                        'Foto'            => base_url( 'uploads/' .$questionario->foto ),                        
                        'Descricao'       => $questionario->descricao,
                        'Pontos'          => $sum,
                        'Total'           => $tot,
                        'Encerrado'       => $encerrado ? true : false,
                        'Acertadas'       => $encerrado ? $encerrado['Pontos'] : 0
                    ];
        }, $questionarios );

        return $this->response->resolve( $questionarios );
    }

    /**
     * obter_questionarios
     *
     * lista os questionarios
     *
     */
    public function obter_perguntas( $quiz ) {

        // carrega o finder
        $this->load->finder( [ 'PerguntasFinder' ] );

        // carrega os produtos da categoria
        $perguntas = $this->PerguntasFinder
		->quiz( $quiz )->get();

        // verifica se existem questionarios
        if ( count( $perguntas ) == 0 ) {
            return $this->response->resolve( [] );
        }

        // faz o mapeamento das cidades
        $perguntas = array_map( function( $perguntas ) {
            return  [ 
                        'CodPergunta'     => $perguntas->CodPergunta, 
                        'Texto'           => $perguntas->texto,
                        'Alternativa1'    => $perguntas->alternativa1,                        
                        'Alternativa2'    => $perguntas->alternativa2,                        
                        'Alternativa3'    => $perguntas->alternativa3,                        
                        'Alternativa4'    => $perguntas->alternativa4,
                        'Respondida'      => $perguntas->respondida( $this->request->user()->CodFuncionario )
                    ];
        }, $perguntas );

        // volta as perguntas
        return $this->response->resolve( $perguntas );
    }

    /**
     * responder_pergunta
     *
     * responde uma pergunta
     *
     */
    public function responder_pergunta( $id ) {

        // carrega os finders
        $this->load->finder( [ 'RespostasFinder', 'PerguntasFinder' ] );

        // carrega a pergunta
        $pergunta = $this->PerguntasFinder->key( $id )->get( true );
        if ( !$pergunta ) return $this->response->reject( 'A pergunta nao existe' );

        // pega a alternativa
        $alternativa = $this->input->post( 'resposta' );
        if ( !in_array( $alternativa, [ 1, 2, 3, 4 ] ) ) return $this->response->reject( 'Alternativa inexistente' );

        // carrega a resposta
        $resposta = $this->RespostasFinder->clean()
                    ->func( $this->request->user()->CodFuncionario )
                    ->pergunta( $pergunta->CodPergunta )
                    ->get( true );

        // verifica se ja existe uma resposta
        if ( $resposta ) return $this->response->reject( 'Usuario já respondeu a essa pergunta' );

        // prepara a resposta
        $resposta = $this->RespostasFinder->getResposta();
        $resposta->setUsuario( $this->request->user()->CodFuncionario )
        ->setPergunta( $pergunta->CodPergunta )
        ->setAlternativa( $alternativa );

        // salva a resposta
        if ( $resposta->save() ) {
            return $this->response->resolve( 'Resposta registrada com sucesso' );
        } else {
            return $this->response->reject( 'Erro ao salvar a resposta.' );
        }
    }

    /**
     * encerrar_questionario
     *
     * encerra um questionario
     *
     */
    public function encerrar_questionario( $id ) {

        // carrega o finder
        $this->load->finder( [ 'QuestionariosFinder', 'PerguntasFinder' ] );

        // carrega o questionario
        $questionario = $this->QuestionariosFinder->clean()->key( $id )->get( true );
        if ( !$questionario ) return $this->response->reject( 'Questionario inexistente' );

        // verifica se o mesmo ja nao foi encerrado
        if ( $questionario->encerrado(  $this->request->user()->CodFuncionario  ) ) {
            $this->response->reject( 'Este quiz ja foi encerrado' );
        } else {

            // pega o funcionario
            $CodFunc = $this->request->user()->CodFuncionario;

            // carrega os produtos da categoria
            $perguntas = $this->PerguntasFinder->quiz( $questionario->CodQuestionario )->get();

            // total
            $sum = 0;

            // percorre as perguntas
            foreach( $perguntas as $pergunta ) {

                // verifica se esta correta
                if ( $pergunta->correta( $CodFunc ) ) {
                    $sum += $pergunta->pontos;
                }
            }

            // carrega o funcionario
            $func = $this->FuncionariosFinder->clean()->key( $this->request->user()->CodFuncionario )->get( true );

            // fecha o questionario
            if ( $questionario->encerrar( $CodFunc, $sum ) ) {

                // seta os pontos
                $func->addPontos( $sum );

                // exibe o resultado
                $this->response->resolve( 'Questionario fechado.' );    
            } else return $this->response->reject( 'Erro ao encerrar o quis' );
        }
    }

    /**
     * obter_gabarito
     *
     * mostra o gabarito de um questionario
     *
     */
    public function obter_gabarito( $id ) {
        
        // carrega o finder
        $this->load->finder( [ 'QuestionariosFinder', 'PerguntasFinder' ] );

        // carrega o questionario
        $questionario = $this->QuestionariosFinder->clean()->key( $id )->get( true );
        if ( !$questionario ) return $this->response->reject( 'Questionario inexistente' );

        // verifica se o questionario foi encerrado
        if ( !$questionario->encerrado( $this->request->user()->CodFuncionario ) )
            return $this->response->reject( 'Questionario nao finalizado' );

        // pega as perguntas
        $perguntas = $this->PerguntasFinder->clean()->quiz( $id )->get();

        // verifica se existem questionarios
        if ( count( $perguntas ) == 0 ) return $this->response->resolve( [] );
        
        // faz o mapeamento
        $perguntas = array_map( function ( $pergunta ) {
            $indice = 'alternativa'.$pergunta->resposta;
            return  [ 
                'CodPergunta'  => $pergunta->CodPergunta, 
                'Texto'        => $pergunta->texto,
                'Alternativa1' => $pergunta->alternativa1,                        
                'Alternativa2' => $pergunta->alternativa2,                        
                'Alternativa3' => $pergunta->alternativa3,                        
                'Alternativa4' => $pergunta->alternativa4,
                'Resposta'     => $pergunta->resposta,
                'Status'       => $pergunta->correta( $this->request->user()->CodFuncionario ),                    
                'Respondida'   => $pergunta->respondida( $this->request->user()->CodFuncionario )['Alternativa']
            ];
        }, $perguntas );

        // volta a resposta
        $this->response->resolve( $perguntas );
    }

    /**  -----------------------------------------------------------
     * 
     * METODOS DE TRANSACOES
     *
     * ------------------------------------------------------------- */
    public function obter_extrato( $page = 1 ) {

        // carrega os finders
        $this->load->finder( 'FuncionariosFinder' );

        // pega o usuario
        $user = $this->request->user();

        // obtem o funcionario
        $func = $this->FuncionariosFinder->clean()->key( $user->CodFuncionario )->get( true );
        
        // obtem o extrato
        $extrato = $func->obterExtrato( $page );

        // faz o mapeamento do extrato
        $extrato = array_map( function( $item ) {

            // pega os dados da data
            $time = strtotime( $item['Data'] );
            $dia  = date( 'd',  $time );
            $mes  = date( 'M', $time );
            $hora = date( 'H:i', $time );

            // retorna os dados
            return [
                'Item'   => $item['Item'],
                'Pontos' => $item['Pontos'],
                'Dia'    => $dia,
                'Mes'    => $mes,
                'Hora'   => $hora
            ];
        }, $extrato );

        // volta os dados
        $this->response->resolve( $extrato );
    }

    /**  -----------------------------------------------------------
     * 
     * METODOS DE RANKING
     *
     * ------------------------------------------------------------- */

    /**
     * obter_ranking_loja
     *
     * pega o ranking da loja
     *
     */
     public function obter_ranking_loja() {

        // carrega os finders
        $this->load->finder( [ 'FuncionariosFinder' ] );

        // obtem o funcionario
        $func = $this->FuncionariosFinder
                ->clean()
                ->key( $this->request->user()->CodFuncionario )
                ->get( true );

        // filtrar por loja
        $funcionarios = $this->FuncionariosFinder
                        ->clean()
                        ->loja( $func->loja )
                        ->cargo( 'Vendedor' )
                        ->orderByPontos()
                        ->get();

        // faz o mapeamento do array
        $funcionarios = array_map( function( $func ) {
            return [
                'uid'    => $func->uid,
                'pontos' => $func->pontos,
                'cpf'    => $func->cpf,
                'nome'   => $func->nome,
            ];
        }, $funcionarios );

        // verifica se obteve o ranking
        if ( $funcionarios ) {
            $this->response->resolve( $funcionarios );
        } else {
            $this->response->reject( 'Nao foi possivel obter o ranking.' );
        }
     }

    /**
     * obter_ranking_cluster
     *
     * pega o ranking do cluster
     *
     */
     public function obter_ranking_cluster() {

        // carrega os finders
        $this->load->finder( [ 'FuncionariosFinder', 'LojasFinder', 'ClustersFinder' ] );

        // obtem o funcionario
        $func = $this->FuncionariosFinder
                ->clean()
                ->key( $this->request->user()->CodFuncionario )
                ->get( true );
        
        // verifica se é vendedor
        if ( $func->cargo == 'Vendedor' ) {

            // pega a loja
            $loja = $this->LojasFinder->key( $func->loja )->get( true );

            // pega o ranking
            $ranking = $this->FuncionariosFinder->rankingCluster( $loja->cluster );
        
            // faz o mapeamento do array
            $ranking = array_map( function( $func ) {
                return [
                    'uid'    => $func['UID'],
                    'pontos' => $func['Pontos'],
                    'cpf'    => $func['CPF'],
                    'nome'   => $func['Nome'],
                ];
            }, $ranking );

            // seta o resolve
            $this->response->resolve( $ranking );
        } else {
            
            // pega a loja
            $loja = $this->LojasFinder->key( $func->loja )->get( true );
            if ( !$loja ) return $this->response->reject( 'Nenhuma loja encontrada' );

            // pega o cluster
            $cluster = $this->ClustersFinder->key( $loja->cluster )->get( true );
            if ( !$cluster ) return $this->response->reject( 'Nenhuma cluster encontrada' );
            
            // pega o ranking
            $ranking = $cluster->obterPrimeirosColocados();

            // faz o mapeamento do array
            $ranking = array_map( function( $func ) {
                return [
                    'uid'    => $func['CodLoja'],
                    'pontos' => $func['Total'],
                    'cpf'    => null,
                    'nome'   => $func['Nome'],
                ];
            }, $ranking );

            // seta o resolve
            $this->response->resolve( $ranking );
        }
     }

    /**
     * obter_minha_colocacao
     *
     * pega o ranking do cluster
     *
     */
     public function obter_minha_colocacao() {

          // carrega os finders
        $this->load->finder( [ 'FuncionariosFinder', 'LojasFinder', 'ClustersFinder' ] );

        // obtem o funcionario
        $func = $this->FuncionariosFinder
                ->clean()
                ->key( $this->request->user()->CodFuncionario )
                ->get( true );
        
        // verifica se é vendedor
        if ( $func->cargo == 'Vendedor' ) {

            // pega a loja
            $loja = $this->LojasFinder->key( $func->loja )->get( true );

            // pega o ranking
            $ranking = $this->FuncionariosFinder->rankingClusterPessoal( $loja->cluster, $func->CodFuncionario );
        
            // faz o mapeamento do array
            $ranking = [
                            'uid'     => $ranking['UID'],
                            'pontos'  => $ranking['Pontos'],
                            'cpf'     => $ranking['CPF'],
                            'nome'    => $ranking['Nome'],
                            'ranking' => $ranking['ranking']
                        ];
    
            // seta o resolve
            $this->response->resolve( $ranking );
        } else {
            
            // pega a loja
            $loja = $this->LojasFinder->key( $func->loja )->get( true );
            if ( !$loja ) return $this->response->reject( 'Nenhuma loja encontrada' );
            
            // pega o cluster
            $cluster = $this->ClustersFinder->key( $loja->cluster )->get( true );
            if ( !$cluster ) return $this->response->reject( 'Nenhuma cluster encontrada' );
            
            // pega o ranking
            $ranking = $cluster->obterLojaPosicao( $loja->CodLoja );
            if ( !$ranking ) return $this->response->reject( 'Loja sem posicao no ranking' );

            // faz o mapeamento do array
            $ranking = [
                            'uid'     => $ranking['CodLoja'],
                            'pontos'  => $ranking['Total'],
                            'cpf'     => null,
                            'nome'    => $ranking['Nome'],
                            'ranking' => $ranking['ranking']
                        ];
    
            // seta o resolve
            $this->response->resolve( $ranking );
        }
     }

    /**  -----------------------------------------------------------
     * 
     * METODOS DE TREINAMENTO
     *
     * ------------------------------------------------------------- */
     /**
     * obter_notificacoes_usuario
     *
     * obtem uma lista de notificacoe recentes do usuario
     *
     */
     public function obter_treinamentos( $pagina ) {

         // carrega os finders
         $this->load->finder( [ 'TreinamentosFinder' ] );

         $treinamentos = $this->TreinamentosFinder->treinamentos()->paginate( $pagina, 5, true );

         if ( count( $treinamentos ) == 0 ) {
            return $this->response->resolve( [] );
        }

        // faz o mapeamento dos treinamentos
        $treinamentos = array_map( function( $treinamento ) {
            return  [ 
                        'CodTreinamento' => $treinamento->CodTreinamento, 
                        'Nome' => $treinamento->nome,
                        'Foto' => base_url('uploads/' .$treinamento->foto),
                        'Descricao' => $treinamento->descricao,
                        'Video' => $treinamento->video
                    ];
        }, $treinamentos );

        return $this->response->resolve( $treinamentos );
     }

    /**  -----------------------------------------------------------
     * 
     * METODOS DE NOTIFICACAO
     *
     
     * ------------------------------------------------------------- */
     /**
     * obter_notificacoes_usuario
     *
     * obtem uma lista de notificacoe recentes do usuario
     *
     */
     public function obter_notificacoes_usuario( $pagina ) {

        // carrega os finder
        $this->load->finder( [ 'DisparosFinder', 'NotificacoesFinder', 'FuncionariosFinder' ] );

        $CodFuncionario = $this->request->user()->CodFuncionario;

        // carrega o funcionario
        $funcionario = $this->FuncionariosFinder->key( $CodFuncionario )->get( true );

        // carrega os disparos
        $disparos = $this->DisparosFinder->porFunc( $CodFuncionario )->paginate( $pagina, 10, true );

        // verifica se tem disparos
        if( !$disparos ) return $this->response->resolve( [] );

        // percorre todos os disparos
        foreach ( $disparos as $key => $disparo ) {

            // busca a notificacao de cada disparo
            $notificacao = $this->NotificacoesFinder->key( $disparo->notificacao )->get( true );

            // guarda o codigo do disparo
            $notificacao->disparo = $disparo->CodDisparo;

            // guarda o status
            $notificacao->status = $disparo->status;

            // coloca a notificacao na array de notificacoes
            $notificacoes[] = $notificacao;
        }

        // faz o mapeamento das notificacoes
        $notificacoes = array_map( function( $notificacao ) {
            return  [ 
                        'CodDisparo'        => $notificacao->disparo,
                        'CodNotificacao'    => $notificacao->CodNotificacao,
                        'Nome'              => $notificacao->nome,
                        'Foto'              => base_url('uploads/' .$notificacao->notificacao),
                        'Texto'             => $notificacao->texto,
                        'Status'            => $notificacao->status
                    ];
        }, $notificacoes );

        return $this->response->resolve( $notificacoes );
     }

    /**
     * ler_notificacao
     *
     * marca uma notificacao como lida
     *
     */
     public function ler_notificacao( $CodDisparo ) {

         // carrega os finder
        $this->load->finder( [ 'DisparosFinder' ] );

        // carrega o disparo
        $disparo = $this->DisparosFinder->key( $CodDisparo )->get( true );

        // seta como lido
        $disparo->setStatus( 'S' );

        if( $disparo->save() ) return $this->response->resolve( "Mensagem lida com sucesso." );

        return $this->response->reject( "Por favor tente mais tarde." );
     }
    
    /**  -----------------------------------------------------------
     * 
     * METODOS DE SUPORTE
     *
     
     * ------------------------------------------------------------- */

    /**
     * obter_mensagens
     *
     * obtem as mensagens que um usuario enviou
     *
     */
     public function obter_mensagens( $page = 1 ) {

        // carrega os finders
        $this->load->finder( [ 'MensagensFinder' ] );

        // seta o funcionario
        $func = $this->request->user();

        // faz a paginacao
        $msg = $this->MensagensFinder->clean()
                    ->func( $func->CodFuncionario )
                    ->paginate( $page, 5, true );
        
        // verifica se existem mensagens
        if ( !$msg ) return $this->response->resolve( [] );

        // faz o mapping
        $msg = array_map( function( $m ) {
            
            // pega os dados da data
            $time = strtotime( $m->data );
            $dia  = date( 'd',  $time );
            $mes  = date( 'M', $time );
            $hora = date( 'H:i', $time );

            // seta a data
            $m->dia = $dia;
            $m->mes = $mes;
            $m->hora = $hora;

            // volta a mensagem
            return [
                'texto' => $m->texto,
                'dia'   => $dia,
                'mes'   => $mes,
                'hora'  => $hora
            ];
        }, $msg );

        // envia as mensagens
        return $this->response->resolve( $msg ); 
     }

     /**
     * obter_mensagens
     *
     * obtem as mensagens que um usuario enviou
     *
     */
     public function enviar_mensagem() {

        // carrega os finders
        $this->load->finder( [ 'FuncionariosFinder', 'MensagensFinder', 'ParametrosFinder' ] );

        // pega o funcionario
        $func = $this->request->user();

        // pega a mesangem
        $msg = $this->input->post( 'msg' );
        if ( !$msg ) return $this->response->reject( 'Nenhum mensagem enviada' );
        
        // limpa a mensagem
        $msg = trim( strip_tags( addslashes( $msg ) ) );
        if ( strlen( $msg ) == 0 ) return $this->response->reject( 'Nenhum mensagem enviada' );

        // instancia a mensagem
        $mensagem = $this->MensagensFinder->clean()->getMensagem();
        $mensagem->setFuncionario( $func->CodFuncionario )
        ->setTexto( $msg )
        ->setData( date( 'Y-m-d H:i:s', time() ) );

        // tenta salvar
        if ( $mensagem->save() ) {

            // carrega os email
            $users = $this->ParametrosFinder->parametro( 'EMAIL_SUPORTE' )->get();
            foreach( $users as $item ) {
                $this->enviarEmail( $func, $mensagem, $item->valor );                
            }
            return $this->response->resolve( 'Mensagem enviada com sucesso' );
        } else {
            return $this->request->reject( 'Erro ao enviar a mensagem' );
        }
    }

    /**
     * enviarEmail
     *
     * envia o email avisando sobre a mensagem recebida
     *
     */
    private function enviarEmail( $func, $mensagem, $email ) {

        // configuracoes do email
        $config = [
            'mailtype' => 'html',
        ];

        // texto
        $texto = 'Uma nova mensagem de colaborador foi enviado através da plataforma #PartiuVender';
        $texto .= '<br>'.$func->nome;
        $texto .= '<br>'.$func->email;
        $texto .= '<br>'.$func->cpf;
        $texto .= '<br>'.$mensagem->texto;
        if ( $func->celular ) $texto .= '<br>'.$func->celular;

        // carrega a library
        $this->load->library( 'email', $config );

        // seta os emails
        $this->email->from( 'suporte@neotass.com', 'Suporte Neotass' )
        ->to( $email )

        // seta o corpo
        ->subject( 'Nova mensagem de colaborador' )
        ->message( $texto )
        ->set_mailtype( 'html' );
        
        // envia o email
        if ( !$this->email->send() ) {

        } else echo 'E-mail enviado com sucesso';
    }
    
    /**  -----------------------------------------------------------
     * 
     * METODOS DO CARTAO
     *
     
     * ------------------------------------------------------------- */
     
     /**
     * usar_cartao
     *
     * seta o status do cartao como usado
     *
     */
     public function usar_cartao( $codigo ) {
         
        // carrega os finders
        $this->load->finder( [ 'CartoesFinder' ] );

        // busca o cartao pelo codigo
        $cartao = $this->CartoesFinder->clean()->codigo( $codigo )->get( true );

        // seta o funcionario
        $funcionario = $this->request->user();

        // verifica se o cartao pode ser utilizado
        if( $cartao->status != 'A' ) return $this->request->reject( 'Erro ao tentar usar o cartão, tente mais tarde' );
        
        // verifica se o funcionario existe
        if( !$funcionario ) return $this->request->reject( 'Erro ao tentar usar o cartão, tente mais tarde' );

        $cartao->setFunc( $funcionario->CodFuncionario );
        $cartao->setData( date( 'Y-m-d H:i:s', time() ) );
        $cartao->setStatus( 'U' );

        if( $cartao->save() ) return $this->response->resolve( $cartao );
     }

     /**
     * obter_cartoes_funcionario
     *
     * obtem uma lista de notificacoe recentes do usuario
     *
     */
     public function obter_cartoes_funcionario( $pagina ) {

         // carrega os finders
         $this->load->finder( [ 'CartoesFinder' ] );

        // seta o funcionario
        $funcionario = $this->request->user();
                
        // verifica se o funcionario existe
        if( !$funcionario ) return $this->request->reject( 'Erro, tente mais tarde' );

        $cartoes = $this->CartoesFinder->porFunc( $funcionario->CodFuncionario )->paginate( $pagina, 5, true );

        if ( count( $cartoes ) == 0 ) {
        return $this->response->resolve( [] );
        }

        // faz o mapeamento dos treinamentos
        $cartoes = array_map( function( $cartao ) {
            return  [ 
                        'CodCartao' => $cartao->CodCartao, 
                        'Codigo'    => $cartao->codigo,
                        'Status'    => $cartao->status,
                        'Data'      => $cartao->data,
                        'Valor'     => $cartao->valor
                    ];
        }, $cartoes );

        return $this->response->resolve( $cartoes );
     }

     /**  -----------------------------------------------------------
     * 
     * METODOS DO ESTADOS
     *
     
     * ------------------------------------------------------------- */
     /**
     * obter_estados
     *
     * obtem uma lista de estados
     *
     */
     public function obter_estados() {

         // carrega os finders
         $this->load->finder( [ 'EstadosFinder' ] );

         $estados = $this->EstadosFinder->estados()->get();

         if ( count( $estados ) == 0 ) {
            return $this->response->resolve( [] );
        }

        // faz o mapeamento dos estados
        $estados = array_map( function( $estado ) {
            return  [ 
                        'CodEstado' => $estado->CodEstado, 
                        'nome'      => $estado->nome,
                        'uf'        => $estado->uf
                    ];
        }, $estados );

        return $this->response->resolve( $estados );
     }
     
     /**  -----------------------------------------------------------
     * 
     * METODOS DO CIDADES
     *
     
     * ------------------------------------------------------------- */
     /**
     * obter_cidades_estados
     *
     * obtem uma lista de estados
     *
     */
     public function obter_cidades_estados( $CodEstado ) {

         // carrega os finders
         $this->load->finder( [ 'EstadosFinder', 'CidadesFinder' ] );

         $estado = $this->EstadosFinder->key( $CodEstado )->get( true );

        if( !$estado ) return $this->request->reject( 'Estado informado não existe' );

        $cidades = $this->CidadesFinder->porEstado( $CodEstado )->get();

        if ( count( $cidades ) == 0 ) {
        return $this->response->resolve( [] );
        }

        // faz o mapeamento dos estados
        $cidades = array_map( function( $cidade ) {
            return  [ 
                        'CodCidade' => $cidade->CodCidade, 
                        'nome' => $cidade->nome
                    ];
        }, $cidades );

        return $this->response->resolve( $cidades );
     }
}

/* end of file */
