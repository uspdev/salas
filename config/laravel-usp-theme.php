<?php

$submenuSalas = [
    [
        'text' => '<i class="fa fa-list"></i>  Lista de Salas',
        'url' => config('app.url') . '/salas',
    ],
    [
        'text' => '<i class="fa fa-plus-square"></i>  Cadastrar nova sala',
        'url' => config('app.url') . '/salas/create',
        'can' => 'admin',
    ],
    [
        'type' => 'divider',
        'can'  => '',
    ],
    [
        'type' => 'header',
        'text' => 'Cabeçalho',
        'can'  => '',
    ],
    [
        'text' => 'SubItem 3',
        'url' => config('app.url') . '/subitem3',
        'can' => '',
    ],
];

$submenuReservas = [
    [
        'text' => '<i class="fa fa-calendar"></i> Todas as reservas',
        'url' => config('app.url') . '/reservas',
    ],
    [
        'text' => '<i class="fa fa-calendar-plus"></i> Nova reserva',
        'url' => config('app.url') . '/reservas/create',
        /* 'can' => 'admin', */
    ],
];

$submenuCategorias = [
    [
        'text' => '<i class="fa fa-list"></i> Lista de categorias',
        'url' => config('app.url') . '/categorias',
    ],
    [
        'text' => '<i class="fa fa-plus-square"></i> Cadastrar nova categoria',
        'url' => config('app.url') . '/categorias/create',
        /* 'can' => 'admin', */
    ],
];

$submenuRecursos = [
    [
        'text' => '<i class="fa fa-list"></i> Lista de recursos',
        'url' => config('app.url') . '/recursos',
    ],
    [
        'text' => '<i class="fa fa-plus-square"></i> Cadastrar novo recurso',
        'url' => config('app.url') . '/recursos/create',
        /* 'can' => 'admin', */
    ],
];
$menu = [
    /* [
        'text' => '<i class="fas fa-home"></i> Home',
        'url' => config('app.url') . '/home',
        'can' => '',
    ],
    [
        'text' => 'Item1',
        'url' => config('app.url') . '',
        'can' => '',
    ],
    [
        'text' => 'Item 3',
        'url' => config('app.url') . '/item3',
        'can' => 'admin',
    ],
    [
        'text' => 'Salas',
        'submenu' => $submenuSalas,
        'can' => 'admin',
    ],
    [
        'text' => 'Reservas',
        'submenu' => $submenuReservas,
        'can' => '',
    ],
    [
        'text' => 'Categorias',
        'submenu' => $submenuCategorias,
        'can' => '',
    ],
    [
        'text' => 'Recursos',
        'submenu' => $submenuRecursos,
        'can' => '',
    ], */

];

$right_menu = [
    [
        'text' => '<i class="fas fa-cog"></i>',
        'title' => 'Configurações',
        'target' => '_blank',
        'url' => config('app.url') . '/item1',
        'align' => 'right',
    ],
];

# dashboard_url renomeado para app_url
# USPTHEME_SKIN deve ser colocado no .env da aplicação 

return [
    'title' => config('app.name'),
    'skin' => env('USP_THEME_SKIN', 'uspdev'),
    'app_url' => config('app.url'),
    'logout_method' => 'POST',
    'logout_url' => config('app.url') . '/logout',
    'login_url' => config('app.url') . '/login',
    'menu' => $menu,
    'right_menu' => $right_menu,
];
