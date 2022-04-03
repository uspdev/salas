<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Reserva;
use App\Models\Sala;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    // rota usada para reservas do dia apenas
    public function home(Request $request)
    {
        $reservas = new Reserva();

        if (isset(request()->busca_data)) {
            $data = $request->busca_data;
            $data = Carbon::createFromFormat('d/m/Y', $data)->format('Y-m-d');
        } else {
            $data = Carbon::today()->toDateString();
        }

        if ($request->filter) {
            $salas = Sala::select('id')->whereIn('categoria_id', $request->filter)->pluck('id');

            $reservas = Reserva::whereIn('sala_id', $salas->toArray())
                                 ->where('data', 'LIKE', "%{$data}%")->paginate(20);
        } else {
            $reservas = Reserva::where('data', 'LIKE', "%{$data}%")->paginate(20);
        }

        $data = Carbon::parse($data)->format('d/m/Y');

        return view('home', [
            'categorias' => Categoria::all(),
            'filter' => ($request->filter) ?: [],
            'reservas' => $reservas,
            'data' => $data,
        ]);
    }
}
