<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarioRequest;
use App\Models\Categoria;
use App\Models\Reserva;
use App\Models\Sala;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(CalendarioRequest $request){
        $data = Carbon::createFromFormat('d/m/Y', $request->data ?? today()->format('d/m/Y'));
        $reservas = Reserva::join('salas','salas.id','reservas.sala_id')
        ->join('finalidades','finalidades.id','reservas.finalidade_id')
        ->select(
            'salas.nome as nome_sala',
            'reservas.sala_id',
            'reservas.nome',
            'reservas.horario_inicio',
            'reservas.horario_fim',
            'reservas.data',
            'finalidades.cor',
            'finalidades.legenda',
            )
            ->where('salas.categoria_id', $request->categoria_id)
            ->whereDate('reservas.data', $data)
            ->get();

        $salas = Sala::where('categoria_id', $request->categoria_id)->get();
        $categorias = Categoria::select('id','nome')->get();

        $dados = [
            'reserva_grafico' => collect($request)->isNotEmpty() ? $reservas : collect(),
            'data' => Carbon::now(),
            'categorias' => $categorias,
            'categoria_id' => $request->categoria_id ?? [],
            'salas_aula' => $salas,
            'finalidade_reserva' => $reservas->groupBy('cor'),
        ];

        return response()->json($dados);
    }
}
