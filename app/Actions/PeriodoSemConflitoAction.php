<?php

namespace App\Actions;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Reserva;
use Illuminate\Support\Facades\DB;

class PeriodoSemConflitoAction
{
    public static function handle(int $sala_id, Carbon $inicio, Carbon $fim, string $horario_inicio, string $horario_fim) {

        $datasReservas = Reserva::select('data')
            ->whereBetween('data', [$inicio->format('Y-m-d'), $fim->format('Y-m-d')])
            ->where('horario_inicio', '<', DB::raw("STR_TO_DATE('{$horario_fim}', '%H:%i')"))
            ->where('horario_fim', '>', DB::raw("STR_TO_DATE('{$horario_inicio}', '%H:%i')"))
            ->where('sala_id', $sala_id)
            ->toBase()
            ->get();

        $periodo = CarbonPeriod::between($inicio, $fim);

        $periodo->filter(function (Carbon $date) use ($datasReservas) {
            return ! $datasReservas->contains('data', $date->format('Y-m-d'));
        });

        return $periodo;
    }
}
