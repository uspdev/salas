<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Reserva;
use App\Models\Sala;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function search(Request $request)
    {
        $reservas = new Reserva();
        $filter = $request->filter;
        $busca_data = request()->busca_data;
        $busca_nome = request()->busca_nome;
        $data = null;

        if (!isset($busca_nome) && !isset($busca_data) && !$filter) {
            $data = Carbon::today()->toDateString();
            $reservas = $reservas->where('data', 'LIKE', "%{$data}%");
        } else {
            if ($filter) {
                $salas = Sala::select('id')->whereIn('categoria_id', $filter)->pluck('id');
                $reservas = $reservas->whereIn('sala_id', $salas->toArray());
            }

            if (isset($busca_nome)) {
                $reservas = $reservas->where('nome', 'LIKE', "%{$busca_nome}%");
            }

            if (isset($busca_data)) {
                $data = $busca_data;
                $data = Carbon::createFromFormat('d/m/Y', $data)->format('Y-m-d');
                $reservas = $reservas->where('data', 'LIKE', "%{$data}%");
            }
        }

        if (!empty($data)) {
            $data = Carbon::parse($data)->format('d/m/Y');
        } else {
            $reservas = $reservas->orderBy('data', 'DESC');
        }

        $reservas = $reservas->orderBy('horario_inicio', 'ASC')->paginate(20);

        return [
            'reservas' => $reservas,
            'data' => $data,
        ];
    }

    public function home(Request $request)
    {
        $result = $this->search($request);
        $data = $result['data'];
        $reservas = $result['reservas'];

        return view('home', [
            'categorias' => Categoria::all(),
            'filter' => ($request->filter) ?: [],
            'reservas' => $reservas,
            'data' => $data,
        ]);
    }
}
