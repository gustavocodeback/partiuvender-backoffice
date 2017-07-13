<?php defined('BASEPATH') OR exit('No direct script access allowed');

// se o login por email e senha esta ativo
$config['LOGIN_WITH_EMAIL_AND_PASSWORD'] = true;

// se o login por nome do usuario e senha esta ativo
$config['LOGIN_WITH_USERNAME_AND_PASSWORD'] = false;

// tamanho para senha
$config['MIN_PASS_LENGTH'] = 6;
$config['MAX_PASS_LENGTH'] = 16;

/**
* users
*
* configuracoes para criacao da tabela de usuarios
*
*/
$config['users'] = [
    'table'  => 'Usuarios',
    'fields' => [
        'uid' => [
                'type'        => 'VARCHAR',
                'constraint'  => 32,
                'primary_key' => TRUE
        ],
        'gid' => [
                'type' => 'INT',
                'constraint' => 11,
        ],
        'email' => [
                'type'        => 'VARCHAR',
                'constraint'  => '100',              
                'unique'      => TRUE,
        ],
        'password' => [
                'type'       =>'VARCHAR',
                'constraint' => '255'
        ],
        'session_token' => [
                'type'        => 'VARCHAR',
                'constraint'  => 32
        ],
    ]
];

/**
* users
*
* configuracoes para criacao da tabela de usuarios
*
*/
$config['groups'] = [
    'table' => 'Grupos',
    'fields'=> [
        'gid' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
                'primary_key'    => TRUE
        ],
        'grupo' => [
                'type'        => 'VARCHAR',
                'constraint'  => 32,           
        ]
    ]
];

/**
* users
*
* configuracoes para criacao da tabela de usuarios
*
*/
$config['routines'] = [
    'table' => 'Rotinas',
    'fields'=> [
        'rid' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
                'primary_key'    => TRUE
        ],
        'CodClassificacao' => [
                'type'           => 'INT',
                'constraint'     => 11,
        ],
        'Rotina' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique'     => TRUE,
        ],
        'Link' => [
                'type'       => 'VARCHAR',
                'constraint' => '255'
        ]
    ]
];

/**
* users
*
* configuracoes para criacao da tabela de usuarios
*
*/
$config['permissions'] = [
    'table' => 'Permissoes',
    'fields'=> [
        'pid' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
                'primary_key'    => TRUE
        ],
        'gid' => [
                'type'           => 'INT',
                'constraint'     => 11,
        ],
        'rid' => [
                'type'           => 'INT',
                'constraint'     => 11,
        ],
        'access' => [
                'type'       =>'CHAR',
                'constraint' => '1'
        ]
    ]
];

/**
* errors
*
* codigo de erros
*
*/
$config['errors'] = [
        'auth-001' => 'E-mail já registrado',
        'auth-002' => 'E-mail inválido',
        'auth-003' => 'A senha precisa ter no mínimo '.$config['MIN_PASS_LENGTH'].' caractéres',
        'auth-004' => 'A senha precisa ter no máximo '.$config['MAX_PASS_LENGTH'].' caractéres',
        'auth-005' => 'A senha digitada está incorreta',
];

/* end of file */
