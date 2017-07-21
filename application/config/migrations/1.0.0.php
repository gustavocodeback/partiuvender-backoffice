<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Tabela de estados
$config['schema']['Estados'] = [
    'CodEstado' => [
        'type'           => 'int',
        'primary_key'    => TRUE,
        'constraint'     => '11',
        'auto_increment' => true
    ],
    'Nome' => [
        'type'       => 'varchar',
        'constraint' => '100'
    ],
    'Uf' => [
        'type'       => 'varchar',
        'constraint' => '2'
    ]
];

// Tabela de Cidades
$config['schema']['Cidades'] = [
    'CodCidade' => [
        'type'           => 'int',
        'primary_key'    => TRUE,
        'constraint'     => '11',
        'auto_increment' => true
    ],
    'CodEstado' => [
        'type'           => 'int',
        'constraint'     => '11',
    ],
    'Nome' => [
        'type'       => 'varchar',
        'constraint' => '100'
    ]
];

// Tabela de Classificacoes
$config['schema']['Classificacoes'] = [
    'CodClassificacao' => [
        'type'           => 'int',
        'primary_key'    => TRUE,
        'constraint'     => '11',
        'auto_increment' => true
    ],
    'Nome' => [
        'type'       => 'varchar',
        'constraint' => '100'
    ],
    'Icone' => [
        'type'       => 'varchar',
        'constraint' => '100'
    ],
    'Ordem' => [
        'type'           => 'int',
        'constraint'     => '11',
    ]
];

// Tabela do Ranking
$config['schema']['Ranking'] = [
    'CodRanking' => [
        'type'           => 'int',
        'constraint'     => '11',
        'primary_key'    => TRUE,
        'auto_increment' => TRUE
    ],
    'CodCluster' => [
        'type'       => 'int',
        'constraint' => '11'
    ],
    'CodUsuario' => [
        'type'       => 'int',
        'constraint' => '11'
    ],
    'Pontos' => [
        'type'       => 'int',
        'constraint' => '11'
    ],
    'Posicao' => [
        'type'       => 'int',
        'constraint' => '11'
    ]
];

// Tabela de cluster
$config['schema']['Clusters'] = [
    'CodCluster' => [
        'type'           => 'int',
        'primary_key'    => TRUE,
        'constraint'     => '11',
        'auto_increment' => true
    ],
    'Nome' => [
        'type'       => 'varchar',
        'constraint' => '100'
    ]
];

// Tabela de Lojas
$config['schema']['Lojas'] = [
    'CodLoja' => [
        'type'           => 'int',
        'primary_key'    => TRUE,
        'constraint'     => '11',
        'auto_increment' => true
    ],
    'CodCluster' => [
        'type'       => 'int',
        'constraint' => '11',
        'null'       => true        
    ],
    'CNPJ' => [
        'type'       => 'varchar',
        'constraint' => '100',
        'null'       => true
    ],
    'Razao' => [
        'type'       => 'varchar',
        'constraint' => '100',
        'null'       => true        
    ],
    'Nome' => [
        'type'       => 'varchar',
        'constraint' => '100',
        'null'       => true        
    ],
    'Endereco' => [
        'type' => 'text',
        'null' => true        
    ],
    'Numero' => [
        'type'       => 'varchar',
        'constraint' => '100',
        'null'       => true
    ],
    'Complemento' => [
        'type'       => 'varchar',
        'constraint' => '100',
        'null'       => true        
    ],
    'Bairro' => [
        'type'       => 'varchar',
        'constraint' => '100',
        'null'       => true        
    ],
    'CodCidade' => [
        'type'       => 'int',
        'constraint' => '11',
        'null'       => true        
    ],
    'CodEstado' => [
        'type'       => 'int',
        'constraint' => '11',
        'null'       => true        
    ],
    'PontosIniciais' => [
        'type'       => 'varchar',
        'constraint' => '100',
    ]
];

