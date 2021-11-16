<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

use App\Models\Reserva;
use Carbon\Carbon;

class IndexController extends Controller
{
    public function index()
    {   
        return view('index',[
            'categorias' => Categoria::orderBy('nome')->get()
        ]);
    }

    public function home(){

        #$reservas = Reserva::where('data', Carbon::today()->toDateString())->get();
        $reservas = Reserva::where('data','>=', Carbon::today()->toDateString())->get();

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
            'calendar' => $calendar
        ]);
    }
}
