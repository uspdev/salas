<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Finalidade;
use App\Models\Reserva;
use App\Models\Sala;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function search(Request $request)
    {
        $reservas = Reserva::make()
            ->when($request->busca_nome, function ($query) use ($request) {
                $query->where('nome', 'LIKE', '%'.$request->busca_nome.'%')
                    ->orderBy('data', 'DESC');
            })
            ->when($request->busca_data, function ($query) use ($request) {
                $query->whereDate('data', Carbon::createFromFormat('d/m/Y', $request->busca_data)->format('Y-m-d'));
            })
            ->when($request->filter, function ($query) use ($request) {
                $salas = Sala::select('id')->whereIn('categoria_id', $request->filter)->pluck('id');
                $query->whereIn('sala_id', $salas->toArray());
            })
            ->when($request->finalidades_filter, function ($query) use ($request){
                $query->whereIn('finalidade_id', $request->finalidades_filter);
            })
            ->when($request->salas_filter, function ($query) use ($request){
                $query->whereIn('sala_id', $request->salas_filter);
            });

        $reservas = $reservas->orderBy('horario_inicio', 'ASC')->paginate(20);

        return view('home', [
            'categorias' => Categoria::all(),
            'filter' => ($request->filter) ?: [],
            'reservas' => $reservas,
            'finalidades' => Finalidade::all(),
            'finalidades_filter' => $request->finalidades_filter ?? [],
            'salas' => Sala::all(),
            'salas_filter' => $request->salas_filter ?? []
        ]);
    }

    public function home(Request $request)
    {
        $data = today();
        $reservas = Reserva::whereDate('data', $data)->orderBy('horario_inicio', 'ASC')->paginate(20);

        return view('home', [
            'categorias' => Categoria::all(),
            'filter' => ($request->filter) ?: [],
            'reservas' => $reservas,
            'finalidades' => Finalidade::all(),
            'finalidades_filter' => $request->finalidades_filter ?? [],
            'salas' => Sala::all(),
            'salas_filter' => $request->salas_filter ?? []
        ]);
    }
}
