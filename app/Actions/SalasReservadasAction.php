<?php

namespace App\Actions;

use Carbon\Carbon;
use App\Models\Reserva;
use Illuminate\Support\Facades\DB;

class SalasReservadasAction
{
    public static function handle(array $validated){
        $query = Reserva::select('sala_id')
        ->where('horario_inicio', '<', DB::raw("STR_TO_DATE('{$validated['horario_fim']}', '%H:%i')"))
        ->where('horario_fim', '>', DB::raw("STR_TO_DATE('{$validated['horario_inicio']}', '%H:%i')"))
        ->orderBy('sala_id', 'asc');

        $query->when($validated['data_limite'], function ($q) use ($validated) {
            $datas = RepeticaoSemanalAction::handle($validated['data'],
                $validated['data_limite'],
                $validated['repeat_days']);
            return $q->whereIn('data', $datas);
        }, function ($q) use ($validated) {
            return $q->where('data', Carbon::createFromFormat('d/m/Y', $validated['data'])->format('Y-m-d'));
        });

        return $query->toBase()->get()->unique();
    }
}
