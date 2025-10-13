<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Requests\SalaLivreRequest;
use App\Models\Reserva;
use App\Actions\SalasLivresAction;


class SalasLivresController extends Controller
{
    public function index()
    {
        $reserva = new Reserva();
        return view('sala.salas_livres', ['today' => Carbon::today(), 'reserva' => $reserva]);
    }

    //pega as reservas que não estão ocupadas nos horários solicitados
    public function search(SalaLivreRequest $request)
    {
        $salas = SalasLivresAction::handle($request->validated());
        return response()->json($salas);
    }
}
