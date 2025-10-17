<?php

namespace App\Actions;

use App\Models\Reserva;

class CreateReservaAction
{
    public static function handle(array $validated, array $periodo) {

        $validated['data'] = reset($periodo)->format('d/m/Y');
        $reserva = Reserva::create($validated);
        $responsaveis = CreateResponsavelAction::handle($validated);
        $reserva->responsaveis()->sync($responsaveis);
        array_shift($periodo);

        $created = '';
        if( isset($validated['repeat_days']) ) {
            $reserva->parent_id = $reserva->id;
            $reserva->save();
            foreach($periodo as $date) {
                if (in_array($date->dayOfWeek, $validated['repeat_days'])) {
                    $new = $reserva->replicate();
                    $new->parent_id = $reserva->id;
                    $new->data = $date->format('d/m/Y');
                    $new->save();
                    $new->responsaveis()->sync($responsaveis);
                    $created .= "<li><a href='/reservas/{$new->id}'> {$date->format('d/m/Y')}- {$new->nome}</a></li>";
                }
            }
        }

        return [
            'reserva' => $reserva,
            'created' => $created
        ];

    }
}
