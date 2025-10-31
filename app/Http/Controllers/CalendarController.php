<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Finalidade;
use App\Models\Reserva;
use App\Models\Sala;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request){
        $categorias = Categoria::select('id','nome')->get();
        $dataSelecionada = Carbon::createFromFormat('d/m/Y', $request->data ?? Carbon::today()->format('d/m/Y'));
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
        ->when($request->categoria_id, function($query) use ($request){
            $query->where('salas.categoria_id',$request->categoria_id);
        })
        ->whereDate('reservas.data', $dataSelecionada)
        ->get();
        
        $salas = Sala::where('categoria_id',$request->categoria_id)->get();
        
        return view('sala.calendario',[
            'reservas' => collect($request)->isNotEmpty() ? $reservas : collect(),
            'data' => Carbon::now(),
            'categorias' => $categorias,
            'categoria_id' => $request->categoria_id ?? [],
            'salas' => $salas,
            'finalidades' => $reservas->groupBy('cor'),
        ]);
    }
}