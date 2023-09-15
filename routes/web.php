<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\GeneralSettingsController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\SalaController;
use Illuminate\Support\Facades\Route;

// Reservas
Route::get('/reservas/my', [ReservaController::class, 'my']);
Route::resource('/reservas', ReservaController::class)->except(['index']);

// Salas
Route::resource('/salas', SalaController::class);
Route::post('/salas/redirect', [SalaController::class, 'redirect']);

// Recursos
Route::resource('/recursos', RecursoController::class)->only(['index', 'store', 'destroy']);

// Categorias
Route::resource('/categorias', CategoriaController::class)->except(['index']);
Route::post('/categorias/adduser/{categoria}', [CategoriaController::class, 'addUser']);
Route::post('/categorias/alterar-vinculos/{categoria}', [CategoriaController::class, 'alterarVinculos'])->name('alterar-vinculos');
Route::delete('/categorias/removeuser/{categoria}/{user}', [CategoriaController::class, 'removeUser']);

// Logs
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('can:admin');

// Settings
Route::get('/settings', [GeneralSettingsController::class, 'show']);
Route::post('/settings', [GeneralSettingsController::class, 'update']);

// Home
Route::get('/', [IndexController::class, 'home']);
Route::get('/search', [IndexController::class, 'search']);
