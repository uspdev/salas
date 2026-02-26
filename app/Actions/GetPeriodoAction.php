<?php

namespace App\Actions;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class GetPeriodoAction
{
    public static function handle(array $validated) {

        if ( ! isset($validated['repeat_until']) ) {
            return [ Carbon::createFromFormat('d/m/Y', $validated['data']) ];
        }

        $inicio = Carbon::createFromFormat('d/m/Y', $validated['data']);
        $fim = Carbon::createFromFormat('d/m/Y', $validated['repeat_until']);

        $periodo = isset($validated['skip']) ?
            PeriodoSemConflitoAction::handle(
                $validated,
                $inicio,
                $fim) :
            CarbonPeriod::create($inicio, $fim);

        $periodo = $periodo->toArray();

        if ( count($periodo) > 0  && isset($validated['repeat_days']) ) {
            $newPeriodo = [];
            foreach($periodo as $date) {
                if (in_array($date->dayOfWeek, $validated['repeat_days'])) {
                    $newPeriodo[] = $date;
                }
            }
        }

        return $newPeriodo ?? $periodo;
    }
}
