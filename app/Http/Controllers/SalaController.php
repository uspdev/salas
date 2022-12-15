<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalaRequest;
use App\Models\Categoria;
use App\Models\Recurso;
use App\Models\Reserva;
use App\Models\Sala;

class SalaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::with(['salas'])->get();

        return view('sala.index', [
            'categorias' => $categorias
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('admin');

        return view('sala.create', [
            'sala' => new Sala(),
            'categorias' => Categoria::all(),
            'recursos' => Recurso::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SalaRequest $request)
    {
        $this->authorize('admin');

        $data = $request->validated();
        //dd($data['recursos']);
        $sala = Sala::create($data);

        $sala->recursos()->sync($data['recursos']);

        return redirect("/salas/{$sala->id}")
            ->with('alert-sucess', 'Sala criada com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Sala $sala)
    {
        // pegando as reservas das salas
        $events = [];

        foreach ($sala->reservas as $reserva) {
            $events[] = \Calendar::event(
                $reserva->nome,
                false, //full day event?
                $reserva->inicio,
                $reserva->fim,
                0, //optionally, you can specify an event ID,
                [
                    'color' => $reserva->cor,
                    'url' => '/reservas/'.$reserva->id,
                ],
            );
        }

        $calendar = \Calendar::addEvents($events)
            ->setOptions([
                'firstDay' => 1,
                'defaultView' => 'agendaWeek',
        ]);

        return view('sala.show', [
            'sala' => $sala,
            'calendar' => $calendar,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Sala $sala)
    {
        $this->authorize('admin');

        $sala->load('recursos');
        //dd($sala);
        //$recursos = Recurso::get()->map(function($recurso) use ($sala) {
        //    $recurso->id = data_get($sala->recursos->firstWhere('id', $recurso->id)) ?? null;
        //    return $recurso;
        //});
        //

        return view('sala.edit', [
            'sala' => $sala,
            'categorias' => Categoria::all(),
            'recursos' => Recurso::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(SalaRequest $request, Sala $sala)
    {
        $this->authorize('admin');

        $validated = $request->validated();

        $sala->update($validated);
        $sala->recursos()->sync($this->mapRecursos($validated['recursos']));

        return redirect("/salas/{$sala->id}")
            ->with('alert-sucess', 'Sala atualizada com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sala $sala)
    {
        $this->authorize('admin');
        
        if($sala->reservas->isNotEmpty()){
            return redirect("/salas/{$sala->id}")
            ->with('alert-danger', 'Não é possível deletar essa sala pois ela contém reservas');   
        }

        $sala->delete();
        return redirect("/")->with('alert-sucess', 'Sala excluída com sucesso');
    }

    private function mapRecursos($recursos)
    {
        return collect($recursos)->map(function ($i) {
            return [$i];
        });
    }
}
