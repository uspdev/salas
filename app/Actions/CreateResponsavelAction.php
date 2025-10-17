<?php

namespace App\Actions;

use Uspdev\Replicado\Pessoa;
use App\Models\ResponsavelReserva;

class CreateResponsavelAction
{
    public static function handle(array $validated) {

        $responsaveis = collect();

        switch ($validated['tipo_responsaveis']) {
            case 'eu':
                $responsavel = ResponsavelReserva::firstOrCreate([
                    'nome' => auth()->user()->name,
                    'codpes' => auth()->user()->codpes
                ]);
                $responsaveis->push($responsavel);

                break;

            case 'unidade':
                foreach ($validated['responsaveis_unidade'] as $responsavel_codpes) {
                    $responsavel = ResponsavelReserva::firstOrCreate([
                        'nome' => Pessoa::obterNome($responsavel_codpes),
                        'codpes' => $responsavel_codpes
                    ]);
                    $responsaveis->push($responsavel);
                }

                break;

            case 'externo':
                foreach($validated['responsaveis_externo'] as $responsavel_nome){
                    $responsavel = ResponsavelReserva::firstOrCreate([
                        'nome' => $responsavel_nome
                    ]);
                    $responsaveis->push($responsavel);
                }

                break;
        }

        return $responsaveis->pluck('id');
    }
}
