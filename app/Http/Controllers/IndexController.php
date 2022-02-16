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
        $reservas = new Reserva;

        if($request->filter){

            $salas = Sala::select('id')->whereIn('categoria_id',$request->filter)->pluck('id');
            
            $reservas = Reserva::whereIn('sala_id',$salas->toArray())
                                 ->where('data', Carbon::today()->toDateString())->get();

        } else {
            $reservas = Reserva::where('data', Carbon::today()->toDateString())->get();
        }

        return view('home',[
            'categorias' => Categoria::all(),
            'filter'     => ($request->filter) ?: [],
            'reservas'   => $reservas 
        ]);
    }
}
