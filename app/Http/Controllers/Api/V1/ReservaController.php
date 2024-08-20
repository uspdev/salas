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
     * Retorna todas as reservas com base nos filtros passados pelo método GET.
     * Se nenhum filtro for passado será retornado todas as reservas do dia corrente.
     * 
     * Três filtros estão disponíveis:
     * - sala        // Recebe o id da sala.
     * - finalidade  // Recebe o id da finalidade.
     * - data        // Deve estar no formato 'Y-m-d'
     * 
     * @param Request $request
     * 
     * @return object
     */
    public function getReservas(Request $request) : object {
       $data = is_null($request->input('data')) ? Carbon::now()->format('Y-m-d') : $request->input('data');

       $reservas = Reserva::where('data', $data)->where('status', 'aprovada')->get();

       if(!is_null($request->input('finalidade'))){
         $reservas = $reservas->where('finalidade_id', $request->input('finalidade'));
       }

       if(!is_null($request->input('sala'))){
         $reservas = $reservas->where('sala_id', $request->input('sala'));
       }

       return ReservaResource::collection($reservas);
    }
}
