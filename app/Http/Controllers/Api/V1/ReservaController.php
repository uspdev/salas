<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ReservaResource;
use App\Models\Finalidade;
use App\Models\Reserva;
use App\Models\Sala;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    /**
     * Retorna as reservas da sala marcadas para o dia corrente.
     * 
     * @param Sala $sala
     * 
     * @return object
     */
    public function porSala(Sala $sala){
       $reservas = Reserva::where('sala_id', $sala->id)->where('data', Carbon::now()->format('Y-m-d'))->get();

       return ReservaResource::collection($reservas);
    }

    /**
     * Retorna as reservas com a finalidade marcadas para o dia corrente.
     * 
     * @param Finalidade $finalidade
     * 
     * @return object
     */
    public function porFinalidade(Finalidade $finalidade){
       $reservas = Reserva::where('finalidade_id', $finalidade->id)->where('data', Carbon::now()->format('Y-m-d'))->get();

       return ReservaResource::collection($reservas);
    }
}
