<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Sala;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request){
        $salas = Sala::where('categoria_id',$request->categorias_id)->get();
        $categorias = Categoria::select('id','nome')->get();
        $horas = range(8, 23);
        $dataSelecionada = Carbon::createFromFormat('d/m/Y', $request->data  ?? Carbon::today()->format('d/m/Y'));
        
        return view('sala.calendario', 
        ['salas' => $salas,
        'horas' => $horas,
        'dataSelecionada' => $dataSelecionada,
        'categorias' => $categorias,
        'categorias_id' => $request->categorias_id ?? []
        ]);
    }

    public function search(Request $request){

    }
}