<?php

namespace App\Actions;

use Carbon\Carbon;

class RepeticaoSemanal
{

    /** 
     * Essa função tem como objetivo buscar os dias do mês de acordo com o(s) dia(s) da semana escolhido(s) pelo usuário.
     * Isto é, se um usuário escolhe apenas segundas-feiras durante 2 meses, a lógica retornará apenas os
     * dias do mês que são segunda-feira até a data limite
     * */

    public static function handle(array $validated){
        if ($validated['data'] && $validated['data_limite']) {
            $periodo = \Carbon\CarbonPeriod::between(
                Carbon::createFromFormat('d/m/Y', $validated['data']),
                Carbon::createFromFormat('d/m/Y', $validated['data_limite'])
            );
            foreach ($periodo as $dia) {
                if (in_array($dia->dayOfWeek, $validated['repeat_days'])) {
                    $dias_repetidos[] = $dia->toDateString();
                }
            }

            //substitui a qtd de dias repetidos por "?"
            $qtd_dias = count($dias_repetidos);
            $placeholder_posicional = implode(',', array_fill(0, $qtd_dias, '?'));
            
            return collect([$dias_repetidos, $placeholder_posicional]);
        }
    }
}
