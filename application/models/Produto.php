<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Produto extends MY_Model {

    // id do estado
    public $CodProduto;

    // nome
    public $nome;

    // categoria
    public $categoria;

    // descricao
    public $descricao;

    // foto
    public $foto;

    // pontos
    public $pontos;

    // video
    public $video;

    // entidade
    public $entity = 'Produto';
    
    // tabela
    public $table = 'Produtos';

    // chave primaria
    public $primaryKey = 'CodProduto';

   /**
    * __construct
    *
    * metodo construtor
    *
    */
    public function __construct() {
        parent::__construct();
    }
    
    public function setCod( $cod ) {
        $this->CodProduto = $cod;
    }

    // nome
    public function setNome( $nome ) {
        $this->nome = $nome;
    }

    // categoria
    public function setCategoria( $categoria ) {
        $this->categoria = $categoria;
    }

    // descricao
    public function setDescricao( $descricao ) {
        $this->descricao = $descricao;
    }

    // foto
    public function setFoto( $foto ) {
        $this->foto = $foto;
    }

    // pontos
    public function setPontos( $pontos ) {
        $this->pontos = $pontos;
    }

    // video
    public function setVideo( $video ) {
        $this->video = $video;
    }
}

/* end of file */
