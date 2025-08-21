<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use Carbon\Carbon;
use App\Http\Requests\SalaLivreRequest;

class SalasLivresController extends Controller
{
    public function index()
    {
        return view('sala.salas_livres', ['today' => Carbon::today()->format('d/m/Y')]);
    }

    //pega as reservas que não estão ocupadas nos horários solicitados
    public function search(SalaLivreRequest $request)
    {
        $salas = Sala::SalasLivresQuery($request->validated());

        return response()->json($salas);
    }
}
