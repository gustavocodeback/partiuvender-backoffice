<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

    // where
    public $whereClause = '';

    // labels
    protected $labels = [];

    // busca cacheada
    protected $cache;

    // funcoes de tratamento de campo
    protected $toApply = [];

    // total de registros encontrados
    public $count;

    // registros por pagina
    public $perPage = 20;

    // pagina atual
    public $page = 1;

    // pagina atual
    public $offset = 0;

    // url de busca
    public $url;

    // ordenacao
    public $order = 'ASC';

    // filtros de busca
    public $filters = [];

    // serialized data
    public $serial = [];

   /**
    * __construct
    *
    * metodo construtor
    *
    */
    public function __construct() {
        
        // chama o metodo construtor
        parent::__construct();
    }

   /**
    * where
    *
    * define os filtros para uma busca
    *
    */
    public function where( $clause ) {

        // verifica se ja existe algum conteudo no where
        if ( empty( $this->whereClause ) ) {
            $this->whereClause = $clause;
        } else {
            $this->whereClause .= " AND ".$clause;            
        }
    }

   /**
    * get
    *
    * faz a busca
    *
    */
    public function get( $only = false, $setFrom = true ) {

        // seta a tabela
        if ( $setFrom ) $this->db->from( $this->table );

        // monta o where
        if ( !empty( $this->whereClause ) ) $this->db->where( $this->whereClause );

        // faz a busca
        $busca = $this->db->get();

        // verifica se existem resultados
        if ( $busca->num_rows() > 0 ) {

            // pega os resultados
            $result = $busca->result_array();

            // verifica se deve pegar somente um
            if ( $only ) {

                // pega o primeiro resultado
                $first = $result[0];

                // instancia uma nova entidade
                $entity = new $this->entity;

                // faz o parse
                $entity->parse( $first );

                // retorna a entidade
                return $entity;
            }

            // seta o array de retorno
            $response = [];

            // percorre todos os resultados
            foreach( $result as $item ) {

                // instancia uma nova entidade
                $entity = new $this->entity;

                // faz o parse
                $entity->parse( $item );

                // retorna a entidade
                $response[] = $entity;
            }

            // retorna os resultados encontrados
            return $response;
        
        // volta nulo por padrao
        } else return null;
    }

   /**
    * key
    *
    * busca a entidade pela chave
    *
    */
    public function key( $key, $alias = false ) {

        // limpa o where
        $this->whereClause = '';

        // seta o alias
        $alias = $alias ? $alias.'.': '';

        // verifica se uma chave foi setada
        if ( isset( $this->primaryKey ) ) $this->where( " $alias"."$this->primaryKey = '$key' " );
        
        // volta  a instancia
        return $this;
    }

  /**
    * clean
    *
    * limpa o cache
    *
    */
    public function clean() {

         // limpa o where
        $this->whereClause = '';

        // volta a instancia
        return $this;
    }

   /**
    * parse
    *
    * adiciona os dados buscados no banco com os dados da classe
    *
    */
    public function parse( $data ) {

        // pega o mapeamento
        $this->config->load( 'mapping' );

        // pega o item referente
        $itens = $this->config->item( $this->entity );

        // verifica se existe o item
        if ( !$itens ) return;

        // seta a chave
        $pk = $this->primaryKey;
        $this->$pk = $data[$pk];

        // seta os dados
        foreach( $itens as $classe => $tabela ) {
            $this->$classe = $data[$tabela];
        }
    }

   /**
    * serialize
    *
    * volta os dados formatados, prontos para serem upados
    *
    */
    public function serialize() {

        // pega o mapeamento
        $this->config->load( 'mapping' );

        // pega o item referente
        $itens = $this->config->item( $this->entity );

        // verifica se existe o item
        if ( !$itens ) return;

        // seta os dados
        $data = [];
        foreach( $itens as $classe => $tabela ) {
            $data[$tabela] = $this->$classe;
        }

        // volta os dados
        return $data;
    }

   /**
    * save
    *
    * salva o objeto no banco de dados
    *
    */
    public function save() {

        // pega os dados
        $this->serial = $this->serialize();

        // chave primaria
        $pk = $this->primaryKey;

        // verifica se é um update
        if ( $this->$pk ) {

            // faz o update
            $this->db->where( $this->primaryKey, $this->$pk );
            return $this->db->update( $this->table, $this->serial ); 
        } else {
            
            // faz o insert
            return $this->db->insert( $this->table, $this->serial );            
        }
    }

   /**
    * getCount
    *
    * pega a contagem das linhas
    *
    */
    public function getCount( $query ) {

        // prepara a busca
        $this->db->flush_cache();

        // retira os dados
        $quering = explode( 'FROM', $query );
        $quering = explode( 'LIMIT', $quering[1] );
        $quering = 'SELECT count( * ) as total FROM '.$quering[0];

        // executa a query
        $src = $this->db->query( $quering );

        // seta o contador
        $this->count = $src->result_array()[0]['total'];
    }

   /**
    * delete
    *
    * Exclui o objeto atual
    * 
    */
    public function delete() {

        // pega a chave primaria
        $pk = $this->primaryKey;

        // verifica se existe um valor para a mesma
        if ( !$this->$pk ) return;

        // faz a exclusao
        return $this->db->delete( $this->table, [ $pk => $this->$pk ] ); 
    }

   /**
    * paginate
    *
    * pagina os resultados
    *
    */
    public function paginate( $page = 0, $qtde = 20, $return = false ) {

        // seta os resultados como zero
        $this->count = 0;

        // seta a pagina
        $page = $this->input->get( 'page' ) ? $this->input->get( 'page' ) : $page;
        $this->page = $page;
        $this->offset = ( $page - 1 ) * $qtde;

        // seta a quantidade por pagina
        $this->perPage = $qtde;

        // seta o offset
        $this->offset = $this->offset < 0 ? 0 : $this->offset;

        // seta o limite
        $this->db->limit( $this->perPage, $this->offset );

        // verifica o retorno
        if ( $return ) return $this->get();

        // monta o where
        if ( !empty( $this->whereClause ) ) $this->db->where( $this->whereClause );

        // chama o get
        $src = $this->db->get();

        // verifica se existem resultado
        if ( $src->num_rows() > 0 ) {
            
            // seta o cache
            $this->cache = $src->result_array();
            
            // faz a contagem
            $query = $this->db->last_query();
            $this->getCount( $query );
            
            return $this;
        } else return $this;
    }

   /**
    * order
    *
    * faz a ordenacao dos resultados
    *
    */
    public function order() {

        // pega a ordenacao
        $order = $this->input->get( 'order' ) ? $this->input->get( 'order' ) : 'ASC';
        $by    = $this->input->get( 'by' );

        // verifica se existe o campo
        if ( !$by ) return $this;

        // ultima ordenacao
        $this->order = $order;

        // faz a ordenacao
        $this->db->order_by( $by, $order );
        return $this;
    }

   /**
    * order_link
    *
    * gera os links de ordenacao
    *
    */
    public function order_link( $row ) {

        // ordem
        $order = $this->order == 'ASC' ? 'DESC' : 'ASC';

        // pega os dados do get
        $params = $_GET;

        // adiciona os de ordenacao
        $params['by']    = $row;
        $params['order'] = $order;

        // monta a url
        $url = $this->url.'?'.http_build_query( $params );

        // seta os links de ordenacao
        if ( $this->getLabel( $row, true ) ) {
            echo '<th><a href="'.$url.'">'.$this->getLabel( $row ).'</a></th>';
        } else {
            echo '<th>'.$this->getLabel( $row ).'</th>';
        }
    }

   /**
    * getLabel
    *
    * pega a label de uma coluna
    *
    */
    public function getLabel( $label, $bool = false ) {

        // verifica se eh checagem
        if ( $bool ) {
            return isset( $this->labels[$label] ) ? true : false;
        }

        // volta a label
        return isset( $this->labels[$label] ) ? $this->labels[$label] : $label;
    }

   /**
    * render
    *
    * renderiza um grid
    *
    */
    public function render( $url = false ) {

        // seta a url
        $this->url = $url ? $url : '';

        // seta os dados da view
        $this->view->set( 'grid', $this->cache )->set( 'finder', $this );

        // retorna a instancia
        return $this;
    }

   /**
    * onApply
    *
    * seta um callback para cada coluna
    *
    */
    public function onApply( $key, $callback ) {

        // verifica se eh um array
        if ( is_array( $key ) ) {
            foreach( $key as $item ) $this->toApply[$item] = $callback;
            return $this;
        }

        // seta o apply
        $this->toApply[$key] = $callback;

        // volta a instancia
        return $this;
    }

   /**
    * apply
    *
    * aplica o callback na coluna
    *
    */
    public function apply( $key, $row ) {

        // verifica se existe um apply
        if ( isset( $this->toApply[$key] ) ) {
            $this->toApply[$key]( $row, $key );
        } else  echo $row[$key];
    }

   /**
    * create_links
    *
    * cria os links da paginacao
    *
    */
    public function create_links() {

        // carrega a biblioteca
        $this->load->library('pagination');

        // configuracoes da biblioteca
        $config['base_url']             = $this->url;
        $config['per_page']             = $this->perPage;
        $config['total_rows']           = $this->count;
        $config['page_query_string']    = TRUE;
        $config['use_page_numbers']     = TRUE;
        $config['query_string_segment'] = 'page';
        $params = $_GET;
        if ( isset( $params['page'] ) ) unset( $params['page'] );
        $config['first_url'] = $config['base_url'].'?page=1&'.http_build_query($params);

        // inicializa a biblioteca
        $this->pagination->initialize( $config );

        // cria os links
        echo $this->pagination->create_links();
    }

   /**
    * filter
    *
    * filtra os resultados da paginacao
    *
    */
    public function filter() {

        // percorre todos os filtros
        foreach( $this->filters as $filter ) {

            // verifica se existe o get
            $elem = $this->input->get( $filter['field'] );

            // verificia se esta vazio
            if ( empty( $elem ) ) continue;

            // monta a query
            $qry = '';
            if ( $filter['type'] !== 'select' ) {
                $qry = $filter['query']." LIKE '%$elem%'";
            } else {
                $qry = $filter['query']." = '$elem'";                
            }
            
            // seta o where
            $this->where( $qry );
        }

        // retorna a instancia
        return $this;
    }

   /**
    * addFilter
    *
    * adiciona um novo filtro
    *
    */
    public function addFilter( $field, $type, $data = false, $alias = '' ) {

        // prepara o array do filtro
        $filter = [
            'field' => $field,
            'label' => $this->getLabel( $field ),
            'type'  => $type,
            'data'  => $data,
            'query' => empty( $alias ) ? $field : $alias.'.'.$field,
            'value' => $this->input->get( $field )
        ];

        // adiciona no array
        $this->filters[] = $filter;

        // retorna a instancia
        return $this;
    }

   /**
    * isFilter
    *
    * verifica se um campo é um filtro
    *
    */
    public function isFilter( $field ) {

        // percorre todos os filtros
        foreach( $this->filters as $filter ) {
            if ( $filter['field'] == $field ) return true;
        }
        return false;
    }
}

/* end of file */