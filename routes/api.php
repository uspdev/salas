<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservaController;

Route::get('/salas',[ReservaController::class,'index']);
Route::get('/agendas',[ReservaController::class,'schedules']);
