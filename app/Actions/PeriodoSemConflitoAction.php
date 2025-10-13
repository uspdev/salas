<?php

namespace App\Actions;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Reserva;

class PeriodoSemConflitoAction
{
    public static function handle(int $sala_id, Carbon $inicio, Carbon $fim, string $horario_inicio, string $horario_fim) {

        $datasReservas = Reserva::select('data')
            ->whereBetween('data', [$inicio, $fim])
            ->where('horario_inicio', '<', $horario_fim)
            ->where('horario_fim', '>', $horario_inicio)
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
