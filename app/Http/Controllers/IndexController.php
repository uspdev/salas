<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Sala;
use App\Models\Reserva;
use Carbon\Carbon;

class IndexController extends Controller
{

    public function index(Request $request){

        $reservas = new Reserva;

        if($request->filter){
            //dd($request->filter);
            $salas = Sala::select('id')->whereIn('categoria_id',$request->filter)->pluck('id');
            
            $reservas = Reserva::whereIn('sala_id',$salas->toArray())
                                 ->where('data','>=', Carbon::today()->toDateString())->get();

        } else {
            $reservas = Reserva::where('data','>=', Carbon::today()->toDateString())->get();
        }

        $events = [];

        foreach($reservas as $reserva) {

            $events[] = \Calendar::event(
                $reserva->nome . " (" . $reserva->sala->categoria->nome . " - ".  $reserva->sala->nome . ")",
                false, //full day event?
                $reserva->inicio,
                $reserva->fim,
                0, //optionally, you can specify an event ID,
                [
                    'color' => $reserva->cor,
                    'url' => '/reservas/' . $reserva->id ,
                ],
            );
        }

        $calendar = \Calendar::addEvents($events)
            ->setOptions([
                'firstDay' => 1,
                'defaultView' => 'listDay'
        ]);

        return view('home',[
            'calendar'   => $calendar,
            'categorias' => Categoria::all()
        ]);
    }
}
