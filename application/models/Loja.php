<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Loja extends MY_Model {

    // id do cluster
    public $CodLoja;

    // cluster
    public $cluster;

    // cnpj
    public $cnpj;

    // razao
    public $razao;

    // nome
    public $nome;

    // endereco
    public $endereco;

    // numero
    public $numero;

    // complemento
    public $complemento;

    // bairro
    public $bairro;

    // cidade
    public $cidade;

    // estado
    public $estado;

    // entidade
    public $entity = 'Loja';
    
    // tabela
    public $table = 'Lojas';

    // chave primaria
    public $primaryKey = 'CodLoja';

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
        $this->CodLoja = $cod;
    }

    // cluster
    public function setCluster( $cluster ) {
        $this->cluster = $cluster;
    }

    // cnpj
    public function setCNPJ( $cnpj ) {
        $this->cnpj = $cnpj;
    }

    // razao
    public function setRazao( $razao ) {
        $this->razao = $razao;
    }    

    // nome
    public function setNome( $nome ) {
        $this->nome = $nome;
    }

    // endereco
    public function setEndereco( $endereco ) {
        $this->endereco = $endereco;
    }  

    // numero
    public function setNumero( $numero ) {
        $this->numero = $numero;
    }

    // complemento
    public function setComplemento( $complemento ) {
        $this->complemento = $complemento;
    }

    // bairro
    public function setBairro( $bairro ) {
        $this->bairro = $bairro;
    }

    // cidade
    public function setCidade( $cidade ) {
        $this->cidade = $cidade;
    }

    // estado
    public function setEstado( $estado ) {
        $this->estado = $estado;
    }
}

/* end of file */
