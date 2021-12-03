<?php

$categoria = [
    [
        'text' => '<i class="fas fa-list"></i> Índice',
        'url'  => config('app.url') . '/categorias',
        'can'  => 'logado',
    ],
    [
        'text' => '<i class="fas fa-plus"></i> Nova Categoria',
        'url'  => config('app.url') . '/categorias/create',
        'can'  => 'admin',
    ],
];

$salas = [
    [
        'text' => '<i class="fas fa-list"></i> Índice',
        'url'  => config('app.url') . '/salas',
        'can'  => 'logado',
    ],
    [
        'text' => '<i class="fas fa-plus"></i> Nova Sala',
        'url'  => config('app.url') . '/salas/create',
        'can'  => 'admin',
    ],
];

$menu = [
    [
        'text' => '<i class="fas fa-plus-square"></i> Nova reserva',
        'url'  => config('app.url') . '/reservas/create',
        'can'  => 'logado',
    ],
    [
        'text'    => 'Categoria',
        'submenu' => $categoria,
        'can'     => 'logado',
    ],
    [
        'text'    => 'Salas',
        'submenu' => $salas,
        'can'     => 'logado',
    ],

];

$right_menu = [
    [
        'text'   => '<i class="fas fa-cog"></i>',
        'title'  => 'Configurações',
        'target' => '_blank',
        'url'    => config('app.url') . '/settings',
        'align'  => 'right',
        'can'    => 'admin',
    ],
    [
        'text'   => '<i class="fas fa-hard-hat"></i>',
        'title'  => 'Logs',
        'target' => '_blank',
        'url'    => config('app.url') . '/logs',
        'align'  => 'right',
        'can'    => 'admin',
    ],
];

# dashboard_url renomeado para app_url
# USPTHEME_SKIN deve ser colocado no .env da aplicação 

return [
    'title' => '',
    'skin' => env('USP_THEME_SKIN', 'uspdev'),
    'app_url' => config('app.url'),
    'logout_method' => 'POST',
    'logout_url' => config('app.url') . '/logout',
    'login_url' => config('app.url') . '/login',
    'menu' => $menu,
    'right_menu' => $right_menu,
];
