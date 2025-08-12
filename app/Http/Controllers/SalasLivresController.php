<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Sala;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalasLivresController extends Controller
{
    public function index()
    {
        return view('sala.salas_livres');
    }

    //pegar as reservas que não estão ocupadas nos horários solicitados
    public function search()
    {

        $data = Carbon::createFromFormat("d/m/Y", request()->data)->format('Y-m-d');
        $horario_inicio = request()->horario_inicio;
        $horario_fim = request()->horario_fim;        

        $regex_horario = '/^(?:[1-9]|1[0-9]|2[0-3]):[0-5][0-9]$/'; //viável?
        if (!preg_match($regex_horario, $horario_inicio) || !preg_match($regex_horario,$horario_fim)) {
            return response()->json(400);
        }

        $salas = Reserva::join('salas', 'salas.id', 'reservas.sala_id')
            ->join('categorias', 'categorias.id', 'salas.categoria_id')
            ->select('salas.id', 'salas.capacidade', 'salas.nome', 'categorias.nome as nomcat')
            ->whereNotIn('reservas.sala_id', function ($query) use ($horario_inicio, $horario_fim, $data) {
                $query->select('reservas.sala_id')
                    ->from('reservas')
                    ->where(function ($query) use ($horario_inicio, $horario_fim, $data) {
                        $query->whereRaw('reservas.horario_inicio = STR_TO_DATE(?, "%H:%i")', [$horario_inicio])
                            ->whereRaw('reservas.horario_fim = STR_TO_DATE(?, "%H:%i")', [$horario_fim])
                            ->whereRaw('reservas.data = ?',[$data]);
                    })
                    ->orWhere(function ($query2) use ($horario_inicio, $horario_fim, $data) {
                        $query2->whereRaw('reservas.horario_inicio >= STR_TO_DATE(?, "%H:%i")', [$horario_inicio])
                            ->whereRaw('reservas.horario_fim <= STR_TO_DATE(?, "%H:%i")', [$horario_fim])
                            ->whereRaw('reservas.data = ?',[$data]);
                    })
                    ->orWhere(function ($query3) use ($horario_inicio, $horario_fim, $data){
                        $query3->whereRaw('reservas.horario_inicio <= STR_TO_DATE(?, "%H:%i")', [$horario_inicio])
                        ->whereRaw('reservas.horario_fim >= STR_TO_DATE(?, "%H:%i")', [$horario_fim])
                        ->whereRaw('reservas.data = ?',[$data]);
                    });
            })
            ->groupBy('reservas.sala_id','salas.id','salas.capacidade','salas.nome','categorias.nome')
            ->get();

        if ($salas->isNotEmpty()) {
            return response()->json($salas);
        } else {
            return response()->json(404);
        }
    }
}
