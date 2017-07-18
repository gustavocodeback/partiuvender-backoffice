<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Treinamento extends MY_Model {

    // id do estado
    public $CodTreinamento;

    // nome
    public $nome;

    // descricao
    public $descricao;

    // foto
    public $foto;

    // video
    public $video;

    // entidade
    public $entity = 'Treinamento';
    
    // tabela
    public $table = 'Treinamentos';

    // chave primaria
    public $primaryKey = 'CodTreinamento';

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
        $this->CodTreinamento = $cod;
    }

    // nome
    public function setNome( $nome ) {
        $this->nome = $nome;
    }

    // descricao
    public function setDescricao( $descricao ) {
        $this->descricao = $descricao;
    }

    // foto
    public function setFoto( $foto ) {
        $this->foto = $foto;
    }

    // video
    public function setVideo( $video ) {
        $this->video = $video;
    }
}

/* end of file */
