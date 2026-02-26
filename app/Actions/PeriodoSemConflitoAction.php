<?php

namespace App\Actions;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Reserva;
use Illuminate\Support\Facades\DB;

class PeriodoSemConflitoAction
{
    public static function handle($validated, Carbon $inicio, Carbon $fim) {
        
        $query = Reserva::select('data')
            ->whereBetween('data', [$inicio->format('Y-m-d'), $fim->format('Y-m-d')])
            ->where('horario_inicio', '<', DB::raw("STR_TO_DATE('{$validated["horario_fim"]}', '%H:%i')"))
            ->where('horario_fim', '>', DB::raw("STR_TO_DATE('{$validated["horario_inicio"]}', '%H:%i')"))
            ->where('sala_id', $validated['sala_id']);

        //é uma tentativa de edição de reserva (serve para ignorar a reserva "pai")
        if(isset($validated['id'])){
            $datasReservas = $query->where('id','!=',$validated['id'])->toBase()->get();
        }
        //senão, é uma criação de reserva
        $datasReservas = $query->toBase()->get();

        $periodo = CarbonPeriod::between($inicio, $fim);

        $periodo->filter(function (Carbon $date) use ($datasReservas) {
            return ! $datasReservas->contains('data', $date->format('Y-m-d'));
        });

        return $periodo;
    }
}
