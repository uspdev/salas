<?php

namespace App\Actions;

use App\Models\Sala;
use Illuminate\Support\Facades\Storage;

class DestroySalaAction
{
    static public function execute(Sala $sala)
    {
        foreach ($sala->reservas as $reserva):
            foreach ($reserva->arquivos as $arquivo):
                Storage::delete($arquivo->caminho);
            endforeach;
        endforeach;
        $sala->reservas()->delete();
        $sala->delete();
    }
}
