<?php

require 'application/models/Estado.php';

class EstadosFinder extends MY_Model {

    // entidade
    public $entity = 'Estado';

    // tabela
    public $table = 'Estados';

    // chave primaria
    public $primaryKey = 'CodEstado';

    // labels
    public $labels = [
        'nome'  => 'Nome',
        'uf' => 'UF',
    ];

   /**
    * __construct
    *
    * metodo construtor
    *
    */
    public function __construct() {
        parent::__construct();
    }

   /**
    * getEstado
    *
    * pega a instancia do estado
    *
    */
    public function getEstado() {
        return new $this->entity();
    }

   /**
    * grid
    *
    * funcao usada para gerar o grid
    *
    */
    public function grid() {
        $this->db->from( $this->table )
        ->select( 'CodEstado as Código, Nome, Uf, CodEstado as Ações' );
        return $this;
    }

    public function uf( $uf ) {
        $this->where( "Uf = '$uf'" );
        return $this;
    }

       /**
    * filtro
    *
    * volta o array para formatar os filtros
    *
    */
    public function filtro() {

        // prepara os dados
        $this->db->from( $this->table )
        ->select( 'CodEstado as Valor, Nome as Label' );

        // faz a busca
        $busca = $this->db->get();

        // verifica se existe resultados
        if ( $busca->num_rows() > 0 ) {

            // seta o array de retorna
            $ret = [];

            // percorre todos os dados
            foreach( $busca->result_array() as $item ) {
                $ret[$item['Valor']] = $item['Label'];
            }

            // retorna os dados
            return $ret;

        } else return [];
    }

    public function estados() {
        
        $this->db->order_by( 'CodEstado', 'ASC' );

        return $this;
    }
}

/* end of file */
