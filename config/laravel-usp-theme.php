<?php

$admin = [
    [
        'type' => 'header',
        'text' => '<i class="fa fa-bookmark"></i> Categoria',
        'can' => 'admin',
    ],
    [
        'text' => '<i class="fas fa-plus-circle"></i> Cadastrar Categoria',
        'url' => 'categorias/create',
        'can' => 'admin'
    ],
    [
        'text' => '<i class="fas fa-list-ul"></i> Listar Categorias',
        'url' => 'categorias',
        'can' => 'admin'
    ],
    [
        'type' => 'divider',
        'can'=> 'admin'
    ],
    [
        'type' => 'header',
        'text' => '<i class="fas fa-map-marker"></i> Sala',
        'can' => 'admin',
    ],
    [
        'text' => '<i class="fas fa-plus-circle"></i> Cadastrar Sala',
        'url' => 'salas/create',
        'can' => 'admin'
    ],
    [
        'text' => '<i class="fas fa-list-ul"></i> Listar Salas',
        'url' => 'salas/listar',
        'can' => 'admin'
    ],
    [
        'type' => 'divider',
        'can'=> 'admin'
    ],
    [
        'type' => 'header',
        'text' => '<i class="fas fa-boxes"></i> Recursos',
        'can' => 'admin',
    ],
    [
        'text' => '<i class="fas fa-plus-circle"></i> Cadastrar Recurso',
        'url' => 'recursos/create',
        'can' => 'admin'
    ],
    [
        'text' => '<i class="fas fa-list-ul"></i> Listar Recursos',
        'url' => 'recursos',
        'can' => 'admin'
    ],
    [
        'type' => 'divider',
        'can'=> 'admin'
    ],
    [
        'type' => 'header',
        'text' => '<i class="fas fa-calendar"></i> Período Letivo',
        'can' => 'admin',
    ],
    [
        'text' => '<i class="fas fa-plus-circle"></i> Cadastrar Período Letivo',
        'url' => 'periodos_letivos/create',
        'can' => 'admin'
    ],
    [
        'text' => '<i class="fas fa-list-ul"></i> Listar Períodos Letivos',
        'url' => 'periodos_letivos',
        'can' => 'admin'
    ],
];

$reservas = [
    [
        'text' => '<i class="fas fa-list"></i> Minhas Reservas',
        'url' => config('app.url').'/reservas/my',
        'can' => 'logado',
    ],
    [
        'text' => '<i class="fas fa-plus-square"></i> Nova reserva',
        'url' => config('app.url').'/reservas/create',
        'can' => 'logado',
    ],
];

$menu = [
    [
        'text' => 'Hoje',
        'url' => config('app.url').'/',
    ],
    [
        'text' => 'Calendário por Sala',
        'url' => config('app.url').'/salas',
    ],
    [
        'text' => 'Nova reserva',
        'url' => config('app.url').'/reservas/create',
        'can' => 'logado',
    ],
    [
        'text' => 'Minhas Reservas',
        'url' => config('app.url').'/reservas/my',
        'can' => 'logado',
    ],
    [
        'text' => 'Administração',
        'submenu' => $admin,
        'can' => 'admin',
    ],
];

$right_menu = [
    [
        'text' => '<i class="fas fa-cog"></i>',
        'title' => 'Configurações',
        'target' => '_blank',
        'url' => config('app.url').'/settings',
        'align' => 'right',
        'can' => 'admin',
    ],
    [
        'key' => 'senhaunica-socialite'
    ],
    [
        'key' => 'laravel-tools'
    ],
];

// dashboard_url renomeado para app_url
// USPTHEME_SKIN deve ser colocado no .env da aplicação

return [
    'title' => config('app.name'),
    'skin' => env('USP_THEME_SKIN', 'uspdev'),
    'app_url' => config('app.url'),
    'logout_method' => 'POST',
    'logout_url' => config('app.url').'/logout',
    'login_url' => config('app.url').'/login',
    'menu' => $menu,
    'right_menu' => $right_menu,
];
