<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Migration Class
| -------------------------------------------------------------------
| Essa classe é responsavel por manter o banco de dados na versao
| atual.
| A versao do banco pode ser alterada no arquivo ../config/database.php
|
*/
class CI_Migration {

    // instancia do ci
    public $ci;

    // config
    public $config;

    // forge
    public $forge;

    // versao da migracao
    public $version;

    // classe do banco de dados
    public $db;

    // tabelas atuais
    public $oldTables;

    // tabelas novas
    public $newTables;

    // schema atual
    public $oldSchema;

    // schema novo
    public $newSchema;

    // tabelas protegidas
    public $protectedTables = [ 'ci_sessions' ];

   /**
    * __construct
    *
    * metodo construtor
    *
    */
    public function __construct() {

        // pega a instancia do codeigniter
        $this->ci =& get_instance();
        
        // carrega o config
        $this->ci->config->load( 'guard' );
        $this->config = $this->ci->config;

        // carrega as bibliotecas
        $this->ci->load->dbforge();
        $this->forge = $this->ci->dbforge;

        // carrega o banco
        $this->db = $this->ci->db;
    }

   /**
    * _removeGuardTables
    *
    * remove as tabelas de autenticacao da lista de tabelas
    * para garantir que nao sejam afetadas na migracao
    *
    */
    private function _removeGuardTables() {

        // carrega as tabela
        $this->config->load( 'guard' );
        $tables = [
            $this->config->item( 'users' )['table'],
            $this->config->item( 'groups' )['table'],
            $this->config->item( 'routines' )['table'],
            $this->config->item( 'permissions' )['table']
        ];

        // percorre todas as tabelas
        foreach( $tables as $table ) {

            // pega o index
            $index = array_search( $table, $this->oldTables );
            if ( $index !== false ) unset( $this->oldTables[$index] );
        }
    }

   /**
    * _setOldSchema
    *
    * seta o schema atual
    *
    */
    private function _setOldSchema() {

        // seta o schema
        $schema = [];

        // percorre todas as tabelas antigas
        foreach( $this->oldTables as $table ) {

            // pega os campos
            $fields = $this->db->field_data( $table );

            // percorre os campos
            foreach ($fields as $field ) {

                // seta como array
                $field = (array) $field;

                // nome do campo
                $name = $field['name'];
                unset( $field['name'] );

                // adiciona no schema
                $schema[$table][$name] = $field;
            }
        }

        // seta o schema antigo
        $this->oldSchema = $schema;
    }

   /**
    * _dropUnusedTables
    *
    * apaga as tabelas da versao antiga
    *
    */
    private function _dropUnusedTables() {

        // tabelas a serem removidas
        $toDrop = [];

        // percorre as tabelas antigas
        foreach( $this->oldTables as $table ) {

            // verifica se a tabela esta na lista de novas
            $index = array_search( $table, $this->newTables );
            if ( $index === false ) {
                $toDrop[] = $table;
            }
        }

        // percorre todas as tabela que devem ser excluidas
        foreach( $toDrop as $table ) {
            $this->forge->drop_table( $table );
        }
    }

    /**
    * _createTable
    *
    * cria uma nova tabela
    *
    */
    private function _createTable( $tableName ) {

        // recupera o schema
        $schema = isset( $this->newSchema[$tableName] ) ? $this->newSchema[$tableName] : false;
        if ( !$schema ) return;

        // adiciona os campos
        $this->forge->add_field( $schema );

        // percorre os campos
        foreach ( $schema as $field_name => $field ) {

            // verifica se eh uma chave
            if ( isset( $field[ 'primary_key' ] ) && $field[ 'primary_key' ] ) {
                $this->forge->add_key( $field_name, TRUE ) ;
            }
        } 

        // cria a tabela
        $this->forge->create_table( $tableName );
    }
    
   /**
    * _createNewTables
    *
    * cria as novas tabelas
    *
    */
    private function _createNewTables() {

        // tabelas a serem criadas
        $toCreate = [];

        // percorre todas as tabelas novas
        foreach( $this->newTables as $table ) {

            // verifica se a tabela ja existe
            $index = array_search( $table, $this->oldTables );

            // verifica o indice
            if ( $index === false ) $toCreate[] = $table;
        }

        // percorre todas as tabelas que devem ser criadas
        foreach( $toCreate as $table ) {

            // cria a tabela
            $this->_createTable( $table );
        }
    }

   /**
    * _isNewField
    *
    * verifica se um campo eh novo
    *
    */
    private function _isNewField( $table, $field ) {

        // pega o schema antigo
        $schema = $this->oldSchema[$table];

        // verifica se o campo exsite
        return !isset( $schema[$field] );
    }