// Tabela de Usuarios
$config['schema']['Funcionarios'] = [
    'CodFuncionario' => [
        'type'           => 'int',
        'primary_key'    => TRUE,
        'constraint'     => '11',
        'auto_increment' => true
    ],
    'CodLoja' => [
        'type'       => 'varchar',
        'constraint' => '100'
    ],
    'UID' => [
        'type'       => 'varchar',
        'constraint' => '100'
    ],
    'Token' => [
        'type'       => 'varchar',
        'constraint' => '32'
    ],
    'Cargo' => [
        'type'       => 'varchar',
        'constraint' => '100',
    ],
    'Nome' => [
        'type'       => 'varchar',
        'constraint' => '100',
    ],
    'Email' => [
        'type'       => 'varchar',
        'constraint' => '100',
    ],
    'Senha' => [
        'type'       => 'varchar',
        'constraint' => '100',
    ],
    'CPF' => [
        'type'       => 'varchar',
        'constraint' => '100',
    ],
    'Pontos' => [
        'type'       => 'varchar',
        'constraint' => '100',
    ]
];

// Tabela de Produtos
$config['schema']['Produtos'] = [
    'CodProduto' => [
        'type'           => 'int',
        'constraint'     => '11',
        'primary_key'    => TRUE,
        'auto_increment' => TRUE,
    ],
    'BasicCode' => [
        'type'       => 'varchar',
        'constraint' => '255'
    ],
    'Nome' => [
        'type'       => 'varchar',
        'constraint' => '255'
    ],
    'CodCategoria' => [
        'type'       => 'int',
        'constraint' => '11'
    ],
    'Descricao' => [
        'type' => 'text'
    ],
    'Foto' => [
        'type'       => 'varchar',
        'constraint' => '255'
    ],
    'Pontos' => [
        'type'       => 'int',
        'constraint' => '11',
    ],
    'Video' => [
        'type'       => 'varchar',
        'constraint' => '255'
    ]
];

// Tabela Categorias
$config['schema']['Categorias'] = [
    'CodCategoria' => [
        'type'           => 'int',
        'primary_key'    => TRUE,
        'constraint'     => '11',
        'auto_increment' => true
    ],
    'Nome' => [
        'type'       => 'varchar',
        'constraint' => '255'
    ],
    'Foto' => [
        'type'       => 'varchar',
        'constraint' => '255'
    ]
];

// questionarios encerrados
$config['schema']['QuestionariosEncerrados'] = [
    'CodQuestionarioEncerrado' => [
        'type'           => 'int',
        'constraint'     => '11',
        'primary_key'    => TRUE,
        'auto_increment' => TRUE
    ],
    'CodQuestionario' => [
        'type'       => 'int',
        'constraint' => '11',
    ],
    'CodUsuario' => [
        'type'       => 'int',
        'constraint' => '11',
    ],
    'Pontos' => [
        'type'       => 'int',
        'constraint' => '11',
    ],
    'Data' => [
        'type' => 'datetime',
    ]
];

// Tabela Questionarios
$config['schema']['Questionarios'] = [
    'CodQuestionario' => [
        'type'           => 'int',
        'constraint'     => '11',
        'primary_key'    => TRUE,
        'auto_increment' => TRUE
    ],
    'Descricao' => [
        'type' => 'text',
    ],
    'Nome' => [
        'type'       => 'varchar',
        'constraint' => '255'
    ],
    'Foto' => [
        'type'       => 'varchar',
        'constraint' => '50',
        'null'       => true
    ]
];

// Tabela de Perguntas
$config['schema']['Perguntas'] = [
    'CodPergunta' => [
        'type'           => 'int',
        'constraint'     => '11',
        'primary_key'    => TRUE,
        'auto_increment' => TRUE
    ],
    'CodQuestionario' => [
        'type'       => 'int',
        'constraint' => '11'
    ],
    'Resposta' => [
        'type'       => 'int',
        'constraint' => '1'
    ],
    'Texto' => [
        'type'       => 'text',
    ],
    'Pontos' => [
        'type'       => 'int',
        'constraint' => '11'
    ],
    'Alternativa1' => [
        'type' => 'text',
        'null' => true        
    ],
    'Alternativa2' => [
        'type' => 'text',
        'null' => true        
    ],
    'Alternativa3' => [
        'type' => 'text',
        'null' => true
    ],
    'Alternativa4' => [
        'type' => 'text',
        'null' => true
    ]
];

