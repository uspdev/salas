<?php

namespace App\Actions;

use App\Models\Sala;

class SalasLivresAction
{
    public static function handle(array $validated) {
        # obtém salas que contém restrição, mas não está bloqueada
        # e também pega as salas que não têm restrição.
        $salasDesbloqueadas = Sala::whereHas('restricao', function($query) {
            $query->where('bloqueada', 0);
        })->orDoesntHave('restricao')->get();

        $salasReservadas = SalasReservadasAction::handle($validated);

        $salasLivres = $salasDesbloqueadas->reject(function ($item) use ($salasReservadas) {
            return $salasReservadas->contains('sala_id', $item->id);
        });

        if ( isset($validated['recursos']) ) {
            $recursos = $validated['recursos'];
            $salasLivres = Sala::whereIn('id', $salasLivres->pluck('id'))
                ->whereHas('recursos', function ($query) use ($recursos) {
                    $query->whereIn('recurso_id', $recursos);
                }, '=', count($recursos))
                ->get();
        }

        $salas = $salasLivres->load('categoria')
                             ->map(function ($item) {
                                 return [
                                     'id' => $item->id,
                                     'capacidade' => $item->capacidade,
                                     'nome' => $item->nome,
                                     'nomcat' => $item->categoria->nome
                                     ];
                             })->groupBy('nomcat');

        return $salas;
    }
}
