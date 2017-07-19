<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['Grupo'] = [
    'grupo'  => 'grupo'
];

$config['Rotina'] = [
    'link'          => 'Link',
    'rotina'        => 'Rotina',
    'classificacao' => 'CodClassificacao',
];

$config['Classificacao'] = [
    'nome'   => 'Nome',
    'icone'  => 'Icone',
    'ordem'  => 'Ordem'
];

$config['Estado'] = [
    'nome' => 'Nome',
    'uf'   => 'Uf',
];

$config['Cidade'] = [
    'nome'   => 'Nome',
    'estado' => 'CodEstado',
];

$config['Usuario'] = [
    'uid'   => 'uid',
    'email' => 'email',
    'senha' => 'password',
    'gid'   => 'gid',
];

$config['Cluster'] = [
    'nome' => 'Nome',
];

$config['Loja'] = [
    'cluster'     => 'CodCluster',
    'cnpj'        => 'CNPJ',
    'razao'       => 'Razao',
    'nome'        => 'Nome',
    'endereco'    => 'Endereco',
    'numero'      => 'Numero',
    'complemento' => 'Complemento',
    'bairro'      => 'Bairro',
    'cidade'      => 'CodCidade',
    'estado'      => 'CodEstado'
];

$config['Funcionario'] = [
    'loja'   => 'CodLoja',
    'uid'    => 'UID',
    'token'  => 'Token',
    'cargo'  => 'Cargo',
    'nome'   => 'Nome',
    'email'  => 'Email',
    'cpf'    => 'CPF',
    'pontos' => 'Pontos'
];

$config['Categoria'] = [
    'nome' => 'Nome',
    'foto' => 'Foto'
];

$config['Produto'] = [
    'basiccode' => 'BasicCode',
    'nome'      => 'Nome',
    'categoria' => 'CodCategoria',
    'descricao' => 'Descricao',
    'foto'      => 'Foto',
    'pontos'    => 'Pontos',
    'video'     => 'Video'
];

$config['Log'] = [
    'entidade' => 'Entidade',
    'planilha' => 'Planilha',
    'mensagem' => 'Mensagem',
    'status'   => 'Status',
    'data'     => 'Data',
];

$config['Questionario'] = [
    'descricao' => 'Descricao',
    'nome'      => 'Nome',
    'foto'      => 'Foto'
];

$config['Pergunta'] = [
    'resposta'     => 'Resposta',
    'texto'        => 'Texto',
    'pontos'       => 'Pontos',
    'questionario' => 'CodQuestionario',
    'alternativa1' => 'Alternativa1',
    'alternativa2' => 'Alternativa2',
    'alternativa3' => 'Alternativa3',
    'alternativa4' => 'Alternativa4',
];

$config['Resposta'] = [
    'usuario'     => 'CodUsuario',
    'pergunta'    => 'CodPergunta',
    'alternativa' => 'Alternativa',
];

$config['Notificacao'] = [
    'notificacao'   => 'Notificacao',
    'nome'          => 'Nome',
    'disparos'      => 'Disparos',
    'texto'         => 'Texto'
];

$config['Disparo'] = [
    'funcionario'   => 'CodFuncionario',
    'notificacao'   => 'CodNotificacao',
    'data'          => 'Data',
    'status'        => 'Status'
];

$config['Venda'] = [
    'funcionario'   => 'CodFuncionario',
    'quantidade'    => 'Quantidade',
    'produto'       => 'CodProduto',
    'pontos'        => 'Pontos',
    'data'          => 'Data',
    'loja'          => 'CodLoja'
];

$config['Treinamento'] = [
    'nome'      => 'Nome',
    'descricao' => 'Descricao',
    'foto'      => 'Foto',
    'video'     => 'Video'
];

/* end of file */
