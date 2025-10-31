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
        'url' => '/reservas/my',
        'can' => 'logado',
    ],
    [
        'text' => '<i class="fas fa-plus-square"></i> Nova reserva',
        'url' => '/reservas/create',
        'can' => 'logado',
    ],
];

$menu = [
    [
        'text' => '<i class="fa fa-calendar-check" aria-hidden="true"></i> Hoje',
        'url' => '/',
    ],
    [
        'text' => '<i class="fa fa-calendar-times" aria-hidden="true"></i> Calendário por Sala',
        'url' => '/salas',
    ],
    [
        'text' => '<i class="fa fa-filter" aria-hidden="true"></i> Filtro de Recursos',
        'url' => '/filtro_de_recursos',
    ],
    [
        'text' => '<i class="fa fa-calendar-plus" aria-hidden="true"></i> Nova reserva',
        'url' => '/reservas/create',
        'can' => 'logado',
    ],
    [
        'text' => '<i class="fa fa-user-check" aria-hidden="true"></i> Minhas Reservas',
        'url' => '/reservas/my',
        'can' => 'logado',
    ],
    [
        'text' =>  '<i class="fas fa-check"></i> Salas disponíveis',
        'url' => '/salas_livres',
        'can' => ''
    ],
    [
        'text' => '<i class="fa fa-user-cog" aria-hidden="true"></i> Administração',
        'submenu' => $admin,
        'can' => 'admin',
    ],
];

$right_menu = [
    [
        'text' => '<i class="fas fa-cog"></i>',
        'title' => 'Configurações',
        'target' => '_blank',
        'url' => '/settings',
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
    'logout_url' => '/logout',
    'login_url' => '/login',
    'menu' => $menu,
    'right_menu' => $right_menu,
];
