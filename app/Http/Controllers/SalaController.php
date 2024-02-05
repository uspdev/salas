<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalaRequest;
use App\Models\Categoria;
use App\Models\Finalidade;
use App\Models\Recurso;
use App\Models\Reserva;
use App\Models\Sala;
use App\Models\Restricao;
use App\Models\PeriodoLetivo;

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

        $validated = $request->validated();

        $sala = Sala::create($validated);

        if(array_key_exists('recursos', $validated)) {
            $sala->recursos()->attach($validated['recursos']);
        }

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

        $eventos = [];

        foreach ($sala->reservas as $reserva) {

            $eventos[] = [
                'title' => $reserva->nome,
                'start' => $reserva->inicio,
                'end' => $reserva->fim,
                'url' => route('reservas.show', $reserva->id),
                'color' => $reserva->status == 'pendente' ? config('salas.cores.pendente') : ($reserva->finalidade->cor ?? config('salas.cores.semFinalidade')),
                'textColor' => 'black',
                'extendedProps' => [
                    'responsaveis'=> $reserva->responsaveis->pluck('nome')
                ]
            ];

        }

        $finalidades = Finalidade::all();

        return view('sala.show', [
            'sala' => $sala,
            'recursos' => Recurso::all(),
            'finalidades' => $finalidades,
            'eventos' => $eventos
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

        $recursos = Recurso::get()->map(function($recurso) use ($sala) {
            $recurso->checked = data_get($sala->recursos->firstWhere('id', $recurso->id), 'pivot.recurso_id') ?? null;
            return $recurso;
        });

        
        $sala->restricao = Restricao::firstOrCreate([
            'sala_id' => $sala->id
        ]);

       

        return view('sala.edit', [
            'sala' => $sala,
            'categorias' => Categoria::all(),
            'recursos' => $recursos,
            'responsaveis' => $sala->responsaveis,
            'periodos' => PeriodoLetivo::all(),
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

                
        if($validated['aprovacao'] && count($sala->responsaveis) < 1)
            return redirect()->route('salas.edit', ['sala' => $sala->id])->with('alert-danger', 'A sala deve ter ao menos um responsável se necessitar de aprovação para reserva.');

        $sala->update($validated);

        if(array_key_exists('recursos', $validated)) {
            $sala->recursos()->sync($validated['recursos']);
        }
        else {
            $recurso_ids = [];
            foreach($sala->recursos as $recurso) {
                $recurso_ids[] = $recurso->id;
            }
            $sala->recursos()->detach($recurso_ids);
        }

        
        

        $sala->restricao()->update(
            [
                'bloqueada'            => $validated['bloqueada'],
                'motivo_bloqueio'      => $validated['motivo_bloqueio'],
                'dias_antecedencia'    => $validated['dias_antecedencia'],
                'tipo_restricao'       => $validated['tipo_restricao'],
                'data_limite'          => $validated['data_limite'],
                'dias_limite'          => $validated['dias_limite'],
                'duracao_minima'       => $validated['duracao_minima'],
                'duracao_maxima'       => $validated['duracao_maxima'],
                'periodo_letivo_id'    => $validated['periodo_letivo']
            ]
            );
        

        return redirect("/salas/{$sala->id}")
            ->with('alert-success', 'Sala atualizada com sucesso');
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
        return redirect("/")->with('alert-success', 'Sala excluída com sucesso');
    }

    public function listar()
    {
        $salas = Sala::all();

        return view('sala.listar', compact('salas'));
    }

    private function mapRecursos($recursos)
    {
        return collect($recursos)->map(function ($i) {
            return [$i];
        });
    }
}