// Tabela de Respostas
$config['schema']['Respostas'] = [
    'CodResposta' => [
        'type'           => 'int',
        'constraint'     => '11',
        'primary_key'    => TRUE,
        'auto_increment' => TRUE
    ],
    'CodUsuario' => [
        'type'       => 'int',
        'constraint' => '11'
    ],
    'CodPergunta' => [
        'type'       => 'int',
        'constraint' => '11',
    ],
    'Alternativa' => [
        'type'       => 'int',
        'constraint' => '1',
    ]
];

// Tabela de Vendas
$config['schema']['Logs'] = [
    'CodLog' => [
        'type'        => 'int',
        'constraint'  => '11',
        'primary_key' => TRUE,
        'auto_increment' => TRUE,
    ],
    'Entidade' => [
        'type'       => 'varchar',
        'constraint' => '255'
    ],
    'Planilha' => [
        'type'       => 'varchar',
        'constraint' => '255'
    ],
    'Mensagem' => [
        'type' => 'text',
    ],
    'Status' => [
        'type'       => 'varchar',
        'constraint' => '2'
    ],
    'Data' => [
        'type' => 'date',
    ]
];

// Tabela Notificacoes
$config['schema']['Notificacoes'] = [
    'CodNotificacao' => [
        'type'           => 'int',
        'primary_key'    => TRUE,
        'constraint'     => '11',
        'auto_increment' => true
    ],
    'Nome' => [
        'type'       => 'varchar',
        'constraint' => '255'
    ],
    'Notificacao' => [
        'type'       => 'varchar',
        'constraint' => '255'
    ],
    'Disparos' => [
        'type'       => 'int',
        'constraint' => '11'
    ],
    'Texto' => [
        'type' => 'text'
    ],
];

// Tabela de Disparos
$config['schema']['Disparos'] = [
    'CodDisparo' => [
        'type'           => 'int',
        'primary_key'    => TRUE,
        'constraint'     => '11',
        'auto_increment' => true
    ],
    'CodNotificacao' => [
        'type'           => 'int',
        'constraint'     => '11',
    ],
    'CodFuncionario' => [
        'type'       => 'int',
        'constraint' => '11'
    ],
    'Data' => [
        'type'       => 'datetime'
    ],
    'Status' => [
        'type'       => 'varchar',
        'constraint' => '2'
    ],
];

// Tabela de Treinamento
$config['schema']['Treinamentos'] = [
    'CodTreinamento' => [
        'type'           => 'int',
        'constraint'     => '11',
        'primary_key'    => TRUE,
        'auto_increment' => TRUE,
    ],
    'Nome' => [
        'type'       => 'varchar',
        'constraint' => '255'
    ],
    'Descricao' => [
        'type' => 'text'
    ],
    'Foto' => [
        'type'       => 'varchar',
        'constraint' => '255'
    ],
    'Video' => [
        'type'       => 'varchar',
        'constraint' => '255'
    ]
];

// Tabela de Vendas
$config['schema']['Vendas'] = [
    'CodVenda' => [
        'type'           => 'int',
        'constraint'     => '11',
        'primary_key'    => TRUE,
        'auto_increment' => TRUE
    ],
    'CodFuncionario' => [
        'type'       => 'int',
        'constraint' => '11'
    ],
    'CodProduto' => [
        'type'       => 'int',
        'constraint' => '11',
    ],
    'Quantidade' => [
        'type'       => 'int',
        'constraint' => '11',
    ],
    'Pontos' => [
        'type'       => 'int',
        'constraint' => '11',
    ],
    'Data' => [
        'type'       => 'datetime'
    ],
    'CodLoja' => [
        'type'       => 'int',
        'constraint' => '11',
    ],
];

// Tabela de Mensagens
$config['schema']['Mensagens'] = [
    'CodMensagem' => [
        'type'           => 'int',
        'constraint'     => '11',
        'primary_key'    => TRUE,
        'auto_increment' => TRUE
    ],
    'CodFuncionario' => [
        'type'       => 'int',
        'constraint' => '11'
    ],
    'Texto' => [
        'type' => 'text',
    ],
    'Data' => [
        'type' => 'datetime',
    ]
];

/* end of file */
