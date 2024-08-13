<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ReservaController;

Route::prefix('v1')->group(function(){
    Route::get('reservas/salas/{sala}', [ReservaController::class, 'porSala']);
    Route::get('reservas/finalidades/{finalidade}', [ReservaController::class, 'porFinalidade']);
});