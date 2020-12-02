<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservaController; 
use App\Http\Controllers\SalaController; 

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
Route::resource('/reserva', ReservaController::class);
Route::resource('/sala', SalaController::class);
