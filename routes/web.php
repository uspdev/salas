<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\FinalidadeController;
use App\Http\Controllers\GeneralSettingsController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PeriodoLetivoController;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ResponsavelController;
use App\Http\Controllers\SalaController;
use Illuminate\Support\Facades\Route;

// Reservas
Route::get('/reservas/my', [ReservaController::class, 'my']);
Route::get('/reservas/{reserva}/aprovar', [ReservaController::class, 'aprovar'])->name('reservas.aprovar');
Route::resource('/reservas', ReservaController::class)->except(['index']);

// Salas
Route::get('/salas/listar', [SalaController::class, 'listar']);
Route::resource('/salas', SalaController::class);
Route::post('/salas/redirect', [SalaController::class, 'redirect']);

// Responsáveis
Route::resource('/responsaveis', ResponsavelController::class)->only(['store', 'destroy'])->parameters(['responsaveis' => 'responsavel']);

// Recursos
Route::resource('/recursos', RecursoController::class)->only(['index', 'store', 'destroy']);

// Categorias
Route::resource('/categorias', CategoriaController::class);
Route::post('/categorias/adduser/{categoria}', [CategoriaController::class, 'addUser']);
Route::post('/categorias/alterar-vinculos/{categoria}', [CategoriaController::class, 'alterarVinculos'])->name('alterar-vinculos');
Route::delete('/categorias/removeuser/{categoria}/{user}', [CategoriaController::class, 'removeUser']);
Route::post('/categorias/updatesetores/{categoria}', [CategoriaController::class, 'updateSetores'])->name('categoria.update-setores');

// Logs
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('can:admin');

// Settings
Route::get('/settings', [GeneralSettingsController::class, 'show']);
Route::post('/settings', [GeneralSettingsController::class, 'update']);

// Home
Route::get('/', [IndexController::class, 'home'])->name('home');
Route::get('/search', [IndexController::class, 'search']);

// Finalidades
Route::resource('finalidades', FinalidadeController::class)->except(['show']);

// Períodos Letivos
Route::resource('/periodos_letivos', PeriodoLetivoController::class)->parameters([
    'periodos_letivos' => 'periodo',
]);;
