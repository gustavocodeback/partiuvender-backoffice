<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Questionario extends MY_Model {

    // id do estado
    public $CodQuestionario;

    // nome
    public $nome;

    // foto
    public $foto;

    // descricao
    public $descricao;

    // entidade
    public $entity = 'Questionario';
    
    // tabela
    public $table = 'Questionarios';

    // chave primaria
    public $primaryKey = 'CodQuestionario';

   /**
    * __construct
    *
    * metodo construtor
    *
    */
    public function __construct() {
        parent::__construct();
    }
    
    // codigo
    public function setCod( $cod ) {
        $this->CodQuestionario = $cod;
        return $this;        
    }

    // nome
    public function setNome( $nome ) {
        $this->nome = $nome;
        return $this;        
    }

    // foto
    public function setFoto( $foto ) {
        $this->foto = $foto;
        return $this;        
    }
    
    // descricao
    public function setDescricao( $desc ) {
        $this->descricao = $desc;
        return $this;
    }

    // fecha o questionario
    public function encerrar( $func, $pontos ) {
        
        // prepara os dados
        $dados = [
            'CodQuestionario' => $this->CodQuestionario,
            'CodUsuario'      => $func,
            'Pontos'          => $pontos,
            'Data'            => date( 'Y-m-d H:i:s', time() )
        ];

        // salva os dados
        return $this->db->insert( 'QuestionariosEncerrados', $dados );
    }

    // verifica se o questionario ja foi encerrado
    public function encerrado( $func ) {

        // prepara a busca
        $this->db->from( 'QuestionariosEncerrados' )
        ->select( '*' )
        ->where( " CodUsuario = $func AND CodQuestionario = $this->CodQuestionario " );

        // faz a busca
        $busca = $this->db->get();

        // verifica se esta encerrado
        return ( $busca->num_rows() > 0 ) ? $busca->result_array()[0] : false;
    }
}

/* end of file */
