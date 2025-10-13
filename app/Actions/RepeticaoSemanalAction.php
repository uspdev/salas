<?php

namespace App\Actions;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class RepeticaoSemanalAction
{

    /**
     * Essa função tem como objetivo buscar os dias do mês de acordo com o(s) dia(s) da semana escolhido(s) pelo usuário.
     * Isto é, se um usuário escolhe apenas segundas-feiras durante 2 meses, a lógica retornará apenas os
     * dias do mês que são segunda-feira até a data limite
     * */

    public static function handle(string $inicial, string $final, array $repeat){
        $inicial = Carbon::createFromFormat('d/m/Y', $inicial);
        $final = Carbon::createFromFormat('d/m/Y', $final);
        $periodo = CarbonPeriod::between($inicial, $final);
        $datas[] = $inicial->toDateString();

        foreach($periodo as $data) {
            if (in_array($data->dayOfWeek, $repeat)) {
                $datas[] = $data->toDateString();
            }
        }

        return $datas;
    }
}
