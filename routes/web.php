<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservaController; 
use App\Http\Controllers\SalaController; 
use App\Http\Controllers\CategoriaController; 
use App\Http\Controllers\RecursoController; 
use App\Http\Controllers\IndexController; 
use App\Http\Controllers\LoginController; 
use App\Http\Controllers\GeneralSettingsController;

Route::get('login', [LoginController::class, 'redirectToProvider']);
Route::get('callback', [LoginController::class, 'handleProviderCallback']);
Route::post('logout', [LoginController::class, 'logout']);

Route::resource('/reservas', ReservaController::class);
Route::resource('/salas', SalaController::class);

Route::resource('/recursos', RecursoController::class);


Route::resource('/categorias', CategoriaController::class);

Route::post('/categorias/adduser/{categoria}', [CategoriaController::class,'addUser']);
Route::delete('/categorias/removeuser/{categoria}/{user}', [CategoriaController::class,'removeUser']);

# Logs  
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('can:admin');

# settings
Route::get('/settings', [GeneralSettingsController::class,'show']);
Route::post('/settings', [GeneralSettingsController::class,'update']);

# index:
Route::get('/', [IndexController::class,'home']);
