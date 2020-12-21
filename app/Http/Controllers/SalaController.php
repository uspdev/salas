<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use Illuminate\Http\Request;
use App\Http\Requests\SalaRequest;
use Illuminate\Support\Facades\Validator;

class SalaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $salas = Sala::all();
        return view('sala.index', [
            'salas' => $salas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sala.create', [
            'sala' => new Sala,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SalaRequest $request)
    {
        $validated = $request->validated();
        $sala = Sala::create($validated);
        request()->session()->flash('alert-info', 'Sala criada com sucesso.');
        return redirect("/salas/{$sala->id}");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function show(Sala $sala)
    {
        # pegando as reservas das salas
        $events = [];

        foreach($sala->reservas as $reserva) {
            $events[] = \Calendar::event(
                $reserva->nome,
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
                'defaultView' => 'agendaWeek'
        ]);


        return view('sala.show',[
            'sala'     => $sala,
            'calendar' => $calendar
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function edit(Sala $sala)
    {
        return view('sala.edit', [
            'sala' => $sala
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function update(SalaRequest $request, Sala $sala)
    {
        $validated = $request->validated();
        $sala->update($validated);
        request()->session()->flash('alert-info', 'Sala atualizada com sucesso.');
        return redirect("/salas/{$sala->id}");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sala $sala)
    {
        $sala->delete();
        request()->session()->flash('alert-info', 'Sala excluída com sucesso.');
        return redirect('/salas');
    }
}
