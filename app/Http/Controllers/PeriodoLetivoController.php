<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeriodoLetivo;
use App\Http\Requests\PeriodoLetivoRequest;

class PeriodoLetivoController extends Controller
{
    public function index()
    {
        $periodos = PeriodoLetivo::all();
        return view('periodos_letivos.index', compact('periodos'));
    }

    public function show($id)
    {
        $periodo = PeriodoLetivo::findOrFail($id);
        return view('periodos_letivos.show', compact('periodo'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('admin');

        return view('periodos_letivos.create', [
            'periodo' => new PeriodoLetivo()
        ]);
    }



/**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(PeriodoLetivo $periodo)  // atenção: o nome do atributo 'periodo' está mapeado no arquivo de rotas
    {
        $this->authorize('admin');
        return view('periodos_letivos.edit', compact('periodo'));
    }



    public function store(PeriodoLetivoRequest $request) {
        $this->authorize('admin');
        $periodo = PeriodoLetivo::create($request->validated());

        return redirect('/periodos_letivos')
            ->with('alert-sucess', 'Recurso criado com sucesso.');
    }

    public function destroy(PeriodoLetivo $periodo)
    {
        $this->authorize('admin');
        $periodo->delete();

        return redirect('/periodos_letivos')
            ->with('alert-sucess', 'Período Letivo excluído com sucesso.');
    }



    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(PeriodoLetivoRequest $request, PeriodoLetivo $periodo)
    {
        $this->authorize('admin');
        
        $validated = $request->validated();

        $periodo->update($validated);
        

        return redirect("/periodos_letivos")
            ->with('alert-success', 'Periodo letivo atualizada com sucesso');
    }

}
