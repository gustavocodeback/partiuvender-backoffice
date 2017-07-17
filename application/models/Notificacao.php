<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notificacao extends MY_Model {

    // id do estado
    public $CodNotificacao;

    // nome
    public $nome;

    // notificacao
    public $notificacao;

    // disparos
    public $disparos;

    // texto
    public $texto;

    // entidade
    public $entity = 'Notificacao';
    
    // tabela
    public $table = 'Notificacoes';

    // chave primaria
    public $primaryKey = 'CodNotificacao';

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
        $this->CodNotificacao = $cod;
    }

    // nome
    public function setNome( $nome ) {
        $this->nome = $nome;
    }

    // texto
    public function setTexto( $texto ) {
        $this->texto = $texto;
    }

    // notificacao
    public function setNotificacao( $notificacao ) {
        $this->notificacao = $notificacao;
    }

    // disparos
    public function setDisparos( $disparos ) {
        $this->disparos = $disparos;
    }
}

/* end of file */
