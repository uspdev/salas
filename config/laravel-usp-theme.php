<?php

$admin = [
    [
        'text' => 'Nova Categoria',
        'url' => config('app.url').'/categorias/create',
        'can' => 'admin',
    ],
    [
        'text' => 'Nova Sala',
        'url' => config('app.url').'/salas/create',
        'can' => 'admin',
    ],
    [
        'text' => 'Recursos',
        'url' => config('app.url').'/recursos',
        'can' => 'admin',
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
