<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Sala;
use App\Models\Reserva;
use Carbon\Carbon;

class IndexController extends Controller
{
    # rota usada para reservas do dia apenas
    public function home(Request $request){

        $query = Reserva::orderBy('sala_id');

        //dd($request->busca_data);

        if($request->filter){

            $salas = Sala::select('id')->whereIn('categoria_id',$request->filter)->pluck('id');
            
            $query = Reserva::whereIn('sala_id',$salas->toArray());
        } 

        if($request->busca_data){
            $day = Carbon::createFromFormat('d/m/Y', $request->busca_data)->format('Y-m-d');
            $query->where('data', $day);
        } else {
            $query->where('data', Carbon::today()->toDateString());
        }

        $reservas = $query->orderBy('data')->get();

        return view('home',[
            'categorias' => Categoria::all(),
            'filter'     => ($request->filter) ?: [],
            'reservas'   => $reservas 
        ]);
    }
}
