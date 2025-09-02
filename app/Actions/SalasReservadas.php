<?php

namespace App\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalasReservadas 
{
    public static function handle(array $validated){
        $repeticao_semanal = RepeticaoSemanal::handle($validated);
        if (!empty($repeticao_semanal)) {
            $placeholder_posicional = $repeticao_semanal->last(); //"?"
            $dias_repetidos = $repeticao_semanal->first(); //dias do mês (YYYY-mm-dd)
        }

        $query = "SELECT r.sala_id
                FROM reservas r
                INNER JOIN salas s ON s.id = r.sala_id
                LEFT JOIN restricoes x ON x.sala_id = r.sala_id
                WHERE
                    (
                        r.horario_inicio < STR_TO_DATE(?, '%H:%i')
                        AND r.horario_fim > STR_TO_DATE(?, '%H:%i')
                        AND r.data = ?
                    )
                    OR (
                        x.bloqueada = 1
                    )
                    " . (!empty($validated['data_limite']) ?
            "OR r.data IN ($placeholder_posicional) 
                    AND r.horario_inicio < STR_TO_DATE(?, '%H:%i') 
                    AND horario_fim > STR_TO_DATE(?, '%H:%i')"
            : "");

        $params = [
            $validated['horario_fim'],
            $validated['horario_inicio'],
            Carbon::createFromFormat('d/m/Y', $validated['data'])->format('Y-m-d'),
        ];

        if (!empty($validated['data_limite']) && !empty($dias_repetidos)) {
            $params = array_merge($params, $dias_repetidos, [$validated['horario_fim'], $validated['horario_inicio']]);
        }

        $salas_reservadas = collect(DB::select($query, $params))
            ->pluck('sala_id')
            ->unique()
            ->toarray();

        return implode(',', $salas_reservadas);
    }
}
