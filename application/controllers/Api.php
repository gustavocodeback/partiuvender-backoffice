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
            'nome' => $func->nome,
            'cpf' => $func->cpf,
            'cargo' => $func->cargo,
            'uid' => $func->uid            
        ];
        return $this->response->resolve( $data );
    }

    /**
     * salvar_uid
     *
     * salva um uid para um cpf
     *
     */
    public function salvar_uid( $cpf ) {

        // carrega os finders
        $this->load->finder( [ 'FuncionariosFinder' ] );

        // verifica se o cpf eh valido
        if ( !$this->valid_cpf( $cpf ) ) return $this->response->reject( 'O CPF informado é inválido.' );

        // carrega pelo cpf
        $func = $this->FuncionariosFinder->clean()->cpf( $cpf )->get( true );
        if ( !$func ) return $this->response->reject( 'Nenhum funcionário encontrado para esse CPF.' );

        // pega o uid
        $uid = $this->input->post( 'uid' );
        if ( !$uid ) return $this->response->reject( 'Nenhum UID informado.' );
        $func->setUid( $uid );

        // faz o update
        if ( $func->save() ) {

            // devolve o funcionario
            $data = [
                'nome' => $func->nome,
                'cpf' => $func->cpf,
                'cargo' => $func->cargo,
                'uid' => $func->uid
            ];
            return $this->response->resolve( $data );

        } else return $this->response->reject( 'Houve um erro ao tentar salvar o UID desse funcionário.' );
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
                        'CodProduto' => $produto->CodProduto, 
                        'CodCategoria' => $produto->categoria,
                        'Nome' => $produto->nome,
                        'Pontos' => $produto->pontos,
                        'Foto' => base_url('uploads/' .$produto->foto),
                        'Descricao' => $produto->descricao,
                        'Video' => $produto->video
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

    /**  -----------------------------------------------------------
     * 
     * METODOS DE RANKING
     *
     * ------------------------------------------------------------- */
     public function obter_primeiros_colocados() {
     }

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
        $this->load->finder( [ 'FuncionariosFinder', 'LojasFinder' ] );

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
        $this->load->finder( [ 'FuncionariosFinder', 'LojasFinder' ] );

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
        }
     }
    /**  -----------------------------------------------------------
     * 
     * METODOS DE TREINAMENTO
     *
     * ------------------------------------------------------------- */

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

            // coloca a notificacao na array de notificacoes
            $notificacoes[] = $notificacao;
        }

        // faz o mapeamento das notificacoes
        $notificacoes = array_map( function( $notificacao ) {
            return  [ 
                        'CodDisparo' => $notificacao->disparo,
                        'CodNotificacao' => $notificacao->CodNotificacao,
                        'Nome' => $notificacao->nome,
                        'Foto' => base_url('uploads/' .$notificacao->notificacao),
                        'Texto' => $notificacao->texto
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

     
}

// SELECT * FROM 
// 	( SELECT f.*, @i := @i+1 AS ranking
// 		FROM (SELECT @i:=0) AS foo, 
//      	( SELECT f.* FROM Funcionarios f
// 	INNER JOIN Lojas l on f.CodLoja = l.CodLoja 
// 	INNER JOIN Clusters c on l.CodCluster = c.CodCluster 
// 	WHERE c.Nome = 'Cluster A'
// 	ORDER BY f.Pontos DESC ) as f ) as s
// WHERE CPF <> '44391032864'