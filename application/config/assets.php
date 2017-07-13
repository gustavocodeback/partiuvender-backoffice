<?php defined('BASEPATH') OR exit('No direct script access allowed');

// pacotes padroes
$config['default'] = [ 'jquery', 'global', 'bootstrap' ];

// configuracao dos assets globais
$config['global'] = [
    'css' => [
        site_url( 'assets/global/global.css' ),
        'https://fonts.googleapis.com/css?family=Varela+Round'
    ],
    'js' => [
        site_url( 'assets/global/global.js' )
    ]
];

// inclui o bootstrap
$config['bootstrap'] = [
    'css'=> [
        site_url( 'assets/bootstrap/dist/css/bootstrap.min.css' )
    ],
    'js' => [
        site_url( 'assets/bootstrap/dist/js/bootstrap.min.js' )
    ]
];

// inclui o jquery
$config['jquery'] = [
    'js' => [
        site_url( 'assets/jquery/dist/jquery.min.js' )
    ]
];

// mascaras
$config['jquery-mask'] = [
    'js' => [
        site_url( 'assets/jquery-mask-plugin/dist/jquery.mask.min.js' ),
        site_url( 'assets/global/masks.js' ),
    ]
];

// inclui o vue
$config['vue'] = [
    'js' => [
        site_url( 'assets/vue/dist/vue.min.js' )
    ]
];

// seta o login
$config['login'] = [
    'css'=> [
        site_url( 'assets/pages/login/login.css' )
    ],
    'js'=> [
        site_url( 'assets/pages/login/login.js' )
    ]
];

// seta o dashboard
$config['aside'] = [
    'css'=> [
        site_url( 'assets/components/aside/aside.css' )
    ]
];

// seta o dashboard
$config['dashboard'] = [
    'css'=> [
        site_url( 'assets/pages/dashboard/dashboard.css' )
    ]
];

// seta o grid
$config['grid'] = [
    'css'=> [
        site_url( 'assets/pages/grid/grid.css' )
    ]
];

// seta o navbar
$config['navbar'] = [
    'css'=> [
        site_url( 'assets/components/navbar/navbar.css' )
    ]
];
