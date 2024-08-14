<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ReservaController;

Route::prefix('v1')->group(function(){
    Route::get('reservas/salas/{sala}', [ReservaController::class, 'getReservasPorSala']);
    Route::get('reservas/finalidades/{finalidade}', [ReservaController::class, 'getReservasPorFinalidade']);
    Route::get('categorias', [ReservaController::class, 'categorias']);
    Route::get('categorias/{categoria}', [ReservaController::class, 'getCategoria']);
    Route::get('salas/{sala}', [ReservaController::class, 'getSala']);
});