   /**
    * _isOldField
    *
    * verifica se um campo eh velho
    *
    */
    private function _isOldField( $table, $field ) {

        // pega o schema antigo
        $schema = $this->newSchema[$table];

        // verifica se o campo exsite
        return !isset( $schema[$field] );
    }

   /**
    * _addNewField
    *
    * adiciona um novo campo
    *
    */
    private function _addNewField( $table, $field ) {
        
        // pega o schema do campo
        $fieldSchema = $this->newSchema[$table][$field];

        // adiciona o campo
        $this->forge->add_column( $table, [ $field => $fieldSchema ] );
    }

    /**
    * _updateField
    *
    * atualiza um campo
    *
    */
    private function _updateField( $table, $field ) {

        // pega o schema
        $schema = $this->newSchema[$table][$field];
        $schama['name'] = $field;

        // seta os campos
        $field = [ $field => $schema ];

        // muda a coluna
        $this->forge->modify_column( $table, $field );
    }

   /**
    * _addNewFields
    *
    * adiciona os novos campos da migracao
    *
    */
    private function _addNewFields() {

        // campos a serem atualizados
        $fieldsToAdd = [];

        // percorre as tabelas novas
        foreach( $this->newTables as $newTable ) {

            // verifica se ela ja existia
            $index = array_search( $newTable, $this->oldTables );
            if ( $index !== false ) {
                
                // pega o schema
                $schema = $this->newSchema[$newTable];

                // percorre os campos
                foreach( $schema as $fieldName => $field ) {

                    // verifica se eh um campo novo
                    if ( $this->_isNewField( $newTable, $fieldName ) ) {
                        $fieldsToAdd[$newTable][] = $fieldName;
                    } else {

                        // modifica a coluna
                        $this->_updateField( $newTable, $fieldName );
                    }
                }
            }
        }

        // percorre todos os campos que devem ser adicionados
        foreach ( $fieldsToAdd as $table => $fields ) {

            // percorre os campos
            foreach( $fields as $field ) {

                // adiciona o campo
                $this->_addNewField( $table, $field );
            }
        }
    }

   /**
    * _removeOldFields
    *
    * remove campos que nao sao mais utilizados
    *
    */
    private function _removeOldFields() {

        // campos a serem atualizados
        $fieldsToRemove = [];

        // percorre as tabelas novas
        foreach( $this->oldTables as $oldTable ) {

            // verifica se ela ainda existe
            $index = array_search( $oldTable, $this->newTables );
            if ( $index !== false ) {
                
                // pega o schema
                $schema = $this->oldSchema[$oldTable];

                // percorre os campos
                foreach( $schema as $fieldName => $field ) {

                    // verifica se eh um campo novo
                    if ( $this->_isOldField( $oldTable, $fieldName ) ) {
                        $fieldsToRemove[$oldTable][] = $fieldName;
                    }
                }
            }
        }

        // percorre todos os campos que devem ser adicionados
        foreach ( $fieldsToRemove as $table => $fields ) {

            // percorre os campos
            foreach( $fields as $field ) {

                // remove o campo
                $this->forge->drop_column( $table, $field );
            }
        }
    }

   /**
    * migre
    *
    * faz a migracao do banco antigo para o novo
    *
    */
    public function migre() {

        // remove tabelas nao mais usadas
        $this->_dropUnusedTables();

        // cria as tabelas novas
        $this->_createNewTables();

        // adiciona os novos campos
        $this->_addNewFields();

        // remove os campos antigos
        $this->_removeOldFields();
    }

   /**
    * start
    *
    * inicia a imigracao
    *
    */
    public function start( $version = false ) {

        // seta a versao da migracao
        $this->version = $version ? $version : $this->ci->db->version;
        
        // carrega o arquivo de configuracao da versao
        $this->config->load( 'migrations/'.$this->version );

        // pega a lista tabelas
        $this->oldTables = $this->db->list_tables();

        // verifica se a autenticacao padrao esta sendo usada
        if ( USE_DEFAULT_AUTHENTICATION ) $this->_removeGuardTables();

        // remove as tabelas protegidas
        foreach( $this->protectedTables as $table ) {

            // pega o index
            $index = array_search( $table, $this->oldTables );
            if ( $index !== false ) unset( $this->oldTables[$index] );
        }

        // carrega o novo schema
        $this->newSchema = $this->config->item( 'schema' );

        // seta as novas tabelas
        $this->newTables = [];
        foreach( $this->newSchema as $table => $fields ) $this->newTables[] = $table;

        // carrega o schema atual
        $this->_setOldSchema();

        // inicia a migracao
        $this->migre();
    }
}

/* end of file */
