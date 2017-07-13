<?php defined('BASEPATH') OR exit('No direct script access allowed');

// inclui a classe de usuario
include_once( 'application/classes/User.php' );

/**
* Guard
*
* classe de autenticacao de usuarios
*
*/
class Guard {

    // instancia do ci
    public $ci;

    // config
    public $config;

    // forge
    public $forge;

    // ultimo erro
    public $last_error = null;

    // sessao
    public $session;

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
        $this->ci->load->library( 'session' );
        $this->session = $this->ci->session;

        // cria a tabela de usuarios
        $this->_createUsersTable();

        // cria a tabela de grupos
        $this->_createGroupsTable();

        // cria a tabela de rotinas
        $this->_createRoutinesTable();

        // cria a tabela de permissoes
        $this->_createPermissionsTable();
    }

   /**
    * _generateKey
    *
    * gera uma key aleatoria
    *
    */
    public function _generateKey() {

        // volta um id aleatorio
        return md5( uniqid( rand() * time() ) );
    }

    /**
    * _validateEmailAndPassword
    *
    * valida o email e a senha
    *
    * @param {string} $email
    * @param {string} $password
    */
    private function _validateEmailAndPassword( $email, $password ) {

        // verifica se o email eh valido
        if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
            $this->error( 'auth-002' );
            return false;
        }

        // verifica o tamanho da senha
        $password = trim( $password );
        if ( strlen( $password ) < $this->config->item( 'MIN_PASS_LENGTH' ) ) {
            $this->error( 'auth-003' );
            return false;
        }
        if ( strlen( $password ) > $this->config->item( 'MAX_PASS_LENGTH' ) ) {
            $this->error( 'auth-004' );
            return false;
        }

        // volta true por padrao
        return true;
    }

   /**
    * _createTable
    *
    * cria uma tabela em especifico
    *
    * @param {string} $param o nome do parametro com os dados da tabela
    */
    private function _createTable( $param ) {

        // obtem os dados da tabela
        $params = $this->config->item( $param );
        if ( !$params ) return false;

        // verifica se a tabela nao existe
        if ( $this->ci->db->table_exists( $params['table'] ) ) return false;

        // adiciona os campos
        $this->forge->add_field( $params['fields'] );

        // percorre os campos
        foreach ( $params['fields'] as $field_name => $field ) {

            // verifica se eh uma chave
            if ( isset( $field[ 'primary_key' ] ) && $field[ 'primary_key' ] ) {
                $this->forge->add_key( $field_name, TRUE ) ;
            }
        } 

        // cria a tabela
        $this->forge->create_table( $params['table'] );
    }

   /**
    * _createUsersTable
    *
    * cria a tabela de usuarios
    *
    */
    private function _createUsersTable() {

        // cria a tabela de usuarios
        $this->_createTable( 'users' );
    }

   /**
    * _createGroupsTable
    *
    * cria a tabela de grupos
    *
    */
    private function _createGroupsTable() {

        // cria a tabela de usuarios
        $this->_createTable( 'groups' );
    }

   /**
    * _createRoutinesTable
    *
    * cria a tabela de rotinas
    *
    */
    private function _createRoutinesTable() {

        // cria a tabela de usuarios
        $this->_createTable( 'routines' );
    }

   /**
    * _createPermissionsTable
    *
    * cria a tabela de permissoes
    *
    */
    private function _createPermissionsTable() {

        // cria a tabela de usuarios
        $this->_createTable( 'permissions' );
    }

   /**
    * cause
    *
    * volta o ultimo erro
    *
    */
    public function cause() {

        // retorna o ultimo erro
        return $this->last_error;
    }

   /**
    * error
    *
    * dispara um erro
    *
    * @param {string} $code o codigo do erro
    */
    private function error( $code = false ) {

        // carrega os erros
        $errors = $this->config->item( 'errors' );

        // pega o erro pelo codigo
        $error = isset( $errors[$code] ) ? $errors[$code] : null;

        // verificia se o erro existe
        if ( $error ) {
            $this->last_error = [
                'code'    => $code,
                'message' => $error
            ];
        } else {
            $this->last_error = [
                'code'    => '001',
                'message' => 'undefined'
            ];
        }

    }
    
   /**
    * getField
    *
    * pega o nome de um campo
    *
    * @param {string} $table
    * @param {string} $alias
    */
    private function getField( $table, $alias ) {

        // pega a tabela
        $table = $this->config->item( $table );

        // volta o nome do campo
        return isset( $table['fields'][$alias]['alias'] ) ? $table['fields'][$alias]['alias'] : $alias;        
    }

   /**
    * login
    *
    * faz o login do usuario
    *
    * @param {string} $uid
    */
    private function login( $uid ) {

        // cria um novo usuario
        $user = new User( $this->ci->db, $this->config );

        // carrega pelo uid
        if ( $user->loadByUid( $uid )->isLoaded() ) {

            // gera um token aleatorio
            $token = $this->_generateKey();

            // grava o token do usuario
            $user->setSessionToken( $token );

            // seta os dados na sessao
            $this->session->set_userdata( 'uid', $uid );
            $this->session->set_userdata( 'session_token', $token );
        } else return false;
    }

   /**
    * currentUser
    *
    * pega o usuario atual
    *
    */
    public function currentUser() {

        // verifica se existe um uid
        $uid = $this->session->userdata( 'uid' );
        $token = $this->session->userdata( 'session_token' );

        // verifica se as propriedades da sessao estao setadas
        if ( !$uid || !$token ) return null;

        // cria um usuario
        $user = new User( $this->ci->db, $this->config );

        // carrega pelo uid
        if ( $user->loadByUid( $uid )->isLoaded() ) {

            // pega o campo do token
            $tokenAlias = $this->getField( 'users', 'session_token' );

            // verifica se o token eh igual ao token salvo
            if ( $user->data[$tokenAlias] === $token ) {
                return $user;
            } else return null;
        } else return null;
    }

   /**
    * logout
    *
    * faz o logout do sistema
    *
    */
    public function logout() {

        // limpa a sessao
        $this->session->sess_destroy();
    }

   /**
    * loginWithEmailAndPassword
    *
    * faz o login com email e senha
    *
    * @param {string} $email
    * @param {string} $password
    */
    public function loginWithEmailAndPassword( $email, $password ) {

        // cria uma nova instancia de User
        $user = new User( $this->ci->db, $this->config );

        // carrega pelo email
        if ( $user->loadByEmail( $email )->isLoaded() ) {

            // pega o nome do campo de senha
            $passwordAlias = $this->getField( 'users', 'password' );
            $uidAlias = $this->getField( 'users', 'uid' );

            // verifica a senha
            if ( crypt( $password, $user->data[$passwordAlias] ) === $user->data[$passwordAlias] ) {

                // faz o login
                $this->login( $user->data[$uidAlias] );
                return true;
            } else {

                // seta o erro
                $this->error( 'auth-005' );
                return false;
            }
        } else {

            // seta o erro
            $this->error( 'auth-002' );
            return false;
        }
    }

   /**
    * createUserWithEmailAndPassword
    *
    * cria um usuario com email e senha
    *
    * @param {string} $email
    * @param {string} $password
    */
    public function createUserWithEmailAndPassword( $email, $password, $gid = false ) {
        
        // cria uma nova instancia de User
        $user = new User( $this->ci->db, $this->config );

        // valida os dados
        if ( !$this->_validateEmailAndPassword( $email, $password ) ) return false;

        // carrega o usuario pelo email
        if ( $user->loadByEmail( $email )->isLoaded() ) {
            $this->error( 'auth-001' );
            return false;
        }
        
        // cria o usuario
        return $user->createUser( $email, $password, $gid );
    }
}

/* end of file */
