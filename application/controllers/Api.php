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
            

            // volta os dados formatados
            return  [ 
                        'CodQuestionario' => $questionario->CodQuestionario, 
                        'Nome'            => $questionario->nome,
                        'Foto'            => base_url( 'uploads/' .$questionario->foto ),                        
                        'Descricao'       => $questionario->descricao,
                        'Pontos'          => $sum,
                        'Total'           => $tot,
                        'Acertadas'       => rand( 0, $tot )
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
        $this->load->finder( [ 'QuestionariosFinder' ] );

        // carrega os produtos da categoria
        $questionarios = $this->QuestionariosFinder
		->quis( $quiz )->get();

        // verifica se existem questionarios
        if ( count( $questionarios ) == 0 ) {
            return $this->response->resolve( [] );
        }

        // faz o mapeamento das cidades
        $questionarios = array_map( function( $questionario ) {
            return  [ 
                        'CodQuestionario' => $questionario->CodQuestionario, 
                        'Nome'            => $questionario->nome,
                        'Foto'            => base_url( 'uploads/' .$questionario->foto ),                        
                        'Descricao'       => $questionario->descricao
                    ];
        }, $questionarios );

        return $this->response->resolve( $questionarios );
    }
}
