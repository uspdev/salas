<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\GeneralSettingsController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\SalaController;
use Illuminate\Support\Facades\Route;

Route::get('/reservas/my', [ReservaController::class, 'my']);
Route::resource('/reservas', ReservaController::class);
Route::get('/reservas/{reserva}/editAll', [ReservaController::class, 'editAll']);
Route::post('/reservas/updateAll/{reserva}', [ReservaController::class, 'updateAll']);

Route::resource('/salas', SalaController::class);

Route::resource('/recursos', RecursoController::class);

Route::resource('/categorias', CategoriaController::class);
Route::post('/categorias/adduser/{categoria}', [CategoriaController::class, 'addUser']);
Route::delete('/categorias/removeuser/{categoria}/{user}', [CategoriaController::class, 'removeUser']);

// Logs
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('can:admin');

// settings
Route::get('/settings', [GeneralSettingsController::class, 'show']);
Route::post('/settings', [GeneralSettingsController::class, 'update']);

// index:
Route::get('/', [IndexController::class, 'home']);
