<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservaController; 
use App\Http\Controllers\SalaController; 
use App\Http\Controllers\CategoriaController; 
use App\Http\Controllers\RecursoController; 
use App\Http\Controllers\IndexController; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/home', 'home');
Route::get('/', [IndexController::class,'index']);
Route::resource('/reservas', ReservaController::class);
Route::resource('/salas', SalaController::class);
Route::resource('/categorias', CategoriaController::class);
Route::resource('/recursos', RecursoController::class);