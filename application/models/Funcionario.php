<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Funcionario extends MY_Model {

    // id do cluster
    public $CodFuncionario;

    // loja
    public $loja;

    // uid
    public $uid;

    // token
    public $token;

    // cargo
    public $cargo;

    // nome
    public $nome;

    // email
    public $email;

    // cpf
    public $cpf;

    // pontos
    public $pontos;

    // entidade
    public $entity = 'Funcionario';
    
    // tabela
    public $table = 'Funcionarios';

    // chave primaria
    public $primaryKey = 'CodFuncionario';

   /**
    * __construct
    *
    * metodo construtor
    *
    */
    public function __construct() {
        parent::__construct();
    }
    
    // seta o codigo
    public function setCod( $cod ) {
        $this->CodFuncionario = $cod;
    }

    // loja
    public function setLoja( $loja ) {
        $this->loja = $loja;
    }

    // uid
    public function setUid( $uid ) {
        $this->uid = $uid;
    }

    // token
    public function setToken( $token ) {
        $this->token = $token;
    }

    // cargo
    public function setCargo( $cargo ) {
        $this->cargo = $cargo;
    }    

    // nome
    public function setNome( $nome ) {
        $this->nome = $nome;
    }

    // cpf
    public function setCpf( $cpf ) {
        $this->cpf = $cpf;
    }

    // pontos
    public function setPontos( $pontos ) {
        $this->pontos = $pontos;
    }

    // adiciona pontos
    public function addPontos( $pontos ) {
        $this->pontos += $pontos;
        $this->save();
    }

    // remove pontos
    public function removePontos( $pontos ) {
        $this->pontos -= $pontos;
        $this->save();
    }

    // obtem o extrato
    public function obterExtrato( $pagina = 1 ) {
        
        // pagina
        $offset = ( $pagina - 1 ) * 10;

        // monta a query
        $query = "SELECT * FROM 
        	( 	SELECT 	Vendas.Pontos as Pontos, 
             			Vendas.Data, 
             			CONCAT( Produtos.BasicCode, ' - ', Produtos.Nome )as Item FROM Vendas
        					INNER JOIN Produtos ON Produtos.CodProduto = Vendas.CodProduto
        					WHERE CodFuncionario = $this->CodFuncionario
        				UNION
        		SELECT 	Pontos, 
             			Data, 
             			CONCAT( 'Quiz - ', Nome )as Item 
             			FROM QuestionariosEncerrados
                         INNER JOIN Questionarios ON QuestionariosEncerrados.CodQuestionario = Questionarios.CodQuestionario
        WHERE CodUsuario = $this->CodFuncionario ) as Extrato
        ORDER BY Data
        LIMIT 10
        OFFSET $offset";

        // faz a busca
        $busca = $this->db->query( $query );

        // volta o resultado
        return $busca->result_array();
    }
}

/* end of file */
