<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User {

    // banco de dados
    public $db;

    // configuracoes
    public $config;

    // se o usuario ja foi carregado
    public $loaded = false;

    // os dados do usuario
    public $data;

   /**
    * _validateEmailAndPassword
    *
    * valida o email e a senha
    *
    * @param {string} $db
    * @param {string} $config
    */
    public function __construct( $db, $config ) {

        // seta o acesso ao banco de dados
        $this->db = $db;

        // seta o acesso as configuracoes
        $this->config = $config;
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
    * loadByEmail
    *
    * carrega o usuario pelo email
    *
    * @param {string} $email
    */
    public function loadByEmail( $email ) {

        // proteje a consulta com um try catch
        try {

            // seta o nome do campo
            $emailAlias = $this->getField( 'users', 'email' );
            
            // monta a query
            $this->db->from( $this->config->item( 'users' )['table'] )
            ->select( '*' )
            ->where( "$emailAlias = '$email'" );

            // faz a busca
            $src = $this->db->get();

            // verifica se existem resultado
            if ( $src->num_rows() > 0 ) {
                $this->loaded = true;
                $this->data = $src->result_array()[0];
            }

            // volta a instancia
            return $this;
        } catch ( Exception $e ) {
            return $this;
        }
    }

   /**
    * loadByUid
    *
    * carrega o usuario pelo uid
    *
    * @param {string} $uid
    */
    public function loadByUid( $uid ) {

        // proteje a consulta com um try catch
        try {

            // seta o nome do campo
            $uidAlias = $this->getField( 'users', 'uid' );
            
            // monta a query
            $this->db->from( $this->config->item( 'users' )['table'] )
            ->select( '*' )
            ->where( "$uidAlias = '$uid'" );

            // faz a busca
            $src = $this->db->get();

            // verifica se existem resultado
            if ( $src->num_rows() > 0 ) {
                $this->loaded = true;
                $this->data = $src->result_array()[0];
            }

            // volta a instancia
            return $this;
        } catch ( Exception $e ) {
            return $this;
        }
    }

   /**
    * generate_hash
    *
    * gera um hash de senha
    *
    * @param {string} $cost
    * @param {string} $password
    */
    public function generate_hash( $password, $cost=11 ) {

            /* To generate the salt, first generate enough random bytes. Because
            * base64 returns one character for each 6 bits, the we should generate
            * at least 22*6/8=16.5 bytes, so we generate 17. Then we get the first
            * 22 base64 characters
            */
            $salt=substr(base64_encode(openssl_random_pseudo_bytes(17)),0,22);
            /* As blowfish takes a salt with the alphabet ./A-Za-z0-9 we have to
            * replace any '+' in the base64 string with '.'. We don't have to do
            * anything about the '=', as this only occurs when the b64 string is
            * padded, which is always after the first 22 characters.
            */
            $salt=str_replace("+",".",$salt);
            /* Next, create a string that will be passed to crypt, containing all
            * of the settings, separated by dollar signs
            */
            $param='$'.implode('$',array(
                    "2y", //select the most secure version of blowfish (>=PHP 5.3.7)
                    str_pad($cost,2,"0",STR_PAD_LEFT), //add the cost in two digits
                    $salt //add the salt
            ));
        
            //now do the actual hashing
            return crypt($password,$param);
    }

   /**
    * pushOnGroup
    *
    * coloca o usuario em um grupo
    *
    * @param {string} $gid
    */
    public function pushOnGroup( $gid ) {

        // seta os dados
        $dados = [ 'gid' => $gid ];

        // faz o update
        return $this->db->where( [ 'uid' => $this->data['uid'] ] )
        ->update( $this->config->item( 'users' )['table'], $dados );
    }

   /**
    * createUser
    *
    * cria um novo usuario 
    *
    * @param {string} $email
    * @param {string} $password
    */
    public function createUser( $email, $password, $gid = false ) {

        // prepara os dados
        $emailAlias = $this->getField( 'users', 'email' );
        $passwordAlias = $this->getField( 'users', 'password' );
        $data = [
            $this->getField( 'users', 'uid' ) => md5( uniqid( rand() * time() ) ),
            $emailAlias    => $email,
            $passwordAlias => $this->generate_hash( $password )
        ];

        // verifica se existe um id do grupo
        if ( $gid ) $data['gid'] = $gid;

        // salva os dados
        if ( $this->db->insert( $this->config->item( 'users' )['table'], $data ) ) {
            return $this;
        } else return false;
    }

   /**
    * updateUser
    *
    * altera os dados do usuario 
    *
    * @param {Usuario} $user
    */
    public function updateUser( $user ) {

        // verifica se o usuario foi carregado
        if ( !$this->isLoaded() ) return false;

        // pega os campos
        $emailAlias = $this->getField( 'users', 'email' );
        $passwordAlias = $this->getField( 'users', 'password' );

        // verifica se as senhas sao iguais
        if ( $this->data[$passwordAlias] != $user->senha ) {
            $user->senha = $this->generate_hash( $user->senha );
        }

        // prepara os dados
        $data = [
            $this->getField( 'users', 'uid' ) => md5( uniqid( rand() * time() ) ),
            $emailAlias    => $user->email,
            $passwordAlias => $user->senha
        ];

        // verifica se existe um id do grupo
        if ( $user->gid ) $data['gid'] = $user->gid;

        // seta o where
        $this->db->where( [ 'uid' => $user->uid ]);

        // salva os dados
        if ( $this->db->update( $this->config->item( 'users' )['table'], $data ) ) {
            return $this;
        } else return false;
    }

   /**
    * _validateEmailAndPassword
    *
    * valida o email e a senha
    *
    * @param {string} $email
    * @param {string} $password
    */
    public function isLoaded() {

        // informa se o usuario ja foi carregado
        return $this->loaded;
    }

   /**
    * setSessionToken
    *
    * seta o token da sessao
    *
    * @param {string} $token
    */
    public function setSessionToken( $token ) {

        // pega os campos
        $tokenAlias = $this->getField( 'users', 'session_token' );

        // prepara os dados
        $data = [
          $tokenAlias => $token  
        ];

        // faz o update
        $this->db->where( "uid = '".$this->data['uid']."'" );
        return $this->db->update( $this->config->item( 'users' )['table'], $data );
    }
}
