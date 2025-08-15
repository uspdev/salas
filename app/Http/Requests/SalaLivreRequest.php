<?php

namespace App\Http\Requests;

use Exception;
use Carbon\Carbon;

class SalaLivreRequest
{

    public static function handle($horario_inicio, $horario_fim, $data)
    {
        $horario_inicio = request()->horario_inicio;
        $horario_fim = request()->horario_fim;
        $regex_horario = '/^(?:[1-9]|1[0-9]|2[0-3]):[0-5][0-9]$/';

        try {
            $data = Carbon::createFromFormat("d/m/Y", $data);
        } catch (Exception) {
            return response()->json([
                'content' => 'Insira a data no formato <b>dd/mm/AAAA</b>',
                'status' => 400
            ]);
        }

        if (!preg_match($regex_horario, $horario_inicio) || !preg_match($regex_horario, $horario_fim)) {
            return response()->json(['content' => 'Insira o horário no formato <b>H:mm</b>', 'status' => 400]);
        }
    }
}
